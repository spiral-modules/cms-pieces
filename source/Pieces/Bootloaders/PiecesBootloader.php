<?php
/**
 * Created by PhpStorm.
 * User: Wolfy-J
 * Date: 29.06.2017
 * Time: 14:33
 */

namespace Spiral\Pieces\Bootloaders;

use Psr7Middlewares\Middleware\Payload;
use Spiral\Core\Bootloaders\Bootloader;
use Spiral\Http\HttpDispatcher;
use Spiral\Http\Routing\Route;

class PiecesBootloader extends Bootloader
{
    const BOOT = true;

    /**
     * Register CMS pieces routes.
     *
     * @param \Spiral\Http\HttpDispatcher $http
     */
    public function boot(HttpDispatcher $http)
    {
        $routes = [];
        $routes[] = new Route(
            'api.pieces.html',
            'api/cms/pieces[/<action>]',
            'Spiral\Pieces\Controllers\PiecesController::<action>'
        );
        $routes[] = new Route(
            'api.pieces.meta',
            'api/cms/meta[/<action>]',
            'Spiral\Pieces\Controllers\MetaController::<action>'
        );

        $routes[] = new Route(
            'api.pieces.images',
            'api/cms/images[/<action>]',
            'Spiral\Pieces\Controllers\ImagesController::<action>'
        );

        /** @var Route $route */
        foreach ($routes as $route) {
            $http->addRoute($route->withMiddleware(Payload::class));
        }
    }
}