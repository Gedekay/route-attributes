<?php 

namespace RouteAttributes;

use ReflectionClass;
use Illuminate\Support\Facades\Route;
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Put;
use RouteAttributes\Attributes\Patch;
use RouteAttributes\Attributes\Delete;
use RouteAttributes\Attributes\Prefix;
use RouteAttributes\Attributes\Middleware;

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

            $classPrefix = null;
            $classMiddleware = [];

            foreach ($reflection->getAttributes(Prefix::class) as $attribute) {
                $prefixInstance = $attribute->newInstance();
                $classPrefix = $prefixInstance->uri;
            }

            foreach ($reflection->getAttributes(Middleware::class) as $attribute) {
                $middlewareInstance = $attribute->newInstance();
                $classMiddleware = array_merge($classMiddleware, (array) $middlewareInstance->middleware);
            }

            foreach ($reflection->getMethods() as $method) {
                $methodPrefix = $classPrefix;
                $methodMiddleware = $classMiddleware;

                foreach ($method->getAttributes(Prefix::class) as $attribute) {
                    $prefixInstance = $attribute->newInstance();
                    $methodPrefix = $prefixInstance->uri;
                }

                foreach ($method->getAttributes(Middleware::class) as $attribute) {
                    $middlewareInstance = $attribute->newInstance();
                    $methodMiddleware = array_merge($methodMiddleware, (array) $middlewareInstance->middleware);
                }

                $uri = $methodPrefix ? $methodPrefix . '/' . $this->getMethodUri($method) : $this->getMethodUri($method);

                foreach ($method->getAttributes(Get::class) as $attribute) {
                    $route = $attribute->newInstance();
                    $finalUri = $methodPrefix ? $methodPrefix . '/' . ltrim($route->uri, '/') : $route->uri;

                    Route::get($finalUri, [$class, $method->getName()])
                        ->name($route->name)
                        ->middleware($methodMiddleware);
                }

                foreach ($method->getAttributes(Post::class) as $attribute) {
                    $route = $attribute->newInstance();
                    $finalUri = $methodPrefix ? $methodPrefix . '/' . ltrim($route->uri, '/') : $route->uri;

                    Route::post($finalUri, [$class, $method->getName()])
                        ->name($route->name)
                        ->middleware($methodMiddleware);
                }

                foreach ($method->getAttributes(Put::class) as $attribute) {
                    $route = $attribute->newInstance();
                    $finalUri = $methodPrefix ? $methodPrefix . '/' . ltrim($route->uri, '/') : $route->uri;

                    Route::put($finalUri, [$class, $method->getName()])
                        ->name($route->name)
                        ->middleware($methodMiddleware);
                }

                foreach ($method->getAttributes(Patch::class) as $attribute) {
                    $route = $attribute->newInstance();
                    $finalUri = $methodPrefix ? $methodPrefix . '/' . ltrim($route->uri, '/') : $route->uri;

                    Route::patch($finalUri, [$class, $method->getName()])
                        ->name($route->name)
                        ->middleware($methodMiddleware);
                }

                foreach ($method->getAttributes(Delete::class) as $attribute) {
                    $route = $attribute->newInstance();
                    $finalUri = $methodPrefix ? $methodPrefix . '/' . ltrim($route->uri, '/') : $route->uri;

                    Route::delete($finalUri, [$class, $method->getName()])
                        ->name($route->name)
                        ->middleware($methodMiddleware);
                }
            }
        }
    }

    private static function getMethodUri(\ReflectionMethod $method): string
    {
        foreach ($method->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();
            if (property_exists($instance, 'uri')) {
                return $instance->uri;
            }
        }
        return '';
    }
}