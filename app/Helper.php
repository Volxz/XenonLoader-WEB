<?php

use App\Models\BannedIP;

/**
 * Format data to JSON then encrypt and B64 it then make it a JSON again (easy right?)
 * @param $data
 * @param $request
 * @return array
 */
function formatAndEncrypt($data, $request){
    $loader = \App\Models\Loader::where('version', '=', $request->header('loader_version'))->first();
    $publicKey = Storage::get($loader->encryption_key_public);
    openssl_public_encrypt(json_encode($data), $encrypted, $publicKey);
    $encryptedB64 = base64_encode($encrypted);
    $response = [];
    $response['data'] = $encryptedB64;
    return $response;
}

function ipBlocked($ip){
    $dbip = BannedIP::firstOrCreate(['ip'=>$ip]);
    if($dbip->attempts > 20){
        return true;
    }
    return false;
}


function incrementFailedIP($ip){
    $dbip = BannedIP::firstOrCreate(['ip'=>$ip]);
    $dbip->increment('attempts', 1);
}

function clearFailedIP($ip){
    $dbip = BannedIP::firstOrCreate(['ip'=>$ip]);
    $dbip->attempts = 0;
    $dbip->save();
}
