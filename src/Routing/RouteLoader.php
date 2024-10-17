<?php

namespace Jfm\SimpleMvcRouteAttributesPackage\Routing;

use Jfm\SimpleMvcRouteAttributesPackage\Attribute\Route;
use DomainException;
use Exception;
use ReflectionClass;

class RouteLoader
{
    private static ?RouteLoader $routeLoader = null;

    private string $rootPath = '';

    private bool $routeFounded = false;

    public function __construct()
    {
        $this->rootPath = dirname($_SERVER['DOCUMENT_ROOT']);
    }

    public static function getInstance(): RouteLoader
    {
        if (self::$routeLoader === null) {
            self::$routeLoader = new RouteLoader();
        }
        return self::$routeLoader;
    }

    /**
     * @throws \ReflectionException
     * @throws Exception
     */
    public function getAttributes($controller): void
    {
        $urlPath = '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
        $reflectionClass = new ReflectionClass($controller);
        foreach ($reflectionClass->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class);
            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();
                $methodName = $method->getName();
                if ($route->path === $urlPath) {
                    echo (new $controller())->$methodName();
                    $this->routeFounded = true;
                    return;
                }
            }
        }
    }

    public function loadRoutes(?string $dir = null): void
    {
        $scanDir = $dir ?? $this->getControllersDir();
        foreach (array_diff(scandir($scanDir), ['.', '..']) as $item) {
            $itemPath = $scanDir . '/' . $item;
            $fqcn = $this->getFQCNFromFile($itemPath);
            if ($fqcn) {
                $this->getAttributes($fqcn);
            } elseif (is_dir($itemPath)) {
                $this->loadRoutes($itemPath);
            }
        }
        if (!$this->routeFounded) {
            throw new DomainException('Page not found');
        }
    }

    private function getControllersDir(): string
    {
        return $this->rootPath . '/src/Controller';
    }

    private function getFQCNFromFile($filePath): ?string
    {
        if (!is_file($filePath)) {
            return null;
        }

        $contents = file_get_contents($filePath);

        // Chercher le namespace
        if (preg_match('/namespace\s+(.+?);/', $contents, $matches)) {
            $namespace = $matches[1];
        } else {
            $namespace = null;
        }

        // Chercher le nom de la classe
        if (preg_match('/class\s+(\w+)/', $contents, $matches)) {
            $className = $matches[1];
        } else {
            return null; // Pas de classe trouvée dans ce fichier
        }

        // Construire le FQCN
        return $namespace ? $namespace . '\\' . $className : $className;
    }
}
