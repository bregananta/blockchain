<?php

namespace Bregananta\Blockchain;

use Illuminate\Support\ServiceProvider;

class BlockchainServiceProvider extends ServiceProvider {

    /**
     * boot the service provider
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('blockchain.php'),
        ]);

        $file = __DIR__ . '/../vendor/autoload.php';

        if (file_exists($file)) {
            require $file;
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('blockchain', function() {
            return new Blockchain;
        });
    }

}