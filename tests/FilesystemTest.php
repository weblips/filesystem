<?php

namespace React\Tests\Filesystem;

use React\Filesystem\Filesystem;
use React\Filesystem\InstantInvoker;

class FilesystemTest extends TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf(
            'React\Filesystem\Filesystem',
            Filesystem::create($this->getMock('React\EventLoop\StreamSelectLoop'))
        );
    }
    public function testCreateWithAdapter()
    {
        $this->assertInstanceOf(
            'React\Filesystem\Filesystem',
            Filesystem::createFromAdapter($this->mockAdapter())
        );
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testFactory()
    {
        $this->assertInstanceOf('React\Filesystem\Filesystem', Filesystem::create());
    }

    public function testFile()
    {
        $file = Filesystem::create($this->getMock('React\EventLoop\StreamSelectLoop'))->file('foo.bar');
        $this->assertInstanceOf('React\Filesystem\Node\File', $file);
        $this->assertInstanceOf('React\Filesystem\Node\GenericOperationInterface', $file);
    }

    public function testDir()
    {
        $directory = Filesystem::create($this->getMock('React\EventLoop\StreamSelectLoop'))->dir('foo.bar');
        $this->assertInstanceOf('React\Filesystem\Node\Directory', $directory);
        $this->assertInstanceOf('React\Filesystem\Node\GenericOperationInterface', $directory);
    }

    public function testGetContents()
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            Filesystem::create($this->getMock('React\EventLoop\StreamSelectLoop'))->getContents('foo.bar')
        );
    }

    public function testSetFilesystemAndInvoker()
    {
        $adapter = $this->mockAdapter();
        $invoker = new InstantInvoker($adapter);
        $adapter
            ->expects($this->at(0))
            ->method('setFilesystem')
            ->with($this->isInstanceOf('React\Filesystem\Filesystem'))
        ;
        $adapter
            ->expects($this->at(1))
            ->method('setInvoker')
            ->with($invoker)
        ;
        Filesystem::createFromAdapter($adapter)->setInvoker($invoker);
    }
}
