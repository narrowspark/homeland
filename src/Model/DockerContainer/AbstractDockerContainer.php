<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\Model\DockerContainer;

abstract class AbstractDockerContainer
{
    /**
     * @var string
     */
    const PACKAGE_MANAGER_TYPE_APT = 'apt';

    /**
     * @var string
     */
    const PACKAGE_MANAGER_TYPE_APTITUDE = 'aptitude';

    /**
     * @var string
     */
    const PACKAGE_MANAGER_TYPE_APK = 'apk';

    /**
     * @var string
     */
    const DOCKER_MAIN_NETWORK = 'main-network';

    public function __construct()
    {
    }

    abstract function getImageName(): string;

    /**
     * @return string
     */
    abstract public function getPackageManager(): string;
}
