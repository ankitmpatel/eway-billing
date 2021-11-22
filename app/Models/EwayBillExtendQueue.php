<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EwayBillExtendQueue extends Model {
  use HasFactory;

  public const DEFAULT = 'DEFAULT';
  public const EWAYBILL_EXTEND = 'EWAYBILL_EXTEND';
  public const EWAYBILL_FUTURE_EXTEND = 'EWAYBILL_FUTURE_EXTEND';

  protected $fillable = [
        'eway_bill_number',
        'batch_id',
        'batch_datetime',
        'request',
        'response',
        'original_request',
        'success',
        'queue_type',
    ];
  // public function getBatchDatetimeAttribute($value)
    // {
    //    return Carbon::parse($value)->timezone('Asia/Kolkata')->format('d/m/Y g:i A');
    // }

    // public function setBatchDatetimeAttribute($value)
    // {
    //     $this->attributes['batch_datetime'] = Carbon::parse($value)->timezone('UTC')->format('Y-m-d H:i:s');
    // }
}
