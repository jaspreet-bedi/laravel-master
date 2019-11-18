<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    //
	public function index()
	{
	      return view('users/index');
	}
        
        public function createUser(){
            return view('users/create');
        }
        
        public function saveUser(Request $request){
           
            $arrUsr=[];
            $arrUsr['username']=$request->get('username');
            $arrUsr['email']=$request->get('email');
            $arrUsr['password']=$request->get('password');
            
            //User::insert($arrUsr);
            $user=new User();
            
            $user->createUser($arrUsr);
            return redirect()->action('UsersController@index');    
                    
        }
}