<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class MakeServiceCommand extends Command
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

        $servicePath = app_path(sprintf('Services/%s/%sService.php', $name, $name));
        $contractPath = app_path(sprintf('Services/Contracts/%sServiceContract.php', $name));

        // Create Contracts directory if not exists
        if (!File::exists(app_path('Services/Contracts'))) {
            File::makeDirectory(app_path('Services/Contracts'), 0755, true);
        }

        if (!File::exists(app_path('Services/'.$name))) {
            File::makeDirectory(app_path('Services/'.$name), 0755, true);
        }

        // Create Interface file
        if (!File::exists($contractPath)) {
            File::put($contractPath, $this->contractStub($name));
            $this->info('Created: '.$contractPath);
        } else {
            $this->warn('Contract already exists: '.$contractPath);
        }

        // Create Service file
        if (!File::exists($servicePath)) {
            File::put($servicePath, $this->serviceStub($name));
            $this->info('Created: '.$servicePath);
        } else {
            $this->warn('Service already exists: '.$servicePath);
        }

    }

    private function contractStub(string $name): string
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

    private function serviceStub(string $name): string
    {
        return <<<PHP
        <?php
        namespace App\Services\\{$name};

        use App\Services\AbstractService;
        use App\Services\Contracts\\{$name}ServiceContract;

        class {$name}Service extends AbstractService implements {$name}ServiceContract
        {
            public function __construct(){}

             // Your service logic goes here

        }
        PHP;
    }
}
