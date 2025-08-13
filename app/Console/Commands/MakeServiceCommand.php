<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a service class and its corresponding interface';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = ucfirst($this->argument('name'));

        $servicePath = app_path("Services/{$name}Service.php");
        $contractPath = app_path("Services/Contracts/{$name}ServiceContract.php");

        // Create Contracts directory if not exists
        if (!File::exists(app_path('Services/Contracts'))) {
            File::makeDirectory(app_path('Services/Contracts'), 0755, true);
        }

        // Create Interface file
        if (!File::exists($contractPath)) {
            File::put($contractPath, $this->contractStub($name));
            $this->info("Created: {$contractPath}");
        } else {
            $this->warn("Contract already exists: {$contractPath}");
        }

        // Create Service file
        if (!File::exists($servicePath)) {
            File::put($servicePath, $this->serviceStub($name));
            $this->info("Created: {$servicePath}");
        } else {
            $this->warn("Service already exists: {$servicePath}");
        }

    }

    protected function contractStub($name): string
    {
        return <<<PHP
        <?php

        namespace App\Services\Contracts;

        interface {$name}ServiceContract
        {
            // Define your contract methods here
        }
        PHP;
    }

    protected function serviceStub($name): string
    {
        return <<<PHP
        <?php
        namespace App\Services;

        use App\Services\Contracts\\{$name}ServiceContract;
        use App\Utils\Trait\HasAuthenticatedUser;
        use App\Utils\Trait\HasLogger;

        class {$name}Service implements {$name}ServiceContract
        {
            use HasAuthenticatedUser, HasLogger;

            public function __construct(){}

             // Your service logic goes here

        }
        PHP;
    }

}
