<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit043895eb74b7c01875c7180eb654de09
{
    public static $files = array (
        '6ecac37f6f56d850dbb027d20c849c0b' => __DIR__ . '/..' . '/outscraper/outscraper/outscraper.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit043895eb74b7c01875c7180eb654de09::$classMap;

        }, null, ClassLoader::class);
    }
}
