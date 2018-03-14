<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\Model\DockerContainer;

abstract class AbstractDockerContainer
{
    /**
     * @var string
     */
    protected const PACKAGE_MANAGER_TYPE_APT = 'apt';

    /**
     * @var string
     */
    protected const PACKAGE_MANAGER_TYPE_APTITUDE = 'aptitude';

    /**
     * @var string
     */
    protected const PACKAGE_MANAGER_TYPE_APK = 'apk';

    /**
     * @var string
     */
    protected const PACKAGE_MANAGER_TYPE_YUM = 'yum';

    /**
     * @var string
     */
    protected const DOCKER_MAIN_NETWORK = 'main-network';

    abstract public function getImageName(): string;

    /**
     * @return string
     */
    abstract public function getPackageManager(): string;
}
