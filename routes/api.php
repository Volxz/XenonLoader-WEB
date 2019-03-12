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
    //Get individual mods assigned to the groups
    $xfUser = XFUser::where('username','=',$request->get('user'))->with('xfgroups.mods.game','xfgroups.games.mods')->get()->first();
    $availableMods = [];
    $groups = $xfUser->toArray()['xfgroups'];
    foreach($groups as $group)
    {
        foreach ($group['mods'] as $mod)
        {
            $temparr = [];
            $temparr['id'] = $mod['id'];
            $temparr['version'] = $mod['version'];
            $temparr['name'] = $mod['name'];
            $temparr['game'] = $mod['game']['name'];

            array_push($availableMods,$temparr);
        }

        foreach ($group['games'] as $game)
        {
            foreach ($game['mods'] as $mod){
                $temparr = [];
                $temparr['id'] = $mod['id'];
                $temparr['version'] = $mod['version'];
                $temparr['name'] = $mod['name'];
                $temparr['game'] = $game['name'];
                array_push($availableMods,$temparr);
            }
        }
    }

    //



    return response()->json(array_map("unserialize", array_unique(array_map("serialize", $availableMods))));
})->middleware(XFAuth::class); // TODO: Test in client and add encrypted channels