<?php
/**
 * Spiral Framework.
 *
 * @licence   MIT
 * @author    Dmitry Mironov
 */

namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;
use Spiral\Pieces\Configs\PiecesConfig;
use Spiral\Pieces\Pieces;

/**
 * Class IdeHelperModule
 *
 * @package Spiral\IdeHelper
 */
class PiecesModule implements ModuleInterface
{
    /**
     * @inheritdoc
     */
    public function register(RegistratorInterface $registrator)
    {
        $service = Pieces::class;

        $registrator->configure('views', 'namespaces.default', 'spiral/pieces', [
            "directory('libraries') . 'spiral/pieces/source/views/'",
        ]);

        $registrator->configure('views', 'environment', 'spiral/pieces', [
            "'cms.editable' => [\\$service::class, 'canEdit'],",
        ]);

        $registrator->configure('tokenizer', 'directories', 'spiral/pieces', [
            "directory('libraries') . 'spiral/pieces/source/',",
        ]);

        $registrator->configure('databases', 'aliases', 'spiral/pieces', [
            "'pieces' => 'default',",
        ]);

        $registrator->configure('storage', 'servers', 'spiral/pieces', [
            "'webroot' => [",
            "    'class'   => Servers\LocalServer::class,",
            "    'options' => [",
            "        'home' => directory('root') . 'webroot/'",
            "    ]",
            " ],"
        ]);

        $registrator->configure('storage', 'buckets', 'spiral/pieces', [
            "'cms.images' => [",
            "    'server'  => 'webroot',",
            "    'prefix'  => '/images/',",
            "    'options' => [",
            "        'directory' => 'images/',",
            "    ],",
            "],"
        ]);
    }

    /**
     * @inheritdoc
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        $publisher->publish(
            __DIR__ . '/config/pieces.php',
            $directories->directory('config') . PiecesConfig::CONFIG . '.php'
        );
    }
}