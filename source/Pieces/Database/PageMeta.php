<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Dmitry Mironov
 */
namespace Spiral\Pieces\Database;

use Spiral\Models\Accessors\SqlTimestamp;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\Record;

/**
 * Class PageMeta
 *
 * @package Spiral\Pieces\Database
 *
 * @property int          $id
 *
 * @property string       $namespace
 * @property string       $view
 * @property string       $code
 *
 * @property string       $title
 * @property string       $description
 * @property string       $keywords
 * @property string       $html
 *
 * @property SqlTimestamp $time_created
 * @property SqlTimestamp $time_updated
 */
class PageMeta extends Record
{
    use TimestampsTrait;

    const SCHEMA = [
        'id' => 'primary',

        'namespace' => 'string',
        'view'      => 'string',
        'code'      => 'string',

        'title'       => 'string',
        'description' => 'text',
        'keywords'    => 'text',
        'html'        => 'text',
    ];

    const INDEXES = [
        ['namespace', 'view', 'code', self::UNIQUE],
    ];

    const SECURED = [];
}