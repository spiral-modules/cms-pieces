<?php
/**
 * Spiral skeleton+ application
 *
 * @author Dmitry Mironov
 */

namespace Spiral\Pieces\Controllers;

use Spiral\Core\Controller;
use Spiral\Core\Traits\AuthorizesTrait;
use Spiral\Pieces\Configs\PiecesConfig;
use Spiral\Pieces\Database\Image;
use Spiral\Pieces\Images;
use Spiral\Pieces\Requests\ImageRequest;
use Spiral\Translator\Traits\TranslatorTrait;

class ImagesController extends Controller
{
    use AuthorizesTrait, TranslatorTrait;

    /**
     * @var PiecesConfig
     */
    private $config;

    /**
     * PiecesController constructor.
     *
     * @param PiecesConfig $config
     */
    public function __construct(PiecesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function listAction(): array
    {
        $this->authorize($this->config->cmsPermission());

        $list = [];
        foreach ($this->orm->source(Image::class)->find() as $image) {
            $list[] = $this->packImage($image);
        }

        return [
            'status' => 200,
            'list'   => $list,
        ];
    }

    /**
     * @param ImageRequest $request
     * @param Images       $service
     *
     * @return array
     */
    public function uploadAction(ImageRequest $request, Images $service): array
    {
        $this->authorize($this->config->cmsPermission());

        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors(),
            ];
        }

        $image = $service->upload(
            $this->orm->source(Image::class)->create(),
            $request->getUpload()
        );

        $image->save();

        return [
            'status' => 200,
            'data' => $this->packImage($image),
        ];
    }

    /**
     * @param Image $image
     *
     * @return array
     */
    private function packImage(Image $image): array
    {
        return [
            'url'          => $image->original_uri,
            'thumbnailUrl' => $image->thumbnail_uri,
            'width'        => $image->width,
            'height'       => $image->height,
        ];
    }
}