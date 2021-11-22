<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  protected function jsonResponse($data = [], $message = '', $status_code = 200) {
    $aResponse = [];

    if ($status_code == '200') {
      $aResponse['status'] = 'success';

      if ($data) {
        $aResponse['data'] = $data;
      } else {
        $aResponse['data'] = [];
      }
    } else {
      $aResponse['status'] = 'error';

      if ($data) {
        $aResponse['errors'] = $data;
      } else {
        $aResponse['errors'] = [];
      }
    }

    if ($message) {
      $aResponse['message'] = $message;
    }

    return response()->json($aResponse, $status_code);
  }
}
