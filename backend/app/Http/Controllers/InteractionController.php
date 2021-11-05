<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use function PHPUnit\Framework\isEmpty;

class InteractionController extends Controller
{
    //

    /*Handle multiple different kind of requests. The controller action will be based on data the JSON provides to out Laravel API.
    -Comments
    -Mentions
    */

    public function index()
    {

    }

    public function showAll(Request $request)
    {

        $request = json_decode($request->getContent(), false);
        $action = $request->tokFlow_record->field;

        if ($action == 'comment') {
            return response()->json([
                'data' => Comment::where($request->tokFlow_record->tokFlow_id)->get()
            ]);
        } else {
            return response()->json(['message' => 'Your filed value is not comment. This will be filled later']);
        }
    }

    public function createNew(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        $action = $request->tokFlow_record->field;


        if ($action == 'comment') {

            //check if comments exists for this tokFlow (team)

            $existingRecord = Comment::where('tokFlow_details.tokFlow_id', $request->tokFlow_record->tokFlow_id)->first();


            if ($existingRecord) {
                //There are existing records. Update the old one and add to them the new comment data
                //UPDATE
                return $this->updateCommentRecord($existingRecord, $request);
//                return response()->json(['message'=>'A record exists for TokFlow '.$request->tokFlow_record->tokFlow_id,'data' => $existingRecord->tokFlow_comments['data']]);


            } else {

                $commentData[] = [
                    'comment' => $request->tokFlow_record->data->text,
                    'user_id' => auth('api')->user()->id,
                    'submissionTime' => Carbon::now()->timestamp
                ];
                //There are NO existing records. Create a new one and return a success message
                //CREATE
                $comment = Comment::firstOrCreate([
                    'tokFlow_details' => [
                        'tokFlow_id' => $request->tokFlow_record->tokFlow_id,
                    ],
                        'comments'=> $commentData

                ]);

                //print OK message
                return response()->json(['message' => 'New comment document was successfully created for your tokFlow ' . $request->tokFlow_record->data->text]);
            }

        } else {
            return 'Anything else besides comment';
        }

    }


    public function delete()
    {
    }

    public function show()
    {
    }

    public function suspend()
    {
    }

    public function updateCommentRecord($oldRecord, $newRecord)
    {
        //Find team name
        $team = Team::where('id', $newRecord->tokFlow_record->tokFlow_id)->first();



//        return $oldRecord->tokFlow_details['tokFlow_id'];
        $oldComments = $object = json_decode(json_encode($oldRecord->comments), FALSE);
        $newComments = $newRecord->tokFlow_record;

//
//        return response()->json($newComments->data);

//        return gettype([$newComments]);

        Comment::where('tokFlow_details.tokFlow_id', $oldRecord->tokFlow_details['tokFlow_id'])->push('comments', $newComments->data);


        return response()->json(['message' => $team->name . "'s team comment were updated"], 200);


    }

}
