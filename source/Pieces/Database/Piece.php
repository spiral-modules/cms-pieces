<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Lev Seleznev
 */

namespace Spiral\Pieces\Database;

use Spiral\Models\Accessors\SqlTimestamp;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\Entities\RecordIterator;
use Spiral\ORM\Entities\Relations\HasManyRelation;
use Spiral\ORM\Record;

/**
 * Class Piece
 *
 * @package Spiral\Pieces\Database
 *
 * @property int                             $id
 * @property string                          $code
 * @property string|null                     $content
 * @property HasManyRelation|PieceLocation[] $locations
 *
 * @property SqlTimestamp                    $time_created
 * @property SqlTimestamp                    $time_updated
 */
class Piece extends Record
{
    use TimestampsTrait;

    const DATABASE = 'pieces';

    /**
     * @var array
     */
    const SCHEMA = [
        'id'        => 'primary',

        //Piece code ID
        'code'      => 'string',

        //Piece content
        'content'   => 'text,null',

        //Where pieces can be found
        'locations' => [
            self::HAS_MANY => PieceLocation::class,
            self::INVERSE  => 'piece'
        ]
    ];

    /**
     * @var array
     */
    const INDEXES = [
        [self::UNIQUE, 'code']
    ];

    /**
     * @var array
     */
    const FILLABLE = ['code', 'content'];

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}