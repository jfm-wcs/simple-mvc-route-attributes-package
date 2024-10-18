# Simple MVC Route Attibutes

## Description
This package is for the Wild Code School Simple MVC project. 
It allows to add attributes on all the Controller methods to define the route.

## Installation
```bash
composer require jfm/simple-mvc-route-attributes
```

After the installation, you need to replace the all content of the `src/routing.php` file with the following:
```php
<?php

use Jfm\SimpleMvcRouteAttributesPackage\Routing\RouteLoader;

RouteLoader::getInstance()->loadRoutes();

```

## Usage
You can now use the `Route` attribute on all your Controller methods to define the route.
```php
<?php

namespace App\Controller;

use JFM\SimpleMVCRouteAttributes\Route;

class ItemController extends AbstractController
{
    #[Route('/items', 'items')]
    public function index()
    {
        // ...
    }

    #[Route('/items/show', 'items_show')]
    public function show()
    {
        // ...
    }
// ...
}
```

That's it!
