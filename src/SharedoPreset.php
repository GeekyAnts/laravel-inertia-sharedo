<?php

namespace Geekyants\Sharedo;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Laravel\Ui\Presets\Preset;

class SharedoPreset extends Preset
{
    public static function install()
    {
        static::updatePackages();
        static::updateComposer(false);
        static::scaffoldComponents();
    }

    protected static function updatePackageArray(array $packages)
    {

        return array_merge([
            "@babel/plugin-syntax-dynamic-import" => "^7.8.3",
            "@inertiajs/inertia" => "^0.8.3",
            "@inertiajs/inertia-vue" => "^0.5.4",
            "vue-multiselect" => "^2.1.6",
            "vue-template-compiler" => "^2.6.10",
            "vue" => "^2.5.17",
            "vue-loader" => "^15.9.6",
            "vue-multiselect" => "^2.1.6"
        ], $packages);
    }

    protected static function updateComposerArray(array $packages)
    {
        return array_merge([
            "inertiajs/inertia-laravel" => "^0.3.5"
        ], $packages);
    }




    protected static function scaffoldComponents()
    {
        copy(__DIR__ . '/resources/js/sharedo.js', resource_path('js/sharedo.js'));
        copy(__DIR__ . '/resources/views/sharedo.blade.php', resource_path('views/sharedo.blade.php'));
        tap(new Filesystem, function ($fs) {
            $fs->copyDirectory(__DIR__ . '/resources/js/sharedo', resource_path('js/sharedo'));
        });
    }

    protected static function updateComposer($dev = true)
    {
        if (!file_exists(base_path('composer.json'))) {
            return;
        }

        $configurationKey = $dev ? 'require-dev' : 'require';

        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        $packages[$configurationKey] = static::updateComposerArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : []
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('composer.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }
}
