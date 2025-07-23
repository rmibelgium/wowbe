<?php

return [

    'title' => [
        'register' => 'Enregistrer un site',
        'update' => 'Mettre à jour les informations de votre site',
    ],

    'location' => [
        'title' => 'Emplacement du site',
        'description' => 'Veuillez entrer un code postal, un emplacement ou des valeurs de latitude/longitude, afin de nous permettre de positionner votre site sur la carte. Une fois que vous avez entré un emplacement, vous pouvez cliquer et faire glisser le pin vers un emplacement plus précis.',

        'longitude' => 'Longitude',
        'latitude' => 'Latitude',
        'altitude' => 'Altitude',
    ],

    'details' => [
        'title' => 'Détails du site',
        'description' => 'Le nom du site est la façon dont les autres verront votre site sur WOW. Le fuseau horaire est également obligatoire - tous les autres champs sont facultatifs.',

        'name' => 'Nom',
        'timezone' => 'Fuseau horaire',
        'timezone_select' => 'Sélectionner un fuseau horaire',
        'website' => 'Site internet',
        'brand' => 'Marque de la station de mesure',
        'software' => 'Logiciel de chargement des données',
    ],

    'pictures' => [
        'title' => 'Image du site',
        'description' => 'Vous pouvez télécharger une image pour votre site. L\'image doit être au format JPG ou PNG et ne doit pas dépasser 5 Mo.',

        'picture_add' => 'Ajouter une image',
        'picture_remove' => 'Supprimer l\'image',
    ],

    'authentication' => [
        'title' => 'Authentification du site',
        'description' => 'Vous devez définir une clé d\'authentification pour votre site. Cette clé est utilisée pour authentifier votre site lors de l\'envoi d\'observations. Vous pouvez utiliser un code PIN ou un mot de passe, mais pas les deux en même temps. Si vous souhaitez modifier votre clé d\'authentification plus tard, vous pouvez le faire.',

        'or' => 'OU',

        'pincode' => 'Code PIN',
        'pincode_description' => 'Définir un code PIN à 6 chiffres comme clé d\'authentification.',
        'password' => 'Mot de passe',
        'password_description' => 'Définir un mot de passe comme clé d\'authentification.',
    ],

    'action' => [
        'submit' => 'Soumettre',
        'save' => 'Enregistrer',
    ],

    'success' => [
        'updated' => [
            'title' => 'Site mis à jour',
            'description' => 'Le site ":site" a été mis à jour avec succès.',
        ],
        'created' => [
            'title' => 'Site créé',
            'description' => 'Le site ":site" a été créé avec succès.',
        ],
    ],

];
