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
    protected $schema = [
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
    protected $validates = [
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
    protected $getters = [
        'html' => ['self', 'getHtml'],
    ];

    /**
     * @return string
     */
    protected function getHtml(): string
    {
        /** @var Isolator $isolator */
        $isolator = $this->container()->get(Isolator::class);
        $isolated = $isolator->isolatePHP($this->getField('html', "", false));

        return $isolated;
    }
}