<?php

namespace Gii\ModuleService\Commands;

class InstallMakeCommand extends EnvironmentCommand{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module-Service:install';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command ini digunakan untuk installing awal Service module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = 'Gii\ModuleService\ModuleServiceProvider';

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'migrations'
        ]);
        $this->info('✔️  Created migrations');

        $migrations = $this->setMigrationBasePath(database_path('migrations'))->canMigrate();
        $this->callSilent('migrate', [
            '--path' => $migrations
        ]);
        
        $this->callSilent('migrate', [
            '--path' => $migrations
        ]);
        $this->info('✔️  Module Card Identities tables migrated');

        $this->comment('gii/module-service installed successfully.');
    }
}