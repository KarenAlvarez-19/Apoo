<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class usersController extends Controller
{
 
    public function getUsers(Request $request) //funcion para obtener registros
    {
        $users=User::all();
        return $users;
    }

    public function postUsers(Request $request) //funcion para crear registros
    {
        $users = new User;
        $users->name=$request['name'];
        $users->email=$request['email'];
        $users->telefono=$request['telefono'];
        $users->password= Hash::make($request['password']);
        $users->tipo=$request['tipo'];
        $users->save();
        return $users;
    }

    public function putUser(Request $request) //funcion para editar registros
    {
        $user=User::where('id','=',$request['id'])
        ->update(['name'=>$request['name'], 
        'email'=>$request['email'],
        'telefono'=>$request['telefono'],
        'tipo'=>$request['tipo']]);
        return $user;
    }

    public function deleteUser(Request $request)
    {
        $user=User::where('id','=',$request['id'])->delete();
        return $user;
    }

}

