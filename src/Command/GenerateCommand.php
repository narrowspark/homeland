<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('generate')
            ->setDescription('Generate Docker configuration.')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                sprintf(
                    'Project local path, containing %s configuration file.',
                    'te'
                )
            );
    }
}
