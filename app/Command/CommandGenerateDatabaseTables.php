<?php
declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;

/**
 * @CommandGenerateDatabaseTables
 * @\App\Command\CommandGenerateDatabaseTables
 */
#[
    Command(name: 'generate:tables'),
]
class CommandGenerateDatabaseTables extends HyperfCommand
{
}