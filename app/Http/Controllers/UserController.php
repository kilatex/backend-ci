<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function register(Request $request){
        $user = new User();

        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->save();
                
        
        $data = array(
            'status' => 'success',
            'code' => '200',
            'message' => 'Persona no registrada',
            'note' => $user
        );

        return response()->json($data);
        
    }
}
