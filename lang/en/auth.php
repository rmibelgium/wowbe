<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'login' => [
        'title' => 'Login',
        'description' => 'Login with your Google or your GitHub account.',

        'form' => [
            'email' => 'Email address',
            'password' => 'Password',
            'remember_me' => 'Remember me',
            'forgot_password' => 'Forgot password?',
            'action' => [
                'submit' => 'Log in',
            ],
        ],

        'oauth' => [
            'google' => 'Login with Google',
            'github' => 'Login with GitHub',
        ],

        'or_continue_with' => 'Or continue with',

        'no_account' => "Don't have an account?",
        'sign_up' => 'Sign up',
    ],

    'register' => [
        'title' => 'Register',
        'description' => 'Create a new account to get started.',

        'form' => [
            'name' => 'Full name',
            'email' => 'Email address',
            'password' => 'Password',
            'password_confirmation' => 'Confirm password',
            'action' => [
                'submit' => 'Create account',
            ],
        ],

        'already_account' => 'Already have an account?',
        'sign_in' => 'Log in',
    ],

    'forgot_password' => [
        'title' => 'Forgot password',
        'description' => 'Enter your email to receive a password reset link.',

        'form' => [
            'email' => 'Email address',
            'action' => [
                'submit' => 'Email password reset link',
            ],
        ],

        'or_return_to' => 'Or, return to',
        'sign_in' => 'log in',
    ],

    'confirm_password' => [
        'title' => 'Confirm Password',
        'description' => 'This is a secure area of the application. Please confirm your password before continuing.',

        'form' => [
            'password' => 'Password',

            'action' => [
                'submit' => 'Confirm Password',
            ],
        ],
    ],

    'reset_password' => [
        'title' => 'Reset Password',
        'description' => 'Please enter your new password below.',

        'form' => [
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',

            'action' => [
                'submit' => 'Reset Password',
            ],
        ],
    ],

    'verify_email' => [
        'title' => 'Verify Email',
        'description' => 'Please verify your email address by clicking on the link we just emailed to you.',

        'link_sent' => 'A new verification link has been sent to the email address you provided during registration.',

        'action' => [
            'resend' => 'Resend verification email',
            'logout' => 'Log out',
        ],
    ],

];
