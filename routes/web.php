<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Crypt;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/testcrypt', function () {
    $plaintext = "testmsg";
    $cipher = "AES-128-CBC";
    $pass = "uQkbjAnBYaMykJP7aDkczXD3KKd2bPeWe8wLXskxdpn29AHNBZMB3NBSyGtGsUnvvZWnHFMBSUfXtbAncQLgA9h9XEmhcWGVq3NUwUDLpT6LFzuUTNNRhgBtSggD3ktQ";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, $pass,$options=OPENSSL_RAW_DATA, $iv);
        //store $cipher, $iv, and $tag for decryption later
        //$original_plaintext = openssl_decrypt($ciphertext, $cipher, "Password", $options=0, $iv, $tag);
        return base64_encode($pass) ."\n" . base64_encode($iv);
});

Route::get('/dashboard', 'DashboardController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/**
 * Returns an encrypted & utf8-encoded
 */
