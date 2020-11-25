<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index()
    {
			return view('home');
			//$users = DB::table('users')->get();
			//return view('home', ['users' => $users]);
    }
    
    public function ajax()
    {
    	//$users = DB::table('committee_membership')->get();
    	$users = DB::table('users')->select('id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at')->get();
    	
    	return $users;
    }
    
}
