<?php

namespace Geekyants\ShareDialog\Controllers;


use Inertia\Inertia;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Bouncer;
use Geekyants\ShareDialog\Services\RemovePreviousAbilties;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Bouncer as BouncerBouncer;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ShareDialogController extends Controller
{




    public function showShareDialog($entity, $entityId)
    {

        $entityCapitalize = ucfirst($entity);
        $entityModel = substr($entityCapitalize, 0, -1);
        $model_name = 'App\Models\\' . $entityModel;
        if (class_exists("App\Models\\" . $entityModel)) {
            $authUser = Auth::user();
            $model = $model_name::with('user')->findOrFail($entityId);
            $users = User::with('abilities')->get();

            if ($authUser->id == $model->user_id) {
                Bouncer::allow($authUser)->toOwn($model_name);
                Bouncer::allow($authUser)->to('write', $model_name);
                Bouncer::allow($authUser)->to('read', $model_name);
            }

            $validUsers = [];
            foreach ($users as $user) {
                foreach ($user->abilities as $ability) {
                    if ($ability->entity_type == $model_name && $ability->entity_id == $model->id && $user->id != $authUser->id) {
                        if ($ability->name == "read")
                            $user->ability = "Read";
                        else if ($ability->name = "write")
                            $user->ability = "Can Edit";
                        array_push($validUsers, $user);
                    }
                }
            }

            if ($model->user_id != $authUser->id) {
                return back()->withErrors("You are not authorized!");
            }

            $entityModelSmall = substr($entity, 0, -1);
            $model['entity_name'] = $entityModelSmall;

            return Inertia::render('ShareDialog/index', ['entity' => $model, 'users' => $validUsers]);
        } else {
            return back()->withErrors("Model does not exist");
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
        $validatedData = $request->validate([
            'emails' => 'required',
            'emails.*.email' => 'bail|required|email|min:5',
            'ability' => 'required'
        ]);

        $entityClass = ucfirst($request->entity_name);
        $model_name = 'App\Models\\' . $entityClass;
        $model = $model_name::findOrFail($request->entity_id);
        $entityModelSmall = $request->entity_name . 's';


        $message = "User Access Changed Successfully!";
        $emails = $request->emails;
        $newUser = false;
        foreach ($emails as $email) {

            $user = User::where('email', $email['email'])->first();
            if (!$user) {
                $user = $this->createUser($email['email']);
                if (!$user)
                    return back()->withErrors("User could not be created successfully!");
                $message = "Users Invited!";
            }
            if ($request->ability == "Read") {
                RemovePreviousAbilties::removeAbilties($user, $model, $model_name);
                Bouncer::allow($user)->to('read', $model);
            } else if ($request->ability == "Can Edit") {
                RemovePreviousAbilties::removeAbilties($user, $model, $model_name);
                Bouncer::allow($user)->to('write', $model);
            } else if ($request->ability == "Remove") {
                $message = "User Removed!";
                RemovePreviousAbilties::removeAbilties($user, $model, $model_name);
            }
        }

        return redirect()->route('share-dialog', ['entity' => $entityModelSmall, 'entityId' => $request->entity_id])->with('message', $message);
    }
}
