<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e09b92875867e3228954fbcd79a3329
{
    public static $files = array (
        '8d50dc88e56bace65e1e72f6017983ed' => __DIR__ . '/..' . '/freemius/wordpress-sdk/start.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit4e09b92875867e3228954fbcd79a3329::$classMap;

        }, null, ClassLoader::class);
    }
}
