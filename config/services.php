<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

    'mailgun' => [
        'domain' => '',
        'secret' => '',
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET', 'vSNawjeAZDvaMWAUa9vIvg'),
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1  ',
    ],

    'stripe' => [
        'model' => 'User',
        'secret' => '',
    ],
];
