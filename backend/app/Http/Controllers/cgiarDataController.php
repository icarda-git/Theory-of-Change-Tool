<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class cgiarDataController extends Controller
{
    //
    public function CreateNewActionAreaData()
    {

        $client = new Client();
        $response = $client->get('https://clarisa.cgiar.org/api/action-areas', [
            'auth' => [
                env('CLARISA_USERNAME'),
                env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());

        foreach ($responseData as $data) {
            $actionAreas[] = [
                'code' => $data->id,
                'title' => $data->name,
                'image' =>  env('APP_URL').'/img/actions_areas/AA-0'.$data->id.'.png',
                'description' => $data->description,
                'outcomes' => []
            ];
        }

//        dd($actionAreas);


        $response = $client->get('https://clarisa.cgiar.org/api/actionAreaOutcomeIndicators', [
            'auth' => [
                env('CLARISA_USERNAME'),
                env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());


        foreach ($responseData as $data) {
            $actionAreaOutcomes[] = [
                'code' => $data->actionAreaId . '.' . $data->outcomeId,
                'SMOCode' => $data->outcomeSMOcode,
                'description' => $data->outcomeStatement
            ];
        }

        $uniqueActionAreaOutcomes = array_unique($actionAreaOutcomes, SORT_REGULAR);

//        dd($uniqueActionAreaOutcomes);

        foreach ($actionAreas as $actionArea) {
            foreach ($uniqueActionAreaOutcomes as $data) {
//                dd($uniqueActionAreaOutcomes);
//                dd((integer)$data['code'][0]);
//                dd($actionArea['code']);
                if ($actionArea['code'] == (integer)$data['code'][0]) {

                    $outcomesArray[] = $data;

                }

            }
            $actionArea['outcomes'] = json_decode(json_encode($outcomesArray));

            $finalActionAreas[] = $actionArea;

            $outcomesArray = [];
        }

        return response()->json(['action_areas' => $finalActionAreas]);

    }

    public function CreateNewImpactAreaData()
    {
        $client = new Client();
        $response = $client->get('https://clarisa.cgiar.org/api/impact-areas', [
            'auth' => [
                env('CLARISA_USERNAME'),
                env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());

        foreach ($responseData as $data) {
            $impactAreas[] = [
                'code' => $data->id,
                'name' => $data->name,
                'image' => env('APP_URL').'/img/impact_areas/IA-0'.$data->id.'.png',
                'description' => $data->description,
                'indicators' => []
            ];
        }

        $response = $client->get('https://clarisa.cgiar.org/api/impact-areas-indicators', [
            'auth' => [
                env('CLARISA_USERNAME'),
                env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());


        foreach ($responseData as $data) {
            $impactAreaOutcomes[] = [
                'code' => $data->impactAreaId,
                'id' => $data->indicatorId,
                'title' => $data->indicatorStatement,
                'target_year' => $data->targetYear,
                'unit' => $data->targetUnit,
                'value' => $data->value
            ];
        }


        foreach ($impactAreas as $impactArea) {

            foreach ($impactAreaOutcomes as $data) {
//                dd((integer)$data['code'][0]);
//                dd($actionArea['code']);
                if ($impactArea['code'] == $data['code']) {

                    $outcomesArray[] = $data;

                }

            }
            $impactArea['indicators'] = json_decode(json_encode($outcomesArray));

            $finalActionAreas[] = $impactArea;

            $outcomesArray = [];
        }

        return response()->json(['impact_areas' => $finalActionAreas]);
    }

    public function CreateNewSDGData()
    {
        $client = new Client();
        $response = $client->get('https://clarisa.cgiar.org/api/allSDG', [
            'auth' => [
               env('CLARISA_USERNAME'),
               env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());

        foreach ($responseData as $data) {
            $sdgBasicData[] = [
                'code' => $data->usndCode,
                'title' => $data->shortName,
                'description' => $data->fullName,
                'image' => env('APP_URL').'/img/sdg/SDG00'.$data->usndCode.'.png',
                'targets' => []
            ];
        }

        $response = $client->get('https://clarisa.cgiar.org/api/allSDGTargets', [
            'auth' => [
                env('CLARISA_USERNAME'),
                env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());


        foreach ($responseData as $data) {
            $sdgTargetsData[] = [
                'code' => $data->sdgTargetCode,
                'title' => $data->sdgTarget,
                'indicators' => []
            ];
        }

        $response = $client->get('https://clarisa.cgiar.org/api/allSDGIndicators', [
            'auth' => [
                env('CLARISA_USERNAME'),
                env('CLARISA_PASSWORD'),
            ]
        ]);

        $responseData = json_decode($response->getBody());


        foreach ($responseData as $data) {

            $sdgTargetIndicators[] = [
                'code' => $data->indicatorCode,
                'unsd_code' => $data->unsdIndicatorCode,
                'title' => $data->indicatorName
            ];
        }

        foreach ($sdgTargetsData as $sdgTargetData) {

            foreach ($sdgTargetIndicators as $sdgTargetIndicator) {
//                dd($sdgTargetData['code']);
//                dd(substr($sdgTargetIndicator['code'],0,-2));
                if ($sdgTargetData['code'] == substr($sdgTargetIndicator['code'],0,-2)) {

                    $outcomesArray[] = $sdgTargetIndicator;

//                    dd($outcomesArray);
                }

            }
            $sdgTargetData['indicators'] = json_decode(json_encode($outcomesArray));

            $finalTargetsData[] = $sdgTargetData;

            $outcomesArray = [];
        }


        foreach($sdgBasicData as $sdgBasicDato){
            foreach ($finalTargetsData as $finalTargetData){

                if($sdgBasicDato['code'] == substr($finalTargetData['code'],0,-2)){
                    $finalTargets[] = (object)$finalTargetData;
                }
            }
//            $sdgBasicDato['targets'] = json_decode(json_encode($finalTargets));
            $sdgBasicDato['targets'] = json_decode(json_encode($finalTargets));

            $finalMergedData[] = $sdgBasicDato;

            $finalTargets = [];
        }


        return response()->json(['sdgs' => $finalMergedData]);
    }
}
