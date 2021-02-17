<?php

namespace Geekyants\ShareDialog\Services;

use Geekyants\ShareDialog\Services\RemovePreviousAbiltiesService;
use App\Models\User;
use Bouncer;
use Illuminate\Support\Facades\Hash;
use Geekyants\ShareDialog\Events\UserAbilityChanged;

class AssignAbilityService{


    public static function assignAbilities($emails,$ability,$model,$modelClass,$entityClass){
  
        $message="";
        $newUser = false;
        foreach ($emails as $email) {
            $user = User::where('email', $email['email'])->first();
            if (!$user) {
                $user = self::createUser($email['email']);
                $newUser = true;
                $message = "Users Invited!";
            }
            $validAbilities = ['read', 'write'];
            if (in_array($ability, $validAbilities)) {
                RemovePreviousAbiltiesService::removeAbilties($user, $model, $modelClass);
                Bouncer::allow($user)->to($ability, $model);
                $message = $newUser ? "Users Invited!" : "User Access Changed Successfully!";
            }
            if ($ability == "remove") {
                RemovePreviousAbiltiesService::removeAbilties($user, $model, $modelClass);
                $message = "User Removed!";
            }
                if(config('share-dialog.sendEmail'))
                event(new UserAbilityChanged($user, $ability, $entityClass, $model));
            
          
        }
        return $message;

    }

    public static function createUser($email){
    
        $user = User::create([
            'name' => "User",
            'email' => $email,
            'password' => Hash::make('user'),
        ]);

        return $user;
    }





}