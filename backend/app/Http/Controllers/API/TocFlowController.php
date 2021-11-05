<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\Toc;
use App\Models\TocFlows;
use App\Models\UnregisteredTeamInvitation;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MongoDB\Exception\Exception;
use App\Http\Controllers\TocController;

class TocFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $userRole = auth('api')->user()->roles()->first();

        switch ($userRole->name) {
            case 'administrator':
                //that's the way OR is supposed to work. It's either a administrator OR a superadministrator
            case 'superadministrator':
                $teamsForReview = Team::where('active', false)->get();
                $teams = Team::all();

                return response()->json(['data' => ['allTeams' => $teams, 'teamsToReview' => $teamsForReview]], 200);
            default:
                $userTeams = auth('api')->user()->teams()->get();
                return response()->json(['data' => $userTeams], 200);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $userRole = auth('api')->user()->roles()->first();
        $validator = Validator::make($request->all(),
            [
                'programme.title' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['data' => ['error' => $validator->messages()]], 422);
        }


        $allTeamMembers = array();
        $requestData = json_decode($request->getContent(), false);

        //Create the team first
        $checkTeam = Team::where('name', $requestData->programme->title)->first();

        if ($checkTeam != null) {
            return response()->json(['data' => ['error' => 'Please pick a different title']], 422);
        } else if ($checkTeam == null) {

            /*
             * Create the SQL team first with createTeam($data)
             * where $data are all the request data coming from the call
             * */
            try {
                Log::info('Attempting tocFlow creation on SQL for: ' . $requestData->programme->title);

                $team = $this->createTocFlowSQL($requestData);

                /*
                 * After the creation, if the user that requests the creation, holds Admin or SuperAdmin privileges,
                 * authorize his team "automatically".
                 * check that the user that creates the tocFlow is admin or not
                 * */
                if (strcmp($userRole->name, 'superadministrator') == 0 || strcmp($userRole->name, 'administrator') == 0) {
                    //Since he is an admin, he will be able to also create and authorize the creation of the tocFlow

//                    return $team->id;
                    $this->authorizeTocCreation($team->id);
                }

            } catch (QueryException $e) {
//                dd($e->getMessage());
                Log::error('ERROR while attempting to create SQL row: ' . $e->getMessage());
            }

            $teamMembers = $requestData->team_members;


            //can't forget about the creator of the tocFlow
            $leader = [
                'email' => auth('api')->user()->email,
                'role' => 4
            ];


            $allTeamMembers[] = $leader;

            foreach ($teamMembers as $teamMember) {
                array_push($allTeamMembers, (array)$teamMember);
            }
//            dd($allTeamMembers);


            //We want to crete invitations for everyone EXCEPT the leader
            // (which is the creator of the tocFlow so by default, does not need an invitation to his own team)
            $members = $this->createTocMemberInvitation($teamMembers, $team->id);
            try {
                //Then save all the data to MongoDB
                $tocFlow = $this->createTocFlowMongoDB($team, $requestData, $allTeamMembers);
            } catch (QueryException $e) {
                return response()->json(['data' => $e->getMessage()], 400);
            }
            return response()->json(['MongoDB_data' => $tocFlow->_id, 'MySQL_data' => $team], 200);
        }
    }

    public function createTocFlowSQL($data)
    {
        /*
         * $data has all the information regarding the team and the contents of the team.
         * Maybe in future version that will change and the following data will
         * not be in the same value so no excess data will be passed to this function
         * (just why...)
        */

        try {
            $team = Team::firstOrCreate([
                'name' => $data->programme->title,
                'clarisa_project_id' => $data->programme->programme_id,
                'user_leader_id' => auth('api')->user()->id,
                'display_name' => $data->programme->title,
                'description' => $data->programme->description,
            ]);

            Log::info('Attempting tocFlow creation on MYSQL for: ' . $data->programme->title);
        } catch (QueryException $e) {

        }
        if ($team) {
            /*
             * If a team was created successfully
             * */
            Log::info('Team created OK');
            return $team;
        } else if (!$team) {
            /*
             * If there was an error during the tocFlow creation, return an error
             * */

//            App::abort(500, 'Team creation was not successful');
            $e = QueryException::class;
            Log::error('Team creation was not successful. Something went crappy' . $e->getMessage());
            return response()->json(['error' => 'There was an error during the MYSQL tocFlow creation'], 500);
        }
    }

    public function createTocFlowMongoDB($teamData, $requestData, $members)
    {
        /*
         * Same thing with createTeam pretty much it is just a more complex (structure wise) creation of documents
         * for MongoDB. If everything goes OK the process will continue otherwise, an error will be logged.
         * */

        try {
            $tocFlow = TocFlows::firstOrCreate([
                'data' => [
                    'team_id' => $teamData->id,
                    "initiative_level" => $requestData->initiative_level,
                    "workpackage_level" => $requestData->workpackage_level,
                    'programme' => [
                        'programme_id' => $requestData->programme->programme_id,
                        'title' => $requestData->programme->title,
                        'description' => $requestData->programme->description,
                        'type' => $requestData->programme->type,
                        'action_areas' =>
                            $requestData->programme->action_areas
                        ,//action_areas
                        'donors' =>
                            $requestData->programme->donors
                        ,//donors
                        'partners' =>
                            $requestData->programme->partners
                        ,//partners
                        'sdgs' =>
                            $requestData->programme->sdgs,//sdgs
                    ],//programme
                    'pdb' => [
                        'status' => $requestData->pdb->status,
                        'pdb_link' => $requestData->pdb->pdb_link
                    ],//pdb
                    'team_members' => $members,
                    'work_packages' => $requestData->work_packages

                ],
            ]);


            //Instanciate the controller of Toc (TocController)
            $request = new \Illuminate\Http\Request();
            $tocCreator = new TocController();
            /*
             * Either value can be true. If one of them is true,
             * take its array contents and shove it in the store() method of TocController
             * the data of each level
             * */

            if ($requestData->initiative_level == true) {
                /*These data will be converted to request object before
                *they are sent to Toc->store() method to be saved on a database
                */
                $data = ([
                    'number' => 0,
                    "tocFlow_id" => $tocFlow->_id,
                    "toc_type" => "Initiative Level (n-1)",
                    "toc_type_id" => 1,
                    "toc_title" => "Initiative Level (n-1)",
                    "toc" => [
                    ]
                ]);

                $request->replace($data);
                $tocCreator->store($request);
            }

            if ($requestData->workpackage_level == true) {

                foreach ($requestData->work_packages as $wp) {

                    /*These data will be converted to request object before
                    *they are sent to Toc->store() method to be saved on a database
                    */
                    $data = ([
                        'number' => $wp->number,
                        "tocFlow_id" => $tocFlow->_id,
                        "toc_type" => "Work Package (n-2)",
                        "toc_type_id" => 2,
                        "toc_title" => $wp->title,
                        "toc" => [
                        ]
                    ]);

                    $request->replace($data);

                    $tocCreator->store($request);
                }
            }
            return ($tocFlow);
        } catch (QueryException $e) {
            return response($e->getMessage(), 500);
        }


    }

    public
    function authorizeTocCreation($teamID)
    {
        //TODO: Should a tokFlow be created (should the tokFlow be registered) before an admin gives permission for a tokFlow to be manageable from a user?
        //TODO: In case of a custom tokFlow (that does not exist in the Clarisa API), how do we handle the team creation?
        //TODO: In the case that Clarisa adds the project in their database in later times, do we upgrade the project_id in the Teams table to match a Clarisa project_id? Anwser: No

        //false ->object,
        //true -> associative array
//        $requestData = json_decode($request->getContent(), false);

        //alter the authorizer_id
        //alter the active

        //get the team request to handle
        $team = Team::where('id', $teamID)->first();

        try {
            //update the specified values on the team
            $team->update(['authorizer_id' => auth('api')->user()->id, 'active' => true]);
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong when updating the tocFlow status', 'error' => $e->getMessage(), 'error_code' => $e->getCode()], 400);
        }

        //assign the leader role to the specified user (the one who requested the team creation)
        //get the leader role so you can assign in to the proper user
        $roleInitiator = Role::where('name', 'leader')->first();
        //get the user that requested the team creation;
        $userInitiator = User::where('id', $team->user_leader_id)->first();

        try {
            $userInitiator->attachRole($roleInitiator, $team);
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong when assigning given user as a leader', 'error' => $e->getMessage(), 'error_code' => $e->getCode()], 400);
        }

        return response()->json(['status' => 'ok', 'message' => 'Everything went A-OK'], 200);


    }

    public
    function createTocMemberInvitation($teamMembers, $teamID)
    {


        /*
         * For each user of the users array, the user will be included in the team (aka TocFlow)
         * If a user is already in the team (for some reason) he will NOT be included again.
         * Also, the user that requested the creation (the logged-in user) of the tocFlow will be assigned
         * as a leader in this tocFlow
         * */

        auth('api')->user()->attachRoles(['leader'], $teamID);
        auth('api')->user()->syncRoles(['leader'], $teamID);

        /*if teamMember email is blank, do not include him in the team creation loop*/

        foreach ($teamMembers as $teamMember) {
            if (empty($teamMember->email)) {
                continue;
            } else {
                if (!strcmp($teamMember->email, auth('api')->user()->email) == 0) {
                    $teamMembers = ['email' => $teamMember->email, 'role' => $teamMember->role];
                }
            }
            $user = User::where('email', $teamMember->email)->first();
            $role = Role::where('id', $teamMember->role)->first();


            /*
             * A user may receive an invitation even if he is not included in the system yet.
             * Another SQL table exists where those emails are stored and in case that email appears, the specified
             * user will be greeted with his invitations.
             * */
            if ($user == null) {
                UnregisteredTeamInvitation::firstOrCreate(
                    [
                        'team_id' => $teamID,
                        'user_email' => $teamMember->email,
                        'role_id' => $role->id
                    ]);
            } else if ($user != null) {
                TeamInvitation::updateOrCreate(
                    [
                        'team_id' => $teamID,
                        'user_id' => $user->id,
                        'role_id' => $role->id
                    ]
                );
                //ADD THE CREATE INVITATIONS
                //$user->attachRole($role, $team->id);
            }
        }

//            dd($teamMembers);

        return $teamMembers;
//        $requestData = json_decode($request->getContent(), false);
//
//        //Pick the team
//        $team = Team::where('id', $requestData->tokFlow_team->team_id)->first();
//
//        //team does not exist
//        if ($team == null) {
//            return response()->json(['message' => 'no team was found. Please check the provided team ID']);
//        }
//        foreach ($requestData->tokFlow_team->users as $key => $value) {
//            //Will return the object(s) keys not values (e.g "a" => "something") will return "a"
//            //$userID = $key;
//            //Pick the role so you can assign in to the proper user
//            //$roleID = $value;
//
//            $team = TeamInvitation::firstOrCreate(
//                [
//                    'team_id' => $team->id,
//                    'user_id' => $key,
//                    'role_id' => $value
//                ]);
//        }
//
//        return response()->json(['message' => 'Invitations were sent to all members. Do note that members that already had an invitation will not receive a new one']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function show($id)
    {
        //
        $tocFlow = TocFlows::where('_id', $id)->first();


//        $tocsOfTocFlow = Toc::where('tocFlow_id', $tocFlow->_id)->get();


        if ($tocFlow->data['work_packages'] == null) {
            $newToc = [];
        } else {
            foreach ($tocFlow->data['work_packages'] as $toc) {

//                dd($tocFlow->data['work_packages']);

                $tocData = Toc::select('number', 'title', 'toc_id')->where('tocFlow_id', $tocFlow->_id)->where('number', $toc['number'])->first();

//                dd($tocData);
                $tocID = $tocData->toc_id;

                $newToc[] = [
                    'number' => $toc['number'],
                    'title' => $toc['title'],
                    'toc_id' => $tocID
                ];

            }
        }
//        $newToc;


        if ($tocFlow == null) {
            return response()->json(['data' => 'no toc flow was found with the specified ID']);
        }
        return response()->json(['id' => $tocFlow->_id, 'data' => $tocFlow->data, 'toc_details' => $newToc]);

    }

    /*
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        $tocDetails [] = null;
        $tocCreator = new TocController();
        $convertRequest = new Request();
        $findTocFlow = TocFlows::where('_id', $id)->first();
        $teamID = $findTocFlow->data['team_id'];
        if ($findTocFlow == null) {
            return response()->json(['data' => ['error' => 'not found for id' . $request->tocflow_id]], 200);
        } else if ($findTocFlow != null) {


            $tocFlow = $request->all();
//            foreach ($tocFlow as $key => $value) {
////            dd($key,$value);
//
//                $findTocFlow->update(['data.' . $key => $value]);
//            }
            if ($request->team_members) {
                $tocFlowUsers = $request->team_members;
            } else {
                $tocFlowUsers = null;
            }
            if ($request->work_packages) {
                $workpackages = $request->work_packages;
            } else {
                $workpackages = null;
            }


            if ($workpackages != null) {
                foreach ($workpackages as $workpackage) {

                    if (!array_key_exists('id', $workpackage)) {
                        $workPackageId = null;
                    } else {
                        $workPackageId = $workpackage['id'];

                    }


                    $checkToc = Toc::where('toc_id', $workPackageId)->orderBy('version', 'DESC')->first();
                    if ($workPackageId == null) {
                        $toc_id = Str::random(10);
                        /*
                         * this toc is not found.
                         * It looks all new and shiny!
                         * (•ω•)
                         * It will be treated as a new toc instead.
                         * NOTE: Only work_packages can be created. There can only be ONE initiative package per tocFlow
                         * which is created during the tocFlow creation
                         * */
                        $tocData = ([
                            'deleted' => false,
                            'version' => 0,
                            'number' => $workpackage['number'],
                            'toc_id' => $toc_id,
                            'tocFlow_id' => $id,
                            'toc_type' => "Work Package (n-2)",
                            'toc_type_id' => 2,
                            'toc_title' => $workpackage['title'],
                            'toc' => [],
                            'published' => false,
                            'published_data' => [],
                        ]);

                        $newTocData = $convertRequest->merge($tocData);

                        $newToc = $tocCreator->store($newTocData);

                        $tocDetails[] = $newToc->getData()->data->TocID;

                    } else {

                        /*
                        * A toc with the specifed toc_id was found on mongo and its title will be replaced!
                        * */

                        if ($checkToc != null) {
                            $checkToc->update(['toc_title' => $workpackage['title']]);

                            $tocDetails[] = $checkToc->toc_id;
                        }
                    }

                }
            }

            if ($tocDetails != null) {
                $tocs = Toc::where('tocFlow_id', $findTocFlow->_id)->where('toc_type_id', 2)->get();
                foreach ($tocs as $toc) {
                    if (!in_array($toc->toc_id, $tocDetails)) {
                        $toc->delete();
                    }
                }
            }


            foreach ($tocFlow as $key => $value) {
                $findTocFlow->update(['data.' . $key => $value]);
            }

            /*This updates the work_packages WITHOUT the id value*/
            if ($tocFlowUsers != null) {
                foreach ($tocFlowUsers as $user) {
                    $userRole = $user['role'];
                    $userEmail = $user['email'];

                    //Get the user from the users table
                    $userTableData = User::where('email', $userEmail)->first();
                    if ($userTableData == null) {
                        //User is NOT found. We we will create a new record on user_team_invitations_unregistered
                        UnregisteredTeamInvitation::firstOrCreate(
                            [
                                'team_id' => $teamID,
                                'user_email' => $userEmail,
                                'role_id' => $userRole
                            ]);
                    } else if ($userTableData != null) {
                        $userID = $userTableData->id;
//                    dd($userID);
                        $checkUserInTeam = DB::table('role_user')->select(DB::raw('*'))->where('team_id', $teamID)->where('user_id', $userID)->first();
//                    dd($checkUserInTeam);
                        if ($checkUserInTeam == null) {
                            TeamInvitation::updateOrCreate(
                                [
                                    'team_id' => $teamID,
                                    'user_id' => $userID,
                                    'role_id' => $userRole
                                ]
                            );
                        } elseif ($checkUserInTeam != null) {
                            //Change the user role in the team
                            $team = Team::where('id', $teamID)->first();
                            $userRoleInTeam = Role::where('id', $checkUserInTeam->role_id)->first();

                            $userNewRoleInTeam = Role::where('id', $userRole)->first();

                            $userTableData->detachRole($userRoleInTeam->id, $team->id);
                            $userTableData->attachRole($userNewRoleInTeam, $team);
                        }

                    }

                }
            }

            if ($workpackages != null) {
                foreach ($workpackages as $workpackage) {
                    $work_packages[] = ([
                        'number' => $workpackage['number'],
                        'title' => $workpackage['title']
                    ]);
                }

                $findTocFlow->update(['data.work_packages' => $work_packages]);
            }

            return response()->json(['data' => $findTocFlow], 200);
        }
    }

    public
    function getLevels()
    {
        $path = storage_path() . "/json/getLevels.json";
        $json = json_decode(file_get_contents($path), true);

        return response()->json(['data' => $json]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }

    public
    function authorizetocflowcreation($id, $response, Request $request)
    {
        //approve the creation of the tocFlow
        //approve or reject (like the tocFlow invitations)

        $team = Team::where('id', $id)->where('active', false)->first();

        if ($team) {
            switch ($response) {
                case 'authorize':
                    $team->update(['active' => true]);
                    $team->update(['authorizer_id' => auth('api')->user()->id]);
                    return response()->json(['data' => 'TocFlow authorization was approved'], 200);
                case 'reject':
                    $team->update(['active' => false]);
                    return response()->json(['data' => 'TocFlow authorization was rejected. Reason:' . $request->rationale], 200);
                default:
                    return response()->json(['message' => 'your response is not defined. Proper response: approve OR active']);
            }
        } elseif (!$team) {
            return response()->json(['data' => 'The team defined was either not found OR is already active'], 404);
        }


    }


    public function updateTocFlowUsers()
    {
        $tocFlowSQLTeams = Team::where('active', 1)->get();
        Log::info('Total of SQL teams that are active: ' . $tocFlowSQLTeams->count());
        foreach ($tocFlowSQLTeams as $sqlTeam) {

            Log::info('Checking MongoDB to find if a tocFLow exists for MySQL ID ' . $sqlTeam->id);

            $tocFlowDocument = TocFlows::where('data.team_id', $sqlTeam->id)->first();
            $teamTeamMembersRoles = RoleUser::where('team_id', $sqlTeam->id)->get();
            $usersWithActiveInvitations = TeamInvitation::where('team_id', $sqlTeam->id)->get();
            $unregisteredUsersWithActiveInvitations = UnregisteredTeamInvitation::where('team_id', $sqlTeam->id)->get();
            $sqlID = $sqlTeam->id;
            $mongoID = $tocFlowDocument['_id'];
            $tocFlowTeamMembers = $tocFlowDocument['data.team_members'];

            //Check that a MongoDB document was found for the specified MySQL ID
            if ($tocFlowDocument) {
                Log::info('A MongoDB record was found for MySQL ID ' . $sqlTeam->id . '. MongoDB document ID ' . $tocFlowDocument->_id);
                //A document was found with the specified ID


                /*-----------------------NOTES-----------------------*/
                //$tocFlowTeamMembers are the people available on MongoDB
                //$teamTeamMembers are the people on MySQL

                /*-----------------------NEW PLAN-----------------------*/
                /*
                 * We delete the code and say we got hacked... #not
                 * What we need is to take it step by step
                 * --First include all the leaders from MySQL to MongoDB. By default, (at that state) only leaders exist in MySQL (if I am not mistaken)
                 * */

                //Does it contain the same values as the role_user?
                $checkUsers = $this->checkMongoDBTocFlowTeamMembers($sqlID, $mongoID, $tocFlowTeamMembers, $teamTeamMembersRoles);

            } else {
                Log::info('A MongoDB document was not found for the specified ID ' . $sqlTeam->id);

                //Check if users are assigned to the specified MySQL team and if they do, delete them
                $this->deleteTeamMembersFromSQL($sqlTeam->id);
                //Delete the unused MySQL team
                $this->deleteTeamFromMySQL($sqlTeam->id);

            }
        }
    }

    private function checkMongoDBTocFlowTeamMembers($sqlID, $mongoID, $mongoUsersData, $sqlUserData)
    {
        //Foreach user that exists in the MongoDB record, a record of the user must exist in one of the following fields:
        //--role_user
        //--user_team_invitation
        //--user_team_invitation_unregistered

        Log::info('Checking that are in the mongoDB document ' . $mongoID . ' match with SQL' . $sqlID);
        foreach ($sqlUserData as $sqlUser) {
            //Get a user based on its ID
            $sqlUser = User::where('id', $sqlUser->user_id)->first();

            //Are there any users inside the MongoDB record?
            //TODO: make that note
            //Does that user exists inside the MongoDB record?
            foreach ($mongoUsersData as $mongoUser) {
                if (strcmp($mongoUser['email'], $sqlUser->email) == 0) {
                    Log::info('the user ' . $sqlUser->email . ' belongs to SQL record with ID ' . $sqlID . ' and MongoDB record ' . $mongoID);
                    dd('the user belongs to SQL and MongoDB records');
                } else {
                    //Maybe he is invited to the team but has not accepted yet

                    Log::info('Checking that the user might be registered but has not accepted the invitation yet');
                    $doesTheUserHasInvitationToTheTeam = TeamInvitation::where('user_id', $sqlUser->id)->where('team_id', $sqlID)->first();

                    //an invitation was found
                    if ($doesTheUserHasInvitationToTheTeam) {
                        Log::info('There is a pending invitation for the user ' . $sqlUser->email . ' for tocFlow ' . $mongoID);

                        if ($doesTheUserHasInvitationToTheTeam->accepted == 0) {
//                            dd('The user has an invitation to that team but have not accepted yet');
                            Log::info('The user ' . $sqlUser->email . ' has an invitation to that team but has not accepted yet');
                        }
                    } else {
                        //an invitation was not found
                        Log::info('Checking that the user that was found inside document ' . $mongoID . ' has an invitation as unregistered');
                        $doesTheUserHasInvitationToTheTeamBUTHaventAcceptedYetANDIsUnregistered = UnregisteredTeamInvitation::where('user_email', $sqlUser->email)->first();
                        if ($doesTheUserHasInvitationToTheTeamBUTHaventAcceptedYetANDIsUnregistered) {
                            //the user might be unregistered but an invitation exists in another table
                            Log::info('The user has an invitation to tocFlow ' . $mongoID . ' but have not accepted yet AND has not completed their registration');
//                            dd('The user has an invitation to that team but have not accepted yet');
                        }
                    }
//                    dd('The user ' . $sqlUser->email . ' does not exist in MongoDB ID ' . $mongoID . ' record');
                    Log::info('The user ' . $sqlUser->email . ' does not exist in MongoDB ID ' . $mongoID . ' record');
//                    Log::info('The user ' . $sqlUser->email . ' will be added to ' . $mongoID . ' document. The role of ');

                }
            }
        }

//        dd($sqlUserData);
        //all went well
        return true;

//        //something went fuzzy
//        return false;
    }

    private function deleteTeamMembersFromSQL($teamID)
    {
        $usersInTheSQLTeam = RoleUser::where('team_id', $teamID)->get();

//        dd($usersInTheSQLTeam);
        foreach ($usersInTheSQLTeam as $userInTheSQLTeam) {
            //Delete these records from MySQL (since it's unused)
            Log::info('Removing user ' . $userInTheSQLTeam->user_id . ' from team ID ' . $teamID);
//            $userInTheSQLTeam->delete();
        }
    }

    private function deleteTeamFromMySQL($teamID)
    {
        Log::info('Deleting team ' . $teamID . ' since no MongoDB record was found');
    }
}
