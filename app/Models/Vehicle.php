<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
  use HasFactory,SoftDeletes;

  protected $fillable = [
        'vehicle_number',
      ];
    
  public static $rules = [
        'vehicle_number' => 'required',
      ];

  public function transporters() {
    return $this->belongsToMany(Transporter::class)->withTimestamps();
  }

  public function getTransporter() {
    return $this->transporters()->first();
  }

  public function isValid() {
    return !Validator::make($this->attributesToArray(), self::$rules)->fails();
  }
}
