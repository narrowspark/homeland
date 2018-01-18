<?php
declare(strict_types=1);
namespace Narrowspark\Homeland;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TemplateFileGenerator
{
    /**
     * A twig environment.
     *
     * @var \Twig\Environment
     */
    private $templateEngine;

    /**
     * Create a new template file generator.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/Resources/views');

        $this->templateEngine = new Environment($loader, ['debug' => true]);
        $this->templateEngine->addExtension(new DebugExtension());
    }

    /**
     * Renders the docker file.
     *
     * @param string $viewPath
     * @param array  $parameters
     *
     * @return string
     */
    public function render(string $viewPath, array $parameters = []): string
    {
        return $this->templateEngine->render($viewPath, $parameters);
    }
}
