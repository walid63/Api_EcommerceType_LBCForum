<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit84c429c9fbfb0ae2340091efe759f636
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Server\\' => 7,
        ),
        'M' => 
        array (
            'Model\\' => 6,
        ),
        'C' => 
        array (
            'Controller\\' => 11,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Server\\' => 
        array (
            0 => __DIR__ . '/../..' . '/server',
        ),
        'Model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'Controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controllers',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit84c429c9fbfb0ae2340091efe759f636::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit84c429c9fbfb0ae2340091efe759f636::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit84c429c9fbfb0ae2340091efe759f636::$classMap;

        }, null, ClassLoader::class);
    }
}