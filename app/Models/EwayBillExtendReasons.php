<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EwayBillExtendReasons extends Model {
  use HasFactory;

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->reason_name;
  }
}
