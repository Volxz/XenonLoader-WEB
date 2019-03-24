<?php

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

