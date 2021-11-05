<?php


use App\Http\Controllers\API\TocFlowController;
use App\Http\Controllers\GraphDBController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*API Controllers*/

/*Auth*/

/*Third-party OAuth*/

use App\Http\Controllers\API\MelCom;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//in case of MEL OAuth, use the code to receive all the necessary information and register the user to
// the application
Route::post('handleMelCode', [MelCom::class, 'receiveCode']);

// so user(s) must be registered and have valid login token to access the following routes
Route::group(['middleware' => 'auth:api'], function () {
    //log the user out
    Route::post('logout', [RegisterController::class, 'logout']);
    //get the user information
    Route::get('user', [RegisterController::class, 'user']);


    Route::group(['middleware' => 'role:administrator|superadministrator'], function () {

    });

    //All the routes in here are available to users with role administrator or superadministrator
    Route::group(['middleware' => 'role:administrator|superadministrator'], function () {
//        Route::post('authorizeTeamCreation', [TeamsController::class,'authorizeTeamCreation']);
    });

    /*USERS WITH ELEVATED PRIVILAGES*/
    //Authorize the creation of a new team with the given data
    Route::post('authorizeTocCreation', [TeamsController::class, 'authorizeTocCreation']);
    /*USERS WITH ELEVATED PRIVILAGES*/

    /*EVERYONE*/
    //Submit the data for a new team creation. ANYONE can submit a form for creating a new team
    Route::post('requestTocCreation', [TeamsController::class, 'requestTocCreation']);
    Route::post('createtocflow', [TeamsController::class, 'createtocflow']);
    Route::post('updatetocflow', [TeamsController::class, 'updatetocflow']);
//    Route::get('gettocflow', [TeamsController::class, 'gettocflow']);
//Show the tocFlows that need approval (from an admin user)
    Route::get('showTocFlowForApproval', [TeamsController::class, 'showTocFlowForApproval']);
//Show the user tocFlows
    Route::get('getuserflows', [UserController::class, 'getuserflows']);
    /*EVERYONE*/


    /*LEADERS AND COLEADERS*/
//Remove a user from a team
    Route::post('removeTocMember', [TeamsController::class, 'removeTocMember']);

//Invite team members to a  specified tokFlow
    Route::post('createTocMemberInvitation', [TeamsController::class, 'createTocMemberInvitation']);
    /*LEADERS AND COLEADERS*/


    Route::get('getroles', [UserController::class, 'getRoles']);


//get

//Accept the invitation to participate in a team
    Route::post('respondTocMemberInvitation', [UserController::class, 'respondTocMemberInvitation']);

    /*COMMENTS AND MENTIONS WILL FOLLOW THE SAME CRUD OPERATIONS*/
//    /*COMMENTS*/
//    Route::get('showAll', [InteractionController::class, 'showAll']);
//    Route::get('createNew', [InteractionController::class, 'createNew']);
//    Route::get('delete', [InteractionController::class, 'delete']);
//    Route::get('edit', [InteractionController::class, 'edit']);
//    Route::get('suspend', [InteractionController::class, 'suspend']);
//    /*COMMENTS*/


    /*GraphDB*/

    /*SEEDERS*/
    Route::get('seedCountries', [GraphDBController::class, 'seedCountries']);
    Route::get('seedSDGTarget', [GraphDBController::class, 'seedSDGTarget']);
    Route::get('seedSdgIndicator', [GraphDBController::class, 'seedSdgIndicator']);
    Route::get('seedActionareas', [GraphDBController::class, 'seedActionareas']);
    Route::get('seedImpactareas', [GraphDBController::class, 'seedImpactareas']);
    Route::get('seedIndicators', [GraphDBController::class, 'seedIndicators']);
    /*SEEDERS*/

    Route::get('repositoriesStatements', [GraphDBController::class, 'repositoriesStatements']);
    Route::get('addProgramme', [GraphDBController::class, 'addProgramme']);
    Route::get('GraphDBAddUser', [GraphDBController::class, 'GraphDBAddUser']);
    Route::get('GraphDBAddFlow', [GraphDBController::class, 'GraphDBAddFlow']);
    Route::get('addTeamMember', [GraphDBController::class, 'addTeamMember']);
    Route::get('addTOC', [GraphDBController::class, 'addTOC']);
    Route::get('addResult', [GraphDBController::class, 'addResult']);
    Route::get('DefineInnovationPackage', [GraphDBController::class, 'DefineInnovationPackage']);
    Route::get('addCountry', [GraphDBController::class, 'addCountry']);
    Route::get('addRegion', [GraphDBController::class, 'addRegion']);
    Route::get('addActionArea', [GraphDBController::class, 'addActionArea']);
    Route::get('addSDG', [GraphDBController::class, 'addSDG']);
    Route::get('addSdgTarget', [GraphDBController::class, 'addSdgTarget']);
    Route::get('addSdgIndicator', [GraphDBController::class, 'addSdgIndicator']);
    Route::get('addWorkPackage ', [GraphDBController::class, 'addWorkPackage']);
    Route::get('addTocSdg ', [GraphDBController::class, 'addTocSdg']);
    Route::get('addTocImpactArea ', [GraphDBController::class, 'addTocImpactArea']);
    Route::get('addTocResult ', [GraphDBController::class, 'addTocResult']);
    Route::get('addActorType ', [GraphDBController::class, 'addActorType']);
    Route::get('addIndicator ', [GraphDBController::class, 'addIndicator']);
    Route::get('addUnit ', [GraphDBController::class, 'addUnit']);
    Route::get('addAction ', [GraphDBController::class, 'addAction']);
    Route::get('addCausalLink ', [GraphDBController::class, 'addCausalLink']);
    Route::get('addComment ', [GraphDBController::class, 'addComment']);
    Route::get('addImpactArea  ', [GraphDBController::class, 'addImpactArea ']);
    /*SELECT*/
    Route::get('selectUsers ', [GraphDBController::class, 'selectUsers']);
//Route::get('getUserFlows', [GraphDBController::class, 'getUserFlows']);
    Route::get('getTocsInFlow', [GraphDBController::class, 'getTocsInFlow']);
    Route::get('getTocElements', [GraphDBController::class, 'getTocElements']);
//    Route::get('getImpactAreas', [GraphDBController::class, 'getImpactArea']);
    Route::get('getImpactAreas', 'cgiarDataController@CreateNewImpactAreaData');
//    Route::get('getActionAreas', [GraphDBController::class, 'getActionAreas']);
    Route::get('getActionAreas','cgiarDataController@CreateNewActionAreaData');
    Route::get('getlevels', [TocFlowController::class, 'getlevels']);
    Route::get('getprogrammetypes', [GraphDBController::class, 'getprogrammetypes']);

    /*GraphDB*/

    /*MONGODB*/
    /*INSERT*/

    /*TOCS RESOURCE*/
    Route::resource('tocs', TocController::class);
    Route::post('tocs/{id}/publish', 'TocController@publishToc');
    /*THESE TWO NEED FIXING*/
    Route::get('tocs/{tocID}/versions', 'TocController@getTocAllVersions');
    Route::get('tocs/{tocFlowID}/{tocType}', 'TocController@getTocByType');
    /*THESE TWO NEED FIXING*/

//    Route::get('gettocbytocflowid', 'TocController@gettocbytocflowid');
    Route::get('tocflowtocs/{tocFlowID}', 'TocController@gettocbytocflowidnew');
    /*TOCS RESOURCE*/

    /*TOCFLOW RESOURCE*/
    Route::apiResource('tocflow', API\TocFlowController::class);
    Route::put('tocflow/{id}/{response}', 'API\TocFlowController@authorizetocflowcreation');
    Route::get('tocflow/{id}/members', 'TeamsController@showAllTocMembers');
    /*TOCFLOW RESOURCE*/

    /*USER INVITATION RESOURCE*/
    Route::apiResource('invites', API\InvitesController::class);
    Route::post('invites/{id}/{response}', 'API\InvitesController@respondToInvitation');
    /*USER INVITATION RESOURCE*/
    /*INSERT*/
    /*MONGODB*/



});
