<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Loader extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loaders';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','version','encryption_key_public','encryption_key_private','enabled'];
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
