<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\Contract;

interface DockerContainer
{
    /**
     * @var string
     */
    public const PACKAGE_MANAGER_TYPE_APT = 'apt';

    /**
     * @var string
     */
    public const PACKAGE_MANAGER_TYPE_APTITUDE = 'aptitude';

    /**
     * @var string
     */
    public const PACKAGE_MANAGER_TYPE_APK = 'apk';

    /**
     * @var string
     */
    public const PACKAGE_MANAGER_TYPE_YUM = 'yum';

    /**
     * @var string
     */
    public const DOCKER_NETWORK = 'main-network';

    /**
     * @return string
     */
    public function render(): string;
}
