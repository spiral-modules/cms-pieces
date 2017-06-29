<?php
/**
 * Spiral skeleton+ application
 *
 * @author Dmitry Mironov
 */

namespace Spiral\Pieces\Database;

use Spiral\ORM\Record;

class Image extends Record
{
    const DATABASE = 'pieces';

    const SCHEMA = [
        'id'            => 'primary',

        // metadata
        'width'         => 'int',
        'height'        => 'int',
        'size'          => 'int',

        // public uris
        'thumbnail_uri' => 'string',
        'original_uri'  => 'string',
    ];
}