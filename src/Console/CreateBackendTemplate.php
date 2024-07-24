<?php

declare(strict_types=1);

namespace Irlix\StubHelper\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class CreateBackendTemplate extends Command
{
    protected $signature = 'irlix:backend-template';

    protected $description = 'Create backend template';

    private string $modelName;

    public function handle(): void
    {
        if (!trim($this->modelName = (string)$this->ask('Model name'))) {
            $this->error('Model name is required!');

            return;
        }

        $table = Str::of($this->modelName)->pluralStudly()->snake()->value();
        $migration = date('Y_m_d_His') . "_create_{$table}_table";

        $backEndFiles = [
            'Api' => "routes/api",
            'Migration' => "database/migrations/$migration",
            'Factory' => "database/factories/{$this->modelName}Factory",
            'Controller' => "app/Http/Controllers/{$this->modelName}Controller",
            'StoreRequest' => "app/Http/Requests/$this->modelName/{$this->modelName}StoreRequest",
            'UpdateRequest' => "app/Http/Requests/$this->modelName/{$this->modelName}UpdateRequest",
            'StoreDto' => "app/Http/Dto/$this->modelName/{$this->modelName}StoreDto",
            'UpdateDto' => "app/Http/Dto/$this->modelName/{$this->modelName}UpdateDto",
            'Service' => "app/Services/{$this->modelName}Service",
            'Model' => "app/Models/$this->modelName",
            'Resource' => "app/Http/Resources/$this->modelName/{$this->modelName}Resource",
            'Policy' => "app/Policies/{$this->modelName}Policy",
            'IndexTest' => "tests/Feature/Api/$this->modelName/{$this->modelName}IndexTest",
            'ShowTest' => "tests/Feature/Api/$this->modelName/{$this->modelName}ShowTest",
            'StoreTest' => "tests/Feature/Api/$this->modelName/{$this->modelName}StoreTest",
            'UpdateTest' => "tests/Feature/Api/$this->modelName/{$this->modelName}UpdateTest",
            'DestroyTest' => "tests/Feature/Api/$this->modelName/{$this->modelName}DestroyTest",
        ];

        if ($this->choice('Should be generated all possible files?', ['Yes', 'No'], 0) !== 'Yes') {
            $files = $this->choice('Generated files', array_keys($backEndFiles), 0, multiple: true);

            $backEndFiles = Arr::only($backEndFiles, $files);
        }

        foreach ($backEndFiles as $stub => $putPath) {
            $this->putBackEndFile($putPath, $stub);
        }

        $this->log($backEndFiles);
    }

    private function getStub(string $name): string
    {
        $stubPath = base_path('/vendor/irlix/stub-helper/src/Stubs/Back');

        return Str::replace(
            $this->getStubPatterns(),
            $this->getStubReplaces(),
            File::get("$stubPath/$name.stub"),
        );
    }

    private function getStubPatterns(): array
    {
        return [
            '{MODEL}',
            '{Model}',
            '{model}',
            '{models}',
            '{model_snake}',
            '{prefix}',
            '{ServiceClassName}',
            '{serviceClassName}',
            '{Controller}',
            '{table}',
        ];
    }

    private function getStubReplaces(): array
    {
        return [
            Str::of($this->modelName)->snake()->upper(), // '{MODEL}',
            $this->modelName, // '{Model}',
            Str::camel($this->modelName), // '{model}',
            Str::of($this->modelName)->pluralStudly()->camel()->value(), // '{models}',
            Str::snake($this->modelName), // '{model_snake}'
            Str::of($this->modelName)->kebab()->plural()->value(), // '{prefix}',
            "{$this->modelName}Service", // '{ServiceClassName}',
            Str::camel($this->modelName)."Service", // '{serviceClassName}',
            "{$this->modelName}Controller", // '{Controller}',
            Str::of($this->modelName)->pluralStudly()->snake()->value(), // '{table}'
        ];
    }

    public function putBackEndFile(string $path, string $stub): void
    {
        if ($stub === 'Api') {
            $data = file($this->getDirectory(base_path($path) . ".php"));

            array_splice( $data, 4, 0, $this->getStub($stub.'Use'));

            $content = implode("", $data);

            File::put(
                $this->getDirectory(base_path($path) . ".php"),
                "$content\n{$this->getStub($stub)}",
            );
            return;
        }

        File::put(
            $this->getDirectory(base_path($path) . ".php"),
            $this->getStub($stub),
        );
    }

    private function getDirectory(string $path): string
    {
        if (!$path || !File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    public function log(array $backEndFiles): void
    {
        $backEndFiles = collect($backEndFiles)
            ->values()
            ->map(
                fn(string $path): array => [$path],
            )
            ->toArray();

        $this->table(['Created Files'], $backEndFiles);
    }
}