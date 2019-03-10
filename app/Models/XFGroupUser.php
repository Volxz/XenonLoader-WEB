<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XFGroupUser extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'xf_user_group_relation';
    protected $primaryKey = 'user_id';

}
