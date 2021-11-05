<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Toc;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request = json_decode($request->getContent(), false);
        urlencode($toccode = $request->graphDB_record->data->toccode);
        urlencode($flow = $request->graphDB_record->data->flow);
        urlencode($toclevel = $request->graphDB_record->data->toclevel);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $toccode . '%20a%20ics%3A' . $toclevel . '%3B%20%20%20ics%3Abelongs_to_flow%20icarda%3A' . $flow . '.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/rdf+xml',
                'Accept: text/plain'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
