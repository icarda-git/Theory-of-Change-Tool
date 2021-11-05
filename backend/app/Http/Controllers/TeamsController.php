<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\Toc;
use App\Models\TocFlows;
use App\Models\UnregisteredTeamInvitation;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use MongoDB\Exception\Exception;
use function Aws\boolean_value;
use App\Http\Controllers\API\TocFlowController;

class TeamsController extends Controller
{
    //

    public function index()
    {
//        return 'hello';
    }

//    public function createTocMemberInvitation($teamMembers, $teamID)
//    {
//        auth('api')->user()->attachRole('leader', $teamID);
//
//        foreach ($teamMembers as $teamMember) {
//
//            if (!strcmp($teamMember->email, auth('api')->user()->email) == 0) {
//                $allTeamMembers[] = ['email' => $teamMember->email, 'role' => $teamMember->role];
//            }
//            $user = User::where('email', $teamMember->email)->first();
//            $role = Role::where('id', $teamMember->role)->first();
//
//
//            if ($user == null) {
//                UnregisteredTeamInvitation::firstOrCreate(
//                    [
//                        'team_id' => $teamID,
//                        'user_email' => $teamMember->email,
//                        'role_id' => $role->id
//                    ]);
//            } else if ($user != null) {
//                TeamInvitation::updateOrCreate(
//                    [
//                        'team_id' => $teamID,
//                        'user_id' => $user->id,
//                        'role_id' => $role->id
//                    ]
//                );
//                //ADD THE CREATE INVITATIONS
//                //$user->attachRole($role, $team->id);
//            }
//        }
////        $requestData = json_decode($request->getContent(), false);
////
////        //Pick the team
////        $team = Team::where('id', $requestData->tokFlow_team->team_id)->first();
////
////        //team does not exist
////        if ($team == null) {
////            return response()->json(['message' => 'no team was found. Please check the provided team ID']);
////        }
////        foreach ($requestData->tokFlow_team->users as $key => $value) {
////            //Will return the object(s) keys not values (e.g "a" => "something") will return "a"
////            //$userID = $key;
////            //Pick the role so you can assign in to the proper user
////            //$roleID = $value;
////
////            $team = TeamInvitation::firstOrCreate(
////                [
////                    'team_id' => $team->id,
////                    'user_id' => $key,
////                    'role_id' => $value
////                ]);
////        }
////
////        return response()->json(['message' => 'Invitations were sent to all members. Do note that members that already had an invitation will not receive a new one']);
//    }



    public function requestTocCreation(Request $request)
    {

        //TODO: Should a tokFlow be created (should the tokFlow be registered) before an admin gives permission for a tokFlow to be manageable from a user?
        //TODO: In case of a custom tokFlow (that does not exist in the Clarisa API), how do we handle the team creation?
        //TODO: In the case that Clarisa adds the project in their database in later times, do we upgrade the project_id in the Teams table to match a Clarisa project_id? Anwser: No


        //TODO: Switches are your friend. Or at least let then become https://www.php.net/manual/en/control-structures.switch.php
        //false ->object,
        //true -> associative array
        $requestData = json_decode($request->getContent(), false);

        //creation == false means the team will not be created
        if ($requestData->tokFlow_record->creation != false) {
            if ($requestData->tokFlow_record->data->project_id != null) {
                //check if a tokFlow already exists for the specified
                $clarisaProjectID = Team::where('clarisa_project_id', $requestData->tokFlow_record->data->project_id)->first();

                //if no tokFlow is found
                if ($clarisaProjectID == null) {

                    //create the new tokFlow
                    $team = Team::create([
                        'name' => $requestData->tokFlow_record->data->name,
                        'clarisa_project_id' => $requestData->tokFlow_record->data->project_id,
                        'user_leader_id' => auth('api')->user()->id,
                        'display_name' => $requestData->tokFlow_record->data->name,
                        'description' => $requestData->tokFlow_record->data->description
                    ]);

                    return response()->json(['message' => 'The request for the new tocFlow is now created. A system administrator must however, validate the given data', 'teamName' => $requestData->tokFlow_record->data->name]);


                } else {
                    return response()->json(['message' => 'A team already exists for this project', 'teamName' => $clarisaProjectID->name, 'project_id' => $clarisaProjectID->project_id, 'team_id' => $clarisaProjectID->id]);
                }
            }
        } else {
            return response()->json(['message' => 'The request will be denied']);
        }
    }

    public function showAllTocMembers($team_id)
    {
        $roles = Role::get();
        $rolesInTeam = array();
        //This will get all members of a team that belongs to any role

        $team = Team::where('id', $team_id)->first();

        if ($team == null) {
            return response()->json(['status' => 'ok', 'message' => 'no team with the specified ID was found']);
        }


        $users = User::whereRoleIs($roles->pluck('name')->toArray(), $team)->get();

        foreach ($users as $user) {
            //join the roles of each user with the teams table to retrieve the name value of the role
            $userRole = $user->roles()->wherePivotIn(Config::get('laratrust.foreign_keys.team'), $team)->first();

            //create a sample array that includes the name of the user and the role for the selected team
            $rolesInTeam[] = array("user" => $user->name, "role" => $userRole->name);
            $rolesInTeamObject = json_decode(json_encode($rolesInTeam), FALSE);
        }


        //This will get all members of a team with a specific role
//        $team = Team::where('name', $request->tokFlow_team->data->team_id)->first();
//        $users = User::whereRoleIs(['leader'], $team)->get();
//        $users = User::whereRoleIs($roles->pluck('name')->toArray(), $team)->get();

//
//        $testUser = User::where('id', 1)->first();
//        $testRole = Role::where('name', 'leader')->first();
//        $testUser->attachRole($testRole,$team);


        return response()->json(['data' => ['users' => $rolesInTeam]]);
    }

