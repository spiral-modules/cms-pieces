<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Dmitry Mironov
 */

namespace Spiral\Pieces\Requests;

use Spiral\Http\Request\RequestFilter;
use Spiral\Tokenizer\Isolator;

/**
 * Class MetaRequest
 *
 * @package Spiral\Pieces\Requests
 *
 * @property string $namespace
 * @property string $view
 * @property string $code
 *
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $html
 */
class MetaRequest extends RequestFilter
{
    /**
     * @var array
     */
    const SCHEMA = [
        // metadata id
        'namespace'   => 'data:namespace',
        'view'        => 'data:view',
        'code'        => 'data:code',
        // metadata content
        'title'       => 'data:title',
        'description' => 'data:description',
        'keywords'    => 'data:keywords',
        'html'        => 'data:html',
    ];

    /**
     * @var array
     */
    const VALIDATES = [
        'namespace' => [
            'notEmpty',
        ],
        'view'      => [
            'notEmpty',
        ],
        'code'      => [
            'notEmpty',
        ],
    ];

    /**
     * @var array
     */
    const GETTERS = [
        'html' => ['self', 'getHtml'],
    ];

    /**
     * @return string
     */
    protected function getHtml(): string
    {
        /** @var Isolator $isolator */
        $isolator = spiral(Isolator::class);
        $isolated = $isolator->isolatePHP($this->getField('html', "", false));

        return $isolated;
    }
}