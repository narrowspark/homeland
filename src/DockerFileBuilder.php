<?php
declare(strict_types=1);
namespace Narrowspark\Homeland;

class DockerFileBuilder
{

    /**
     * @var string
     */
    private $from = 'base';


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
     * Set the FROM instruction of Dockerfile
     *
     * @param string $from From which image we start
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the CMD instruction in the Dockerfile
     *
     * @param string $command Command to execute
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function command($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Set the ENTRYPOINT instruction in the Dockerfile
     *
     * @param string $entrypoint The entrypoint
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function entrypoint($entrypoint)
    {
        $this->entrypoint = $entrypoint;

        return $this;
    }

    /**
     * Add a ADD instruction to Dockerfile
     *
     * @param string $path    Path wanted on the image
     * @param string $content Content of file
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function add($path, $content)
    {
        $this->commands[] = ['type' => 'ADD', 'path' => $path, 'content' => $content];

        return $this;
    }

    /**
     * Add a RUN instruction to Dockerfile
     *
     * @param string $command Command to run
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function run($command)
    {
        $this->commands[] = ['type' => 'RUN', 'command' => $command];

        return $this;
    }

    /**
     * Add a ENV instruction to Dockerfile
     *
     * @param string $name Name of the environment variable
     * @param string $value Value of the environment variable
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function env($name, $value)
    {
        $this->commands[] = ['type' => 'ENV', 'name' => $name, 'value' => $value];

        return $this;
    }

    /**
     * Add a COPY instruction to Dockerfile
     *
     * @param string $from Path of folder or file to copy
     * @param string $to Path of destination
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function copy($from, $to)
    {
        $this->commands[] = ['type' => 'COPY', 'from' => $from, 'to' => $to];

        return $this;
    }

    /**
     * Add a WORKDIR instruction to Dockerfile
     *
     * @param string $workdir Working directory
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function workdir($workdir)
    {
        $this->commands[] = ['type' => 'WORKDIR', 'workdir' => $workdir];

        return $this;
    }

    /**
     * Add a EXPOSE instruction to Dockerfile
     *
     * @param int $port Port to expose
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function expose($port)
    {
        $this->commands[] = ['type' => 'EXPOSE', 'port' => $port];

        return $this;
    }

    /**
     * Adds an USER instruction to the Dockerfile
     *
     * @param string $user User to switch to
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function user($user)
    {
        $this->commands[] = ['type' => 'USER', 'user' => $user];

        return $this;
    }

    /**
     * Adds a VOLUME instruction to the Dockerfile
     *
     * @param string $volume Volume path to add
     *
     * @return \Narrowspark\Homeland\DockerFileBuilder
     */
    public function volume($volume)
    {
        $this->commands[] = ['type' => 'VOLUME', 'volume' => $volume];

        return $this;
    }

    /**
     * Render docker file.
     *
     * @return string
     */
    public function render(): string
    {
        $dockerfile = [];
        $dockerfile[] = 'FROM '.$this->from;

        foreach ($this->commands as $command) {
            switch ($command['type']) {
                case 'RUN':
                    $dockerfile[] = 'RUN '.$command['command'];
                    break;
                case 'ADD':
                    $dockerfile[] = 'ADD '.$this->getFile($this->directory, $command['content']).' '.$command['path'];
                    break;
                case 'COPY':
                    $dockerfile[] = 'COPY '.$command['from'].' '.$command['to'];
                    break;
                case 'ENV':
                    $dockerfile[] = 'ENV '.$command['name'].' '.$command['value'];
                    break;
                case 'WORKDIR':
                    $dockerfile[] = 'WORKDIR '.$command['workdir'];
                    break;
                case 'EXPOSE':
                    $dockerfile[] = 'EXPOSE '.$command['port'];
                    break;
                case 'VOLUME':
                    $dockerfile[] = 'VOLUME ' . $command['volume'];
                    break;
                case 'USER':
                    $dockerfile[] = 'USER ' . $command['user'];
                    break;
            }
        }

        if (!empty($this->entrypoint)) {
            $dockerfile[] = 'ENTRYPOINT ' . $this->entrypoint;
        }

        if (!empty($this->command)) {
            $dockerfile[] = 'CMD ' . $this->command;
        }

        return implode(PHP_EOL, $dockerfile);
    }

    /**
     * Generated a file in a directory
     *
     * @param string $directory Targeted directory
     * @param string $content   Content of file
     *
     * @return string Name of file generated
     */
    private function getFile(string $directory, string $content): string
    {
        $hash = md5($content);

        if (!array_key_exists($hash, $this->files)) {
            $file = tempnam($directory, '');
            // @TODO dump file
            $this->files[$hash] = basename($file);
        }

        return $this->files[$hash];
    }
}