    public function showTocFlowForApproval(){
        $teamsNotApproved = Team::where('active', false)->with('leader')->get();

        return response()->json(['data'=>$teamsNotApproved],200);
    }

    public function removeTocMember(Request $request)
    {
        $request = json_decode($request->getContent(), false);

        $checkboolean = false;
        $check = array();
        $roleName = '';
        $roles = Role::get();
        $team = Team::where('id', $request->tokFlow_team->team_id)->first();
        $user = User::where('id', $request->tokFlow_team->user_id)->first();

        //remove this when middleware is attached to the specific route.
        //If a user ID is not provided, this will show an erorr.
        //If all the route middleware(s) are applied though, you will not be able to come to this without being authenticated
        //or without being a HACKERMAN. If you are, please let me know how the F I screwed up.
        if (auth('api')->user() == null) {
            return response()->json(['please register or provide token']);
        }

        $userRole = $user->roles()->wherePivotIn(Config::get('laratrust.foreign_keys.team'), $team)->first();

        if ($userRole != null) {
            $user->detachRole($userRole, $team);
            return response()->json(['message' => 'Role ' . $userRole . ' was removed for user ' . $user->name . ' in team ' . $team->name]);
        } else {
            return response()->json(['message' => 'User ' . $user->name . ' does not belong to ' . $team->name . '. No action was taken']);
        }
    }


//    public function createtocflow(Request $request)
//    {
//        $validator = Validator::make($request->all(),
//            [
//                'programme.title' => 'required'
//            ]
//        );
//
//        if ($validator->fails()) {
//            return response()->json(['data' => ['error' => $validator->messages()]], 422);
//        }
//
//        $allTeamMembers = array();
//        $requestData = json_decode($request->getContent(), false);
//        //Create the team first
//        $checkTeam = Team::where('name', $requestData->programme->title)->first();
//
//        if ($checkTeam != null) {
//            return response()->json(['data' => ['error' => 'Please pick a different title']], 422);
//        } else if ($checkTeam == null) {
//
//            $team = Team::firstOrCreate([
//                'name' => $requestData->programme->title,
//                'clarisa_project_id' => $requestData->programme->programme_id,
//                'user_leader_id' => auth('api')->user()->id,
//                'display_name' => $requestData->programme->title,
//                'description' => $requestData->programme->description,
//            ]);
//
//            /*check that the user that creates the tocFlow is admin or not*/
//            $userRole = auth('api')->user()->roles()->first();
//            if (strcmp($userRole->name, 'superadministrator') == 0 || strcmp($userRole->name, 'administrator') == 0) {
//                //Since he is an admin, he will be able to also create and authorize the creation of the tocFlow
//                $this->authorizeTocCreation($team->id);
//            }
//
//            $teamMembers = $requestData->team_members;
//
//            $this->createTocMemberInvitation($teamMembers, $team->id);
//
//
//            $leader = [
//                'email' => auth('api')->user()->email,
//                'role' => 'leader'
//            ];
//
//            $allTeamMembers[] = $leader;
//
//            try {
//                //Then save all the data to MongoDB
//                $tocFlow = TocFlows::firstOrCreate([
//                    'data' => [
//                        'team_id' => $team->id,
//                        'programme' => [
//                            'programme_id' => $requestData->programme->programme_id,
//                            'title' => $requestData->programme->title,
//                            'description' => $requestData->programme->description,
//                            'type' => $requestData->programme->type,
//                            'action_areas' =>
//                                $requestData->programme->action_areas
//                            ,//action_areas
//                            'donors' =>
//                                $requestData->programme->donors
//                            ,//donors
//                            'partners' =>
//                                $requestData->programme->partners
//                            ,//partners
//                            'sdgs' =>
//                                $requestData->programme->sdgs,//sdgs
//                        ],//programme
//                        'pdb' => [
//                            'status' => $requestData->pdb->status,
//                            'pdb_link' => $requestData->pdb->pdb_link
//                        ],//pdb
//                        'team_members' => $allTeamMembers,
//                    ],
//                ]);
//
//                return response()->json(['MongoDB_data' => $tocFlow->_id, 'MySQL_data'=>$team], 200);
//            } catch (Exception $e) {
//                return response()->json(['data' => $e], 400);
//            }
//
//        }
//    }

    public function updatetocflow(Request $request)
    {
        $requestData = json_decode($request->getContent(), false);

        $findTocFlow = TocFlows::where('_id', $request->tocflow_id)->first();

        if ($findTocFlow == null) {
            return response()->json(['data' => ['error' => 'not found for id' . $request->tocflow_id]], 200);
        } else if ($findTocFlow != null) {


            $tocFlow = $request->all();
            foreach ($tocFlow as $key => $value) {
//            dd($key,$value);
                $findTocFlow->update(['data.' . $key => $value]);
            }

            return response()->json(['data' => $findTocFlow], 200);
        }
    }

    public function gettocflow(Request $request)
    {
        $tocFlow = TocFlows::where('_id', $request->id)->first();

        if ($tocFlow == null) {
            return response()->json(['data' => 'no toc flow was found with the specified ID']);
        }
        return response()->json(['id' => $tocFlow->_id, 'data' => $tocFlow->data]);
    }

}
