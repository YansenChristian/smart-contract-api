<?php
namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

class GenerateDocs extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'docs:generate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Set the application key";
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        exec('php artisan swagger-lume:generate');

        if (DIRECTORY_SEPARATOR == '/') {
            exec('./vendor/bin/openapi app/Http/Controllers -o storage/api-docs/api-docs.yaml ');        
        }
        
        if (DIRECTORY_SEPARATOR == '\\') {
            exec('.\vendor\bin\openapi app/Http/Controllers -o storage/api-docs/api-docs.yaml ');
        }
        
        $file_1 = Yaml::parseFile('storage/api-docs/api-docs.yaml', 1);

        $file_2 = Yaml::parseFile('storage/static-docs/docs.yaml');
        $data = array_replace_recursive($file_1, $file_2);

        $docs = Yaml::dump($data, 10, 2,Yaml::DUMP_OBJECT_AS_MAP ^ Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);

        file_put_contents('storage/static-docs/static-docs.yaml', $docs);

        $this->info("Swagger and Static Docs generated!");
    }
    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('show', null, InputOption::VALUE_NONE, 'Simply display the key instead of modifying files.'),
        );
    }
}