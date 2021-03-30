<?php

namespace Geekyants\Sharedo\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use stdClass;

class InvitedUsersService
{
    public static function getInvitedUsers($model)
    {
        //join on permission and abilities
        //returns user_id and ability of valid users on model
        $users = DB::table('abilities')
            ->select('abilities.name', 'permissions.entity_id')
            ->join('permissions', function ($join) use ($model) {
                $join->on('abilities.id', '=', 'permissions.ability_id')
                    ->where('abilities.entity_id', $model->id);
            })
            ->get();

        return self::getUsersInformation($users, $model);
    }
    public static function getUsersInformation($users, $model)
    {


        //find user according to id and assign permission to user object

        $invitedUsers = [];
        $mapArray = array();
        $userAbilities = array();
        foreach ($users as $index => $key) {


            if ($key->entity_id == $model->user->id)
                continue;

            $key->name = ($key->name == "read") ? "Can View" : "Can Edit";
            $abilityObject = new stdClass;
            if ($key->name == "Can View") {
                $abilityObject->value = "read";
                $abilityObject->ability = "Can View";
            } else {
                $abilityObject->value = "write";
                $abilityObject->ability = "Can Edit";
            }
            $userAbilities[$key->entity_id] = $abilityObject;
            $mapArray[$key->entity_id] = $key->name;
            array_push($invitedUsers, $key->entity_id);
        }
        $users = User::find($invitedUsers);
        foreach ($users as $user) {
            $user->ability = $mapArray[$user->id];
        }
        return array($users, $userAbilities);
    }
}
