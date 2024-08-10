<?php

namespace iVirtual\LaravelDevelopment\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'ivirtual:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Generate the website sitemap.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path(config('ivirtual.sitemap.path')));

        return self::SUCCESS;
    }
}
