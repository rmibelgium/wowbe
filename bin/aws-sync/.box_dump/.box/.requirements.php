<?php

return array (
  0 => 
  array (
    'type' => 'php',
    'condition' => '^8.3',
    'source' => NULL,
    'message' => 'This application requires a PHP version matching "^8.3".',
    'helpMessage' => 'This application requires a PHP version matching "^8.3".',
  ),
  1 => 
  array (
    'type' => 'extension',
    'condition' => 'iconv',
    'source' => 'symfony/polyfill-mbstring',
    'message' => 'The package "symfony/polyfill-mbstring" requires the extension "iconv".',
    'helpMessage' => 'The package "symfony/polyfill-mbstring" requires the extension "iconv". You either need to enable it or request the application to be shipped with a polyfill for this extension.',
  ),
  2 => 
  array (
    'type' => 'extension',
    'condition' => 'pcre',
    'source' => 'vlucas/phpdotenv',
    'message' => 'The package "vlucas/phpdotenv" requires the extension "pcre".',
    'helpMessage' => 'The package "vlucas/phpdotenv" requires the extension "pcre". You either need to enable it or request the application to be shipped with a polyfill for this extension.',
  ),
  3 => 
  array (
    'type' => 'extension',
    'condition' => 'pdo_oci',
    'source' => NULL,
    'message' => 'This application requires the extension "pdo_oci".',
    'helpMessage' => 'This application requires the extension "pdo_oci". You either need to enable it or request the application to be shipped with a polyfill for this extension.',
  ),
  4 => 
  array (
    'type' => 'extension',
    'condition' => 'pdo_pgsql',
    'source' => NULL,
    'message' => 'This application requires the extension "pdo_pgsql".',
    'helpMessage' => 'This application requires the extension "pdo_pgsql". You either need to enable it or request the application to be shipped with a polyfill for this extension.',
  ),
  5 => 
  array (
    'type' => 'extension',
    'condition' => 'zlib',
    'source' => NULL,
    'message' => 'This application requires the extension "zlib".',
    'helpMessage' => 'This application requires the extension "zlib". You either need to enable it or request the application to be shipped with a polyfill for this extension.',
  ),
  6 => 
  array (
    'type' => 'extension-conflict',
    'condition' => 'psr',
    'source' => 'symfony/service-contracts',
    'message' => 'The package "symfony/service-contracts" conflicts with the extension "psr".',
    'helpMessage' => 'The package "symfony/service-contracts" conflicts with the extension "psr". You need to disable it in order to run this application.',
  ),
);