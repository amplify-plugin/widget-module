<?php

namespace Amplify\Widget;

use Amplify\Widget\Abstracts\Widget;
use Amplify\Widget\Commands\WidgetMakeCommand;
use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/widget.php',
            'amplify.widget'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'widget');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/widget'),
        ], 'widget-asset');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/amplify/widget'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([WidgetMakeCommand::class]);
        }

        if (! $this->app->runningInConsole()) {
            $this->app->booted(function ($app) {
                $request = $this->app->make(\Illuminate\Http\Request::class);
                if (! $request?->is('admin/*')) {
                    push_js(mix('js/modernizr.min.js', 'vendor/widget'), 'head-script');
                    push_js(mix('js/utility.js', 'vendor/widget'), 'custom-script');
                    push_js(mix('js/widgets.js', 'vendor/widget'), 'plugin-script');

                    foreach (config('amplify.widget', []) as $classNameSpace => $options) {
                        Widget::process($classNameSpace, $options['name']);
                    }
                }
            });
        }
    }
}
