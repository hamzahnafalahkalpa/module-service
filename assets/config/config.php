<?php

use Hanafalah\ModuleService\{
    Models as ModuleService,
    Contracts
};

return [
    'app' => [
        'contracts' => [
            //ADD YOUR CONTRACTS HERE
        ],
    ],
    'database'   => [
        'models' => [
        ]
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas'
    ]
];
