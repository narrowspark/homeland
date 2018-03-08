<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\DockerContainer;

use Symfony\Component\OptionsResolver\Options;

abstract class AbstractDockerContainer
{
    abstract function getOptionsResolver(): Options;
}
