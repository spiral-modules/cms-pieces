<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Pieces\Controllers;

use Spiral\Core\Controller;
use Spiral\Core\Traits\AuthorizesTrait;
use Spiral\Pieces\Configs\PiecesConfig;
use Spiral\Pieces\Pieces;
use Spiral\Pieces\Requests\PieceRequest;
use Spiral\Translator\Traits\TranslatorTrait;

/**
 * Class PiecesController
 *
 * @package Spiral\Pieces\Controllers
 */
class PiecesController extends Controller
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
     * @param PieceRequest $request
     * @param Pieces       $cms
     * @return array
     */
    public function saveAction(PieceRequest $request, Pieces $cms)
    {
        $this->authorize($this->config->cmsPermission());

        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors()
            ];
        }

        if (empty($piece = $cms->findPiece($request->getCode()))) {
            return [
                'status' => 400,
                'error'  => $this->say('Unable to find requested piece')
            ];
        }

        $piece->setContent($request->getContent());
        $piece->save();

        $cms->pushPiece($piece);

        return [
            'status'  => 200,
            'pieceID' => $piece->primaryKey()
        ];
    }

    /**
     * @param PieceRequest $request
     * @param Pieces       $cms
     * @return array
     */
    public function getAction(PieceRequest $request, Pieces $cms)
    {
        $this->authorize($this->config->cmsPermission());

        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors()
            ];
        }

        if (empty($piece = $cms->findPiece($request->getCode()))) {
            return [
                'status' => 400,
                'error'  => $this->say('Unable to find requested piece')
            ];
        }

        return [
            'status' => 200,
            'piece'  => [
                'data' => [
                    'html' => $piece->getContent(),
                ],
            ],
        ];
    }
}