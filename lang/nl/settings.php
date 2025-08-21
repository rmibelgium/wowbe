<?php

return [

    'header' => [
        'title' => 'Instellingen',
        'description' => 'Beheer je profiel en accountinstellingen.',
    ],

    'menu' => [
        'profile' => 'Profiel',
        'password' => 'Wachtwoord',
        'appearance' => 'Weergave',
    ],

    'appearance' => [
        'title' => 'Weergave',
        'description' => 'Werk de visuele weergave van je account bij.',

        'options' => [
            'light' => 'Licht',
            'dark' => 'Donker',
            'system' => 'Systeem',
        ],
    ],

    'password' => [
        'title' => 'Wachtwoordinstellingen',
        'description' => 'Werk het wachtwoord van je account bij.',

        'form' => [
            'title' => 'Wachtwoord bijwerken',
            'description' => 'Zorg ervoor dat je account een lang, willekeurig wachtwoord gebruikt om veilig te blijven.',

            'current_password' => 'Huidig wachtwoord',
            'new_password' => 'Nieuw wachtwoord',
            'new_password_confirmation' => 'Bevestig nieuw wachtwoord',

            'action' => [
                'save' => 'Wachtwoord opslaan',
            ],

            'success' => 'Opgeslagen.',
        ],
    ],

    'profile' => [
        'title' => 'Profielinstellingen',
        'description' => 'Werk de profielinformatie van je account bij.',

        'form' => [
            'title' => 'Profielinformatie',
            'description' => 'Werk je naam en e-mailadres bij.',
            'description_oauth' => 'Je account is gekoppeld aan een externe provider en kan hier niet worden bijgewerkt.',

            'name' => 'Volledige naam',
            'email' => 'E-mailadres',
            'locale' => 'Taal',
            'email_unverified' => 'Je e-mailadres is niet geverifieerd.',
            'resend_verification_email' => 'Klik hier om de verificatie e-mail opnieuw te versturen.',
            'verification_link_sent' => 'Er is een nieuwe verificatielink naar je e-mailadres gestuurd.',

            'oauth_linked' => 'Je account is gekoppeld aan :provider.',

            'action' => [
                'save' => 'Opslaan',
            ],

            'success' => 'Opgeslagen.',
        ],

        'delete' => [
            'title' => 'Account verwijderen',
            'description' => 'Verwijder je account permanent.',

            'warning' => [
                'title' => 'Waarschuwing',
                'description' => 'Zodra je account is verwijderd, worden alle persoonlijke gegevens permanent verwijderd.',
            ],

            'action' => [
                'delete' => 'Account verwijderen',
            ],

            'dialog' => [
                'title' => 'Bevestig accountverwijdering',
                'description' => 'Zodra je account is verwijderd, worden alle persoonlijke gegevens permanent verwijderd. Voer je wachtwoord in om te bevestigen dat je je account permanent wilt verwijderen.',

                'password' => 'Wachtwoord',

                'delete_data' => [
                    'title' => 'Verwijder ook je observaties',
                    'description' => 'Als dit vakje is aangevinkt, wordt niet alleen je account permanent verwijderd, maar ook de site(s) en observaties gerelateerd aan je account worden permanent verwijderd.',
                ],

                'action' => [
                    'cancel' => 'Annuleren',
                    'delete' => 'Account verwijderen',
                ],
            ],
        ],
    ],

];
