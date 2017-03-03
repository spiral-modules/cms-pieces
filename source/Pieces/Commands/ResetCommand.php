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
    protected $name = 'pieces:reset';

    /**
     * @var string
     */
    protected $description = 'Delete all pieces from database and recompile views';

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

        $dispatcher->command('views:compile');
    }
}