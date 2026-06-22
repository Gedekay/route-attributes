<?php

namespace RouteAttributes;

use ReflectionClass;
use Illuminate\Support\Facades\Route;
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Put;
use RouteAttributes\Attributes\Patch;
use RouteAttributes\Attributes\Delete;

class RouteScanner
{
    public static function scan(string $path, string $baseNamespace)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path)
        );

        foreach ($files as $file) {

            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            
            $class = str_replace(
                [$path, '/', '.php'],
                [$baseNamespace, '\\', ''],
                $file->getPathname()
            );

          
            $class = preg_replace('/\\\\+/', '\\', $class);

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            foreach ($reflection->getMethods() as $method) {

                // GET
                foreach ($method->getAttributes(Get::class) as $attribute) {
                    $route = $attribute->newInstance();

                    Route::get($route->uri, [$class, $method->getName()])
                        ->name($route->name);
                }

                // POST
                foreach ($method->getAttributes(Post::class) as $attribute) {
                    $route = $attribute->newInstance();

                    Route::post($route->uri, [$class, $method->getName()])
                        ->name($route->name);
                }

                foreach ($method->getAttributes(Put::class) as $attribute) {
                    $route = $attribute->newInstance();

                    Route::put($route->uri, [$class, $method->getName()])
                        ->name($route->name);
                }

                foreach ($method->getAttributes(Patch::class) as $attribute) {
                    $route = $attribute->newInstance();

                    Route::patch($route->uri, [$class, $method->getName()])
                        ->name($route->name);
                }

                foreach ($method->getAttributes(Delete::class) as $attribute) {
                    $route = $attribute->newInstance();

                    Route::delete($route->uri, [$class, $method->getName()])
                        ->name($route->name);
                }
            }
        }
    }
}