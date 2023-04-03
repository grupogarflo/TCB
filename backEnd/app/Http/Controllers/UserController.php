<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;

use App\User;
use App\level_user;
use App\cmsAction;
use App\cmsModule;
use App\action_module;

class UserController extends Controller
{
    //recupera todos los usuarios
    function getAllUsers()
    {
        $res = User::select("id", "name", "email", "type_user")->get();
        return $res;
    }

    //agrega nuevos usuarios
    function addUsers(UserCreateRequest $request)
    {
        $usuario = new User;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->type_user = 2;
        $usuario->save();
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //update de el nombre y del email del usuario
    public function upateUsers(UserCreateRequest $request)
    {
        $usuario = User::find($request->id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //elimina usuarios
    function deleteUsers(Request $request)
    {
        try {
            $task = User::findOrFail($request->id);
            $task->delete();
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'user not found or deleted'
            ], 404);
        }
    }

    // level user module

    //recupera los modulos y sus acciones para armar los permisos
    function getLevelModule(Request $request)
    {
        $module = cmsModule::select("*")
            ->where("active", 1)
            ->orderBy('name', 'ASC')
            ->get();

        if (count($module) > 0) {
            $arr = [];
            foreach ($module as $valueModule) {

                $actions = action_module::select(
                    "cms_actions.id as actionId",
                    "cms_actions.name as actionName",
                    "action_modules.id"
                )
                    ->join('cms_actions', 'action_modules.cms_actions_id', '=', 'cms_actions.id')
                    ->where("action_modules.cms_modules_id", $valueModule->id)
                    ->get();
                if (count($actions) > 0) {
                    $aux = [];
                    $checado = [];
                    foreach ($actions as $valueAction) {
                        $aux[] = array(
                            "idAction" => $valueAction->actionId,
                            "nameAction" => $valueAction->actionName
                        );
                        $existentes = level_user::select("*")
                            ->where('users_id', $request->idUser)
                            ->where('cms_modules_id', $valueModule->id)
                            ->where('cms_actions_id', $valueAction->actionId)
                            ->get();
                        if (count($existentes) > 0) {
                            $checado[] = $valueAction->actionId;
                        }
                    }
                    $arr[] = array(
                        "idModule" => $valueModule->id,
                        "nameModule" => $valueModule->name,
                        //"modelFather" => str_replace("/", "", str_replace(" ", "", strtolower($valueModule->name))),
                        "action" => $aux,
                        "checkAction" => $checado
                    );
                }
            }

            return response()->json([
                'data' => $arr,
                'message' => ''
            ], 200);
        } else {
            return response()->json([
                'data' => '',
                'message' => 'No data  module found'
            ], 400);
        }
    }

    //add and remove level user
    function addRemoveLevelUser(Request $request)
    {
        $res = level_user::where('users_id', $request->idUser)
            ->where('cms_modules_id', $request->idModule)
            ->where('cms_actions_id', $request->idAction)
            ->get();
        if (count($res) > 0) {
            level_user::where('id', $res[0]->id)
                ->delete();
        } else {
            $agrega = new level_user();
            $agrega->users_id = $request->idUser;
            $agrega->cms_modules_id = $request->idModule;
            $agrega->cms_actions_id = $request->idAction;
            $agrega->save();
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //get level by user and module
    function getMeLevelUser(Request $request)
    {

        $res = level_user::select("cms_actions_id")
            ->where('users_id', $request->idUser)
            ->where('cms_modules_id', $request->idModule)
            ->get();

        if (count($res) > 0) {
            return response()->json([
                'data' => $res,
                'message' => 'success'
            ], 200);
        } else {
            return response()->json([
                'data' => '',
                'message' => 'no level for this user'
            ], 400);
        }
    }
}
