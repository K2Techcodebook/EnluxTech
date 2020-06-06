<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
  protected $fillable = ['name', 'value', 'type'];

  public function metable(): MorphTo{
    return $this->morphTo();
  }
}
