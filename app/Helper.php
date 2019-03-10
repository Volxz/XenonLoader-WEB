<?php

/**
 * Format data to JSON then encrypt and B64 it then make it a JSON again (easy right?)
 * @param $data
 * @param $key
 * @return array
 */
function formatAndEncrypt($data, $key){
    openssl_public_encrypt(json_encode($data), $encrypted, $key);
    $encryptedB64 = base64_encode($encrypted);
    $response = [];
    $response['data'] = $encryptedB64;
    return $response;
}