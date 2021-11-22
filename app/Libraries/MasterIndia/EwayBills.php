<?php

namespace App\Libraries\MasterIndia;

use Illuminate\Support\Facades\Http;
use App\Libraries\MasterIndia\MasterIndiaAuth;
use App\Libraries\MasterIndia\EwaybillExtend;
use App\Libraries\Helpers\Helper;
use Illuminate\Support\Facades\Session;
use Exception;
use Log;
use Cache;
use Debugbar;
use \Carbon\Carbon;

class EwayBills {
  protected $getURL;
  protected $getEwayBillDataURL;
  protected $token;
  protected $pageLimit = 20;

  public function __construct() {
    // Cache::flush(); 
    $oAuth = new MasterIndiaAuth();
    $this->token = $oAuth->getSessionToken();
    Debugbar::info($this->token);
    $this->getURL = $oAuth->getBaseURL().'/getEwayBillData';
    $this->getEwayBillDataURL = $oAuth->getBaseURL().'/ewayBill/list';
    $this->getEwayBillValidityExtendURL = $oAuth->getBaseURL().'/ewayBillValidityExtend';
  }

  public function getEwayBill($eWayBillNumber, $gstin) {
    try {
      if ($eWayBillNumber=='' || $gstin =='') {
        throw new Exception('Invalid Data');
      }
        
      $eWayBillURL = $this->getURL.'?access_token='.$this->token.'&action=GetEwayBill&gstin='.$gstin.'&eway_bill_number='.$eWayBillNumber;
      
      if (Cache::has($eWayBillURL)) {
        return Cache::get($eWayBillURL);
      }

      $response = Http::get($eWayBillURL)->json('results');
        
      if ($response['code'] != 200) {
        throw new Exception('Invalid Response '. $eWayBillURL.' Response : '.json_encode($response));
      }
      Cache::put($eWayBillURL, $response);
      return $response;
    } catch (\Throwable $th) {
      Log::warning($th->getMessage());
    }
  }

  public function getEwayBillLists($documentNumber, $gstin, $documentDate, $generateStatus=0, $page=1, $limit=20, $cacheCode='') {
    try {
      if ($gstin =='') {
        throw new Exception('Invalid Data');
      }

      $getEwayBillDataURL = $this->getEwayBillDataURL.'?access_token='.$this->token.'&gstin='.$gstin.'&document_number='.$documentNumber.'&document_date='.$documentDate.'&generate_status='.$generateStatus.'&page='.$page.'&limit='.$limit.'&c='.$cacheCode;
      Log::info('URL:::::');
      Log::info($getEwayBillDataURL);      
      if (Cache::has(md5($getEwayBillDataURL))) {
        return Cache::get(md5($getEwayBillDataURL));
      }

      $response = Http::get($getEwayBillDataURL)->json('results');
      Log::info([$getEwayBillDataURL, $response]);
      if ($response['code'] != 200) {
        Cache::forget(md5($getEwayBillDataURL));
        throw new Exception('Invalid Response '. $getEwayBillDataURL.' Response : '.json_encode($response));
      }
      Cache::put(md5($getEwayBillDataURL), $response['message']);
      return $response['message'];
    } catch (\Throwable $th) {
      if ($response!=null) {
        return ['error'=>$response['message']];
      } else {
        return ['error'=>'Response NULL'];
      }
        
      Log::warning($th->getMessage());
    }
  }

  public function getEwayBillListsByDate($documentNumber, $gstin, $documentDate, $generateStatus=0, $page=1, $limit=20000) {
    $eWayBills = $this->getEwayBillLists($documentNumber, $gstin, $documentDate, $generateStatus=1, $page=1, $limit=10000, 'filterbydate');
    
    $filteredEwayBills = array_filter(
      $eWayBills,
      function ($a) {
        if (isset($a['eway_bill_valid_date']) && $a['eway_bill_valid_date']!='') {
          $ewayDate = Carbon::createFromFormat('d/m/Y H:i:s A', $a['eway_bill_valid_date'])->format('d/m/Y');
          $today = Carbon::now()->format('d/m/Y');
          $tomorrow = Carbon::tomorrow()->format('d/m/Y');
          return ($ewayDate==$tomorrow || $ewayDate==$today) ? true : false;
        }
        return false;
      }
    );
    return $filteredEwayBills;
  }


  public function extendEwayBill($data) {
    $e = new EwaybillExtend();
    $e->url = $this->getEwayBillValidityExtendURL;
    $e->data = $data;
    $e->access_token = $this->token;
    $e->extend();
  }
}
