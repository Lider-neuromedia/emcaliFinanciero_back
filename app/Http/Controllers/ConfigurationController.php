<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Rol;

class ConfigurationController extends Controller{


    public function getUsers(){
        $users = User::join('roles', 'rol', '=', 'id_rol')->get();
        $response = [
            'response' => 'success',
            'status' => 200,
            'users' => $users
        ];

        return response()->json($response, $response['status']);
    }

    public function registerUser(Request $request){
        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required|unique:usuarios',
            'celular' => 'required|max:10',
            'email' => 'required|unique:usuarios',
            'rol' => 'exists:App\Rol,id_rol|required',
            'usuario' => 'required|unique:usuarios',
            'password' => 'required'
        ]);

        $user = New User;
        $request['password'] = Hash::make($request['password']);

        if($user->create($request->all())){
            $response = [
                'response' => 'success',
                'status' => 200,
                'message' => 'Usuario registrado de manera correcta.',
            ];
        }else{
            $response = [
                'response' => 'error',
                'status' => 403,
                'message' => 'Error en la creación. Intente nuevamente.',
            ];
        }

        return response()->json($response, $response['status']);

    }

    public function updateUser(Request $request, $id){
        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required|unique:usuarios,cedula,'.$id,
            'celular' => 'required|max:10',
            'email' => 'required|unique:usuarios,email,'.$id,
            'rol' => 'exists:App\Rol,id_rol|required',
            'usuario' => 'required|unique:usuarios,usuario,'.$id,
        ]);

        $user = User::find($id);
        (isset($request['password'])) && $request['password'] = Hash::make($request['password']);
        
        if($user->update($request->all())){
            $response = [
                'response' => 'success',
                'status' => 200,
                'message' => 'Usuario actualizado de manera correcta.',
            ];
        }else{
            $response = [
                'response' => 'error',
                'status' => 403,
                'message' => 'Error en la creación. Intente nuevamente.',
            ];
        }

        return response()->json($response, $response['status']);
    }

    public function destroyUser($id){
        $auth_user = auth('api')->user();
        if ($id == $auth_user->id) {
            $response = [
                'response' => 'error',
                'status' => 403,
                'message' => 'No puedes eliminar tu usuario. Verifica por favor.'
            ];
        }else{
            $user = User::find($id);
            if ($user->delete()) {
                $response = [
                    'response' => 'success',
                    'status' => 200,
                    'message' => 'Usuario eliminado.'
                ];
            }else{
                $response = [
                    'response' => 'error',
                    'status' => 403,
                    'message' => "El usuario {$user->nombres} {$user->apellidos} no se puede eliminar."
                ];
            }
        }

        return response()->json($response, $response['status']);
    }

    public function roles(){
        $roles = Rol::all();

        $response = [
            'response' => 'success',
            'status' => 200,
            'roles' => $roles
        ];

        return response()->json($response, $response['status']);
    }

    public function downloadTemplate(){
        $file = public_path('files/datos_pr.xlsx');
        return response()->download($file);
    }
}
