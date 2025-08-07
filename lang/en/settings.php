<?php

return [

    'header' => [
        'title' => 'Settings',
        'description' => 'Manage your profile and account settings.',
    ],

    'menu' => [
        'profile' => 'Profile',
        'password' => 'Password',
        'appearance' => 'Appearance',
    ],

    'appearance' => [
        'title' => 'Appearance settings',
        'description' => 'Update your account\'s appearance settings.',

        'options' => [
            'light' => 'Light',
            'dark' => 'Dark',
            'system' => 'System',
        ],
    ],

    'password' => [
        'title' => 'Password settings',
        'description' => 'Update your account\'s password.',

        'form' => [
            'title' => 'Update password',
            'description' => 'Ensure your account is using a long, random password to stay secure.',

            'current_password' => 'Current password',
            'new_password' => 'New password',
            'new_password_confirmation' => 'Confirm new password',

            'action' => [
                'save' => 'Save password',
            ],

            'success' => 'Saved.',
        ],
    ],

    'profile' => [
        'title' => 'Profile settings',
        'description' => 'Update your account\'s profile information.',

        'form' => [
            'title' => 'Profile information',
            'description' => 'Update your name and email address.',
            'description_oauth' => 'Your account is linked to an OAuth provider and can not be updated here.',

            'name' => 'Full name',
            'email' => 'Email address',
            'email_unverified' => 'Your email address is unverified.',
            'resend_verification_email' => 'Click here to resend the verification email.',
            'verification_link_sent' => 'A new verification link has been sent to your email address.',

            'oauth_linked' => 'Your account is linked to :provider.',

            'action' => [
                'save' => 'Save',
            ],

            'success' => 'Saved.',
        ],

        'delete' => [
            'title' => 'Delete account',
            'description' => 'Permanently delete your account and all associated data.',

            'warning' => [
                'title' => 'Warning',
                'description' => 'Once your account is deleted, all of its resources and data will also be permanently deleted.',
            ],

            'action' => [
                'delete' => 'Delete account',
            ],

            'dialog' => [
                'title' => 'Confirm account deletion',
                'description' => 'Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',

                'password' => 'Password',

                'action' => [
                    'cancel' => 'Cancel',
                    'delete' => 'Delete account',
                ],
            ],
        ],
    ],

];
