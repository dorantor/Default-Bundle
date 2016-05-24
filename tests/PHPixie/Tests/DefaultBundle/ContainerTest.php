<?php

namespace PHPixie\Tests\DefaultBundle;

class ContainerStub extends \PHPixie\DefaultBundle\Container
{

}

/**
 * @coversDefaultClass \PHPixie\DefaultBundle\Container
 */
class ContainerTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $frameworkBuilder;
    protected $components;

    protected $container;

    public function setUp()
    {
        $this->builder = $this->abstractMock('\PHPixie\DefaultBundle\Builder');

        $this->frameworkBuilder = $this->quickMock('\PHPixie\Framework\Builder');
        $this->method($this->builder, 'frameworkBuilder', $this->frameworkBuilder, array(), 0);

        $this->components = $this->quickMock('\PHPixie\Framework\Components');
        $this->method($this->builder, 'components', $this->components, array(), 1);

        $this->container = new ContainerStub($this->builder);
    }

    public function testConstruct()
    {

    }

    public function testConfigure()
    {
        $this->assertSame($this->builder, $this->container->builder());
        $this->assertSame($this->frameworkBuilder, $this->container->frameworkBuilder());
        $this->assertSame($this->components, $this->container->components());
    }
}
