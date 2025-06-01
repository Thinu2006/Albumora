<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class MongodbServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
        
        Model::setEventDispatcher($this->app['events']);
        
        // Add morph map for cross-database relationships
        Relation::morphMap([
            'reviews' => \App\Models\Review::class,
        ]);
    }
}