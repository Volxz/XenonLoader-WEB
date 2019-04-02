<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class XFUser extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'xf_user';
    protected $primaryKey = 'user_id';


    public function checkHWID($hwid)
    {
        $currentHwid = null;
        $hwidRow = DB::connection('mysql2')->table('xf_user_field_value')
            ->where('field_id', "=", "HWID")
            ->where("user_id", "=", $this->user_id)->first();
        if ($hwidRow) {
            $currentHwid = $hwidRow->field_value;
            if (!$hwidRow->field_value) {
                DB::connection('mysql2')->table('xf_user_field_value')
                    ->where([
                        ['user_id', '=', $this->user_id],
                        ['field_id', '=', 'HWID']
                    ])
                    ->update([
                        'field_value' => $hwid
                    ]);
                return true;
            } else {
                return $currentHwid == $hwid;
            }
        } else {
            DB::connection('mysql2')->table('xf_user_field_value')
                ->insert(['user_id' => $this->user_id, 'field_id' => 'HWID', 'field_value' => $hwid]);
            return true;
        }
        return false;
    }

    public function checkPassword($checkPassword)
    {
        $xenPasswordHash = DB::connection('mysql2')->table('xf_user_authenticate')->where('user_id', '=', $this->user_id)->get('data')->first();
        $passHash = substr($xenPasswordHash->data, 22, -3);
        if (strlen($passHash) < 5) {
            return false;
        }
        return password_verify($checkPassword, $passHash);
    }

    public function xfGroups()
    {
        return $this->hasManyThrough(\App\Models\XFGroup::class, \App\Models\XFGroupUser::class, "user_id", "user_group_id", "user_id", "user_group_id");
    }
}
