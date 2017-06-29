<?php
/**
 * Spiral skeleton+ application
 *
 * @author Dmitry Mironov
 */

namespace Spiral\Pieces\Requests;

use Psr\Http\Message\UploadedFileInterface;
use Spiral\Http\Request\RequestFilter;

class ImageRequest extends RequestFilter
{
    const SCHEMA = [
        'image' => 'file:image',
    ];

    const VALIDATES = [
        'image' => ['file::uploaded', 'image::valid',],
    ];

    /**
     * @return \Psr\Http\Message\UploadedFileInterface
     */
    public function getUpload(): UploadedFileInterface
    {
        return $this->image;
    }
}