<?php
declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Concerns\NullDisableEventDispatcher;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;

//use Symfony\Component\Console\Input\InputArgument;

/**
 * @CommandInitDatabase
 * @\App\Command\CommandInitDatabase
 */
#[
    Command(name: 'wheakerd:init'),
]
class CommandInitDatabase extends HyperfCommand
{

    use NullDisableEventDispatcher;

    /**
     * @var ConfigInterface $config
     */
    public function __construct(protected ConfigInterface $config)
    {
        parent::__construct();
    }

//    protected function getArguments(): array
//    {
//    }

    public function handle(): void
    {
        var_dump(
            $this->config->get('wheakerd')
        );
    }
}