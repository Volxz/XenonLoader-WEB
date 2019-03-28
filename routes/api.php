<?php

use App\Http\Middleware\EncryptedLoaderAPI;
use App\Http\Middleware\XFAuth;
use App\Models\Mod;
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


Route::post('/loader/checklogin', function (Request $request) {
    if (ipBlocked($request->ip())) {
        abort(403);
    }

    //TODO: REQUIRE AND VERIFY HWID
    $request->validate([
        'username' => 'required',
        'password' => 'required',
        'hwid' => 'required',
        'token' => 'required'
    ]);
    $data['token'] = $request->get('token');
    $username = $request->get('username');
    $user = XFUser::where('username', '=', $username)->first();
    if (!$user) {
        $data['success'] = false;
        $data['error'] = "User " . $username . " was not found.";
    } else {
        if ($user->checkPassword($request->get('password'))) {
            if($user->checkHWID($request->get('hwid'))){
                $data['success'] = true;
            } else {
                $data['success'] = false;
                $data['error'] = "Your HWID does not match. Please contact support";
            }
            clearFailedIP($request->ip());
        } else {
            incrementFailedIP($request->ip());
            $data['success'] = false;
            $data['error'] = "The password entered was incorrect.";
        }
    }

    return response()->json(formatAndEncrypt($data, $request));
})->middleware(EncryptedLoaderAPI::class);


Route::post('/loader/modlist', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
        'token' => 'required'
    ]);
    //Get individual mods assigned to the groups
    $xfUser = XFUser::where('username', '=', $request->get('username'))->with('xfgroups.mods.game', 'xfgroups.games.mods')->get()->first();
    $availableMods = [];
    $response = [];
    $groups = $xfUser->toArray()['xfgroups'];
    foreach ($groups as $group) {
        foreach ($group['mods'] as $mod) {
            $temparr = [];
            $temparr['id'] = $mod['id'];
            $temparr['version'] = $mod['version'];
            $temparr['name'] = $mod['name'];
            $temparr['game']['name'] = $mod['game']['name'];
            $temparr['game']['executable'] = $mod['game']['executable'];

            array_push($availableMods, $temparr);
        }

        foreach ($group['games'] as $game) {
            foreach ($game['mods'] as $mod) {
                $temparr = [];
                $temparr['id'] = $mod['id'];
                $temparr['version'] = $mod['version'];
                $temparr['name'] = $mod['name'];
                $temparr['game']['name'] = $game['name'];
                $temparr['game']['executable'] = $game['executable'];

                array_push($availableMods, $temparr);
            }
        }
    }

    //
    $availableMods = array_values(array_map("unserialize", array_unique(array_map("serialize", $availableMods))));
    $response['data'] = $availableMods;
    $response['success'] = true;
    $response['token'] = $request->get('token');
    return response()->json($response);
    //return response()->json(formatAndEncrypt($response, $request));
})->middleware(EncryptedLoaderAPI::class, XFAuth::class);

Route::post('/loader/downloadmod', function (Request $request) {
    $chosenMod = Mod::find($request->get('mod_id'))->first();
    if (!$chosenMod)
        abort(404);
    $xfUser = XFUser::where('username', '=', $request->get('username'))->with('xfgroups.mods.game', 'xfgroups.games.mods')->get()->first();
    $availableModsIDs = [];
    $groups = $xfUser->toArray()['xfgroups'];
    foreach ($groups as $group) {
        foreach ($group['mods'] as $mod) {
            array_push($availableModsIDs, $mod['id']);
        }

        foreach ($group['games'] as $game) {
            foreach ($game['mods'] as $mod) {
                array_push($availableModsIDs, $mod['id']);
            }
        }
    }

    if (!in_array($chosenMod->id, $availableModsIDs))
        abort(403);
    return Response::download(storage_path("app/{$chosenMod->mod_file}"), 'inject.dll');

})->middleware(EncryptedLoaderAPI::class, XFAuth::class);