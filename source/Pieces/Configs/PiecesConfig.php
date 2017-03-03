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
        'permission' => 'cms.pieces'
    ];

    /**
     * @return string
     */
    public function cmsPermission()
    {
        return $this->config['permission'];
    }
}