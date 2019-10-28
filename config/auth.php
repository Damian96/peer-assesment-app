<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],


    /* Log the user in after verification */
    'verification' => [
        'auto-login' => false,
        'type' => 'markdown',
        'view' => 'emails.verify',
        'expire' => 60,
        'strings' => [
            'subject'   => 'Verify Email Address',
            'heading'   => 'Please click the button below to verify your email address.',
            'action'    => 'Verify Email Address',
            'notice'    => 'If you did not register, please send us an email at ' .
                "<a href='mailto:" . config('constants.mails.support.address') . "' title='" . config('constants.mails.support.name') . "'>" .
                config('constants.mails.support.address') . '<\/a>'
        ]
    ],

    /**
     * Reset password
     */
    'password_reset' => [
        'auto-login' => false,
        'type' => 'markdown',
        'view' => 'emails.reset',
        'expire' => 60,
        'strings' => [
            'subject'   => 'Reset Password',
            'heading'   => 'You are almost done!',
            'description' => 'Copy paste the code below, into the Password Reset form, and you are good to go!',
            'notice'    => 'If you did not perform this action, please send us an email at ' .
                "<a href='mailto:" . config('constants.mails.support.address') . "' title='" . config('constants.mails.support.name') . "'>" .
                config('constants.mails.support.address') . '<\/a>'
        ]
    ]
];
