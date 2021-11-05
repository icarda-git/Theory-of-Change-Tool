<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class GraphDBController extends Controller
{
    //
    public function addProgramme(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($code = $request->graphDB_record->data->code);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20INSERT%20DATA%20%7B%20icarda%3Acrp-' . $code . '%20a%20ics%3ACgiarInitiative%20%3B%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20dct%3Atitle%20%22' . $title . '%22%40en%20%3B%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20ics%3Aprogramme-code%20' . $code . '%20.%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%7D&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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
        return ($response);
    }

    public function GraphDBAddUser(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($userName = $request->graphDB_record->data->name);
        urlencode($userMail = $request->graphDB_record->data->mail);
        urlencode($userCode = $request->graphDB_record->data->code);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20foaf%3A%20%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Auser155%20a%20ics%3AToCUser%3B%20%20%20foaf%3Aname%20%22' . $userName . '%22%3B%20%20%20foaf%3Aemail%20%22' . $userMail . '%22%3B%20%20%20ics%3Auser_identifier%20%22' . $userCode . '%22.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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
        return ($response);
    }

    public function GraphDBAddFlow(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($programmecode = $request->graphDB_record->data->programmecode);
        urlencode($flowIdentifier = $request->graphDB_record->data->flowIdentifier);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Aflowidentifier%20a%20ics%3ATocFlow%20%3B%20%20%20ics%3Afor_programme%20icarda%3A' . $programmecode . '%20%3B%20%20%20ics%3Aflow_identifier%20%22' . $flowIdentifier . '%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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
        return ($response);
    }

    public function addTeamMember(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($flowID = $request->graphDB_record->data->flowID);
        urlencode($userID = $request->graphDB_record->data->userID);
        urlencode($userRole = $request->graphDB_record->data->role);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $flowID . '%20ics%3Ahas_membership%20icarda%3Amembership001%20.%20%20%20icarda%3Amembership001%20ics%3Ahas_user%20icarda%3A' . $userID . '%3B%20%20%20ics%3Aacting_as%20ics%3A' . $userRole . '.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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

    public function addTOC(Request $request)
    {
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

    public function addResult(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($resultCode = $request->graphDB_record->data->resultCode);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($programCode = $request->graphDB_record->data->programCode);
        urlencode($resultType = implode(',', $request->graphDB_record->data->resultType));


        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20INSERT%20DATA%20%7B%20icarda%3A' . $resultCode . '%20a%20ics%3A' . $resultType . '%20%3B%20dct%3Atitle%20%22' . $title . '%22%20%3B%20ics%3Aproduced_by_programme%20icarda%3A' . $programCode . '2%20.%20%7D&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';


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

    public function DefineInnovationPackage(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($packageCode = $request->graphDB_record->data->packageCode);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($coreInnovationCode = $request->graphDB_record->data->coreinnovationCode);
        urlencode($geocScope = implode(',', $request->graphDB_record->data->geocScope));

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $packageCode . '%20a%20ics%3AInnovationPackage%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%3B%20%20%20ics%3Aincludes_innovation%20icarda%3A' . $coreInnovationCode . '%3B%20%20%20ics%3Ahas_core_innovation%20icarda%3Acoreinnovationcode%20%3B%20%20%20ics%3Aapplies_to_scope%20icarda%3Ageoscope1%2C%20icarda%3Ageoscope2%2C%20icarda%3Ageoscope3%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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

    public function addCountry(Request $request)
    {

        $request = json_decode($request->getContent(), false);
        urlencode($isoCode = $request->graphDB_record->data->isocode);
        urlencode($name = $request->graphDB_record->data->name);
        urlencode($regionCode = $request->graphDB_record->data->regioncode);


//        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20foaf%3A%20%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Acountrycode%20a%20ics%3ACountry%20%3B%20%20%20foaf%3Aname%20%22'.$name.'%22%20%3B%20%20%20ics%3Aalphacode2%20'.$isoCode.'%3B%20%20%20ics%3Ain_region%20ics%3Aregion'.$regionCode.'%20.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';
        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20foaf%3A%20%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Acountrycode%20a%20ics%3ACountry%20%3B%20%20%20foaf%3Aname%20%22' . $name . '%22%20%3B%20%20%20ics%3Aalphacode2%20' . $isoCode . '%3B%20%20%20ics%3Ain_region%20ics%3Aregion' . $regionCode . '%20.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';

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

    public function addRegion(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($name = $request->graphDB_record->data->name);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($parentRegion = $request->graphDB_record->data->parentRegion);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20foaf%3A%20%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Aregioncode%20a%20ics%3ARegion%20%3B%20%20%20foaf%3Aname%20%22' . $name . '%22%3B%20%20%20ics%3Aun49code%20' . $code . '%3B%20%20%20ics%3Aparent_region%20ics%3A' . $parentRegion . '.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';

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

    public function addActionArea(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($areaID = $request->graphDB_record->data->areaID);
        urlencode($areaTitle = $request->graphDB_record->data->areaTitle);
        urlencode($areaDesc = $request->graphDB_record->data->areaDesc);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $areaID . '%20a%20ics%3AActionArea%20%3B%20%20%20dct%3Atitle%20%22' . $areaTitle . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $areaDesc . '%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';

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

    public function addSDG(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($sdgCode = $request->graphDB_record->data->sdgCode);
        urlencode($sdgTitle = $request->graphDB_record->data->sdgTitle);
        urlencode($sdgDesc = $request->graphDB_record->data->sdgDesc);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Asdg' . $sdgCode . '%20a%20ics%3ASDG%20%3B%20%20%20dct%3Atitle%20%22' . $sdgTitle . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $sdgDesc . '%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';

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

    public function addSdgTarget(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($id = $request->graphDB_record->data->id);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($desc = $request->graphDB_record->data->desc);
        urlencode($sdg = implode(',', $request->graphDB_record->data->sdg));

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $id . '%20a%20ics%3ASdgTarget%20%3B%20%20%20dct%3Atitle%20' . $code . '%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20%3B%20%20%20ics%3Aunder_sdg%20' . $sdg . '.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';

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

    public function addSdgIndicator(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($id = $request->graphDB_record->data->id);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($uncode = $request->graphDB_record->data->uncode);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($desc = $request->graphDB_record->data->desc);
        urlencode($sdgtarget = implode(',', $request->graphDB_record->data->sdgDesc));

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Asdgtargetid%20a%20ics%3ASdgTarget%20%3B%20%20%20ics%3Auncode%20%22CODE%22%20%3B%20%20%20dct%3Atitle%20%22TITLE%22%20%3B%20%20%20dct%3Adescription%20%22DESC%22%20%3B%20%20%20ics%3Afor_target%20icarda%3Asdg' . $sdgtarget . '%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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

    public function addWorkPackage(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($id = $request->graphDB_record->data->id);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($programme = $request->graphDB_record->data->programme);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $id . '%20a%20ics%3AWorkPackage%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20%3B%20%20%20ics%3Aunder_programme%20icarda%3A' . $programme . '%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';

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

    public function addTocSdg(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($toc = $request->graphDB_record->data->toc);
        urlencode($sdgcode = $request->graphDB_record->data->sdgcode);
        urlencode($target = $request->graphDB_record->data->target);
        urlencode($indicator = $request->graphDB_record->data->indicator);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Atoccodesdgcode%20a%20ics%3ASdgTocEntity%20%3B%20%20%20ics%3Ain_toc%20icarda%3A' . $toc . '%20%3B%20%20%20ics%3Arefers_to_sdg%20icarda%3A' . $sdgcode . '%20%3B%20%20%20ics%3Aincludes_sdg_target%20icarda%3A' . $target . '%20%3B%20%20%20ics%3Aincludes_sdg_indicator%20icarda%3A' . $indicator . '%20.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';

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

    public function addTocImpactArea(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($toc = $request->graphDB_record->data->toc);
        urlencode($impactarea = $request->graphDB_record->data->impactarea);
        urlencode($target = $request->graphDB_record->data->target);
        urlencode($narative = $request->graphDB_record->data->narative);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $impactarea . '%20a%20ics%3AImpactAreaTocEntity%20%3B%20%20%20ics%3Ain_toc%20icarda%3A' . $toc . '%20%3B%20%20%20ics%3Arefers_to_impact_area%20icarda%3Aimpactarea%20%3B%20%20%20ics%3Aincludes_target%20icarda%3A' . $target . '%20%3B%20%20%20ics%3Aimpact_area_narrative%20icarda%3A' . $narative . '%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';
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

    public function addTocResult(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($toc = $request->graphDB_record->data->toc);
        urlencode($resultcode = $request->graphDB_record->data->impactarea);
        urlencode($resulttype = $request->graphDB_record->data->target);
        urlencode($responsible = $request->graphDB_record->data->narative);
        urlencode($genderaspect = $request->graphDB_record->data->narative);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20INSERT%20DATA%20%7B%20%20%20icarda%3Atocresult' . $resultcode . '%20a%20ics%3A' . $resulttype . '%20%3B%20%20%20ics%3Ain_toc%20icarda%3A' . $toc . '%20%3B%20%20%20ics%3Aresponsible_entity%20icarda%3A' . $responsible . '%20%3B%20%20%20ics%3Aresult_indicator%20icarda%3Aindicator000%2C%20icarda%3Aindicator1111%3B%20%20%20ics%3Ahas_gender_responsive_transformative_aspect%20%22' . $genderaspect . '%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';
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

    public function addActorType(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($title = $request->graphDB_record->data->title);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $code . '%20a%20ics%3AActorType%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20.%20%20%20%7D&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';
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

    public function addIndicator(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($desc = $request->graphDB_record->data->desc);
        urlencode($unit = $request->graphDB_record->data->unit);
        urlencode($target = $request->graphDB_record->data->target);
        urlencode($baseline_value = $request->graphDB_record->data->baselineValue);
        urlencode($baseline_yeaer = $request->graphDB_record->data->baselineYear);
        urlencode($target_value = $request->graphDB_record->data->targetValue);
        urlencode($target_year = $request->graphDB_record->data->targetYear);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $code . '%20a%20ics%3AIndicator%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20%3B%20%20%20ics%3Ameasured_in%20icarda%3A' . $unit . '%20%3B%20%20%20ics%3Afor_target%20icarda%3A' . $target . '%20%3B%20%20%20ics%3Abaseline_value%20' . $baseline_value . '%20%3B%20%20%20ics%3Abaseline_year%20' . $baseline_yeaer . '%20%3B%20%20%20ics%3Atarget_value%20' . $target_value . '%20%3B%20%20%20ics%3Atarget_year%20' . $target_year . '%20.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';
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

    public function addUnit(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($desc = $request->graphDB_record->data->desc);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $code . '%20a%20ics%3AUnitOfMeasurement%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';
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

    public function addAction(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($actor = $request->graphDB_record->data->actor);
        urlencode($desc = $request->graphDB_record->data->desc);
        urlencode($scope = implode(',', $request->graphDB_record->data->scope));
        urlencode($assumption = $request->graphDB_record->data->assumption);


        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $code . '%20a%20ics%3AAction%20%3B%20%20%20ics%3Aresponsible_actor%20icarda%3A' . $actor . '%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20%3B%20%20%20ics%3Aaction_scope%20' . $scope . '%20%3B%20%20%20ics%3Aunder_assumption%20%22' . $assumption . '%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';
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

    public function addCausalLink(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($toc = $request->graphDB_record->data->toc);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($fromresult = $request->graphDB_record->data->fromresult);
        urlencode($toresult = $request->graphDB_record->data->toresult);
        urlencode($action = implode(',', $request->graphDB_record->data->action));
        urlencode($narrative = $request->graphDB_record->data->narrative);


        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $code . '%20a%20ics%3ACausalLink%20%3B%20%20%20ics%3Adefined_in_toc%20icarda%3A' . $toc . '%20%3B%20%20%20ics%3Alink_from%20icarda%3A' . $fromresult . '%20%3B%20%20%20ics%3Alink_to%20icarda%3A' . $toresult . '%20%3B%20%20%20ics%3Aexpected_action%20' . $action . '%3B%20%20%20ics%3Acausal_link_' . $narrative . '%20%22nararative%22%20.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';
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

    public function addComment(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($code = $request->graphDB_record->data->code);
        urlencode($item = $request->graphDB_record->data->item);
        urlencode($creator = $request->graphDB_record->data->creator);
        urlencode($mentions = implode(',', $request->graphDB_record->data->mentions));
        urlencode($content = $request->graphDB_record->data->content);


        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $code . '%20a%20ics%3AComment%20%3B%20%20%20ics%3Acomment_target%20icarda%3A' . $item . '%20%3B%20%20%20ics%3Acommenter%20icarda%3A' . $creator . '%20%3B%20%20%20ics%3Amentions%20' . $mentions . '%3B%20%20%20dct%3Adescription%20%22' . $content . '%22.%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';
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

    public function addImpactArea(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($id = $request->graphDB_record->data->id);
        urlencode($title = $request->graphDB_record->data->title);
        urlencode($description = $request->graphDB_record->data->description);


        $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%2520icarda%253A%2520%253Chttp%253A%252F%252Fwww.icarda.org%252Fdata%252Ftoc%252F%253E%2520%2520%2520PREFIX%2520ics%253A%2520%253Chttp%253A%252F%252Fwww.icarda.org%252Fschemas%252Ftoc%252F%253E%2520%2520%2520PREFIX%2520dct%253A%2520%253Chttp%253A%252F%252Fpurl.org%252Fdc%252Fterms%252F%253E%2520%2520%2520%2520%2520%2520INSERT%2520DATA%2520%257B%2520%2520%2520icarda%253A' . $id . '%2520a%2520ics%253AimpactArea%2520%253B%2520%2520%2520dct%253Atitle%2520%2522' . $title . '%2522%253B%2520%2520%2520dct%253Adescription%2520%2522' . $description . '%2522%2520.%2520%2520%2520%257D%2520&baseURI=http%253A%252F%252Fwww.icarda.org%252Fdata%252Ftoc%252F';
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


    public function selectUsers(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        urlencode($userID = $request->graphDB_record->data->userID);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC?query=PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20foaf%3A%20%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%20%20%20PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20%20%20%20SELECT%20%3Fs%20%3Fname%20%3Femail%20%20%20WHERE%20%7B%20%20%20%20%20%20%20%3Fs%20a%20ics%3AToCUser%20%3B%20%20%20%20%20%20%20%20%20%20ics%3Auser_identifier%20%22' . $userID . '%22%20%3B%20%20%20%20%20%20%20%20%20%20foaf%3Aname%20%3Fname%20%3B%20%20%20%20%20%20%20%20%20%20foaf%3Aemail%20%3Femail%20.%20%20%20%7D%20';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept' => 'application/sparql-results+json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function getUserFlows(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        ($userID = $request->graphDB_record->data->userID);

        $url = 'http://graphdb.scio.services:7200/repositories/TOC?query=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20SELECT%20%3Ftoc%20%20%20WHERE%20%7B%20%20%20%20%20%20%20%3Ftoc%20ics%3Abelongs_to_flow%20icarda%3Aflow001%20.%20%20%20%7D%20';

        $response = Http::withHeaders([
            'Accept: application/sparql-results+xml'
        ])->get($url);

        return $response;
    }

    public function getTocsInFlow(Request $request)
    {
        $request = json_decode($request->getContent(), false);
        ($flowID = $request->graphDB_record->data->flowID);

        $response = Http::withHeaders([
            'Accept: application/sparql-results+xml'
        ])->get('http://graphdb.scio.services:7200/repositories/TOC?query=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20SELECT%20%3Ftoc%20%20%20WHERE%20%7B%20%20%20%20%20%20%20%3Ftoc%20ics%3Abelongs_to_flow%20icarda%3A' . $flowID . '%20.%20%20%20%7D%20');

        return $response;

    }

    public function getTocElements(Request $request)
    {
        $request = json_decode($request->getContent(), false);

        ($flowID = $request->graphDB_record->data->flowID);

        $response = Http::withHeaders([
            'Accept: application/sparql-results+xml'
        ])->get('http://graphdb.scio.services:7200/repositories/TOC?query=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20%20%20%20SELECT%20%3Fsdg%20%3Fiate%20%3Fresult%20%3Flink%20%20%20WHERE%20%7B%20%20%20%20%20%20%20icarda%3A' . $flowID . '%20ics%3Ainvolves_impact_area%20%3Fiate%20%3B%20%20%20%20%20%20%20%20%20%20%20%20ics%3Ainvolves_sdg%20%3Fsdg%20%3B%20%20%20%20%20%20%20%20%20%20%20%20ics%3Ainvolves_result%20%3Fresult%20%3B%20%20%20%20%20%20%20%20%20%20%20%20ics%3Adefined_causal_link%20%3Flink%20.%20%20%20%7D%20');

        return $response;
    }

    public function seedCountries()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://clarisa.cgiar.org/api/countries',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic Z2xkYy5kYXRhOjc4MjMyODI=',
                'Cookie: JSESSIONID=258F3497D42FA136F31776DFDAB127F6'
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);


        $countries = json_decode($response, false);


        $i = 0;


        foreach ($countries as $country) {
//            return gettype($data->graphDB_record->data['isocode']);

//            return $this->startSeedingCountry($country);
            $dataArray = array("graphDB_record" => [
                "data" => [
                    "isocode" => $country->isoAlpha2,
                    "code" => $country->code,
                    "name" => $country->name,
                    "regionCode" => $country->regionDTO->um49Code,

                ]
            ]);

            $object = json_decode(json_encode($dataArray), FALSE);

            //Example used for first country
            //iso code = AD = isoAlpha2 -> alphacode2
            // code = 20 = code -> country6060 country20
            // name = Andora = name -> name
            //regioncode = 39 = regionDTO.um49Code -> region100

            urlencode($isoCode = $object->graphDB_record->data->isocode);
            urlencode($countryCode = 'country' . $object->graphDB_record->data->code);
            urlencode($name = $object->graphDB_record->data->name);
            urlencode($regionCode = 'region' . $object->graphDB_record->data->regionCode);

            $response = Http::withHeaders([
                'Content-Type: application/rdf+xml',
                'Accept: application/json'
            ])->get('http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20foaf%3A%20%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $countryCode . '%20a%20ics%3ACountry%20%3B%20%20%20foaf%3Aname%20%22' . $name . '%22%20%3B%20%20%20ics%3Aalphacode2%20%22' . $isoCode . '%22%3B%20%20%20ics%3Ain_region%20ics%3A' . $regionCode . '.%20%20%7D%20');


            //return response()->json($response);

            $i = $i + 1;
        }


        return response()->json("ran for " . $i);

    }

    public function seedSDGTarget()
    {
        $i = 0;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://clarisa.cgiar.org/api/allSDGIndicators',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=258F3497D42FA136F31776DFDAB127F6'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $sdgTargets = json_decode($response, false);

//        return response()->json($sdgTargets);


        foreach ($sdgTargets as $sdgTarget) {

            /*DATA*/
            $dataArray = array("graphDB_record" => [
                "data" => [
                    "id" => $sdgTarget->sdgTarget->id,
                    "code" => $sdgTarget->sdgTarget->sdgTargetCode,
                    "desc" => $sdgTarget->sdgTarget->sdgTarget,
                    "sdg" => $sdgTarget->sdgTarget->sdg->smoCode

                ]
            ]);
            $object = json_decode(json_encode($dataArray), FALSE);

            /*DATA*/

//            return response()->json($object);

            urlencode($id = $object->graphDB_record->data->id);
            urlencode($code = $object->graphDB_record->data->code);
            urlencode($desc = $object->graphDB_record->data->desc);
//            urlencode($sdg = implode(',', $object->graphDB_record->data->sdg));
            urlencode($sdg = $object->graphDB_record->data->sdg);


//            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%2520icarda%253A%2520%253Chttp%253A%252F%252Fwww.icarda.org%252Fdata%252Ftoc%252F%253E%2520%2520%2520PREFIX%2520ics%253A%2520%253Chttp%253A%252F%252Fwww.icarda.org%252Fschemas%252Ftoc%252F%253E%2520%2520%2520PREFIX%2520dct%253A%2520%253Chttp%253A%252F%252Fpurl.org%252Fdc%252Fterms%252F%253E%2520%2520%2520%2520%2520%2520INSERT%2520DATA%2520%257B%2520%2520%2520icarda%253A'.$id.'%2520a%2520ics%253ASdgTarget%2520%253B%2520%2520%2520dct%253Atitle%2520%2522'.intval($code).'%2522%2520%253B%2520%2520%2520dct%253Adescription%2520%2522'.$desc.'%2522%2520%253B%2520%2520%2520ics%253Aunder_sdg%2520icarda%253A'.$sdg.'%2520%2520%257D%2520';
//            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $id . '%20a%20ics%3ASdgTarget%20%3B%20%20%20dct%3Atitle%20' . $code . '%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20%3B%20%20%20ics%3Aunder_sdg%20' . $sdg . '.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';
            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20INSERT%20DATA%20%7B%20%20icarda%3Asdgtarget' . $id . '%20a%20ics%3ASdgTarget%20%3B%20%20dct%3Atitle%20%22' . $code . '%22%20%3B%20%20dct%3Adescription%20%22=' . $desc . '%22%20%3B%20%20ics%3Aunder_sdg%20icarda%3Asdg' . $sdg . '.%20%20%7D';

            $response = Http::withHeaders([
                'Content-Type: application/rdf+xml',
                'Accept: text/plain'
            ])->post($url);


//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_HTTPHEADER => array(
//                    'Content-Type: application/rdf+xml',
//                    'Accept: text/plain'
//                ),
//            ));

//            $response = curl_exec($curl);

//            if(empty($response) || $response == ''){
//                return response()->json(['data'=>'no changes were made']);
//            }else{
//                return $response;
//            }

            $i = $i + 1;
        }

        if ($response->successful()) {
            return response()->json("ran for " . $i);
        } elseif ($response->failed()) {
            return response()->json('failed');
        } else {
            return resposne()->json('somehow I got here');
        }

    }

    public function seedSdgIndicator()
    {
        $i = 0;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://clarisa.cgiar.org/api/allSDGIndicators',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=258F3497D42FA136F31776DFDAB127F6'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $sdgTargets = json_decode($response, false);

//        return response()->json($sdgTargets);


        foreach ($sdgTargets as $sdgTarget) {

//            return response()->json($sdgTarget);
            /*DATA*/
            $dataArray = array("graphDB_record" => [
                "data" => [
                    "id" => $sdgTarget->sdgTarget->id,
                    "code" => $sdgTarget->sdgTarget->sdgTargetCode,
                    "uncode" => $sdgTarget->unsdIndicatorCode,
                    "title" => $sdgTarget->indicatorName,
                    "desc" => $sdgTarget->sdgTarget->sdgTarget,
                    "sdgtarget" => $sdgTarget->sdgTarget->sdg->smoCode

                ]
            ]);
            $object = json_decode(json_encode($dataArray), FALSE);

            /*DATA*/
//
//            return response()->json($object);

            urlencode($id = $object->graphDB_record->data->id);
            urlencode($code = $object->graphDB_record->data->code);
            urlencode($uncode = $object->graphDB_record->data->uncode);
            urlencode($title = $object->graphDB_record->data->title);
            urlencode($desc = $object->graphDB_record->data->desc);
//            urlencode($sdgtarget = implode(',', $object->graphDB_record->data->sdgtarget));
            urlencode($sdgtarget = $object->graphDB_record->data->sdgtarget);

//            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%2520icarda%253A%2520%253Chttp%253A%252F%252Fwww.icarda.org%252Fdata%252Ftoc%252F%253E%2520%2520%2520PREFIX%2520ics%253A%2520%253Chttp%253A%252F%252Fwww.icarda.org%252Fschemas%252Ftoc%252F%253E%2520%2520%2520PREFIX%2520dct%253A%2520%253Chttp%253A%252F%252Fpurl.org%252Fdc%252Fterms%252F%253E%2520%2520%2520%2520%2520%2520INSERT%2520DATA%2520%257B%2520%2520%2520icarda%253A'.$id.'%2520a%2520ics%253ASdgTarget%2520%253B%2520%2520%2520dct%253Atitle%2520%2522'.intval($code).'%2522%2520%253B%2520%2520%2520dct%253Adescription%2520%2522'.$desc.'%2522%2520%253B%2520%2520%2520ics%253Aunder_sdg%2520icarda%253A'.$sdg.'%2520%2520%257D%2520';
//            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3A' . $id . '%20a%20ics%3ASdgTarget%20%3B%20%20%20dct%3Atitle%20' . $code . '%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20%3B%20%20%20ics%3Aunder_sdg%20' . $sdg . '.%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F';
//            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Asdgtargetid%20a%20ics%3ASdgTarget%20%3B%20%20%20ics%3Auncode%20%22CODE%22%20%3B%20%20%20dct%3Atitle%20%22TITLE%22%20%3B%20%20%20dct%3Adescription%20%22DESC%22%20%3B%20%20%20ics%3Afor_target%20icarda%3Asdg' . $sdgtarget . '%20%20%20%7D%20&baseURI=http%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F';
            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Asdgindicator' . $id . '%20a%20ics%3ASdgIndicator%20%3B%20%20%20ics%3Auncode%20%22' . $uncode . '%22%20%3B%20%20%20dct%3Atitle%20%22' . $code . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20%3B%20%20%20ics%3Afor_target%20icarda%3Asdgtarget' . $sdgtarget . '%20%20%7D%20';

            $response = Http::withHeaders([
                'Content-Type: application/rdf+xml',
                'Accept: text/plain'
            ])->post($url);


//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_HTTPHEADER => array(
//                    'Content-Type: application/rdf+xml',
//                    'Accept: text/plain'
//                ),
//            ));

//            $response = curl_exec($curl);
//
//            return $response;

//            if(empty($response) || $response == ''){
//                return response()->json(['data'=>'no changes were made']);
//            }else{
//                return $response;
//            }

            $i = $i + 1;
        }
        if ($response->successful()) {
            return response()->json("ran for " . $i);
        } elseif ($response->failed()) {
            return response()->json('failed');
        } else {
            return resposne()->json('somehow I got here');
        }
    }

    public function seedActionareas()
    {
        $i = 0;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://clarisa.cgiar.org/api/action-areas',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=258F3497D42FA136F31776DFDAB127F6'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $sdgTargets = json_decode($response, false);

//        return response()->json($sdgTargets);


        foreach ($sdgTargets as $sdgTarget) {

//            return response()->json($sdgTarget);
            /*DATA*/
            $dataArray = array("graphDB_record" => [
                "data" => [
                    "areaid" => $sdgTarget->id,
                    "areatitle" => $sdgTarget->name,
                    "areadesc" => $sdgTarget->description,
                ]
            ]);
            $object = json_decode(json_encode($dataArray), FALSE);

            /*DATA*/
//
//            return response()->json($object);

            urlencode($id = $object->graphDB_record->data->areaid);
            urlencode($title = $object->graphDB_record->data->areatitle);
            urlencode($desc = $object->graphDB_record->data->areadesc);

            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Aactionarea' . $id . '%20a%20ics%3AActionArea%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20.%20%20%20%7D%20';

            $response = Http::withHeaders([
                'Content-Type: application/rdf+xml',
                'Accept: text/plain'
            ])->post($url);


//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_HTTPHEADER => array(
//                    'Content-Type: application/rdf+xml',
//                    'Accept: text/plain'
//                ),
//            ));

//            $response = curl_exec($curl);
//
//            return $response;

//            if(empty($response) || $response == ''){
//                return response()->json(['data'=>'no changes were made']);
//            }else{
//                return $response;
//            }

            $i = $i + 1;
        }
        if ($response->successful()) {
            return response()->json("ran for " . $i);
        } elseif ($response->failed()) {
            return response()->json('failed');
        } else {
            return resposne()->json('somehow I got here');
        }
    }

    public function seedImpactareas()
    {
        $i = 0;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://clarisa.cgiar.org/api/impact-areas',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=258F3497D42FA136F31776DFDAB127F6'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $sdgTargets = json_decode($response, false);

        return response()->json($sdgTargets);


        foreach ($sdgTargets as $sdgTarget) {

//            return response()->json($sdgTarget);
            /*DATA*/
            $dataArray = array("graphDB_record" => [
                "data" => [
                    "id" => $sdgTarget->id,
                    "title" => $sdgTarget->name,
                    "desc" => $sdgTarget->description,
                ]
            ]);
            $object = json_decode(json_encode($dataArray), FALSE);

            /*DATA*/
//
//            return response()->json($object);

            urlencode($id = $object->graphDB_record->data->id);
            urlencode($title = $object->graphDB_record->data->title);
            urlencode($desc = $object->graphDB_record->data->desc);

            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Aimpactarea' . $id . '%20a%20ics%3AimpactArea%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20.%20%20%20%7D%20';

            $response = Http::withHeaders([
                'Content-Type: application/rdf+xml',
                'Accept: text/plain'
            ])->post($url);


//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_HTTPHEADER => array(
//                    'Content-Type: application/rdf+xml',
//                    'Accept: text/plain'
//                ),
//            ));

//            $response = curl_exec($curl);
//
//            return $response;

//            if(empty($response) || $response == ''){
//                return response()->json(['data'=>'no changes were made']);
//            }else{
//                return $response;
//            }

            $i = $i + 1;
        }
        if ($response->successful()) {
            return response()->json("ran for " . $i);
        } elseif ($response->failed()) {
            return response()->json('failed');
        } else {
            return resposne()->json('somehow I got here');
        }
    }

    public function seedIndicators()
    {
        $i = 0;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://clarisa.cgiar.org/api/impact-areas-indicators',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=258F3497D42FA136F31776DFDAB127F6'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $sdgTargets = json_decode($response, false);



        foreach ($sdgTargets as $sdgTarget) {

//            return response()->json($sdgTarget);
            /*DATA*/
            $dataArray = array("graphDB_record" => [
                "data" => [
                    "code" => $sdgTarget->indicatorId,
                    "title" => $sdgTarget->impactAreaName,
                    "desc" => $sdgTarget->indicatorStatement,
                    "unit" => $sdgTarget->targetUnit,
                    "target" => $sdgTarget->targetUnit,
                    "base_value" => $sdgTarget->value,
                    "target_value" => $sdgTarget->description,
                    "target_yeaer" => $sdgTarget->description,
                ]
            ]);
            $object = json_decode(json_encode($dataArray), FALSE);

            /*DATA*/
//
//            return response()->json($object);

            urlencode($id = $object->graphDB_record->data->id);
            urlencode($title = $object->graphDB_record->data->title);
            urlencode($desc = $object->graphDB_record->data->desc);

            $url = 'http://graphdb.scio.services:7200/repositories/TOC/statements?update=PREFIX%20icarda%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fdata%2Ftoc%2F%3E%20%20%20PREFIX%20ics%3A%20%3Chttp%3A%2F%2Fwww.icarda.org%2Fschemas%2Ftoc%2F%3E%20%20%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%20%20%20%20%20%20INSERT%20DATA%20%7B%20%20%20icarda%3Aimpactarea' . $id . '%20a%20ics%3AimpactArea%20%3B%20%20%20dct%3Atitle%20%22' . $title . '%22%20%3B%20%20%20dct%3Adescription%20%22' . $desc . '%22%20.%20%20%20%7D%20';

            $response = Http::withHeaders([
                'Content-Type: application/rdf+xml',
                'Accept: text/plain'
            ])->post($url);


//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_HTTPHEADER => array(
//                    'Content-Type: application/rdf+xml',
//                    'Accept: text/plain'
//                ),
//            ));

//            $response = curl_exec($curl);
//
//            return $response;

//            if(empty($response) || $response == ''){
//                return response()->json(['data'=>'no changes were made']);
//            }else{
//                return $response;
//            }

            $i = $i + 1;
        }
        if ($response->successful()) {
            return response()->json("ran for " . $i);
        } elseif ($response->failed()) {
            return response()->json('failed');
        } else {
            return resposne()->json('somehow I got here');
        }
    }


    public function sdgcollections()
    {

        $path = storage_path() . "/json/getSdgs.json";
        $json = json_decode(file_get_contents($path), false);
        $sdgs = $json;
        $targets = array();
        $allData = array();
        $checkID = 1;

        return response()->json(['data' =>$sdgs]);
    }


    public function getImpactArea()
    {
        $path = storage_path() . "/json/getImpactAreas.json";
        $json = json_decode(file_get_contents($path), false);

        $i = 0;
        foreach ($json as $allData){
            foreach($allData as $data){
                foreach($data->indicators as $indicators){
                    $i++;
                  $indicators->code = $i;
                }
            }
        }

        return response()->json(['data' => $json]);
    }

    public function getActionAreas()
    {
        $path = storage_path() . "/json/getActionAreas.json";
        $json = json_decode(file_get_contents($path), true);

        return response()->json(['data' => $json]);
    }

    public function getprogrammetypes(){
        $path = storage_path() . "/json/getProgrammeTypes.json";
        $json = json_decode(file_get_contents($path), true);

        return response()->json(['data' => $json]);
    }


}
