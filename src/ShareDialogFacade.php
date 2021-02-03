<?php

namespace Geekyants\ShareDialog;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Geekyants\ShareDialog\Skeleton\SkeletonClass
 */
class ShareDialogFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'share-dialog';
    }
}
