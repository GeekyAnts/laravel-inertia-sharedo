<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    "modelPath" => "App\Models\\",
    "inertiaPath" => "Pages",
    'middleware' => ['web', 'auth'],
    'button' => [
        "bg-purple-600",
        "hover:bg-purple-500",
        "text-white",
        "rounded",
        "text-sm",
        "focus:outline-none"
    ]
];
