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

        $this->registerMenuAuthorizationMiddleware();

        $this->info('Menu scaffolding installed successfully.');
    }

    /**
     * Register the Menu authorization middleware in the application Kernel file.
     *
     * @return void
     */
    protected function registerMenuAuthorizationMiddleware()
    {
        $bootstrapFile = base_path('bootstrap/app.php');
        $bootstrapContents = file_get_contents($bootstrapFile);

        if (Str::contains($bootstrapContents, 'VerifyMenuAuthorization::class')) {
            return;
        }

        file_put_contents($bootstrapFile, str_replace(
            '->withMiddleware(function (Middleware $middleware) {',
            '->withMiddleware(function (Middleware $middleware) {'.PHP_EOL."        \$middleware->alias(['menu' => \\PhpCollective\\MenuMaker\\Http\\Middleware\\VerifyMenuAuthorization::class]);",
            $bootstrapContents
        ));
    }
}
