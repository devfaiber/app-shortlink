<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class PerfilController extends Controller
{

    public function __construct(){
        $this->middleware("auth");
    }

    public function index(Request $request){
        $id = Auth::id();
        $user = User::find($id)->first();
        return view("perfil",compact("user"));
    }

    public function show($id, Request $request){
        // $user = Auth::user();
        $user = User::find($id)->first();
        return $user;
    }

    public function update($id, Request $request){

        $user = User::find($id);

        $user->name = $request->name;


        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
            //$user->password = $request->password;
        }

        $user->email = $request->email;


        $user->save();
        return $user;
    }
}
