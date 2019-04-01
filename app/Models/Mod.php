<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mod extends Model
{
    use CrudTrait;
    use SoftDeletes;

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
    protected $fillable = ['version', 'secret', 'name', 'mod_file'];
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
    public function groups()
    {
        return $this->belongsToMany(\App\Models\XFGroup::class, env('DB_DATABASE', 'xenonloader') . ".xf_group_mods", "mod_id", "xf_group_id", "id", "user_group_id");
    }

    public function game()
    {
        return $this->belongsTo(\App\Models\Game::class);
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
