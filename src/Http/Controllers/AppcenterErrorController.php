<?php

namespace VoyagerAppcenterCrashes\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class AppcenterErrorController extends \App\Http\Controllers\Controller
{
  public function browse(Request $request){         
    $owner = env('APPCENTER_OWNER_APP', null);
    $appName = env('APPCENTER_APP_NAME', null);
    $apiToken = env('APPCENTER_API_TOKEN', null);
    $lastOccurrence_from = Carbon::createFromFormat('d-m-Y', env('APPCENTER_LAST_OCCURRENCE_FROM', '01-01-2000'))->toAtomString();
    if(!$owner || !$appName || !$apiToken){
      abort(403, 'Appcenter credentials not provided.');
    }
    
    $apps = explode(',', $appName);
    $datas = array();    

    foreach ($apps as $value) {
      $client = new Client(['base_uri' => 'https://api.appcenter.ms/v0.1/']);
      $response = $client->request('GET', "apps/$owner/$value/crash_groups", [
        'headers' => [
          'Accept'        => 'application/json',              
          'X-API-Token'   => $apiToken,
          'Content-Type'  => 'application/json'
        ],
        'query' => ['last_occurrence_from' => $lastOccurrence_from]
      ]);
      $datas[$value] = json_decode($response->getBody());
    }
   
    return view('crashes::browse', compact('datas'));
  }
}