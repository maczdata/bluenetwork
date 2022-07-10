<?php

return [
    'app' => [
        'title' => 'General',
        'desc'  => 'All the general settings for application.',
        'icon'  => 'fas fa-cube',

        'elements' => [
            [
                'type'  => 'text', // input fields type
                'data'  => 'string', // data type, string, int, boolean
                'name'  => 'app_name', // unique name for field
                'label' => 'App Name', // you know what label it is
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'class' => '', // any class for input
                'value' => 'BlueDiamond', // default value if you want
            ],
        ],
    ],
    'frontend' => [
        'title' => 'Frontend Settings',
        'desc'  => 'Frontend settings for app',
        'icon'  => 'fas fa-globe-asia',

        'elements' => [
            [
                'type'  => 'file', // input fields type
                'multiple' => 'yes',
                'data'  => 'file', // data type, string, int, boolean
                'name'  => 'homepage_carousel', // unique name for field
                'label' => 'Homepage Carousel', // you know what label it is
                'rules' => '', // validation rule of laravel
                'class' => '', // any class for input
                'value' => null, // default value if you want
            ],
            [
                'type'  => 'file', // input fields type
                'multiple' => 'no',
                'data'  => 'file', // data type, string, int, boolean
                'name'  => 'homepage_sidebar_image', // unique name for field
                'label' => 'Homepage Sidebar Image', // you know what label it is
                'rules' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048', // validation rule of laravel
                'class' => '', // any class for input
                'value' => null, // default value if you want
            ],
        ],

    ],
    'social' => [
        'title' => 'Social Profiles',
        'desc'  => 'Link of all the social profiles.',
        'icon'  => 'fas fa-users',

        'elements' => [
            [
                'type'  => 'text', // input fields type
                'multiple' => 'no',
                'data'  => 'string', // data type, string, int, boolean
                'name'  => 'facebook_url', // unique name for field
                'label' => 'Facebook Page URL', // you know what label it is
                'rules' => 'required|nullable|max:191', // validation rule of laravel
                'class' => '', // any class for input
                'value' => '#', // default value if you want
            ],
            [
                'type'  => 'text', // input fields type
                'multiple' => 'no',
                'data'  => 'string', // data type, string, int, boolean
                'name'  => 'twitter_url', // unique name for field
                'label' => 'Twitter Profile URL', // you know what label it is
                'rules' => 'required|nullable|max:191', // validation rule of laravel
                'class' => '', // any class for input
                'value' => '#', // default value if you want
            ],
            [
                'type'  => 'text', // input fields type
                'multiple' => 'no',
                'data'  => 'string', // data type, string, int, boolean
                'name'  => 'instagram_url', // unique name for field
                'label' => 'Instagram Account URL', // you know what label it is
                'rules' => 'required|nullable|max:191', // validation rule of laravel
                'class' => '', // any class for input
                'value' => 'https://instagram.com', // default value if you want
            ],
            [
                'type'  => 'text', // input fields type
                'multiple' => 'no',
                'data'  => 'string', // data type, string, int, boolean
                'name'  => 'linkedin_url', // unique name for field
                'label' => 'LinkedIn URL', // you know what label it is
                'rules' => 'required|nullable|max:191', // validation rule of laravel
                'class' => '', // any class for input
                'value' => '#', // default value if you want
            ],
            [
                'type'  => 'text', // input fields type
                'multiple' => 'no',
                'data'  => 'string', // data type, string, int, boolean
                'name'  => 'youtube_url', // unique name for field
                'label' => 'Youtube Channel URL', // you know what label it is
                'rules' => 'required|nullable|max:191', // validation rule of laravel
                'class' => '', // any class for input
                'value' => '#', // default value if you want
            ],
        ],

    ],
];
