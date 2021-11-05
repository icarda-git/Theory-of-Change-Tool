<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TocFlows;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class UserController extends Controller

{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $users = User::orderBy('id', 'DESC')->get();

//        return view('users.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
        return response()->json($users);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()

    {

        $roles = Role::pluck('name', 'name')->all();

        return view('users.create', compact('roles'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)

    {

        $this->validate($request, [

            'name' => 'required',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|same:confirm-password',

            'roles' => 'required'

        ]);


        $input = $request->all();

        $input['password'] = Hash::make($input['password']);


        $user = User::create($input);

        $user->assignRole($request->input('roles'));


        return redirect()->route('users.index')
            ->with('success', 'User created successfully');

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)

    {

        $user = User::find($id);

        return view('users.show', compact('user'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)

    {

        $user = User::find($id);

        $roles = Role::pluck('name', 'name')->all();

        $userRole = $user->roles->pluck('name', 'name')->all();


        return view('users.edit', compact('user', 'roles', 'userRole'));

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

        $this->validate($request, [

            'name' => 'required',

            'email' => 'required|email|unique:users,email,' . $id,

            'password' => 'same:confirm-password',

            'roles' => 'required'

        ]);


        $input = $request->all();

        if (!empty($input['password'])) {

            $input['password'] = Hash::make($input['password']);

        } else {

            $input = Arr::except($input, array('password'));

        }


        $user = User::find($id);

        $user->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();


        $user->assignRole($request->input('roles'));


        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)

    {

        User::find($id)->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');

    }

    public function respondTocMemberInvitation(Request $request)
    {
        //Get the invitation
        $invitation = TeamInvitation::where('team_id', $request->tokFlow_team['team_id'])->where('user_id', auth('api')->user()->id)->first();

        if ($invitation != null) {
            //Get the response of the user (either true or false)
            if ($request->tokFlow_team['invitation_response'] != true) {
                //reject the invitation and clear the table row on TeamInvitation, so invitation_response will be false
                $invitation->delete();

                return response()->json(['message' => 'Your invitation was deleted from our records.']);
            } else {

                //accept the invitation, so invitation_response will be true
                $invitation->update(['accepted' => 1]);

                //get the role that it is meant to be assigned to the user from the invitation table
                $role = Role::where('id', $invitation->role_id)->first();
                $user = User::where('id', $invitation->user_id)->first();
                $team = Team::where('id', $invitation->team_id)->first();


                if ($this->checkTeamExists($team) == false) {
                    return response()->json(['message' => 'Please make sure that the team still exists']);;
                }
                $user->attachRoles([$role], $team);
                $user->syncRoles([$role], $team);

                $invitation->delete();

                return response()->json(['message' => 'Welcome to ' . $team->name . ' ' . auth('api')->user()->name]);

            }
        } else {
            return response()->json(['message' => 'This invitation is not valid anymore']);
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

    public function getUserFlows(Request $request)
    {
        $teams = Team::get()->where('active', 1);
        $roles = Role::get();
        $userFlows = array();
        $users = array();


        foreach ($teams as $team) {
            $user = User::whereRoleIs($roles->pluck('name')->toArray(), $team)->where('id', auth('api')->user()->id)->first();
            $tocFlow = TocFlows::where('data.team_id', $team->id)->first();


            if ($user != null) {
                if ($tocFlow == null) {
                    $tocFlow = (
                    [
                        '_id' => 'N/A',
                        'data' =>
                            ['programme' =>
                                [
                                    'title' => 'N/A'
                                ],
                                'pdb' => [
                                    'pdb_link' => 'N/A'
                                ]
                            ]
                    ]);

                    $tocFlow = json_decode(json_encode($tocFlow), false);
                }

                if ($tocFlow->_id != 'N/A') {

                    $tocFlowData = json_decode(json_encode($tocFlow), false);
                    //join the roles of each user with the teams table to retrieve the name value of the role
                    $userRole = $user->roles()->wherePivotIn(Config::get('laratrust.foreign_keys.team'), $team)->first();

                    //create a sample array that includes the name of the user and the role for the selected team
                    $userFlow = array('team' => $team->id, 'id' => $tocFlowData->_id, "title" => $tocFlowData->data->programme->title, "pdb_link" => $tocFlowData->data->pdb->pdb_link, "role" => $userRole->name);

                    $userFlows[] = $userFlow;
                }
            }


        }

        $userData = json_decode(json_encode($userFlows), false);
        return response()->json(['data' => ['user_id' => auth('api')->user()->id, 'flows' => $userData]], 200);


    }

    public function getroles()
    {
        $userRole = auth('api')->user()->roles()->first();


        $allRoles = array();
        switch ($userRole->name) {

            //that's the way OR is supposed to work. It's either a administrator OR a superadministrator
            case 'administrator':
            case 'superadministrator':
                $roles = Role::where('id', '>', 3);


                foreach ($roles->get() as $role) {
                    $roleData = [
                        'id' => $role->id,
                        'name' => $role->name
                    ];

                    $allRoles[] = $roleData;
                }

                return response()->json(['data' => $allRoles], 200);

            case 'leader':
            default:
                $roles = Role::where('id', '>', 4);


                foreach ($roles->get() as $role) {
                    $roleData = [
                        'id' => $role->id,
                        'name' => $role->name
                    ];

                    $allRoles[] = $roleData;
                }

                return response()->json(['data' => $allRoles], 200);

        }
    }
}
