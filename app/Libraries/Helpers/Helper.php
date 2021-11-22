<?php

namespace App\Libraries\Helpers;

use Illuminate\Support\Facades\Http;
use App\Libraries\MasterIndia\MasterIndiaAuth;
use Illuminate\Support\Facades\Session;
use Exception;
use Log;
use Cache;
use Debugbar;
use \Carbon\Carbon;

class Helper {

    public static function dateFormatIndia($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public static function dateFormatUnix($date){
        return Carbon::parse($date)->format('Y-m-d');
    }

  public static function dateFormatUnixDateTime($date) {
    // Master India format of eWay Bill Date 27/02/2021 11:58:00 PM
    return Carbon::createFromFormat('d/m/Y g:i A', $date)->format('Y-m-d H:i:s');
  }
  
    public static function getKeysFromSessionObject($key){
        $obj = Session::get($key);
        $keys = [];
        if ($obj!=null) {
            $keys = array_keys($obj);
        }
        return $keys;
    }

    public static function addValueSessionObject($s_name, $key,$data){
        $obj = Session::get($s_name);
        if ($obj==null) {
            $obj = [];
        }
        $obj[$key] = urldecode($data);
        Session::put($s_name, array_unique($obj));
    }

    public static function removeValueSessionObject($s_name,$key){
        $obj = Session::get($s_name);

        if($obj != null && isset($obj[$key])){
            unset($obj[$key]);
            Session::put($s_name, array_unique($obj));
        }
    }
    
    public static function getTimeZone() {
        return env('TIMEZONE', 'Asia/Kolkata');
    }

}