<?php
declare(strict_types=1);
namespace Narrowspark\Homeland\Tests;

use Narrowspark\Homeland\DockerFileBuilder;
use PHPUnit\Framework\TestCase;

class DockerFileBuilderTest extends TestCase
{
    public function testFrom(): void
    {
        $builder = new DockerFileBuilder('php:7.2-fpm');

        self::assertSame('FROM php:7.2-fpm', $builder->render());
    }

    public function testFormWithArg(): void
    {
        $builder = new DockerFileBuilder('base:${CODE_VERSION}', 'CODE_VERSION=latest');
        $builder->command('/code/run-app');

        self::assertSame(
            'ARG CODE_VERSION=latest
FROM base:${CODE_VERSION}
CMD /code/run-app',
            $builder->render()
        );

        $builder = new DockerFileBuilder('base:${VERSION}', 'VERSION=latest');
        $builder->arg('VERSION');
        $builder->run('echo $VERSION > image_version');

        self::assertSame(
            'ARG VERSION=latest
FROM base:${VERSION}
ARG VERSION
RUN echo $VERSION > image_version',
            $builder->render()
        );
    }

    public function testRun()
    {
        $builder = new DockerFileBuilder('base');
        $builder->run('["executable", "param1", "param2"]');
        $builder->run('echo test');

        self::assertSame(
            'FROM base
RUN ["executable", "param1", "param2"]
RUN echo test',
            $builder->render()
        );
    }

    public function testCmd()
    {
        $builder = new DockerFileBuilder('base');
        $builder->command('["executable","param1","param2"]');

        self::assertSame(
            'FROM base
CMD ["executable","param1","param2"]',
            $builder->render()
        );

        $builder = new DockerFileBuilder('base');
        $builder->command('["param1","param2"]');

        self::assertSame(
            'FROM base
CMD ["param1","param2"]',
            $builder->render()
        );

        $builder = new DockerFileBuilder('base');
        $builder->command('command param1 param2');

        self::assertSame(
            'FROM base
CMD command param1 param2',
            $builder->render()
        );
    }

    public function testLabel()
    {
        $builder = new DockerFileBuilder('base');
        $builder->label('"com.example.vendor"="ACME Incorporated"');

        self::assertSame(
            'FROM base
LABEL "com.example.vendor"="ACME Incorporated"',
            $builder->render()
        );

        $builder = new DockerFileBuilder('base');
        $builder->label('com.example.label-with-value="foo"');

        self::assertSame(
            'FROM base
LABEL com.example.label-with-value="foo"',
            $builder->render()
        );

        $builder = new DockerFileBuilder('base');
        $builder->label('version="1.0"');

        self::assertSame(
            'FROM base
LABEL version="1.0"',
            $builder->render()
        );

        $builder = new DockerFileBuilder('base');
        $builder->label('description="This text illustrates \
that label-values can span multiple lines."');

        self::assertSame(
            'FROM base
LABEL description="This text illustrates \
that label-values can span multiple lines."',
            $builder->render()
        );
    }

    public function testEnv()
    {
        $builder = new DockerFileBuilder('base');
        $builder->env('myName', 'John Doe');
        $builder->env('myDog', 'Rex The Dog ');

        self::assertSame(
            'FROM base
ENV myName John Doe
ENV myDog Rex The Dog ',
            $builder->render()
        );
    }

    public function testAdd()
    {
        $builder = new DockerFileBuilder('base');
        $builder->add('hom*', '/mydir/');
        $builder->add('hom?.txt', '/mydir/');
        $builder->add('test', 'relativeDir/');
        $builder->add('arr[[]0].txt', '/mydir/');
        $builder->add('--chown=55:mygroup files*', '/somedir/');
        $builder->add('--chown=bin files*', '/somedir/');
        $builder->add('--chown=1 files*', '/somedir/');
        $builder->add('--chown=10:11 files*', '/somedir/');

        self::assertSame(
            'FROM base
ADD hom* /mydir/
ADD hom?.txt /mydir/
ADD test relativeDir/
ADD arr[[]0].txt /mydir/
ADD --chown=55:mygroup files* /somedir/
ADD --chown=bin files* /somedir/
ADD --chown=1 files* /somedir/
ADD --chown=10:11 files* /somedir/',
            $builder->render()
        );
    }

    public function testCopy()
    {
        $builder = new DockerFileBuilder('base');
        $builder->copy('hom*', '/mydir/');
        $builder->copy('hom?.txt', '/mydir/');
        $builder->copy('test', 'relativeDir/');
        $builder->copy('arr[[]0].txt', '/mydir/');
        $builder->copy('--chown=55:mygroup files*', '/somedir/');
        $builder->copy('--chown=bin files*', '/somedir/');
        $builder->copy('--chown=1 files*', '/somedir/');
        $builder->copy('--chown=10:11 files*', '/somedir/');

        self::assertSame(
            'FROM base
COPY hom* /mydir/
COPY hom?.txt /mydir/
COPY test relativeDir/
COPY arr[[]0].txt /mydir/
COPY --chown=55:mygroup files* /somedir/
COPY --chown=bin files* /somedir/
COPY --chown=1 files* /somedir/
COPY --chown=10:11 files* /somedir/',
            $builder->render()
        );
    }

    public function testEntrypoint()
    {
        $builder = new DockerFileBuilder('php:7.2-fpm');
        $builder->entrypoint('["top", "-b"]');
        $builder->command('["-c"]');

        self::assertSame(
            'FROM php:7.2-fpm
ENTRYPOINT ["top", "-b"]
CMD ["-c"]',
            $builder->render()
        );
    }

    public function testVolume()
    {
        $builder = new DockerFileBuilder('php:7.2-fpm');
        $builder->volume('/myvol');
        $builder->volume('["/data"]');

        self::assertSame(
            'FROM php:7.2-fpm
VOLUME /myvol
VOLUME ["/data"]',
            $builder->render()
        );
    }

    public function testOnbuild()
    {
        $builder = new DockerFileBuilder('php:7.2-fpm');
        $builder->onbuild('ADD . /app/src')
            ->onbuild('RUN /usr/local/bin/python-build --dir /app/src');

        self::assertSame(
            'FROM php:7.2-fpm
ONBUILD ADD . /app/src
ONBUILD RUN /usr/local/bin/python-build --dir /app/src',
            $builder->render()
        );
    }

    public function testBuildASimpleDockerfile(): void
    {
        $builder = new DockerFileBuilder('php:7.2-fpm');
        $builder->run('apt-get update')
            ->run(
            'apt-get install -y --no-install-recommends \
curl \
libmemcached-dev \
libz-dev \
libpq-dev \
libjpeg-dev \
libpng-dev \
libfreetype6-dev \
libssl-dev \
libmcrypt-dev')
            ->run('rm -rf /var/lib/apt/lists/*')
            ->command('echo "This is a test." | wc -')
            ->expose(80);

        self::assertSame(
            'FROM php:7.2-fpm
RUN apt-get update
RUN apt-get install -y --no-install-recommends \
curl \
libmemcached-dev \
libz-dev \
libpq-dev \
libjpeg-dev \
libpng-dev \
libfreetype6-dev \
libssl-dev \
libmcrypt-dev
RUN rm -rf /var/lib/apt/lists/*
EXPOSE 80
CMD echo "This is a test." | wc -',
            $builder->render()
        );
    }
}
