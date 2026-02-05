<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateOpenApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openapi:generate 
        {--path=app : Path to scan}
        {--out=storage/app/openapi.json : Output file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OpenAPI v3 spec from PHP 8 attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scanPath = base_path($this->option('path'));
        $outPath  = base_path($this->option('out'));

        $generator = new \OpenApi\Generator();
        $openapi = $generator->generate([$scanPath]);

        if (! is_dir(dirname($outPath))) {
            mkdir(dirname($outPath), 0777, true);
        }

        file_put_contents($outPath, $openapi->toJson());

        $this->info("OpenAPI spec generated at: {$outPath}");

        return self::SUCCESS;
    }
}
