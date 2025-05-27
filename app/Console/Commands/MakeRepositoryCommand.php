<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name : The name of the repository}';
    protected $description = 'Create a new repository class extending the abstract Repository';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $className = Str::endsWith($name, 'Repository') ? $name : $name . 'Repository';

        $path = app_path('Repositories/' . $className . '.php');

        if ($this->filesystem->exists($path)) {
            $this->error("Repository {$className} already exists!");
            return;
        }

        $stub = $this->generateStub($className);

        $this->ensureDirectoryExists($path);
        $this->filesystem->put($path, $stub);

        $this->info("Repository {$className} created successfully.");
    }

    protected function ensureDirectoryExists($path)
    {
        $directory = dirname($path);

        if (!$this->filesystem->isDirectory($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }
    }

    protected function generateStub($className)
    {
        $stub = $this->filesystem->get(__DIR__.'/stubs/repository.stub');
        $stub = str_replace('{{className}}', $className, $stub);
        return $stub;
    }
}
