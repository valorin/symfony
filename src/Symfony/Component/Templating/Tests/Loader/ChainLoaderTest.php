<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Templating\Tests\Loader;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Templating\Loader\ChainLoader;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\TemplateReference;

class ChainLoaderTest extends TestCase
{
    protected FilesystemLoader $loader1;
    protected FilesystemLoader $loader2;

    protected function setUp(): void
    {
        $fixturesPath = realpath(__DIR__.'/../Fixtures/');
        $this->loader1 = new FilesystemLoader($fixturesPath.'/null/%name%');
        $this->loader2 = new FilesystemLoader($fixturesPath.'/templates/%name%');
    }

    public function testConstructor()
    {
        $loader = new ProjectTemplateLoader1([$this->loader1, $this->loader2]);
        $this->assertEquals([$this->loader1, $this->loader2], $loader->getLoaders(), '__construct() takes an array of template loaders as its second argument');
    }

    public function testAddLoader()
    {
        $loader = new ProjectTemplateLoader1([$this->loader1]);
        $loader->addLoader($this->loader2);
        $this->assertEquals([$this->loader1, $this->loader2], $loader->getLoaders(), '->addLoader() adds a template loader at the end of the loaders');
    }

    public function testLoad()
    {
        $loader = new ProjectTemplateLoader1([$this->loader1, $this->loader2]);
        $this->assertFalse($loader->load(new TemplateReference('bar', 'php')), '->load() returns false if the template is not found');
        $this->assertFalse($loader->load(new TemplateReference('foo', 'php')), '->load() returns false if the template does not exist for the given renderer');
        $this->assertInstanceOf(
            'Symfony\Component\Templating\Storage\FileStorage',
            $loader->load(new TemplateReference('foo.php', 'php')),
            '->load() returns a FileStorage if the template exists'
        );
    }
}

class ProjectTemplateLoader1 extends ChainLoader
{
    public function getLoaders()
    {
        return $this->loaders;
    }
}
