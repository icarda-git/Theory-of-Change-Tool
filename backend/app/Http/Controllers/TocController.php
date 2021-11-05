<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Toc;
use App\Models\TocFlows;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Gaufrette\Adapter\AwsS3 as AwsS3Adapter;
use Gaufrette\Filesystem;

class TocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    public function getTocByType($tocFlowID, $tocType)
    {

        $tocs = Toc::where('tocFlow_id', $tocFlowID)->where('toc_type_id', (int)$tocType)->where('deleted', false)->get();

        switch ($tocs) {
            case !$tocs:
                return response()->json(['error' => 'no data'], 404);
            case $tocs:
                return response()->json(['data' => $tocs], 200);
        }
    }


    public function store(Request $request)
    {
        //
        if ($request->has('newVersion')) {

            $existingTocRecord = Toc::where('toc_id', $request->toc_id)->orderBy('version', 'DESC')->first();
            if ($existingTocRecord) {
                $oldTocDocumentID = $existingTocRecord->_id;
                $oldTocId = $existingTocRecord->toc_id;
                return $this->update($request, $oldTocId, $oldTocDocumentID);

            } else if (!$existingTocRecord) {
                return 'reached else if on TocController!';

            }
        } else if (!$request->has('newVersion')) {

            $tocVersion = 0;
            $tocFlowID = $request->tocFlow_id;
            $tocData = $request->toc;

            $newToc = Toc::firstOrCreate([
                'deleted' => false,
                'version' => $tocVersion,
                'number' => $request->number,
                'toc_id' => Str::random(10),
                'tocFlow_id' => $tocFlowID,
                'toc_type' => $request->toc_type,
                'toc_type_id' => $request->toc_type_id,
                'toc_title' => $request->toc_title,
                'toc' => $tocData,
                'published' => false,
                'published_data' => [],
            ]);

            return response()->json(['message' => 'success', 'data' => ['MongoID' => $newToc->_id, 'TocID' => $newToc->toc_id]], 200);

        }
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

        $toc = Toc::where('toc_id', $id)->orWhere('_id', $id)->where('deleted', false)->orderBy('version', 'desc')->first();

        if ($toc != null) {
            return response()->json(['data' => $toc]);
        } else if ($toc == null) {
            return response()->json(['data' => []]);
        }
    }

    public function getTocAllVersions($id)
    {
        $toc = Toc::where('toc_id', $id)->where('deleted', false)->orderBy('version', 'desc')->get();

        if ($toc != null) {
            return response()->json(['data' => $toc]);
        } else if ($toc == null) {
            return response()->json(['data' => []]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
    {
        //
    }


    public function update(Request $request, $id, $documentID = false)
    {
        if (!$request->has('newVersion')) {
            //The request DOES NOT have the newVersion
            $newVersion = false;
//            dd($newVersion);
        } elseif ($request->has('newVersion')) {
            $newVersion = $request->newVersion;
//            dd($newVersion);
        }

        switch ($newVersion) {
            case $newVersion == true:
                try {
                    $oldToc = Toc::where('toc_id', $id)->orderBy('version', 'DESC')->first();
                    $oldTocFlowID = $oldToc->tocFlow_id;
                    $oldTocType = $oldToc->toc_type;
                    $oldTocTypeID = $oldToc->toc_type_id;
                    $oldTocTitle = $oldToc->toc_title;
                    $oldTocVersion = $oldToc->version;
                    $oldTocNumber = $oldToc->number;
                    $oldTocID = $oldToc->toc_id;

                    if (!$oldTocVersion) {
                        $oldTocVersion = 0;
                    }

                    $tocFlowID = $request->tocFlow_id;
                    $toc = Toc::create([
                        'deleted' => false,
                        'version' => $oldTocVersion + 1,
                        'number' => $oldTocNumber,
                        'toc_id' => $oldTocID,
                        'tocFlow_id' => $oldTocFlowID,
                        'toc_type' => $oldTocType,
                        'toc_type_id' => $oldTocTypeID,
                        'toc_title' => $oldTocTitle,
                        'toc' => $request->toc,
                        'published' => false,
                        'published_data' => [],
                    ]);

                    return response()->json(['data' => 'created new document successfully', 'toc_id:' => $toc->toc_id], 200);
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 400);
                }

            case $newVersion == false:
                try {
                    $toc = Toc::where('toc_id', $id)->orderBy('version', 'DESC')->first();
                    if (!$toc) {
                        return response()->json(['error' => 'no toc with the specified toc_id was found']);
                    }
//                    Toc::where('toc_id', $id)->orderBy('version', 'DESC')->update(['toc' => $request->toc]);
                    $toc->update(['toc' => $request->toc]);
                } catch (Exception $e) {
                    return response()->json($e);
                }
                return response()->json(['data' => 'updated existing document (' . $toc->toc_id . ') successfully'], 200);
            default:
                return 'this is the default case';
        }

        return response()->json(['data' => 'success'], 200);

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
    function gettocbytocflowid(Request $request)
    {
        $findTocs = Toc::where('tocFlow_id', $request->tocFlow_id)->get();


        if ($findTocs->isEmpty()) {
            return response()->json(['data' => []], 200);
        } else if ($findTocs != null) {
            $tocs = json_decode($findTocs, false);

            foreach ($tocs as $toc) {

                $formattedToc[] = ['title' => $toc->toc_title, 'id' => $toc->_id, 'toc_type' => $toc->toc_type];
            }

            return response()->json(['data' => $formattedToc], 200);
        }
    }

    function gettocbytocflowidnew($tocFlowID)
    {
//        $findTocs = Toc::where('tocFlow_id', $tocFlowID)->groupBy('toc_id')->get();
        $findTocswithTocFlowID = Toc::groupBy('toc_id')->where('tocFlow_id', $tocFlowID)->get(['version']);

        foreach ($findTocswithTocFlowID as $ftd) {
            $tocID = $ftd->toc_id;

            $tocData = Toc::where('toc_id', $tocID)->orderBy('version', 'desc')->first();

            $latestTocs[] = $tocData;
        }

        if ($latestTocs == null) {
            return $latestTocs = 0;
        } else {
            return $latestTocs;
        }

        $filename = 'getLevels';
        $path = storage_path() . "/json/${filename}.json";
        $json = json_decode(file_get_contents($path), true);


        $tocLevels = ($json['toc_levels']);


        if ($latestTocs = 0) {
            return response()->json(['data' => []], 200);
        } else if ($latestTocs > 0) {
            $tocs = json_decode($findTocs, false);

            foreach ($latestTocs as $toc) {

                foreach ($tocLevels as $tocLevel) {
                    if ($toc->toc_type == ($tocLevel['title'])) {
                        $tocID = $tocLevel['id'];
                    } else {
                        $tocID = 1;
                    }
                }
                $formattedToc[] = [
                    'version' => $toc->version,
                    'title' => $toc->toc_title,
                    'id' => $toc->_id,
                    'toc_type' => $toc->toc_type,
                    'toc_type_id' => $toc->toc_type_id,
                    'toc_id' => $toc->toc_id,
                    'number' => $toc->number
                ];
            }

            return response()->json(['data' => $formattedToc], 200);
        }
    }

    public function publishToc($id, Request $request)
    {
        $requestNarrativeData = $request->narrative;
        if ($requestNarrativeData == null) {
            $requestNarrativeData = '';
        }
        $userID = auth('api')->user()->id;
        $findToc = Toc::where('toc_id', $id)->where('deleted', false)->orderBy('version', 'desc')->first();


        $img = str_replace('data:image/png;base64,', '', $request->image);
        $img = str_replace(' ', '+', $img);
        $imageData = base64_decode($img);

        if ($findToc != null) {
            //toc was found, will be published

            $requestData = json_decode($request->getContent());
            $fileName = $findToc->toc_id;
            $s3client = new S3Client([
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
            ]);

            $adapter = new AwsS3Adapter($s3client, env('AWS_BUCKET_NAME'));
            $filesystem = new Filesystem($adapter);

            //write arguments: filename, directory, overwrite
            $imageFile = $filesystem->write("toc_$fileName/$fileName.png", $imageData, true);
            $textFile = $filesystem->write("toc_$fileName/$fileName.txt", $requestNarrativeData, true);
            $findToc->update(['published' => true]);
            $findToc->update([
                'published_data' => [
//                    'narrativeUrl' => 'https://dev-toc.s3.us-east-2.amazonaws.com/user_' . $userID . '/' . $fileName . '.txt',
//                    'imageUrl' => 'https://dev-toc.s3.us-east-2.amazonaws.com/user_' . $userID . '/' . $fileName . '.png'
                     'narrativeUrl' => 'https://dev-toc.s3.us-east-2.amazonaws.com/toc_' . $findToc->toc_id . '/' . $fileName . '.txt',
                    'imageUrl' => 'https://dev-toc.s3.us-east-2.amazonaws.com/toc_' . $findToc->toc_id . '/' . $fileName . '.png'
                ]
            ]);

            return response()->json(['narrativeUrl' => 'https://dev-toc.s3.us-east-2.amazonaws.com/toc_' . $findToc->toc_id . '/' . $fileName . '.txt', 'imageUrl' => 'https://dev-toc.s3.us-east-2.amazonaws.com/toc_' . $findToc->toc_id . '/' . $fileName . '.png'], 200);

        } else if (!$findToc) {
            //toc was not found OR is flagged as deleted, return error message.

            return response()->json(['error' => 'the given toc_id was not found OR the toc is flagged as deleted'], 404);

        }

    }

    public function deteleAllMongo()
    {
//        dd(auth('api')->user());
        $teams = Team::where('user_leader_id', 18)->get();
        $teamCount = $teams->count();
        $tocCount = 0;
        $tocFlowCount = 0;
        foreach ($teams as $team) {
            $tocFlow = TocFlows::where('data.team_id', $team->id)->first();
//            dd($tocFlow);

            if ($tocFlow != null) {
                $tocs = Toc::where(['tocFlow_id' => $tocFlow->_id])->get();

                foreach ($tocs as $toc) {
                    $toc->delete();
                    $tocCount++;
                }
                $tocFlow->delete();
                $tocFlowCount++;

                $team->delete();
            }
        }


        return response()->json(['deleted' => $teamCount, 'tocs' => $tocCount, 'tocFlows' => $tocFlowCount], 200);
    }

    public function showNonEmptyTocs()
    {
        $tocs = Toc::get();

        foreach ($tocs as $toc){
            if($toc['toc.elements'] == null){
                continue;
//                dd('empty');
            }else{
                foreach($toc['toc.elements'] as $element){
//                    dd($element['type']);
                    if(!array_key_exists('type',$element)){
                        continue;
                    }
                    else if($element['type'] == 'SDGIndicator'){
                        $returnData[] = $toc;
                    }
                }
//                dd('NOT empty');
            }
        }

        return response()->json(['count' =>count($returnData), 'data' => $returnData], 200);
    }


}
