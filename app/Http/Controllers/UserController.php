<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function update(Request $request, User $users){

        $data = [
            'updated'  => 0,
            'created'  => 0,
            'total'    => 0
        ];
        
        foreach ($request->get('results') as $note){
            
            $user = User::updateOrCreate(
                ['first_name' => $note['name']['first'], 'last_name' => $note['name']['last']],
                ['email' => $note['email'], 'age'=>$note['dob']['age']]
                );
            
            if(!$user->wasRecentlyCreated &&  $user->wasChanged()){
                $data['updated']++;
            }
            
            if($user->wasRecentlyCreated){
                $data['created']++; 
            }
            
        }
        
        $data['total']  =  $users->count();
        
        return response()->json($data);

    }
    
    public function getTotal(User $users){
        return response()->json(
                           $users
                           ->count()
                          );
    }
    
   
}
