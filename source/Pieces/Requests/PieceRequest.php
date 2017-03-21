<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Pieces\Requests;

use Spiral\Http\Request\RequestFilter;
use Spiral\Tokenizer\Isolator;

/**
 * Class PieceRequest
 *
 * @package Spiral\Pieces\Requests
 *
 * @property string $code
 * @property array  $data
 */
class PieceRequest extends RequestFilter
{
    /**
     * @var array
     */
    const SCHEMA = [
        'code' => 'data:id',
        'data' => 'data:data'
    ];

    /**
     * @var array
     */
    const VALIDATES = [
        'code' => ['notEmpty', 'string'],
        'data' => ['array'],
    ];

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getField('code');
    }

    /**
     * @return string
     */
    public function getContent()
    {
        /** @var Isolator $isolator */
        $isolator = spiral(Isolator::class);
        $isolated = $isolator->isolatePHP($this->data['html']);

        return $isolated;
    }
}