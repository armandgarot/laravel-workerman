<?php

namespace ArmandGarot\LaravelWorkerman;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;
use ArmandGarot\LaravelWorkerman\Commands\WorkermanCommand;

class LaravelWorkermanServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 */
	public function boot()
	{
		if ($this->app instanceof LaravelApplication) {
			$this->publishes([
				__DIR__ . '/../config/laravel-workerman.php' => config_path('laravel-workerman.php'),
			], 'config');
		} elseif ($this->app instanceof LumenApplication) {
			$this->app->configure('laravel-workerman');
		}
	}

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/laravel-workerman.php', 'laravel-workerman');

		$this->app->bind('command.workerman:server', WorkermanCommand::class);

		$this->commands([
			'command.workerman:server',
		]);
	}
}
