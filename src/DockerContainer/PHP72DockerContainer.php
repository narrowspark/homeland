<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\DockerContainer;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PHP72DockerContainer extends AbstractDockerContainer
{
    /**
     * {@inheritdoc}
     */
    public static function getImageName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getPackageManager(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver(): Options
    {
        $resolver = new OptionsResolver();

        return $resolver;
    }
}
