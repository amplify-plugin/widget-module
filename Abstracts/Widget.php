<?php

namespace Amplify\Widget\Abstracts;

use Illuminate\Support\Facades\Blade;

abstract class Widget
{
    protected static array $overwrites = [];

    /**
     * Register a binding for widget class
     * if abstract is an array <key,value> as widget code and value overwrite
     *
     * @param array|string $abstract
     * @param callable|string|null $concrete
     * @return void
     */
    public static function overwrite(array|string $abstract, callable|string $concrete = null): void
    {
        if (is_string($abstract) && empty($concrete)) {
            throw new \InvalidArgumentException('Concrete argument must not be empty.');
        }

        if (is_string($abstract)) {
            $abstract = [$abstract => $concrete];
        }

        foreach ($abstract as $class => $concrete) {
            self::$overwrites[$class] = $concrete;
        }
    }

    /**
     * Resolve the widget class to render form binding
     *
     * @param string $code
     * @param string $default
     * @return string
     */
    public static function resolve(string $code, string $default): string
    {
        if (isset(self::$overwrites[$code])) {

            $class = self::$overwrites[$code];

            return match ($class) {

                $class instanceof \Closure => call_user_func(self::$overwrites[$code]),

                default => $class,
            };
        }

        return $default;
    }

    /**
     * Register the class with short code to use in frontend
     *
     * @param string $defaultClass
     * @param string $shortcode
     * @param array $options
     * @return void
     */
    public static function register(string $defaultClass, string $shortcode, array $options = []): void
    {
        $class = self::resolve($shortcode, $defaultClass);

        Blade::component($class, $shortcode);
    }

    /**
     * Return a list of overwrites registers for widget
     *
     * @return array
     */
    public static function getOverwriteAlias(): array
    {
        return self::$overwrites;
    }
}
