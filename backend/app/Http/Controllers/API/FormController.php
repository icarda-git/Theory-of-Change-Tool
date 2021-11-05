<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FormResource;


/*
 * Someone is here?
 * Oh, hey! No no no no don't run away!
 * Let me show you the magic of this controller
 * (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*(˘▾˘~)
 *Store(Request $request): takes all the data it's posted on this controller and stores it in a (mongo) database.
*/

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index()
//    {
//        $forms = Form::all();
//        return response(['forms' => FormResource::collection($forms), 'message' => 'Retrieved successfully'], 200);
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        //TODO: Enable this again... Sometime
//        Looks up on the upcoming data
//        $validator = Validator::make($data, [
//            'innovation_record' => [
//                'deleted' => 'required',
//                'user_id' => 'required',
//                'data' => 'required'
//            ]
//        ]);

//        if ($validator->fails()) {
//            return response()->json(['message' => $validator->errors(), 'Validation Error'], 400);
//        } else {

        try {
            $innovation = Form::create([
                'innovation_record' => [
                    'deleted' => false,
                    'user_id' => auth()->guard('api')->user()->id,
                    'data' =>
                    /*New data here*/
                        $data,
                ],
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Something went wrong ' . $e], 400);
        }

        if ($innovation->exists) {
            return response()->json(['message' => 'Innovation stored successfully'], 201);
        }

//        }

    }

    /**
     * Display the all resource(s) of the authenticated user.
     *
     * @param \App\Models\Form $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUsersInnovations()
    {

        /*Show all the users Innovations that are NOT marked as deleted*/
        $usersInnovations = Form::where('innovation_record.user_id', auth()->guard('api')->user()->id)->where('innovation_record.deleted', false);

        $usersInnovationsResult = $usersInnovations->get();
        //If there are results
        if (!$usersInnovations) {
            return response()->json(['message' => 'Nothing found'], 200);
        } else {
            //else return nothing
            return response()->json(['message' => 'Found', 'data' => $usersInnovationsResult], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Form $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(request $request)
    {
        /*Show a specific innovation based on the ID provided*/
        $innovation = Form::where('_id', $request->id);
        $innovationResult = ($innovation->get());

        if ($innovationResult->isEmpty()) {
            return response()->json(['message' => 'Nothing found'], 201);
        } else {
            return response()->json(['message' => 'Found', 'data' => $innovationResult], 201);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Form $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(request $request)
    {
        /*Update an innovation based on the given ID*/
        //Find for the innovation based on the provided ID
        $innovation = Form::where('_id', $request->id)->get()->first();

        //No innovation found to update
        if (!$innovation) {
            return response()->json(['message' => 'Nothing found'], 401);
        } else {
            //Innovation found and will be updated with the new provided data
            $innovation->update(["innovation_record" => $request->innovation_record]);
            return response()->json(['message' => 'Updated'], 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Form $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(request $request)
    {
        //Find the innovation
        $innovation = Form::where('_id', $request->id)->where('innovation_record.user_id', auth()->guard('api')->user()->id)->first();

        $innovationTitle = $innovation['innovation_record.data.innovationData.initiative.value'];
        //dd($innovation['innovation_record.data.innovationData.initiative.value']);
        if ($innovation) {
            //Update
            $innovation->update(['innovation_record.deleted' => $request->status]);
            return response()->json(['message' => $innovationTitle . ' was altered successfully'], 201);
        } else {
            //Innovation not found
            return response()->json(['message' => 'Your requested innovation was not found'], 401);
        }

    }
}
