<?php

return [

    'driver' => 'eloquent',

    'model' => App\UserAccount::class,   // ← MUST BE ::class, NOT string!

    'table' => 'users',                  // ← YOUR TABLE IS users, NOT user_accounts!

    'password' => [
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
    ],

];