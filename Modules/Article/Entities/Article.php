<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table='articles';
    protected $fillable = [];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function category()
    {
      return $this->belongsTo('Modules\Category\Entities\Category');
    }

    public function role()
    {
      return $this->belongsTo('Modules\Dashboard\Entities\Role');
    }
}
