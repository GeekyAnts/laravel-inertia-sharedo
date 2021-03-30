<?php

namespace Geekyants\Sharedo\Services;

use Geekyants\Sharedo\Services\RemovePreviousAbiltiesService;
use App\Models\User;
use Bouncer;
use Illuminate\Support\Facades\Hash;
use Geekyants\Sharedo\Events\UserAbilityChanged;
use Illuminate\Support\Facades\DB;

class AssignAbilityService
{


    public static function assignAbilities($emails, $ability, $model, $modelClass, $entityClass)
    {

        $message = "";
        $newUser = false;
        foreach ($emails as $email) {
            $user = User::where('email', $email['email'])->first();
            if (!$user) {
                $user = self::createUser($email['email']);
                DB::table('new_users_shared_dialog')->insert([
                    'user_id' => $user->id,
                    'has_ever_logged_in' => "false"
                ]);
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
            event(new UserAbilityChanged($user, $ability, $entityClass, $model));
        }
        return $message;
    }

    public static function createUser($email)
    {

        $user = User::create([
            'name' => "User",
            'email' => $email,
            'password' => Hash::make('user'),
        ]);

        return $user;
    }
}
