<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Menu\Entities\MenuItem;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = [];

    // Parent items + children item
    public function menuAllItems()
    {
      return $this->hasMany(MenuItem::class);
    }

    // // Parent items
    // public function menuItems()
    // {
    //   return $this->hasMany(MenuItem::class)->where('parent_id', 0);
    // }

    // Parent activated items
    public function menuActivatedItems ()
    {
      return $this->hasMany(MenuItem::class)->where([
        'activated' => 1
      ])->with('role');
    }

    // Activated and public menu_items
    public function menuActivatedPublicItems ()
    {
      return $this->hasMany(MenuItem::class)->where([
        'activated' => 1,
        'role_id' => 1
      ]);
    }

    public function access()
    {
      return $this->belongsTo('Modules\Dashboard\Entities\Role','role_id','id');
    }
}
