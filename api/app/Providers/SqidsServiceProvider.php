<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Sqids\Sqids;
class SqidsServiceProvider extends ServiceProvider
{
    const PAD = 7;
    const ALPHABET = 'aefghEFGijklmnopqxyzABCDHIMNOPQRSrstuvwTUVWXYZ012389';
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Sqids::class, function () {
            return new Sqids(self::ALPHABET, self::PAD);
        });
    }
}
