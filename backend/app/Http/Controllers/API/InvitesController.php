<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;

class InvitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //Show all the invitations of the logged in user
        $teams = Team::all();
        $result = array();


        foreach ($teams as $team){
            if($team->active == false){
                //team not active and the invite will not be shown
                $result = [];
                continue;

            }else if($team->active == true){
//                $result = array_merge($result, TeamInvitation::where('user_id', auth('api')->user()->id)->where('team_id', $team->id)->with('team','role','user')->get()->toArray());
                $userTeamInvitations = TeamInvitation::where('user_id', auth('api')->user()->id)->where('team_id', $team->id)->get();
                foreach ($userTeamInvitations as $userInvitation) {
                    $teamData = Team::where('id', $team->id)->first();
                    $leaderOfTeamData = User::where('id', $teamData->user_leader_id)->first();
                    $userAssignedRoleInTeam = Role::where('id', $userInvitation->role_id)->first();

                    $result[] = ['invitation_id'=> $userInvitation->id, 'project_name' => $teamData->name, 'team_name' => $teamData->name, 'inviter_name' => $leaderOfTeamData->name, 'inviter_email' => $leaderOfTeamData->email, 'user_assigned_role' => $userAssignedRoleInTeam->display_name];
                }
            }
        }

        return response()->json(['data'=>$result],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), false);
        //Store a new invitation for multiple users for a specific tocFlow

        //The validator checks that the input from the specified data are included
        $validatedData = $request->validate([
            'team_id' => 'required',
            'users' => 'required',
        ]);

        $team = Team::where('id', $requestData->team_id)->first();
        if ($team == null) {
            return response()->json(['message' => 'no team was found. Please check the provided team ID']);
        } else if ($team != null) {


            foreach ($requestData->users as $key => $value) {
                //Will return the object(s) keys not values (e.g "a" => "something") will return "a"
                //$userID = $key;
                //Pick the role, so you can assign in to the proper user
                //$roleID = $value;


                    $team = TeamInvitation::firstOrCreate(
                        [
                            'team_id' => $requestData->team_id,
                            'user_id' => $key,
                            'role_id' => $value
                        ]);
            }

            return response()->json(['message' => 'Invitations were sent to all members. Do note that members that already had an invitation will not receive a new one'], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondToInvitation($id, $response)
    {
        //Accept of reject the invitation for a tocflow
        $invitation = TeamInvitation::where('id', $id)->where('user_id', auth('api')->user()->id)->first();

//        if(!$invitation){
//            return response()->json(['message'=>'The specified ID was not found'],200);
//        }else if ($invitation){
//            return response()->json(['data'=>$invitation],200);
//        }

//        return $response;

        //the $response value can be either "accept" or "reject"
        switch ($response) {
            //in case the response is "reject"
            case 'reject':
                $invitation->delete();
                return response()->json(['message' => 'Your invitation was rejected and deleted from our records.'], 200);
                break;
            case 'accept':
                $invitation->update(['accepted' => 1]);

                //get the role that it is meant to be assigned to the user from the invitation table
                $role = Role::where('id', $invitation->role_id)->first();
                $user = User::where('id', $invitation->user_id)->first();
                $team = Team::where('id', $invitation->team_id)->first();

                switch ($this->checkTeamExists($team)) {
                    case false:
                        return response()->json(['message' => 'Please make sure that the team still exists']);
                        break;
                    case true:
                        $user->attachRoles([$role], $team);
                        $user->syncRoles([$role], $team);

                        $invitation->delete();

                        return response()->json(['message' => 'Welcome to ' . $team->name . ' ' . auth('api')->user()->name],200);
                }
            default:
                return response()->json(['message' => 'The response provided is not accepted'], 400);
        }
    }

    public function checkTeamExists($team)
    {
        //Check if the given team ID exists in the DB.
        //If it does not, return false, otherwise (exists) sent true.
        if ($team == null) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
