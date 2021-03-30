<?php

namespace Geekyants\Sharedo\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class ShareInertiaData extends Middleware
{

    public function share(Request $request)
    {
        return array_merge(parent::share($request), [


            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },


        ]);
    }
}
