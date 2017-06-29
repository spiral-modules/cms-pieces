<?php
/**
 * Created by PhpStorm.
 * User: Wolfy-J
 * Date: 29.06.2017
 * Time: 15:14
 */

namespace Spiral\Pieces;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Spiral\Core\Service;
use Spiral\Files\Streams\StreamWrapper;
use Spiral\Pieces\Configs\PiecesConfig;
use Spiral\Pieces\Database\Image;
use Spiral\Support\Strings;
use Zend\Diactoros\Stream;

class Images extends Service
{
    /**
     * Random seed prefix.
     */
    const SEED_LENGTH = 8;

    /**
     * @var PiecesConfig
     */
    private $config = null;

    /**
     * @param \Spiral\Pieces\Configs\PiecesConfig $config
     */
    public function __construct(PiecesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param Image                 $image
     * @param UploadedFileInterface $file
     *
     * @return Image
     */
    public function upload(Image $image, UploadedFileInterface $file): Image
    {
        $seed = Strings::random(self::SEED_LENGTH);

        $originalFilename = $this->createName($file, $seed);
        $thumbnailFilename = $this->createName($file, $seed, '-min');

        //Preview
        $thumbnail = $this->createThumbnail($file->getStream());

        //Process image
        $imagick = new \Imagick();
        $imagick->readImageBlob($file->getStream());

        $image->width = $imagick->getImageWidth();
        $image->height = $imagick->getImageHeight();
        $image->size = $file->getSize();

        $image->thumbnail_uri = $this->storage->put($this->config->imageStorage(), $thumbnailFilename, $thumbnail)->getAddress();
        $image->original_uri = $this->storage->put($this->config->imageStorage(), $originalFilename, $file)->getAddress();

        return $image;
    }

    /**
     * @param StreamInterface $file
     *
     * @return StreamInterface
     */
    private function createThumbnail(StreamInterface $file): StreamInterface
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob($file);
        $imagick->cropThumbnailImage($this->config->thumbnailWidth(), $this->config->thumbnailHeight());

        $stream = new Stream('php://temp', 'wb+');
        $imagick->writeImageFile(StreamWrapper::getResource($stream));

        return $stream;
    }

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $file
     * @param string                                  $postfix
     *
     * @return string
     */
    protected function createName(UploadedFileInterface $file, string $seed, string $postfix = ''): string
    {
        $prefix = date('Y-m');

        $name = Strings::slug($this->getName($file));
        $extension = $this->getExtension($file);

        return "{$prefix}/{$seed}-{$name}{$postfix}.{$extension}";
    }

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $file
     *
     * @return string
     */
    private function getName(UploadedFileInterface $file): string
    {
        return substr($file->getClientFilename(), 0,-1 * (1 + strlen($this->getExtension($file))));
    }

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $file
     *
     * @return string
     */
    private function getExtension(UploadedFileInterface $file): string
    {
        $info = pathinfo($file->getClientFilename());
        if (empty($info['extension'])) {
            return '';
        }

        return $info['extension'];
    }
}