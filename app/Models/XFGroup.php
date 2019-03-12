<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XFGroup extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'xf_user_group';
    protected $primaryKey = 'user_group_id';

    protected $hidden = [
        'banner_css_class', 'banner_class','username_css','display_style_priority'
    ];

    public function mods()
    {    //cols reversed
        //return $this->hasManyThrough(\App\Models\Mod::class,\App\Models\XFGroupMod::class,"mod_id","id","user_group_id","user_group_id");
        //                                                                                                        PIVOT KEY 1  | MODS TABLE ID |      DOESNT FAIL WTF WHY FML     |  PIVOT VALUE 2
        return $this->hasManyThrough(\App\Models\Mod::class,\App\Models\XFGroupMod::class,"xf_group_id","id","user_group_id","mod_id");
    }

    public function games()
    {
        return $this->hasManyThrough(\App\Models\Game::class,\App\Models\XFGroupGame::class, "xf_group_id", "id","user_group_id","game_id");
    }
}
