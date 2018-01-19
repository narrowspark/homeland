<?php
declare(strict_types=1);
namespace Narrowspark\Homeland;

final class DockerFileBuilder
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var array
     */
    private $commands = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $entrypoint;

    /**
     * Dockerfile output folder.
     *
     * @var string
     */
    private $directory = '/';

    /**
     * @var string|array
     */
    private $shell;

    /**
     * @var string
     */
    private $healthcheck;

    /**
     * @var string
     */
    private $stopsignal;

    /**
     * Create a new DockerFileBuilder instance.
     *
     * @param string $from Set the FROM instruction of Dockerfile
     */
    public function __construct(string $from)
    {
        $this->from = $from;
    }

    /**
     * Set output folder path of the dockerfile.
     *
     * @param string $directory
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function setOutputPath(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Set the CMD instruction in the Dockerfile.
     *
     * @param string $command Command to execute
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function command(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Set the ENTRYPOINT instruction in the Dockerfile.
     *
     * @param string $entrypoint The entrypoint
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function entrypoint(string $entrypoint): self
    {
        $this->entrypoint = $entrypoint;

        return $this;
    }

    /**
     * Add a ADD instruction to Dockerfile.
     *
     * @param string $path    Path wanted on the image
     * @param string $content Path of the file
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function add(string $path, string $content): self
    {
        $this->commands[] = ['type' => 'ADD', 'path' => $path, 'content' => $content];

        return $this;
    }

    /**
     * Add a RUN instruction to Dockerfile.
     *
     * @param string $command Command to run
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function run(string $command): self
    {
        $this->commands[] = ['type' => 'RUN', 'command' => $command];

        return $this;
    }

    /**
     * Add a ENV instruction to Dockerfile.
     *
     * @param string $name  Name of the environment variable
     * @param string $value Value of the environment variable
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function env(string $name, string $value): self
    {
        $this->commands[] = ['type' => 'ENV', 'name' => $name, 'value' => $value];

        return $this;
    }

    /**
     * Add a COPY instruction to Dockerfile.
     *
     * @param string $from Path of folder or file to copy
     * @param string $to   Path of destination
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function copy(string $from, string $to): self
    {
        $this->commands[] = ['type' => 'COPY', 'from' => $from, 'to' => $to];

        return $this;
    }

    /**
     * Add a WORKDIR instruction to Dockerfile.
     *
     * @param string $workdir Working directory
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function workdir(string $workdir): self
    {
        $this->commands[] = ['type' => 'WORKDIR', 'workdir' => $workdir];

        return $this;
    }

    /**
     * Add a EXPOSE instruction to Dockerfile.
     *
     * @param int $port Port to expose
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function expose(int $port): self
    {
        $this->commands[] = ['type' => 'EXPOSE', 'port' => $port];

        return $this;
    }

    /**
     * Adds an USER instruction to the Dockerfile.
     *
     * @param string $user User to switch to
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function user(string $user): self
    {
        $this->commands[] = ['type' => 'USER', 'user' => $user];

        return $this;
    }

    /**
     * Adds a VOLUME instruction to the Dockerfile.
     *
     * @param string $volume Volume path to add
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function volume(string $volume): self
    {
        $this->commands[] = ['type' => 'VOLUME', 'volume' => $volume];

        return $this;
    }

    /**
     * Adds a ONBUILD instruction to the Dockerfile.
     *
     * @param string $command
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function onbuild(string $command): self
    {
        $this->commands[] = ['type' => 'ONBUILD', 'command' => $command];

        return $this;
    }

    /**
     * Adds a ARG instruction to the Dockerfile.
     *
     * @param string $argument
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function arg(string $argument): self
    {
        $this->commands[] = ['type' => 'ARG', 'argument' => $argument];

        return $this;
    }

    /**
     * Adds a SHELL instruction to the Dockerfile.
     *
     * @param string|array $commands
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function shell($commands): self
    {
        $this->shell = $commands;

        return $this;
    }

    /**
     * Adds a HEALTHCHECK instruction to the Dockerfile.
     *
     * @param string $command
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function healthcheck(string $command): self
    {
        $this->healthcheck = $command;

        return $this;
    }

    /**
     * Adds a STOPSIGNAL instruction to the Dockerfile.
     *
     * @param string $command
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function stopsignal(string $command): self
    {
        $this->stopsignal = $command;

        return $this;
    }

    /**
     * Render docker file.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function render(): string
    {
        $dockerfile   = [];
        $dockerfile[] = 'FROM ' . $this->from;

        foreach ($this->commands as $command) {
            switch ($command['type']) {
                case 'RUN':
                    $dockerfile[] = 'RUN ' . $command['command'];

                    break;
                case 'ADD':
                    $dockerfile[] = 'ADD ' . $this->getFile($this->directory, $command['content']) . ' ' . $command['path'];

                    break;
                case 'COPY':
                    $dockerfile[] = 'COPY ' . $command['from'] . ' ' . $command['to'];

                    break;
                case 'ENV':
                    $dockerfile[] = 'ENV ' . $command['name'] . ' ' . $command['value'];

                    break;
                case 'WORKDIR':
                    $dockerfile[] = 'WORKDIR ' . $command['workdir'];

                    break;
                case 'EXPOSE':
                    $dockerfile[] = 'EXPOSE ' . $command['port'];

                    break;
                case 'VOLUME':
                    $dockerfile[] = 'VOLUME ' . $command['volume'];

                    break;
                case 'USER':
                    $dockerfile[] = 'USER ' . $command['user'];

                    break;
                case 'ONBUILD':
                    $dockerfile[] = 'ONBUILD ' . $command['command'];

                    break;
                case 'ARG':
                    $dockerfile[] = 'ARG ' . $command['argument'];

                    break;
            }
        }

        if (! empty($this->STOPSIGNAL)) {
            $dockerfile[] = 'STOPSIGNAL ' . $this->stopsignal;
        }

        if (! empty($this->healthcheck)) {
            $dockerfile[] = 'HEALTHCHECK ' . $this->healthcheck;
        }

        if (! empty($this->shell)) {
            $dockerfile[] = 'SHELL ' . $this->shell;
        }

        if (! empty($this->entrypoint)) {
            $dockerfile[] = 'ENTRYPOINT ' . $this->entrypoint;
        }

        if (! empty($this->command)) {
            $dockerfile[] = 'CMD ' . $this->command;
        }

        return \implode(PHP_EOL, $dockerfile);
    }

    /**
     * Generated a file in a directory.
     *
     * @param string $directory Targeted directory
     * @param string $content   Content of file
     *
     * @return string Name of file generated
     */
    private function getFile(string $directory, string $content): string
    {
        $hash = \sha1($content);

        if (! \array_key_exists($hash, $this->files)) {
            $file = \tempnam($directory, '');

            \file_put_contents($file, $content);

            $this->files[$hash] = \basename($file);
        }

        return $this->files[$hash];
    }
}
