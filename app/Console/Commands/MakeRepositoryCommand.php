<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository class';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = ucfirst($this->argument('name'));

        $repositoryPath = app_path(sprintf('Repositories/%sRepository.php', $name));

        // Create Contracts directory if not exists
        if (! File::exists(app_path('Repositories/'))) {
            File::makeDirectory(app_path('Repositories/'), 0755, true);
        }

        // Create Repository file
        if (! File::exists($repositoryPath)) {
            File::put($repositoryPath, $this->repositoryStub($name));
            $this->info('Created: '.$repositoryPath);
        } else {
            $this->warn('Service already exists: '.$repositoryPath);
        }

    }

    private function repositoryStub(string $name): string
    {
        return <<<PHP
        <?php
        namespace App\Repositories;
        
        use App\Models\\$name;

        class {$name}Repository 
        {
            public function __construct(){}

        }
        PHP;
    }
}
