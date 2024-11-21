<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb691d944c659cf22c19ad7f2b9985262
{
    public static $prefixLengthsPsr4 = array (
        'F' =>
        array (
            'Feng\\Logger\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Feng\\Logger\\' =>
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/vendor' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb691d944c659cf22c19ad7f2b9985262::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb691d944c659cf22c19ad7f2b9985262::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb691d944c659cf22c19ad7f2b9985262::$classMap;

        }, null, ClassLoader::class);
    }
}
