<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Pieces\Configs;

use Spiral\Core\InjectableConfig;

/**
 * Class PiecesConfig
 *
 * @package Spiral\Pieces\Configs
 */
class PiecesConfig extends InjectableConfig
{
    const CONFIG = 'modules/pieces';

    /**
     * @var array
     */
    protected $config = [
        'permission' => 'cms.pieces',
        'images'     => [
            'storage'    => 'cms.images',
            'thumbnails' => [
                'width'  => 120,
                'height' => 120
            ]
        ]
    ];

    /**
     * @return string
     */
    public function cmsPermission(): string
    {
        return $this->config['permission'];
    }

    /**
     * @return string
     */
    public function imageStorage(): string
    {
        return $this->config['images']['storage'];
    }

    /**
     * @return int
     */
    public function thumbnailWidth(): int
    {
        return $this->config['images']['thumbnails']['width'];
    }

    /**
     * @return int
     */
    public function thumbnailHeight(): int
    {
        return $this->config['images']['thumbnails']['height'];
    }
}