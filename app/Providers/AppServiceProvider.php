<?php

namespace App\Providers;

use App\Modules\Rules\v2\Generals\GeneralValidator;
use App\Modules\Rules\v2\Orders\OrderValidator;
use App\Modules\Rules\v2\Items\ItemValidator;
use App\Modules\Rules\v2\Prices\PriceValidator;
use App\Rules\IsClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Http\ResponseFactory;
use Rollbar\Laravel\RollbarServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('is_class', sprintf("%s@%s", GeneralValidator:: class, 'validateIsClass'));
        Validator::extend('order_exists', sprintf("%s@%s", OrderValidator:: class, 'validateOrderExistByOrderSerial'));
        Validator::extend('item_not_in_process', sprintf("%s@%s", ItemValidator:: class, 'validateItemNotInProcess'));
        Validator::extend('wholesale_discounted_price_format', sprintf("%s@%s", PriceValidator:: class, 'validateHigherDiscountedPriceOnLowerQuantity'));
        Validator::extend('wholesale_price_format', sprintf("%s@%s", PriceValidator:: class, 'validatePricesAreInDescendingOrder'));

        Validator::replacer('is_class', function ($message, $attribute, $rule, $parameters) {
            return sprintf('The given data is not instance of %s.', $parameters[0]);
        });
        
        Validator::replacer('order_exists', function ($message, $attribute, $rule, $parameters) {
            return 'The given order serial is not valid.';
        });

        Validator::replacer('item_not_in_process', function ($message, $attribute, $rule, $parameters) {
            return 'Item could not be deleted because it is in process.';
        });
        
        Validator::replacer('wholesale_discounted_price_format', function($message, $attribute, $rule, $parameters){
            return "Promotion's price with higher 'quantity' must be lower than promotion\'s price with lower 'quantity'";
        });

        Validator::replacer('wholesale_price_format', function($message, $attribute, $rule, $parameters){
            return "Prices should be presented in descending order";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Illuminate\Contracts\Routing\ResponseFactory', function ($app) {
            return new ResponseFactory();
        });

        $environment = $this->app->environment();
        if($environment === 'staging' or $environment === 'production') {
            $this->app->register(RollbarServiceProvider::class);
        }

        if ($environment === 'production') {
            $this->app['url']->forceScheme('https');
        }
    }
}
