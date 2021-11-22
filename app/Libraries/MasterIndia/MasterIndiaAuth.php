<?php

namespace App\Libraries\MasterIndia;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Log;
use Debugbar;
use Exception;

class MasterIndiaAuth {
  protected static $baseURL = '';
  public $token_type;
  public $access_token;
  public $expires_in;
  public $expiry_datetime;


  public function __construct() {
    self::$baseURL = env('MASTERINDIA_BASEURL');
  }

  public function getSessionToken() {
    Log::info('Session is Expired'.$this->isExpired());
    if ($this->isExpired() || Session::get('m_access_token')==null || env('MASTERINDIA_FORCE_LOGIN')==true) {
      $this->authenticate();
    }

    return Session::get('m_access_token');
  }

  public function setTokenType($token_type) {
    $this->token_type = $token_type;
  }

  public function setAccessToken($access_token) {
    $this->access_token = $access_token;
  }

  public function setExpiresIn($expires_in) {
    $this->expiry_datetime = Carbon::now()->addSeconds($expires_in);
    Session::put('expiry_datetime', $this->expiry_datetime);
    $this->expires_in = $expires_in;
  }

  public function getTokenType() {
    return $this->token_type;
  }

  public function getAccessToken() {
    return $this->access_token;
  }

  public function getExpiresIn() {
    return $this->expires_in;
  }

  public function isExpired() : bool {
    // Debugbar::info('Session Master India');
    $now = Carbon::now();
    $expiry = Session::get('expiry_datetime');
    // dd($expiry, $now, Session::get('mtoken'));
    // Debugbar::info($expiry->lessThan($now));
    return ($expiry == null) ? false : $expiry->lessThan($now);
  }

  public function getBaseURL() {
    return self::$baseURL;
  }

  public function authenticate() {
    try {
      Log::info('MasterIndia Auth Called.');
      $params = json_encode([
          "username" => env('MASTERINDIA_USERNAME', ''),
          "password" => env('MASTERINDIA_PASSWORD', ''),
          "client_id" => env('MASTERINDIA_CLIENT_ID', ''),
          "client_secret" => env('MASTERINDIA_CLIENT_SECRET', ''),
          "grant_type" => env('MASTERINDIA_GRANT_TYPE', '')
          ]);
      Log::info(json_encode($params));  
      $response = Http::withHeaders([
          'content_type' => 'application/json',
      ])->withBody($params, 'application/json')->post(self::$baseURL.'/oauth/access_token');
      
      Log::info(json_encode($response->json()));  
      
      if($response->getStatusCode()==200){
        $this->setTokenType($response->json()['token_type']);
        $this->setAccessToken($response->json()['access_token']);
        Session::put('m_access_token', $response->json()['access_token']);
        Log::info(['Access Token - '.$response->json()['access_token']]);
        $this->setExpiresIn($response->json()['expires_in']);
      }else{
        throw new Exception(json_encode($response->json()));
      }
    } catch (Exception $th) {
      Log::error($th->getMessage());
      return abort(500, 'Unauthorized action.');
    }
    
  }
}
