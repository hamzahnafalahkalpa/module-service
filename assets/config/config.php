<?php

use Hanafalah\ModuleService\{
    Commands
};

return [
    'namespace' => 'Hanafalah\\ModuleService',
    'app' => [
        'contracts' => [
            //ADD YOUR CONTRACTS HERE
        ],
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas',
        'database' => 'Database',
        'data' => 'Data',
        'resource' => 'Resources',
        'migration' => '../assets/database/migrations'
    ],
    'database'   => [
        'models' => [
        ]
    ],
    'commands' => [
        Commands\InstallMakeCommand::class
    ],
    'is_using_services' => [
        //ADD YOUR MODEL NAME HERE
    ]
];
