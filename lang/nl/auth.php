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

    'failed' => 'Deze inloggegevens komen niet overeen met onze gegevens.',
    'password' => 'Het opgegeven wachtwoord is niet correct.',
    'throttle' => 'Te veel inlogpogingen. Probeer het opnieuw over :seconds seconden.',

    'login' => [
        'title' => 'Inloggen',
        'description' => 'Log in met je Google- of GitHub-account.',

        'form' => [
            'email' => 'E-mailadres',
            'password' => 'Wachtwoord',
            'remember_me' => 'Onthoud mij',
            'forgot_password' => 'Wachtwoord vergeten?',
            'action' => [
                'submit' => 'Inloggen',
            ],
        ],

        'oauth' => [
            'google' => 'Inloggen met Google',
            'github' => 'Inloggen met GitHub',
        ],

        'or_continue_with' => 'Of ga verder met',

        'no_account' => 'Heb je nog geen account?',
        'sign_up' => 'Registreren',
    ],

    'register' => [
        'title' => 'Registreren',
        'description' => 'Maak een nieuw account aan om te beginnen.',

        'form' => [
            'name' => 'Volledige naam',
            'email' => 'E-mailadres',
            'password' => 'Wachtwoord',
            'password_confirmation' => 'Bevestig wachtwoord',
            'locale' => 'Taal',
            'action' => [
                'submit' => 'Account aanmaken',
            ],
        ],

        'already_account' => 'Heb je al een account?',
        'sign_in' => 'Inloggen',
    ],

    'forgot_password' => [
        'title' => 'Wachtwoord vergeten',
        'description' => 'Vul je e-mailadres in om een link te ontvangen om je wachtwoord opnieuw in te stellen.',

        'form' => [
            'email' => 'E-mailadres',
            'action' => [
                'submit' => 'Verzend link voor reset wachtwoord',
            ],
        ],

        'or_return_to' => 'Of ga terug naar',
        'sign_in' => 'inloggen',
    ],

    'confirm_password' => [
        'title' => 'Bevestig wachtwoord',
        'description' => 'Dit is een beveiligd gedeelte van de website. Bevestig je wachtwoord voordat je verder gaat.',

        'form' => [
            'password' => 'Wachtwoord',

            'action' => [
                'submit' => 'Bevestig wachtwoord',
            ],
        ],
    ],

    'reset_password' => [
        'title' => 'Wachtwoord opnieuw instellen',
        'description' => 'Voer hieronder je nieuwe wachtwoord in.',

        'form' => [
            'email' => 'E-mail',
            'password' => 'Wachtwoord',
            'password_confirmation' => 'Bevestig wachtwoord',

            'action' => [
                'submit' => 'Wachtwoord opnieuw instellen',
            ],
        ],
    ],

    'verify_email' => [
        'title' => 'E-mailadres verifiëren',
        'description' => 'Verifieer je e-mailadres door op de link te klikken die we je net hebben gemaild.',

        'link_sent' => 'Een nieuwe verificatielink is verzonden naar het e-mailadres dat je tijdens de registratie hebt opgegeven.',

        'action' => [
            'resend' => 'Verificatie e-mail opnieuw verzenden',
            'logout' => 'Uitloggen',
        ],
    ],

];
