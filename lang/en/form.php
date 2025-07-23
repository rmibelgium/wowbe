<?php

return [

    'title' => [
        'register' => 'Register a site',
        'update' => 'Update your site information',
    ],

    'location' => [
        'title' => 'Site location',
        'description' => 'Please enter either a postcode, location, or lat/lon values, to allow us to position your site on the map. Once you\'ve entered a location, you may click and drag the pin to a more accurate location.',

        'longitude' => 'Longitude',
        'latitude' => 'Latitude',
        'altitude' => 'Altitude',
    ],

    'details' => [
        'title' => 'Site details',
        'description' => 'Site name is how others will see your Site on WOW. Timezone is also mandatory - all other fields are optional.',

        'name' => 'Name',
        'timezone' => 'Timezone',
        'timezone_select' => 'Select a timezone',
        'website' => 'Website',
        'brand' => 'Brand of the station',
        'software' => 'Upload software',
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

    'action' => [
        'submit' => 'Submit',
        'save' => 'Save',
    ],

    'success' => [
        'updated' => [
            'title' => 'Site updated',
            'description' => 'The site ":site" has been updated successfully.',
        ],
        'created' => [
            'title' => 'Site created',
            'description' => 'The site ":site" has been created successfully.',
        ],
    ],

];
