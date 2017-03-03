<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Pieces\Database;

use Spiral\ORM\Record;

/**
 * Class PieceLocation
 *
 * @package Spiral\Pieces\Database
 *
 * @property int    $id
 * @property string $view
 * @property string $namespace
 *
 * @property Piece  $piece
 */
class PieceLocation extends Record
{
    /**
     * @var array
     */
    protected $schema = [
        'id'        => 'primary',
        'view'      => 'string(255)',
        'namespace' => 'string(255)'
    ];

    /**
     * @var array
     */
    protected $fillable = ['view', 'namespace'];
}