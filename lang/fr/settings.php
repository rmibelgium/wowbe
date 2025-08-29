<?php

return [

    'header' => [
        'title' => 'Paramètres',
        'description' => 'Gérez votre profil et les paramètres de votre compte.',
    ],

    'menu' => [
        'profile' => 'Profil',
        'password' => 'Mot de passe',
        'appearance' => 'Apparence',
    ],

    'appearance' => [
        'title' => 'Paramètres d\'apparence',
        'description' => 'Mettez à jour les paramètres d\'apparence de votre compte.',

        'options' => [
            'light' => 'Clair',
            'dark' => 'Sombre',
            'system' => 'Système',
        ],
    ],

    'password' => [
        'title' => 'Paramètres de mot de passe',
        'description' => 'Mettez à jour le mot de passe de votre compte.',

        'form' => [
            'title' => 'Mettre à jour le mot de passe',
            'description' => 'Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.',

            'current_password' => 'Mot de passe actuel',
            'new_password' => 'Nouveau mot de passe',
            'new_password_confirmation' => 'Confirmer le nouveau mot de passe',

            'action' => [
                'save' => 'Enregistrer le mot de passe',
            ],

            'success' => 'Enregistré.',
        ],
    ],

    'profile' => [
        'title' => 'Paramètres de profil',
        'description' => 'Mettez à jour les informations de votre profil.',

        'form' => [
            'title' => 'Informations de profil',
            'description' => 'Mettez à jour votre nom et votre adresse e-mail.',
            'description_oauth' => 'Votre compte est lié à un fournisseur OAuth et ne peut pas être mis à jour ici.',

            'name' => 'Nom complet',
            'email' => 'Adresse e-mail',
            'locale' => 'Langue',
            'email_unverified' => 'Votre adresse e-mail n\'est pas vérifiée.',
            'resend_verification_email' => 'Cliquez ici pour renvoyer l\'e-mail de vérification.',
            'verification_link_sent' => 'Un nouveau lien de vérification a été envoyé à votre adresse e-mail.',

            'oauth_linked' => 'Votre compte est lié à :provider.',

            'action' => [
                'save' => 'Enregistrer',
            ],

            'success' => 'Enregistré.',
        ],

        'delete' => [
            'title' => 'Supprimer le compte',
            'description' => 'Supprimer définitivement votre compte.',

            'warning' => [
                'title' => 'Avertissement',
                'description' => 'Une fois votre compte supprimé, toutes vos données personnelles seront définitivement supprimées.',
            ],

            'action' => [
                'delete' => 'Supprimer le compte',
            ],

            'dialog' => [
                'title' => 'Confirmer la suppression du compte',
                'description' => 'Une fois votre compte supprimé, toutes vos données personnelles seront définitivement supprimées. Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.',

                'password' => 'Mot de passe',

                'delete_data' => [
                    'title' => 'Supprimer également vos observations',
                    'description' => 'Si cette case est cochée, non seulement votre compte est définitivement supprimé, mais aussi le(s) site(s) et les observations lié(e)s à votre compte seront définitivement supprimé(e)s.',
                ],

                'action' => [
                    'cancel' => 'Annuler',
                    'delete' => 'Supprimer le compte',
                ],
            ],
        ],
    ],

];
