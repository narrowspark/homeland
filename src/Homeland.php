<?php
declare(strict_types=1);
namespace Narrowspark\Homeland;

use Composer\Composer;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Homeland implements PluginInterface, EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io): void
    {

    }

    /**
     * Execute on composer uninstall event.
     *
     * @param \Composer\Installer\PackageEvent $event
     *
     * @return void
     */
    public function uninstall(PackageEvent $event): void
    {

    }

    /**
     * Execute on composer dump event.
     *
     * @param \Composer\Script\Event $event
     *
     * @throws \Exception
     *
     * @return void
     */
    public function dump(Event $event): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_UNINSTALL => 'uninstall',
            ScriptEvents::POST_AUTOLOAD_DUMP      => 'dump',
        ];
    }
}
