<?php

declare(strict_types=1);

namespace Irlix\StubHelper\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateResourceTemplate extends Command
{
    protected $signature = 'irlix:resource';

    protected $description = 'Create resource template';

    private string $modelName;

    public function handle(): void
    {
        if (!trim($this->modelName = (string)$this->ask('Model name'))) {
            $this->error('Model name is required!');
            return;
        }

        File::put(
            $this->getDirectory(base_path("app/Http/Resources/$this->modelName/{$this->modelName}Resource") . ".php"),
            $this->getStub('Resource'),
        );
    }

    private function getStub(string $name): string
    {
        $stubPath = base_path('/vendor/irlix/stub-helper/src/Stubs/Back');

        return Str::replace(
            ['{Model}'],
            [$this->modelName],
            File::get('$stubPath/Resource.stub'),
        );
    }

    private function getDirectory(string $path): string
    {
        if (!$path || !File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }
}