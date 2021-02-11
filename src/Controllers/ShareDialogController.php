<?php

namespace Geekyants\ShareDialog\Controllers;


use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use Bouncer;
use Geekyants\ShareDialog\Services\RemovePreviousAbilties;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Bouncer as BouncerBouncer;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Queries\Abilities;
use Illuminate\Support\Facades\DB;



class ShareDialogController extends Controller
{




    public function showShareDialog($entity, $entityId)
    {


        try {
            $entityCapitalize = ucfirst($entity);
            $entityModel = substr($entityCapitalize, 0, -1);
            $entityModelSmall = substr($entity, 0, -1);
            $modelClass = config('share-dialog.modelPath') . $entityModel;

            if (class_exists($modelClass)) {


                $authUser = Auth::user();

                //find model
                $model = $modelClass::with('user')->findOrFail($entityId);

                $model['entity_name'] = $entityModelSmall;


                //if auth user does not own model
                if ($model->user_id != $authUser->id) {
                    return back()->withErrors("You are not authorized!");
                }

                //else assign ability to auth user
                if ($authUser->id == $model->user_id) {
                    Bouncer::allow($authUser)->toOwn($model);
                    Bouncer::allow($authUser)->to('write', $model);
                    Bouncer::allow($authUser)->to('read', $model);
                }

                //join on permission and abilities
                //returns user_id and ability of valid users on model
                $users = DB::table('abilities')
                    ->select('abilities.name', 'permissions.entity_id')
                    ->join('permissions', function ($join) use ($model) {
                        $join->on('abilities.id', '=', 'permissions.ability_id')
                            ->where('abilities.entity_id', $model->id);
                    })
                    ->get();


                //find user according to id and assign permission to user object
                $invitedUsers = [];
                $mapArray = array();
                foreach ($users as $index => $key) {
                    if ($key->entity_id != $model->user_id) {
                        $key->name = ($key->name == "read") ? "Read" : "Can Edit";
                        $mapArray[$key->entity_id] = $key->name;
                        array_push($invitedUsers, $key->entity_id);
                    }
                }
                $users = User::find($invitedUsers);

                foreach ($users as $user) {
                    $user->ability = $mapArray[$user->id];
                }
                return Inertia::render('ShareDialog/index', ['entity' => $model, 'users' => $users]);
            } else {
                return back()->withErrors("Model does not exist");
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function createUser($email)
    {

        $user = User::create([
            'name' => "User",
            'email' => $email,
            'password' => Hash::make('user'),
        ]);
        return $user;
    }


    public function assignAbility(Request $request)
    {
        try {
            $request->validate([
                'emails' => 'required',
                'emails.*.email' => 'bail|required|email|min:5',
                'ability' => 'required'
            ]);

            $entityClass = ucfirst($request->entity_name);
            $modelClass = config('share-dialog.modelPath') . $entityClass;
            $model = $modelClass::findOrFail($request->entity_id);
            $entityModelSmall = $request->entity_name . 's';


            $newUser = false;
            $emails = $request->emails;
            foreach ($emails as $email) {

                $user = User::where('email', $email['email'])->first();
                if (!$user) {
                    $user = $this->createUser($email['email']);
                    $newUser = true;
                    $message = "Users Invited!";
                }


                $validAbilities = ['read', 'write'];
                if (in_array($request->ability, $validAbilities)) {
                    RemovePreviousAbilties::removeAbilties($user, $model, $modelClass);
                    Bouncer::allow($user)->to($request->ability, $model);
                    $message = $newUser ? "Users Invited!" : "User Access Changed Successfully!";
                }
                if ($request->ability == "remove") {
                    RemovePreviousAbilties::removeAbilties($user, $model, $modelClass);
                    $message = "User Removed!";
                }
            }
            return redirect()->route('share-dialog', ['entity' => $entityModelSmall, 'entityId' => $request->entity_id])->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
