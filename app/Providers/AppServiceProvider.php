<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Validator;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(500);
        Validator::extend('equal', function($attribute, $value, $parameters) {
            
            if(!Hash::check($parameters[0], $parameters[1])){
                return false;
            }
            else {return true;}
        });

        Validator::extend('uniquename', function($attribute, $value, $parameters) {
            
            if ($parameters[0] > 0) {
                return false;
            } else {
                return true;
            }
        });
        
        Validator::extend('newpassword', function($attribute, $value, $parameters) {
            
           if(!Hash::check($parameters[0], $parameters[1])){
                return true;
            }
            else {return false;}
        });
    }

    public function equal() {
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
