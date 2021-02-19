<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    "modelPath" => "App\Models\\",
    'middleware' => ['web', 'auth'],
    'restrict-entities' => [],
    "sendEmail"=> true,
];
