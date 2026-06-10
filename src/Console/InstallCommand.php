<?php

namespace PhpCollective\MenuMaker\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class InstallCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Menu Maker resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Menu Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'menu-assets']);

        $this->comment('Publishing Menu Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'menu-config']);

        $this->comment('Menu Database Files Migrating...');
        $this->call('migrate', [
            '--force'   => true
        ]);

        $this->info('Menu scaffolding installed successfully.');
    }
}
