<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Dmitry Mironov
 */
namespace Spiral\Pieces\Commands;

use Spiral\Console\Command;
use Spiral\Console\ConsoleDispatcher;
use Spiral\Pieces\Database\PageMeta;
use Spiral\Pieces\Database\Piece;

/**
 * Class ResetCommand
 *
 * @package Spiral\Pieces\Commands
 */
class ResetCommand extends Command
{
    /**
     * @var string
     */
    const NAME = 'pieces:reset';

    /**
     * @var string
     */
    const DESCRIPTION = 'Delete all pieces from database and recompile views';

    /**
     * @param ConsoleDispatcher $dispatcher
     */
    public function perform(ConsoleDispatcher $dispatcher)
    {
        /** @var Piece[] $pieces */
        $pieces = $this->orm->source(Piece::class)->find();
        foreach ($pieces as $piece) {
            $piece->delete();
        }

        /** @var PageMeta[] $metas */
        $metas = $this->orm->source(PageMeta::class)->find();
        foreach ($metas as $meta) {
            $meta->delete();
        }

        $dispatcher->run('views:compile');
    }
}