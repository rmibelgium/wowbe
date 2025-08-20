<?php

return [

    'header' => [
        'site' => 'Site ":site"',

        'register' => [
            'title' => 'Register a site',
        ],
        'update' => [
            'title' => 'Update your site information',
            'description' => 'Manage your site information.',
        ],
        'authentication' => [
            'title' => 'Update your site information',
            'description' => 'Update your site authentication key.',
        ],
        'delete' => [
            'title' => 'Delete your site',
            'description' => 'Delete your site and all of its observations.',
        ],
    ],

    'menu' => [
        'edit' => 'Edit',
        'authentication' => 'Authentication key',
        'delete' => 'Delete',
    ],

    'location' => [
        'title' => 'Site location',
        'description' => 'Please enter either longitude and latitude values directly in the form or use the map to select a location. You can either search for a location or click on the map to define the location.',

        'longitude' => 'Longitude',
        'latitude' => 'Latitude',
        'altitude' => 'Altitude',
    ],

    'details' => [
        'title' => 'Site details',
        'description' => 'Site name is how others will see your Site on WOW-BE. Timezone is also mandatory - all other fields are optional.',

        'name' => 'Name',
        'timezone' => 'Timezone',
        'timezone_select' => 'Select a timezone',
        'website' => 'Personal website of your station',
        'brand' => 'Brand and model of the station',
        'software' => 'Software used to upload data to WOW-BE',
    ],

    'pictures' => [
        'title' => 'Site picture',
        'description' => 'You can upload a picture for your site. The picture should be in JPG or PNG format and should not exceed 5MB in size.',

        'picture_add' => 'Add picture',
        'picture_remove' => 'Remove picture',
    ],

    'authentication' => [
        'title' => 'Site authentication',
        'description' => 'You need to define an authentication key for your site. This key is used to authenticate your site while sending observations. You can use a PIN code or a password, but not both at the same time. If you want to change your authentication key later, you can do so.',

        'or' => 'OR',

        'pincode' => 'PIN code',
        'pincode_description' => 'Set a 6 digits PIN code as authentication key.',
        'password' => 'Password',
        'password_description' => 'Set a password as authentication key.',
    ],

    'delete' => [
        'warning' => [
            'title' => 'Warning',
            'description' => 'This action cannot be undone.',
        ],
        'dialog' => [
            'title' => 'Are you sure you want to delete your site?',
            'description' => 'Once your site is deleted, all of its data will also be archived. Please type the authentication key of your site to confirm you would like to delete it.',
        ],
    ],

    'action' => [
        'submit' => 'Submit',
        'save' => 'Save',
        'set_pincode' => 'Set PIN code',
        'set_password' => 'Set password',
        'delete' => 'Delete site',
        'cancel' => 'Cancel',
    ],

    'success' => [
        'updated' => [
            'title' => 'Site updated',
            'description' => 'The site ":site" has been updated successfully.',
            'description_auth' => 'The authentication key for the site ":site" has been updated successfully.',
        ],
        'created' => [
            'title' => 'Site created',
            'description' => 'The site ":site" has been created successfully.',
        ],
    ],

    'public' => 'This information will be shown publicly.',
    'not_public' => 'This information will not be shown publicly.',

];
