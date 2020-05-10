<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Link;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index($token, Request $request)
    {
        $ruta = Link::whereRaw("BINARY `token`= ?",[$token])->first();

        if(!$ruta){
            return view("Home",compact("ruta"));
        }

        return redirect($ruta->url,);
    }

}
