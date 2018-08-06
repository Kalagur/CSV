<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit852083128f6389db3be0bf3191e0f4c0
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Faker\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/fzaninotto/faker/src/Faker',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit852083128f6389db3be0bf3191e0f4c0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit852083128f6389db3be0bf3191e0f4c0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
