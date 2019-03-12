<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class EncryptedLoaderAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->has("data")){
            abort(403);
        }
        $version = $request->header('loader_version');
        $loader = \App\Models\Loader::where('version', '=',$version)->get()->first();
        if(!$loader || !$loader->enabled){ // If the loader doesnt exist or isnt enabled 403 them
            abort(403); //TODO: setup custom abort codes so the client knows to update etc. Custom messages possibly?
        }
        $privateKeyLoc = $loader->encryption_key_private;
        $privateKey = Storage::get($privateKeyLoc);

        openssl_private_decrypt(base64_decode($request->get('data')), $decrypted, openssl_get_privatekey($privateKey));

        $data = json_decode($decrypted,true);
        $request->replace($data);
        return $next($request);

        //$publicKey = file_get_contents('/mnt/x/Development/CERT/publickey.pem');


        //$plaintext = json_encode($request->all());
        //openssl_public_encrypt($plaintext, $encrypted, openssl_get_publickey($publicKey));
        //dd(base64_encode($encrypted));
    }
}
