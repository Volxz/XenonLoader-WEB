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
    {
        return $this->hasManyThrough(\App\Models\Mod::class,\App\Models\XFGroupMod::class, "xf_user_group_id", "id");
    }

    public function games()
    {
        return $this->hasManyThrough(\App\Models\Game::class,\App\Models\XFGroupGame::class, "xf_user_group_id", "id");
    }
}
