<?php

namespace Datatable;

use Illuminate\Support\ServiceProvider;

class DataTableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->bind('datatable', function()
        {
            return new \Datatable\DataTable;
        });
    }
}
