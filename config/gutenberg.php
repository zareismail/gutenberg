<?php 

use Zareismail\Gutenberg\Models\GutenbergFragment;
use Zareismail\Gutenberg\Models\GutenbergWebsite;
use Zareismail\Gutenberg\Nova\Fragment;
use Zareismail\Gutenberg\Nova\Website;

return [ 

    /*
    |--------------------------------------------------------------------------
    | Gutenberg Resources Classe
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify custom resources classe
    | to use instead of the type that ships with Gutenberg. You may use this to
    | define any extra form fields or other custom behavior as required.
    |
    */
   
	'resources' => [
        'fragment'  => Fragment::class,
        'website'   => Website::class,  
	],

    /*
    |--------------------------------------------------------------------------
    | Gutenberg Resources Model Classes
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify custom resources model
    | to use instead of the type that ships with Gutenberg.
    |
    */
   
    'models' => [
        Fragment::class => GutenbergFragment::class, 
        Website::class  => GutenbergWebsite::class, 
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Gutenberg Supported Locales
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to add or remove locale from Gutenberg website.
    |
    */
   
    'locales' => [ 
        'en' => __('English'),
    ], 
];