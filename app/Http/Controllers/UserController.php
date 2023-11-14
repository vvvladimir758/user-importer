<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function update(Request $request, User $user){

        $data = [
            'updated'  => 0,
            'created'  => 0,
            'total'    => 0
        ];
        
       
        
       $users = [];
       foreach ($request->get('results') as $note){
           
           $users[] =  [
               'first_name'  => $note['name']['first'],
               'last_name'   => $note['name']['last'],
               'email'       => $note['email'],
               'age'         => $note['dob']['age']
           ];
           
       }
       
        $now = Carbon::now();
        DB::beginTransaction();
        
        User::upsert([ ...$users], ['first_name','last_name'], ['email', 'age']);

        DB::commit();

        $data['updated'] = User::where('updated_at','>=', $now)
                                ->where('created_at','<', $now)
                                ->get()->count();
        $data['created'] = User::where('created_at','>=', $now)->get()->count();
        $data['total']  =  $user->count();
        
        return response()->json($data);

    }
    
    public function getTotal(User $users){
        return response()->json(
                           $users
                           ->count()
                          );
    }
    
   
}
