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

    'failed' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe entré est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    'login' => [
        'title' => 'Connexion',
        'description' => 'Connectez-vous avec votre compte Google ou GitHub.',

        'form' => [
            'email' => 'Adresse e-mail',
            'password' => 'Mot de passe',
            'remember_me' => 'Se souvenir de moi',
            'forgot_password' => 'Mot de passe oublié ?',
            'action' => [
                'submit' => 'Se connecter',
            ],
        ],

        'oauth' => [
            'google' => 'Se connecter avec Google',
            'github' => 'Se connecter avec GitHub',
        ],

        'or_continue_with' => 'Ou continuez avec',

        'no_account' => "Vous n'avez pas de compte ?",
        'sign_up' => "S'inscrire",
    ],

    'register' => [
        'title' => 'Inscription',
        'description' => 'Créez un nouveau compte pour commencer.',

        'form' => [
            'name' => 'Nom complet',
            'email' => 'Adresse e-mail',
            'password' => 'Mot de passe',
            'password_confirmation' => 'Confirmer le mot de passe',
            'action' => [
                'submit' => 'Créer un compte',
            ],
        ],

        'already_account' => 'Vous avez déjà un compte ?',
        'sign_in' => 'Se connecter',
    ],

    'forgot_password' => [
        'title' => 'Mot de passe oublié',
        'description' => 'Entrez votre e-mail pour recevoir un lien de réinitialisation du mot de passe.',

        'form' => [
            'email' => 'Adresse e-mail',
            'action' => [
                'submit' => 'Envoyer le lien de réinitialisation du mot de passe',
            ],
        ],

        'or_return_to' => 'Ou, retournez à',
        'sign_in' => 'se connecter',
    ],

    'confirm_password' => [
        'title' => 'Confirmer le mot de passe',
        'description' => 'Ceci est une zone sécurisée de l\'application. Veuillez confirmer votre mot de passe avant de continuer.',

        'form' => [
            'password' => 'Mot de passe',
            'action' => [
                'submit' => 'Confirmer le mot de passe',
            ],
        ],
    ],

    'reset_password' => [
        'title' => 'Réinitialiser le mot de passe',
        'description' => 'Veuillez entrer votre nouveau mot de passe ci-dessous.',

        'form' => [
            'email' => 'Adresse e-mail',
            'password' => 'Mot de passe',
            'password_confirmation' => 'Confirmer le mot de passe',

            'action' => [
                'submit' => 'Réinitialiser le mot de passe',
            ],
        ],
    ],

    'verify_email' => [
        'title' => 'Vérifier l\'email',
        'description' => 'Veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.',

        'link_sent' => 'Un nouveau lien de vérification a été envoyé à l\'adresse e-mail que vous avez fournie lors de l\'inscription.',

        'action' => [
            'resend' => 'Renvoyer l\'email de vérification',
            'logout' => 'Se déconnecter',
        ],
    ],

];
