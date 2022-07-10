<?php

use App\Services\Connected\Providers;
use App\Services\ServiceHandler\AirtimeTopupHandler;
use App\Services\ServiceHandler\AirtimeToCash;
use App\Services\ServiceHandler\BrandingGraphic;
use App\Services\ServiceHandler\CableSubscription;
use App\Services\ServiceHandler\CacHandler;
use App\Services\ServiceHandler\ElectricityBill;
use App\Services\ServiceHandler\GiftCardExchange;
use App\Services\ServiceHandler\DataPurchase;
use App\Services\ServiceHandler\PrintingHandler;
use App\Services\ServiceHandler\WebHandler;

return [
    'test_mode' => env('TEST_MODE', false),
    'domain' => [
        'control_domain' => env('CONTROL_DOMAIN'),
    ],
    'datatypes' => [
        App\Services\DataType\BooleanHandler::class,
        App\Services\DataType\NullHandler::class,
        App\Services\DataType\IntegerHandler::class,
        App\Services\DataType\FloatHandler::class,
        App\Services\DataType\FileHandler::class,
        App\Services\DataType\StringHandler::class,
        App\Services\DataType\DateTimeHandler::class,
        App\Services\DataType\ArrayHandler::class,
        App\Services\DataType\ModelHandler::class,
        App\Services\DataType\ModelCollectionHandler::class,
        App\Services\DataType\SerializableHandler::class,
        App\Services\DataType\ObjectHandler::class,
    ],

    'connected_providers' => [
        Providers::facebook(),
        Providers::google()
    ],
    'sequencer' => [
        'order' => [
            'order_number_prefix' => 'purchase_',
            'order_number_length' => 5,
            'order_number_suffix' => '',
        ]
    ],
    'vtpass' => [
        'username' => env('VTPASS_USERNAME'),
        'password' => env('VTPASS_PASSWORD'),
        // specify to use sandbox mode or live mode
        "mode" => env("VTPASS_MODE", "sandbox"), // app mode sandbox ?? live
    ],
    'smshostng' => [
        'api_key' => env('SMSHOST_AIRTIME_DATA_API_KEY'),
        'mtn' => [
            'server' => env('SMSHOST_MTN_SERVER'),
            /*'ussd' => [
                'airtime' => '*777*?*?*PIN#',
                'data' => ''
            ],*/
        ],
        'airtel' => [
            'server' => env('SMSHOST_AIRTEL_SERVER'),
            /* 'ussd' => [
                 'airtime' => '*777*?*?*PIN#',
                 'data' => ''
             ],*/
        ],
        'glo' => [
            'server' => env('SMSHOST_GLO_SERVER'),
            /*'ussd' => [
                'airtime' => '*777*?*?*PIN#',
                'data' => ''
            ],*/
        ],
        '9mobile' => [
            'server' => env('SMSHOST_9MOBILE_SERVER'),
        ]
 
    ],
    'frontend_urls' => [
        'verify_email' => env('EMAIL_VERIFICATION_URL'),
        'reset_password' => env('PASSWORD_RESET_URL'),
    ],
    'flutterwave' => [
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY')
    ],
    'bds_service_types' => [
        'bd-swap' => [
            'label' => 'BD Swap',
            'services' => [
                'gift-card-exchange' => [
                    'label' => 'Gift Card Exchange',
                    'description' => '',
                    'class' => GiftCardExchange::class,
                    'icon' => resource_path('assets/img/gift-card.svg'),
                    'meta' => [
                        'requires_payment' => false,
                        'requires_preview' => false
                    ],
                ],
                'airtime-for-cash' => [
                    'label' => 'Airtime For Cash',
                    'description' => '',
                    'class' => AirtimeToCash::class,
                    'icon' => resource_path('assets/img/payment-method.svg'),
                    'meta' => [
                        'requires_payment' => false,
                        'requires_preview' => false
                    ],
                    'fields' => [
                        [
                            'title' => 'Airtime for cash network',
                            'type' => 'select',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Airtime for cash Sender phone number',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Airtime amount transferred',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                    ],
                    'child_services' => [
                        'airtime-to-cash-mtn' => [
                            'label' => 'MTN',
                            'meta' => [
                                'percentage_taken' => 25,
                                'receiving_number' => '080133445555'
                            ],
                        ],
                        'airtime-to-cash-airtel' => [
                            'label' => 'Airtel',
                            'meta' => [
                                'percentage_taken' => 25,
                                'receiving_number' => '080133445555'
                            ],
                        ],
                        'airtime-to-cash-glo' => [
                            'label' => 'Glo',
                            'meta' => [
                                'percentage_taken' => 20,
                                'receiving_number' => '080133445555'
                            ],
                        ],
                        'airtime-to-cash-9mobile' => [
                            'label' => '9mobile',
                            'meta' => [
                                'percentage_taken' => 20,
                                'receiving_number' => '080133445555'
                            ],
                        ]
                    ]
                ]
            ]
        ],

        'bd-bills' => [
            'label' => 'BD Bills',
            'services' => [
                'airtime-topup' => [
                    'label' => 'Airtime Top up',
                    'description' => '',
                    'class' => AirtimeTopupHandler::class,
                    'icon' => resource_path('assets/img/smartphone.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => false
                    ],
                    'fields' => [
                        [
                            'title' => 'Network',
                            'type' => 'select',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Phone number',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Amount',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                    ],
                    'child_services' => [
                        'airtime-topup-mtn' => [
                            'label' => 'MTN',
                            'server_id' => env('SMSHOST_MTN_SERVER'),
                            'meta' => [
                                'simhost_dispense_mode' => 'ussd',
                                'simhost_subscription_code' => '*456*1*2*?*?*1*13579#'
                            ],
                        ],
                        'airtime-topup-airtel' => [
                            'label' => 'Airtel',
                            'server_id' => env('SMSHOST_AIRTEL_SERVER'),
                            'meta' => [
                                'simhost_dispense_mode' => 'ussd',
                                //'simhost_subscription_code' => '*456*1*2*?*13579#'
                            ],
                        ],
                        'airtime-topup-glo' => [
                            'label' => 'Glo',
                            'server_id' => env('SMSHOST_GLO_SERVER'),
                            'meta' => [
                                'simhost_dispense_mode' => 'ussd',
                                'simhost_subscription_code' => '*202*2*?*?*1971*1#'
                            ],
                        ],
                        'airtime-topup-9mobile' => [
                            'label' => '9mobile',
                            'server_id' => env('SMSHOST_9MOBILE_SERVER'),
                            'meta' => [
                                'simhost_dispense_mode' => 'ussd',
                                'simhost_subscription_code' => '*224*?*?*080605#'
                            ],
                        ]
                    ]
                ],
                'data-subscription' => [
                    'label' => 'Data Subscription',
                    'description' => '',
                    'class' => DataPurchase::class,
                    'icon' => resource_path('assets/img/mobile.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => false
                    ],
                    'fields' => [
                        [
                            'title' => 'Network',
                            'type' => 'select',
                            'required' => true,
                        ],
                        [
                            'title' => 'Variant',
                            'type' => 'select',
                            'required' => true,
                        ],
                        [
                            'title' => 'Phone number',
                            'type' => 'text',
                            'required' => true,
                        ]
                    ],
                    'child_services' => [
                        'mtn-data' => [
                            'label' => 'MTN',
                            'server_id' => env('SMSHOST_MTN_SERVER'),
                            'variants' => [
                                'data-mtn-1-gb' => [
                                    'label' => '1 GB',
                                    'price' => '350',
                                    'meta' => [
                                        'simhost_dispense_mode' => 'sms',
                                        'simhost_subscription_sms_content' => 'SMEC ? ? ?',
                                        'simhost_sms_receiver' => '131'
                                    ]
                                ],
                                'data-mtn-2-gb' => [
                                    'label' => '2 GB',
                                    'price' => '700',
                                    'meta' => [
                                        'simhost_dispense_mode' => 'sms',
                                        'simhost_subscription_sms_content' => 'SMED ? ? ?',
                                        'simhost_sms_receiver' => '131'
                                    ]
                                ],
                                'data-mtn-3-gb' => [
                                    'label' => '3 GB',
                                    'price' => '1050',
                                    'meta' => [
                                        'simhost_dispense_mode' => 'sms',
                                        'simhost_subscription_sms_content' => 'SMEF ? ? ?',
                                        'simhost_sms_receiver' => '131'
                                    ]
                                ],
                                'data-mtn-5-gb' => [
                                    'label' => '5 GB',
                                    'price' => '1750',
                                    'meta' => [
                                        'simhost_dispense_mode' => 'sms',
                                        'simhost_subscription_sms_content' => 'SMEE ? ? ?',
                                        'simhost_sms_receiver' => '131'
                                    ]
                                ],
                                'data-mtn-10-gb' => [
                                    'label' => '10 GB',
                                    'price' => '300',
                                    'meta' => [
                                        'simhost_dispense_mode' => 'sms',
                                        'simhost_subscription_sms_content' => 'SMEG ? ? ?',
                                        'simhost_sms_receiver' => '131'
                                    ]
                                ],
                            ]
                        ],
                        'airtel-data' => [
                            'label' => 'Airtel',
                            'server_id' => env('SMSHOST_AIRTEL_SERVER'),
                            'variants' => [
                                'airt-50' => [
                                    'label' => 'Airtel Data - 50 Naira - 40MB  - 1Day',
                                    'price' => '49.99',
                                ],
                                'airt-100' => [
                                    'label' => 'Airtel Data - 100 Naira - 100MB - 1Day',
                                    'price' => '99.00',
                                ],
                                'airt-200' => [
                                    'label' => 'Airtel Data - 200 Naira - 200MB - 3Days',
                                    'price' => '199.03',
                                ],
                                'airt-300' => [
                                    'label' => 'Airtel Data - 300 Naira - 350MB - 7 Days',
                                    'price' => '299.02',
                                ],
                                'airt-500' => [
                                    'label' => 'Airtel Data - 500 Naira - 750MB - 14 Days',
                                    'price' => '499.00',
                                ],
                                'airt-1000' => [
                                    'label' => 'Airtel Data - 1,000 Naira - 1.5GB - 30 Days',
                                    'price' => '999.00',
                                ],
                                'airt-1500' => [
                                    'label' => 'Airtel Data - 1,500 Naira - 3GB - 30 Days',
                                    'price' => '1499.01',
                                ]
                            ]
                        ],
                        'glo-data' => [
                            'label' => 'Glo',
                            'server_id' => env('SMSHOST_GLO_SERVER'),
                            'meta' => [
                                'simhost_dispense_means' => 'sms'
                            ],
                            'variants' => [
                                'data-glo-1-3-5-gb' => [
                                    'label' => '1.35 GB',
                                    'price' => '480',
                                ],
                                'data-glo-2-9-gb' => [
                                    'label' => '2.9 GB',
                                    'price' => '900',
                                ],
                                'data-glo-5-8-gb' => [
                                    'label' => '5.8 GB',
                                    'price' => '1800',
                                ],
                                'data-glo-7-7-gb' => [
                                    'label' => '7.7 GB',
                                    'price' => '2250',
                                ],
                                'data-glo-10-gb' => [
                                    'label' => '10 GB',
                                    'price' => '2700',
                                ],
                                'data-glo-13-25-gb' => [
                                    'label' => '13.25 GB',
                                    'price' => '3600',
                                ],
                                'data-glo-18-25-gb' => [
                                    'label' => '18.25 GB',
                                    'price' => '4500',
                                ],
                                'data-glo-29-5-gb' => [
                                    'label' => '29.5 GB',
                                    'price' => '7200',
                                ]
                            ]
                        ],
                        '9mobile-data' => [
                            'label' => '9mobile',
                            'server_id' => env('SMSHOST_9MOBILE_SERVER'),
                            'meta' => [
                                'simhost_dispense_mode' => 'sms'
                            ],
                            'variants' => [
                                'data-9mobile-500-mb' => [
                                    'label' => '500 mb',
                                    'price' => '450',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*12*?#'
                                    ],
                                ],
                                'data-9mobile-1-5-gb' => [
                                    'label' => '1.5 GB',
                                    'price' => '800',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*7*?#'
                                    ],
                                ],
                                'data-9mobile-2-gb' => [
                                    'label' => '2 GB',
                                    'price' => '960',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*25*?#'
                                    ],
                                ],
                                'data-9mobile-3-gb' => [
                                    'label' => '3 GB',
                                    'price' => '1200',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*3*?#'
                                    ],
                                ],
                                'data-9mobile-4-5-gb' => [
                                    'label' => '4.5 GB',
                                    'price' => '1600',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*8*?#'
                                    ],
                                ],
                                'data-9mobile-11-gb' => [
                                    'label' => '11 GB',
                                    'price' => '3200',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*36*?#'
                                    ],
                                ],
                                'data-9mobile-15-gb' => [
                                    'label' => '15 GB',
                                    'price' => '4000',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*37*?#'
                                    ],
                                ],
                                'data-9mobile-40-gb' => [
                                    'label' => '40 GB',
                                    'price' => '8000',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*4*1*?#'
                                    ],
                                ],
                                'data-9mobile-75-gb' => [
                                    'label' => '75 GB',
                                    'price' => '12000',
                                    'meta' => [
                                        'simhost_subscription_code' => '*229*2*4*?#'
                                    ],
                                ]
                            ]
                        ]
                    ]
                ],
                'cable_tv' => [
                    'label' => 'Cable Tv Subscription',
                    'description' => '',
                    'class' => CableSubscription::class,
                    'icon' => resource_path('assets/img/television-screen.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Cable Tv Service',
                            'type' => 'select',
                            'required' => true,
                        ],
                        [
                            'title' => 'Cable Tv Package',
                            'type' => 'select',
                            'required' => true,
                        ],
                        [
                            'title' => 'Cable Tv Smart Card Number',
                            'type' => 'text',
                            'required' => true,
                        ]
                    ],
                    'child_services' => [
                        'gotv' => [
                            'label' => 'Gotv',
                            'variants' => [
                                'jinja' => [
                                    'label' => 'Jinja',
                                    'price' => '1640'
                                ],
                                'jolli' => [
                                    'label' => 'Jolli',
                                    'price' => '2460'
                                ],
                                'max' => [
                                    'label' => 'Max',
                                    'price' => '3600'
                                ]
                            ]
                        ],
                        'dstv' => [
                            'label' => 'DStv',
                            'variants' => [
                                'dstv-padi' => [
                                    'label' => 'Padi',
                                    'price' => '1850'
                                ],
                                'dstv-yanga' => [
                                    'label' => 'Yanga',
                                    'price' => '2565'
                                ],
                                'dstv-confam' => [
                                    'label' => 'Confam',
                                    'price' => '4615'
                                ],
                                'dstv79' => [
                                    'label' => 'Compact',
                                    'price' => '7900'
                                ],
                                'dstv7' => [
                                    'label' => 'Compact Plus',
                                    'price' => '12400'
                                ],
                                'dstv3' => [
                                    'label' => 'Premium',
                                    'price' => '18400'
                                ],
                                'dstv6' => [
                                    'label' => 'Dstv Asia',
                                    'price' => '6200'
                                ]
                            ]
                        ],
                        'startimes' => [
                            'label' => 'StarTimes',
                            'variants' => [
                                'nova' => [
                                    'label' => 'Nova',
                                    'price' => '900'
                                ],
                                'basic' => [
                                    'label' => 'Basic',
                                    'price' => '1700'
                                ],
                                'smart' => [
                                    'label' => 'Smart',
                                    'price' => '2200'
                                ],
                                'classic' => [
                                    'label' => 'Classic',
                                    'price' => '2500'
                                ],
                                'super' => [
                                    'label' => 'Super',
                                    'price' => '4200'
                                ]
                            ]
                        ]
                    ]
                ],
                'electricity' => [
                    'label' => 'Electricity',
                    'description' => '',
                    'class' => ElectricityBill::class,
                    'icon' => resource_path('assets/img/eco-house.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Electricity Disco',
                            'type' => 'select',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Electricity Amount',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Electricity Meter Type',
                            'type' => 'radio',
                            'answers' => ['prepaid', 'postpaid'],
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Electricity Meter Number',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ]
                    ],
                    'child_services' => [
                        'ikeja-electric' => [
                            'label' => 'Ikeja Electric'
                        ],
                        'eko-electric' => [
                            'label' => 'Eko Electric'
                        ],
                        'kano-electric' => [
                            'label' => 'Kano Electric'
                        ],
                        'portharcourt-electric' => [
                            'label' => 'Port Harcourt Electric'
                        ],
                        'abuja-electric' => [
                            'label' => 'Abuja Electric'
                        ],
                        'ibadan-electric' => [
                            'label' => 'Ibadan Electric'
                        ],
                        'jos-electric' => [
                            'label' => 'Jos Electric'
                        ],
                        'kaduna-electric' => [
                            'label' => 'Kaduna Electric'
                        ]
                    ]
                ]
            ]
        ],

        'bd-web' => [
            'label' => 'BD Web',
            'class' => WebHandler::class,
            'services' => [
                'web-design' => [
                    'label' => 'WebDesign',
                    'description' => 'Web description',
                    'class' => '',
                    'icon' => resource_path('assets/img/design.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Website title',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Domain name',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Brand Color',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'variants' => [
                        'web-design-blog' => [
                            'label' => 'Blog',
                            'description' => 'contains 1 year Domain & hosting, 5, pages, ssl, and payment gateway',
                            'class' => '',
                            'price' => 10000,
                            'icon' => resource_path('assets/img/services/blog.png'),
                            'addons' => [
                                [
                                    'label' => 'Auto Social Post',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Search engine optimization',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],
                        'web-design-corporate' => [
                            'label' => 'Corporate',
                            'description' => 'contains 1 year Domain & hosting, 5, pages, ssl, and payment gateway',
                            'class' => '',
                            'icon' => resource_path('assets/img/services/coperate.png'),
                            'price' => 40000,
                            'addons' => [
                                [
                                    'label' => 'Auto Social Post',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Search engine optimization',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],
                        'web-design-ecommerce' => [
                            'label' => 'Ecommerce',
                            'description' => 'contains 1 year Domain & hosting, 5, pages, ssl, and payment gateway',
                            'class' => '',
                            'price' => 90000,
                            'icon' => resource_path('assets/img/services/ecommerce.svg'),
                            'addons' => [
                                [
                                    'label' => 'Auto Social Post',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Search engine optimization',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],
                    ],
                ],
                'social-media-management' => [
                    'label' => 'Social Media Management',
                    'description' => '',
                    'class' => '',
                    'icon' => resource_path('assets/img/Outline_27.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Brand Color',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'variants' => [
                        'social-media-management-bronze' => [
                            'label' => 'Bronze',
                            'description' => 'contains 1 year Domain & hosting, 5, pages, ssl, and payment gateway',
                            'class' => '',
                            'icon' => resource_path('assets/img/services/marketing1.png'),
                            'price' => 10000,
                            'addons' => [
                                [
                                    'label' => '5000 followers',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => '1000 clicks',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'features' => [
                                [
                                    'label' => 'Channels',
                                    'feature_values' => [
                                        [
                                            'label' => 'Facebook',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Twitter',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Instagram',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ]
                        ],
                        'social-media-management-silver' => [
                            'label' => 'Gold',
                            'description' => 'contains 1 year Domain & hosting, 5, pages, ssl, and payment gateway',
                            'class' => '',
                            'icon' => resource_path('assets/img/services/marketing2.png'),
                            'price' => 20000,
                            'addons' => [
                                [
                                    'label' => '5000 followers',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => '1000 clicks',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'features' => [
                                [
                                    'label' => 'Channels',
                                    'feature_values' => [
                                        [
                                            'label' => 'Facebook',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Twitter',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Instagram',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ]
                        ],
                        'social-media-management-gold' => [
                            'label' => 'Gold',
                            'description' => 'contains 1 year Domain & hosting, 5, pages, ssl, and payment gateway',
                            'class' => '',
                            'price' => 40000,
                            'icon' => resource_path('assets/img/services/marketing3.png'),
                            'addons' => [
                                [
                                    'label' => '5000 followers',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => '1000 clicks',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'features' => [
                                [
                                    'label' => 'Channels',
                                    'feature_values' => [
                                        [
                                            'label' => 'Facebook',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Twitter',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Instagram',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ]

                        ],
                    ],
                ],
                'content-writing' => [
                    'label' => 'Content Writing',
                    'description' => '',
                    'class' => '',
                    'icon' => resource_path('assets/img/blog.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Post title',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Content goals',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'variants' => [
                        'content-writing-blog-post' => [
                            'label' => 'Blog Post',
                            'description' => 'description of choice',
                            'class' => '',
                            'icon' => resource_path('assets/img/services/blog_post.png'),
                            'price' => 20000,
                            'features' => [
                                [
                                    'label' => 'No of words',
                                    'feature_values' => [
                                        [
                                            'label' => '500',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => '1000',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => '5000',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Delivery',
                                    'feature_values' => [
                                        [
                                            'label' => 'Standard (5 days)',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Express (3 days)',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => '5000',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ],
                            'addons' => [
                                [
                                    'label' => 'Addon 1',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Addon 2',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],
                        'content-writing-social-media' => [
                            'label' => 'Social Media',
                            'description' => 'description of choice',
                            'class' => '',
                            'icon' => resource_path('assets/img/services/marketing3.png'),
                            'price' => 50000,
                            'features' => [
                                [
                                    'label' => 'Channels',
                                    'meta' => [
                                        'field_type' => 'checkbox'
                                    ],
                                    'feature_values' => [
                                        [
                                            'label' => 'Facebook',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Twitter',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Instagram',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Delivery',
                                    'meta' => [
                                        'field_type' => 'radio'
                                    ],
                                    'feature_values' => [
                                        [
                                            'label' => 'Standard(5 days)',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Express (3 days)',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ],
                            'addons' => [
                                [
                                    'label' => 'Addon 1',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Addon 2',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],

                    ]
                ],
            ]
        ],
        'bd-branding' => [
            'label' => 'BD Branding',
            'services' => [
                'graphic-design' => [
                    'label' => 'Graphic Design',
                    'description' => '',
                    'class' => BrandingGraphic::class,
                    'icon' => resource_path('assets/img/vector.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'variants' => [
                        'graphic-design-logo' => [
                            'label' => 'Logo',
                            'description' => 'This is logo design',
                            'class' => '',
                            'price' => 40000,
                            'icon' => resource_path('assets/img/services/logodesign.png'),
                            'meta' => [
                                'requires_payment' => true,
                                'requires_preview' => true
                            ],
                            'fields' => [
                                [
                                    'title' => 'Slogan',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Description',
                                    'type' => 'textarea',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Brand Color',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'textarea',
                                    'required' => false,
                                ],
                            ],
                            'addons' => [
                                [
                                    'label' => '3 concept design',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Presentation',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],
                        'graphic-design-flier' => [
                            'label' => 'Fliers',
                            'description' => 'This is for flier',
                            'class' => '',
                            'price' => 90000,
                            'icon' => resource_path('assets/img/services/fliersdesign.png'),
                            'meta' => [
                                'requires_payment' => true,
                                'requires_preview' => true
                            ],
                            'fields' => [
                                [
                                    'title' => 'Business name',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Slogan',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Description',
                                    'type' => 'textarea',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Brand Color',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'textarea',
                                    'required' => false,
                                ],
                            ],
                            'addons' => [
                                [
                                    'label' => '3 Concept design',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Presentation',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'features' => [
                                [
                                    'label' => 'Type',
                                    'feature_values' => [
                                        [
                                            'label' => 'Print',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'e-Flyer',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Size',
                                    'feature_values' => [
                                        [
                                            'label' => 'Trifold',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'A4',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'A3',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ]
                        ],
                        'graphic-design-banner' => [
                            'label' => 'Banners',
                            'description' => 'This is banner design',
                            'class' => '',
                            'price' => 15000,
                            'icon' => resource_path('assets/img/services/bannerdesign.png'),
                            'meta' => [
                                'requires_payment' => true,
                                'requires_preview' => true
                            ],
                            'fields' => [
                                [
                                    'title' => 'Business name',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Slogan',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Description',
                                    'type' => 'textarea',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Brand Color',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'textarea',
                                    'required' => false,
                                ],
                            ],
                            'addons' => [
                                [
                                    'label' => '3 Concept design',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Presentation',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'features' => [
                                [
                                    'label' => 'Card Type',
                                    'feature_values' => [
                                        [
                                            'label' => 'Business',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'ID Card',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Size',
                                    'feature_values' => [
                                        [
                                            'label' => '6ft x 3ft',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => '8ft x 3ft',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ]
                        ],
                        'graphic-design-card' => [
                            'label' => 'Cards',
                            'description' => 'This is card design',
                            'class' => '',
                            'price' => 20000,
                            'icon' => resource_path('assets/img/services/carddesign.png'),
                            'meta' => [
                                'requires_payment' => true,
                                'requires_preview' => true
                            ],
                            'fields' => [
                                [
                                    'title' => 'Business name',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Slogan',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Description',
                                    'type' => 'textarea',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Brand Color',
                                    'type' => 'text',
                                    'required' => true,
                                    'validation_rules' => 'required'
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'textarea',
                                    'required' => false,
                                ],
                            ],
                            'addons' => [
                                [
                                    'label' => '3 Concept design',
                                    'description' => 'what ever description',
                                    'price' => 20000,
                                ],
                                [
                                    'label' => 'Presentation',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'features' => [
                                [
                                    'label' => 'Business Card Type',
                                    'feature_values' => [
                                        [
                                            'label' => 'Vertical',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Horizontal',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Slide',
                                    'feature_values' => [
                                        [
                                            'label' => 'Single Side',
                                            'description' => '',
                                            'meta' => [],
                                        ],
                                        [
                                            'label' => 'Double Side',
                                            'description' => '',
                                            'meta' => [],
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ]
                ],
                'cac_registration' => [
                    'label' => 'Cac Registration',
                    'description' => '',
                    'class' => CacHandler::class,
                    'icon' => resource_path('assets/img/briefcase.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'variants' => [
                        'business-name' => [
                            'label' => 'Business',
                            'price' => '25000',
                            'description' => '
                                ** Requirements **

                                * Business Name.
                                * Nature and objective of business.
                                * Personal email, Phone number and address.
                                * Business email, Phone number and address.
                                * Valid ID card
                                * Passport Photograph.
                                * Signature.
                            ',
                            'ready_duration' => '5 - 10 days',
                            'meta' => [
                                'requires_directors' => false,
                            ],
                            'addons' => [
                                [
                                    'label' => 'Logo Design',
                                    'description' => 'what ever description',
                                    'price' => 15000,
                                ],
                                [
                                    'label' => 'Business branding',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'fields' => [
                                [
                                    'title' => 'Nature Of Business',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Business Name',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Business Email',
                                    'type' => 'text',
                                    'required' => false,
                                ],
                                [
                                    'title' => 'Business Phone Number',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Business Address',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Full name',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Email',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Phone Number',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Address',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'text',
                                    'required' => true,
                                ]
                            ],
                        ],
                        'limited-liability-company' => [
                            'label' => 'Limited Liability Company',
                            'price' => '50000',
                            'description' => '
                                 ** Requirements **

                                * Business Name.
                                * Nature and objective of business.
                                * Personal email, Phone number and address of directors.
                                * Business email, Phone number and address.
                                * Valid ID card of directors.
                                * Passport Photograph of directors.
                                * Signature of directors.
                            ',
                            'ready_duration' => '5 - 10 days',
                            'meta' => [
                                'requires_directors' => true,
                            ],
                            'fields' => [
                                [
                                    'title' => 'Nature Of Business',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Business Name',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Business Email',
                                    'type' => 'text',
                                    'required' => false,
                                ],
                                [
                                    'title' => 'Business Phone Number',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Business Address',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Full name',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Email',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Phone Number',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Address',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'text',
                                    'required' => true,
                                ]
                            ],
                            'addons' => [
                                [
                                    'label' => 'Logo Design',
                                    'description' => 'what ever description',
                                    'price' => 15000,
                                ],
                                [
                                    'label' => 'Business branding',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                        ],
                        'incorporated-trustees' => [
                            'label' => 'Incorporated Trustees',
                            'price' => '100000',
                            'description' => '
                                 ** Requirements **

                                * NGO Name.
                                * Nature and objective of NGO.
                                * Personal email, Phone number and address of trustees.
                                * Business email, Phone number and address.
                                * Valid ID card of trustees.
                                * Passport photograph trustees.
                                * Signature trustees .
                            ',
                            'ready_duration' => '5 - 6 weeks',
                            'meta' => [
                                'requires_directors' => true,
                            ],
                            'addons' => [
                                [
                                    'label' => 'Logo Design',
                                    'description' => 'what ever description',
                                    'price' => 15000,
                                ],
                                [
                                    'label' => 'Business branding',
                                    'description' => 'what ever description',
                                    'price' => 10000,
                                ]
                            ],
                            'fields' => [
                                [
                                    'title' => 'Nature Of NGO',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'NGO Name',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'NGO Email',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'NGO Phone Number',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'NGO Address',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Full name',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Email',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Phone Number',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Address',
                                    'type' => 'text',
                                    'required' => true,
                                ],
                                [
                                    'title' => 'Additional Information',
                                    'type' => 'text',
                                    'required' => true,
                                ]
                            ],
                        ]
                    ],
                ],
            ]
        ],
        'bd-printing' => [
            'label' => 'BD Printing',
            'class' => PrintingHandler::class,
            'services' => [
                'banners' => [
                    'label' => 'Banners',
                    'description' => ' A description',
                    'class' => '',
                    'icon' => resource_path('assets/img/roll-up.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Quantity',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'addons' => [
                        [
                            'label' => 'Design',
                            'description' => 'what ever description',
                            'price' => 20000,
                        ],
                        [
                            'label' => 'GLossy Paper',
                            'description' => 'what ever description',
                            'price' => 10000,
                        ]
                    ],
                    'features' => [
                        [
                            'label' => 'Sizes',
                            'feature_values' => [
                                [
                                    'label' => '6ft x 3ft',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => '8ft x 3ft',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                        [
                            'label' => 'Banner Types',
                            'feature_values' => [
                                [
                                    'label' => 'Roll Up Banner',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'Flex Banner',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                    ]
                ],
                'fliers' => [
                    'label' => 'Fliers',
                    'description' => ' A description',
                    'class' => '',
                    'icon' => resource_path('assets/img/Group.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Quantity',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'addons' => [
                        [
                            'label' => 'Design',
                            'description' => 'what ever description',
                            'price' => 20000,
                        ],
                        [
                            'label' => 'GLossy Paper',
                            'description' => 'what ever description',
                            'price' => 10000,
                        ]
                    ],
                    'features' => [
                        [
                            'label' => 'Flier Types',
                            'feature_values' => [
                                [
                                    'label' => 'Hand Bill',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'Trifold',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                        [
                            'label' => 'Size',
                            'feature_values' => [
                                [
                                    'label' => 'A4',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'A3',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'B4',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                    ]
                ],
                'cards' => [
                    'label' => 'Cards',
                    'description' => ' A description',
                    'class' => '',
                    'icon' => resource_path('assets/img/cut.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Quantity',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'addons' => [
                        [
                            'label' => 'Design',
                            'description' => 'what ever description',
                            'price' => 20000,
                        ],
                        [
                            'label' => 'GLossy Paper',
                            'description' => 'what ever description',
                            'price' => 10000,
                        ]
                    ],
                    'features' => [
                        [
                            'label' => 'Card Type',
                            'feature_values' => [
                                [
                                    'label' => 'Business Card',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'ID Card',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                        [
                            'label' => 'Orientation',
                            'feature_values' => [
                                [
                                    'label' => 'Portrait',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'Landscape',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                    ]
                ],
                'documents' => [
                    'label' => 'documents',
                    'description' => ' A description',
                    'class' => '',
                    'icon' => resource_path('assets/img/Group_2.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Number of Pages',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'addons' => [
                        [
                            'label' => 'Design',
                            'description' => 'what ever description',
                            'price' => 20000,
                        ],
                        [
                            'label' => 'GLossy Paper',
                            'description' => 'what ever description',
                            'price' => 10000,
                        ]
                    ],
                    'features' => [

                        [
                            'label' => 'Color Type',
                            'feature_values' => [
                                [
                                    'label' => 'Colored',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'Black & White',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                        [
                            'label' => 'Type',
                            'feature_values' => [
                                [
                                    'label' => 'Document',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'Business Letter',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                        [
                            'label' => 'Size',
                            'feature_values' => [
                                [
                                    'label' => 'A4',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'A3',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                    ]
                ],
                'Typesetting' => [
                    'label' => 'Typesetting',
                    'description' => ' A description',
                    'class' => '',
                    'icon' => resource_path('assets/img/typography.svg'),
                    'meta' => [
                        'requires_payment' => true,
                        'requires_preview' => true
                    ],
                    'fields' => [
                        [
                            'title' => 'Number of Pages',
                            'type' => 'text',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Description',
                            'type' => 'textarea',
                            'required' => true,
                            'validation_rules' => 'required'
                        ],
                        [
                            'title' => 'Additional Information',
                            'type' => 'textarea',
                            'required' => false,
                        ],
                    ],
                    'addons' => [
                        [
                            'label' => 'Design',
                            'description' => 'what ever description',
                            'price' => 20000,
                        ],
                        [
                            'label' => 'GLossy Paper',
                            'description' => 'what ever description',
                            'price' => 10000,
                        ]
                    ],
                    'features' => [
                        [
                            'label' => 'Type',
                            'feature_values' => [
                                [
                                    'label' => 'Document',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'Business Letter',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                        [
                            'label' => 'Size',
                            'feature_values' => [
                                [
                                    'label' => 'A4',
                                    'description' => '',
                                    'meta' => [],
                                ],
                                [
                                    'label' => 'A3',
                                    'description' => '',
                                    'meta' => [],
                                ]
                            ]
                        ],
                    ]
                ],
            ]
        ],
    ],


];
