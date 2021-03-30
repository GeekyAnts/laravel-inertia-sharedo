<?php


namespace Geekyants\Sharedo\Controllers;


use Inertia\Inertia;
use Illuminate\Http\Request;
use Bouncer;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Bouncer as BouncerBouncer;
use App\Http\Controllers\Controller;
use Geekyants\Sharedo\Services\InvitedUsersService;
use Geekyants\Sharedo\Services\AssignAbilityService;






class SharedoController extends Controller
{


    public function searchUsers($query)
    {

        if (!config('sharedo.typehead'))
            return response()->json(['searchUsers' => []]);
        $className = config('sharedo.typehead');
        $controller =  new $className;
        $users = json_decode($controller->getUserContacts($query));
        return response()->json(['searchUsers' => $users]);
    }

    public function showSharedo($entity, $entityId)
    {

        try {
            $entityCapitalize = ucfirst($entity);
            $entityModel = substr($entityCapitalize, 0, -1);
            $entityModelSmall = substr($entity, 0, -1);
            $modelClass = config('sharedo.modelPath') . $entityModel;

            if (class_exists($modelClass)) {

                $authUser = Auth::user();
                //find model
                $model = $modelClass::with('user')->findOrFail($entityId);
                $model['entity_name'] = $entityModelSmall;

                //if auth user does not own model
                if ($model->user_id != $authUser->id && Bouncer::cannot('write', $model)) {
                    return back()->withErrors("You are not authorized!");
                }

                //else assign ability to auth user
                if ($authUser->id == $model->user_id) {
                    Bouncer::allow($authUser)->toOwn($model);
                    Bouncer::allow($authUser)->to('write', $model);
                    Bouncer::allow($authUser)->to('read', $model);
                }
                $findUser = false;
                if (config('sharedo.typehead'))
                    $findUser = true;

                //get invited users  
                list($users, $usersAbiltites) = InvitedUsersService::getInvitedUsers($model);
                Inertia::setRootView('sharedo');
                return Inertia::render('Sharedo/index', ['entity' => $model, 'users' => $users, 'findUser' => $findUser, 'usersAbilities' => $usersAbiltites, 'modelOwner' => $model->user]);
            } else {
                return back()->withErrors("Model does not exist");
            }
        } catch (\Exception $e) {

            return back()->withErrors($e->getMessage());
        }
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
            $modelClass = config('sharedo.modelPath') . $entityClass;
            $model = $modelClass::findOrFail($request->entity_id);
            $entityModelSmall = $request->entity_name . 's';

            //get emails from request
            $emails = $request->emails;

            //assign abilities
            $message = AssignAbilityService::assignAbilities($emails, $request->ability, $model, $modelClass, $entityClass);

            return redirect()->route('sharedo', ['entity' => $entityModelSmall, 'entityId' => $request->entity_id])->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
