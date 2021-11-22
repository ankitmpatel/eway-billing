<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EwayDownloadQueue extends Model {
  use HasFactory;

  protected $fillable = [
        'eway_bill_number',
        'eway_bill_date',
        'eway_bill_valid_date',
        'eway_bill_valid_date_unix',
        'request',
        'response',
    ];
}
