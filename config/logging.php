<?php

return [
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['rollbar','single'],
        ],
        'rollbar' => [
            'driver' => 'rollbar',
            'handler' => \Rollbar\RollbarLogger::class,
            'access_token' => env('ROLLBAR_TOKEN'),
            'level' => 'debug',
            'person_fn' => 'get_user',
        ],
    ],
];

function get_user() {
    if(\Illuminate\Support\Facades\Auth::guest() == false) {
        $user = \Illuminate\Support\Facades\Auth::user();
        return [
            'id' => $user->id,
            'username' => $user->name,
            'email' => $user->email
        ];
    }

    return null;
}