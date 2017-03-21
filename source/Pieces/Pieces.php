<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Lev Seleznev
 */
namespace Spiral\Pieces;

use Spiral\Auth\ContextInterface;
use Spiral\Core\Service;
use Spiral\ORM\RecordEntity;
use Spiral\Pieces\Configs\PiecesConfig;
use Spiral\Pieces\Database\PageMeta;
use Spiral\Pieces\Database\Piece;
use Spiral\Pieces\Database\PieceLocation;
use Spiral\Security\Traits\GuardedTrait;
use Spiral\Views\Exceptions\ViewsException;
use Spiral\Views\ViewManager;

/**
 * Class Pieces
 *
 * @package Spiral\Pieces
 *
 * @property-read \Spiral\Views\ViewManager $views
 * @property-read \Spiral\ORM\ORM           $orm
 */
class Pieces extends Service
{
    use GuardedTrait;

    /**
     * @var null|bool
     */
    private $editable = null;

    /**
     * @var PiecesConfig
     */
    private $config = null;

    /**
     * @param PiecesConfig $config
     */
    public function __construct(PiecesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        if (!is_null($this->editable)) {
            return $this->editable;
        }

        // robots can't edit :(
        if (!$this->container->has(ContextInterface::class)) {
            return false;
        }

        return $this->allows($this->config->cmsPermission());
    }

    /**
     * Find piece by code.
     *
     * @param string $code
     * @return Piece|RecordEntity|null
     */
    public function findPiece(string $code)
    {
        return $this->orm->source(Piece::class)->findOne(['code' => $code]);
    }

    /**
     * @param string $namespace
     * @param string $view
     * @param string $code
     * @return null|RecordEntity|PageMeta
     */
    public function findMeta(string $namespace, string $view, string $code)
    {
        return $this->orm->source(PageMeta::class)->findOne(compact('namespace', 'view', 'code'));
    }

    /**
     * Get CMS piece or create on demand.
     *
     * @param string $code
     * @param string $defaultContent
     * @param string $view
     * @param string $namespace
     * @return Piece
     */
    public function getPiece(
        string $code,
        string $defaultContent = '',
        string $view = '',
        string $namespace = ''
    ): Piece {
        if (empty($piece = $this->findPiece($code))) {
            /** @var Piece $piece */
            $piece = $this->orm->source(Piece::class)->create(['code' => $code]);

            $piece->setContent($defaultContent);
        }

        return $this->ensureLocation($piece, $view, $namespace);
    }

    /**
     * Get page meta data or create on demand.
     *
     * @param string $namespace
     * @param string $view
     * @param string $code
     * @param array  $defaults
     * @return PageMeta
     */
    public function getMeta(
        string $namespace,
        string $view,
        string $code,
        array $defaults
    ): PageMeta {
        if (empty($meta = $this->findMeta($namespace, $view, $code))) {
            $data = compact('namespace', 'view', 'code') + $defaults;
            $meta = $this->orm->source(PageMeta::class)->create($data);
            $meta->save();
        }

        return $meta;
    }

    /**
     * Compile two versions of template, for editing and not
     *
     * @param string $namespace
     * @param string $view
     */
    public function recompileView(string $namespace, string $view)
    {
        try {
            $viewPath = $namespace . ViewManager::NS_SEPARATOR . $view;

            //We have to create two versions of template, for editing and not
            $this->editable = false;
            $this->views->compile($viewPath);

            $this->editable = true;
            $this->views->compile($viewPath);
        } catch (ViewsException $e) {
            //Nothing to do
        } finally {
            $this->editable = null;
        }
    }

    /**
     * Recompile all piece related view files
     *
     * @param Piece $piece
     */
    public function recompilePiece(Piece $piece)
    {
        /** @var PieceLocation $location */
        foreach ($piece->locations as $location) {
            $this->recompileView($location->namespace, $location->view);
        }
    }

    /**
     * Recompile all meta related view files
     *
     * @param PageMeta $meta
     */
    public function recompileMeta(PageMeta $meta)
    {
        $this->recompileView($meta->namespace, $meta->view);
    }

    /**
     * Set relation with location.
     *
     * @param Piece  $piece
     * @param string $view
     * @param string $namespace
     * @return Piece
     */
    protected function ensureLocation(Piece $piece, $view, $namespace)
    {
        if (!$piece->isLoaded()) {
            $piece->save();
        }

        $hasLocation = false;
        /** @var PieceLocation $location */
        foreach ($piece->locations as $location) {
            if ($location->view == $view && $location->namespace == $namespace) {
                $hasLocation = true;
                break;
            }
        }

        if (!$hasLocation) {
            $location = $this->orm->source(PieceLocation::class)
                ->create(compact('view', 'namespace'));
            $location->piece = $piece;
            $location->save();
        }

        return $piece;
    }
}