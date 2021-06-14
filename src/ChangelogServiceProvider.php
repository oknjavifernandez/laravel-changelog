<?php

namespace OknJaviFernandez\Changelog;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OknJaviFernandez\Changelog\Adapters\FeatureAdapter;
use OknJaviFernandez\Changelog\Adapters\ReleaseAdapter;
use OknJaviFernandez\Changelog\Adapters\XmlFeatureAdapter;
use OknJaviFernandez\Changelog\Adapters\XmlReleaseAdapter;
use OknJaviFernandez\Changelog\Commands\ChangelogAddCommand;
use OknJaviFernandez\Changelog\Commands\ChangelogGenerateCommand;
use OknJaviFernandez\Changelog\Commands\ChangelogListCommand;
use OknJaviFernandez\Changelog\Commands\ChangelogReleaseCommand;
use OknJaviFernandez\Changelog\Commands\ChangelogUnreleasedCommand;

class ChangelogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/changelog.php', 'changelog');

        $this->registerServices();
    }

    /**
     * Register Changelog services.
     */
    private function registerServices(): void
    {
        $this->app->singleton(FeatureAdapter::class, function () {
            return new XmlFeatureAdapter;
        });

        $this->app->singleton(ReleaseAdapter::class, function () {
            return new XmlReleaseAdapter;
        });

        $this->app->singleton(ChangelogFormatterFactory::class, function (Application $app) {
            return new ChangelogFormatterFactory($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/changelog.php' => $this->app->configPath('changelog.php'),
            __DIR__.'/../resources/views/changelog.blade.php' => $this->app->resourcePath('views/vendor/changelog/changelog.blade.php'),
        ]);

        $this->bootCommands();
        $this->bootViews();
    }

    /**
     * Boot commands when the application is running in the console.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChangelogAddCommand::class,
                ChangelogListCommand::class,
                ChangelogUnreleasedCommand::class,
                ChangelogReleaseCommand::class,
                ChangelogGenerateCommand::class,
            ]);
        }
    }

    /**
     * Load the publishable views.
     */
    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'changelog');
    }
}
