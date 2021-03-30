<?php



namespace Geekyants\Sharedo\Services;

use Bouncer;
use Exception;

class RemovePreviousAbiltiesService

{
    public function __construct()
    {
    }

    public static function removeAbilties($user, $model, $model_name)
    {
        $abilities = $user->getAbilities();
        foreach ($abilities as $ability) {
            if ($ability->entity_id = $model->id && $ability->entity_type == $model_name) {
                Bouncer::disallow($user)->to($ability->name, $model);
            }
        }
    }
}
