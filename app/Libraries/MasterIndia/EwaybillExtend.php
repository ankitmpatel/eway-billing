<?php

namespace App\Libraries\MasterIndia;

use Illuminate\Support\Facades\Http;
use App\Libraries\MasterIndia\MasterIndiaAuth;
use App\Libraries\Helpers\Helper;
use Illuminate\Support\Facades\Session;
use App\Models\EwayBillExtendQueue;
use Exception;
use Log;
use Cache;
use Debugbar;
use Validator;

class EwaybillExtend {
  public $url;
  public $access_token;
  public $data;

  public static $pending = 'pending';
  public static $success = 'success';
  public static $failure = 'failure';

  public static $rules = [
        // 'address_line1' => 'required',
        // 'address_line2' => 'required',
        // 'address_line3' => 'required',
        // 'from_pincode' => 'required',
        // 'remaining_distance' => 'required',
        'vehicle_number' => 'required'
    ];

  public function __construct() {
  }

  public function extend() {
    try {
      Log::info('eWayBill Extend ---> ');

      $this->validateData($this->data);
            
      $params = $this->data;
      $params['access_token'] = $this->access_token;
      // dd('move',$params);
      $response = Http::post($this->url, $params)->json('results');
            
      if ($response['code']=='200') {
        // Helper::removeValueSessionObject('session_eway_ids',$response['message']['ewayBillNo']);
        Helper::addValueSessionObject('session_eway_extend', $params['eway_bill_number'], ' extended - valid upto: '.$response['message']['validUpto']);
        $this->updateStatus($params, $response, self::$success);
      } else {
        Helper::addValueSessionObject('session_eway_extend', $params['eway_bill_number'], $response['message']);
        $this->updateStatus($params, $response, self::$failure);
      }
            
      Helper::removeValueSessionObject('session_eway_ids', $params['eway_bill_number']);

      Log::info(json_encode($response));
    } catch (\Throwable $th) {
      Log::error(json_encode($this->data));
      Log::error($th->getMessage());
    }
  }

  public function updateStatus($params, $response, $status) {
    EwayBillExtendQueue::where('eway_bill_number', $params['eway_bill_number'])
        ->where('batch_id', $params['batch_id'])
        ->update(['response' => json_encode($response),'status'=>$status]);
  }

  public function validateData($data) {
    $validator = Validator::make($data, self::$rules);
    if ($validator->fails()) {
      throw new Exception($validator->errors());
    }
  }
}
