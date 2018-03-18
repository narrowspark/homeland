<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\DockerContainer;

use Narrowspark\Homeland\Contract\DockerContainer as DockerContainerContract;
use Narrowspark\Homeland\DockerFileBuilder;
use Symfony\Component\OptionsResolver\Options;

abstract class AbstractDockerContainer implements DockerContainerContract
{
    /**
     * @var DockerFileBuilder
     */
    protected $builder;

    /**
     *
     */
    public function __construct()
    {
        $this->builder = new DockerFileBuilder(static::getImageName());
    }

    /**
     * @return string
     */
    abstract static public function getImageName(): string;

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        return $this->builder->render();
    }

    /**
     * @return string
     */
    abstract public function getPackageManager(): string;

    /**
     * array[]
     *     ['networkName']    array
     *        ['alias']       string
     *
     * @return array
     */
    public function getNetworks(): array
    {
        return [];
    }

    /**
     * @return Options
     */
    abstract protected function getOptionsResolver(): Options;
}
