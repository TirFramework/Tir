<?php


namespace Tir\Crud\Providers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class CrudSeedServiceProvider extends ServiceProvider
{
    protected array $seeders = [];

    public function setSeeders()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */


    public function boot()
    {

        if ($this->app->runningInConsole()) {
            if ($this->isConsoleCommandContains(['db:seed', '--seed'], ['--class', 'help', '-h'])) {
                $this->addSeedsAfterConsoleCommandFinished();
            }
        }
    }

    /**
     * Get a value that indicates whether the current command in console
     * contains a string in the specified $fields.
     *
     * @param string|array $contain_options
     * @param string|array $exclude_options
     *
     * @return bool
     */
    protected function isConsoleCommandContains($contain_options, $exclude_options = null): bool
    {
        $args = Request::server('argv', null);
        if (is_array($args)) {
            $command = implode(' ', $args);
            if (
                Str::contains($command, $contain_options) &&
                ($exclude_options == null || !Str::contains($command, $exclude_options))
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add seeds from the $seed_path after the current command in console finished.
     */
    protected function addSeedsAfterConsoleCommandFinished()
    {
        Event::listen(CommandFinished::class, function (CommandFinished $event) {
            // Accept command in console only,
            // exclude all commands from Artisan::call() method.
            if ($event->output instanceof ConsoleOutput) {
                $this->addSeedsFrom($this->seeders);
            }
        });
    }

    /**
     * Register seeds.
     *
     * @param array $seeders
     * @return void
     */
    protected function addSeedsFrom(array $seeders)
    {
        foreach ($seeders as $seeder) {
            echo "\033[1;33mSeeding:\033[0m {$seeder}\n";
            $startTime = microtime(true);
            Artisan::call('db:seed', ['--class' => $seeder, '--force' => '']);
            $runTime = round(microtime(true) - $startTime, 2);
            echo "\033[0;32mSeeded:\033[0m {$seeder} ({$runTime} seconds)\n";
        }
    }
}