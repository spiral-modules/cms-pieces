<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Dmitry Mironov
 */
namespace Spiral\Pieces\Controllers;

use Spiral\Core\Controller;
use Spiral\Core\Traits\AuthorizesTrait;
use Spiral\Pieces\Configs\PiecesConfig;
use Spiral\Pieces\Pieces;
use Spiral\Pieces\Requests\MetaRequest;
use Spiral\Translator\Traits\TranslatorTrait;

/**
 * Class MetaController
 *
 * @package Spiral\Pieces\Controllers
 */
class MetaController extends Controller
{
    use AuthorizesTrait, TranslatorTrait;

    /**
     * @param MetaRequest  $request
     * @param Pieces       $pieces
     * @param PiecesConfig $config
     * @return array
     */
    public function saveAction(MetaRequest $request, Pieces $pieces, PiecesConfig $config): array
    {
        $this->authorize($config->cmsPermission());

        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors()
            ];
        }

        if (empty($meta = $pieces->findMeta($request->namespace, $request->view, $request->code))) {
            return [
                'status' => 400,
                'error'  => $this->say('Unable to find requested meta')
            ];
        }

        $meta->setFields($request->getFields());
        $meta->save();

        $pieces->pushMeta($meta);

        return [
            'status' => 200,
            'metaID' => $meta->primaryKey()
        ];
    }

    /**
     * @param MetaRequest  $request
     * @param Pieces       $pieces
     * @param PiecesConfig $config
     * @return array
     */
    public function getAction(MetaRequest $request, Pieces $pieces, PiecesConfig $config): array
    {
        $this->authorize($config->cmsPermission());

        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors()
            ];
        }

        if (empty($meta = $pieces->findMeta($request->namespace, $request->view, $request->code))) {
            return [
                'status' => 400,
                'error'  => $this->say('Unable to find requested meta')
            ];
        }

        return [
            'status' => 200,
            'piece'  => [
                'data' => [
                    'html'        => $meta->html,
                    'title'       => $meta->title,
                    'description' => $meta->description,
                    'keywords'    => $meta->keywords,
                ],
            ]
        ];
    }
}