<?php

use App\Http\Middleware\EncryptedLoaderAPI;
use App\Http\Middleware\XFAuth;
use App\Models\XFUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/modinfo', function () {
    return App\Models\Mod::with('game')->get()->toJSON();
});

Route::post('/launcher/checklogin', function (Request $request) {
    //TODO: Add route field verification!!!! Spaces CRASH THE THREAD
    $loader = \App\Models\Loader::where('version', '=', $request->header('loader_version'))->first();
    $publicKey = Storage::get($loader->encryption_key_public);
    $data['token'] = $request->get('token');
    $user = XFUser::where('username', '=', $request->get('username'))->first();
    if(!$user){
        $data['success'] = false;
    } else {
        $data['success'] = $user->checkPassword($request->get('password'));
    }
    return response()->json(formatAndEncrypt($data, $publicKey));
})->middleware(EncryptedLoaderAPI::class);

Route::post('/test', function (Request $request) {
    $xfUser = XFUser::where('username','=','sp3ctre')->first()->xfGroups()->get();
         return $xfUser;
})->middleware(EncryptedLoaderAPI::class, XFAuth::class);

Route::post('/launcher/modlist', function (Request $request){
    $xfUser = XFUser::where('username','=','sp3ctre')->with('xfgroups.mods.game')->get()->first();
    $availableMods = [];
    $groups = $xfUser->toArray()['xfgroups'];
    //dd($groups);
    foreach($groups as $group)
    {
        foreach ($group['mods'] as $mod)
        {
            $mastertemp = [];
            $temparr = [];
            $temparr['id'] = $mod['id'];
            $temparr['version'] = $mod['version'];
            $temparr['name'] = $mod['name'];
            $temparr['game'] = $mod['game']['name'];
            array_push($mastertemp, $temparr);
            $availableMods = array_unique(array_merge($availableMods,$mastertemp), SORT_REGULAR);
        }
    }
     //dd($groups);
    return $availableMods;
})->middleware(XFAuth::class);