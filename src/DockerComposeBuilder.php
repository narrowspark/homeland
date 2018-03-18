<?php
declare(strict_types=1);
namespace Narrowspark\Homeland;

use Symfony\Component\Yaml\Yaml;

final class DockerComposeBuilder
{
    /**
     * @var array $services
     */
    private $services = [];

    /**
     * @var string
     */
    private $version = '3.6';

    /**
     * @var array $networks
     */
    protected $networks = [];

    /**
     * @var array $volumes
     */
    protected $volumes = [];

    /**
     * @param string $name
     * @param array $values
     *
     * @return \Narrowspark\Homeland\DockerComposeBuilder
     */
    public function addService(string $name, array $values): self
    {
        $this->services[$name] = $values;

        return $this;
    }

    /**
     * @param string $version
     *
     * @return \Narrowspark\Homeland\DockerComposeBuilder
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Render docker-compose.yml.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function render(): string
    {
        return Yaml::dump([]);
    }
}
