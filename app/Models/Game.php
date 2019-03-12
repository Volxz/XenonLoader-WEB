<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $connection = 'mysql';
    protected $table = 'games';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = [];
    protected $fillable = ['name','executable'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    protected static function boot() {
        parent::boot();

        static::deleting(function($game) {
            $game->mods()->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function mods()
    {
        return $this->hasMany(\App\Models\Mod::class,"game_id", "id");
    }

    public function groups()
    {
        return $this->belongsToMany(\App\Models\XFGroup::class,env('DB_DATABASE' ,'xenonloader') . ".xf_group_games","game_id","xf_group_id","id","user_group_id");
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
