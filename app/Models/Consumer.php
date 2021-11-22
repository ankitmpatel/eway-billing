<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Consumer extends Model {
  use HasFactory,SoftDeletes;

  protected $fillable = [
    'consumer_name',
    'gstin',
    'contact_person',
    'contact_number',
    'address',
    'pincode',
    'state_id'
    ];
    
  public static $rules = [
    'consumer_name' => 'required',
    'gstin' => 'required|unique:consumers,gstin',
    'address' => 'required',
    'pincode' => 'required',
    'state_id' => 'required|numeric'
    ];

  public function isValid() {
    return !Validator::make($this->attributesToArray(), self::$rules)->fails();
  }

  public function states(){
    return $this->belongsTo(State::class,'state_id');
  }

  public function getConsumerName() {
    return $this->consumer_name;
  }

  public function getId() {
    return $this->id;
  }

  public function getState(){
    return $this->states()->first();
  }
}
