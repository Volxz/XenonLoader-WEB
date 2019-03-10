<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Mod extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $connection = 'mysql';
    protected $table = 'mods';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = ['version','secret','name', 'mod_file', 'encryption_key_public', 'encryption_key_private'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function game()
    {
        return $this->belongsTo(\App\Models\Game::class,"game_id", "id");
    }

    public function groups()
    {
        return $this->hasManyThrough(\App\Models\Mod::class,\App\Models\XFGroupMod::class);
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

    public function setModFileAttribute($value)
    {
        $attribute_name = "mod_file";
        $disk = "local";
        $destination_path = "/mods";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setEncryptionKeyPrivateAttribute($value)
    {
        $attribute_name = "encryption_key_public";
        $disk = "local";
        $destination_path = "/keys";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setEncryptionKeyPublicAttribute($value)
    {
        $attribute_name = "encryption_key_private";
        $disk = "local";
        $destination_path = "/keys";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

}
