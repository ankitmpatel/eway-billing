<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Transporter extends Model {
  use HasFactory,SoftDeletes;

  protected $fillable = [
        'name',
        'gstin',
        'contact_person',
        'contact_number',
      ];
    
  public static $rules = [
        'name' => 'required',
        'gstin' => 'required|unique:transporters,gstin',
        'contact_person' => 'required',
        'contact_number' => 'required|numeric|digits:10',
      ];

  public function isValid() {
    return !Validator::make($this->attributesToArray(), self::$rules)->fails();
  }

  public function getName() {
    return $this->name;
  }

  public function getId() {
    return $this->id;
  }
}
