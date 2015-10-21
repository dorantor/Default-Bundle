<?php

namespace PHPixie\Tests;

/**
 * @coversDefaultClass \PHPixie\DefaultBundle
 */
class DefaultBundleTest extends \PHPixie\Test\Testcase
{
    protected $bundleClass  = '\PHPixie\DefaultBundle';
    protected $builderClass = '\PHPixie\DefaultBundle\Builder';
    
    protected $frameworkBuilder;
    
    protected $bundle;
    
    protected $builder;
    
    public function setUp()
    {
        $this->frameworkBuilder = $this->quickMock('\PHPixie\Framework\Builder');
        $this->builder          = $this->builderMock();
        $this->bundle           = $this->bundleMock();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::name
     * @covers ::<protected>
     */
    public function testName()
    {
        $this->method($this->builder, 'bundleName', 'pixie', array(), 0);
        $this->assertSame('pixie', $this->bundle->name());
    }
    
    
    /**
     * @covers ::httpProcessor
     * @covers ::<protected>
     */
    public function testHttpProcessors()
    {
        $this->builderMethodTest('httpProcessor', '\PHPixie\Processors\Processor');
    }
    
    /**
     * @covers ::routeResolver
     * @covers ::<protected>
     */
    public function testRouteResolver()
    {
        $this->builderMethodTest('routeResolver', '\PHPixie\Route\Resolvers\Resolver');
    }
    
    /**
     * @covers ::templateLocator
     * @covers ::<protected>
     */
    public function testTemplateLocator()
    {
        $this->builderMethodTest('templateLocator', '\PHPixie\Filesystem\Locators\Locator');
    }
    
    /**
     * @covers ::webRoot
     * @covers ::<protected>
     */
    public function testWebRoot()
    {
        $this->builderMethodTest('webRoot', '\PHPixie\Filesystem\Root');
    }
    
    /**
     * @covers ::ormConfig
     * @covers ::<protected>
     */
    public function testOrmConfig()
    {
        $this->builderMethodTest('ormConfig', '\PHPixie\Slice\Data');
    }
    
    /**
     * @covers ::config
     * @covers ::<protected>
     */
    public function testConfig()
    {
        $this->builderMethodTest('config', '\PHPixie\Slice\Data');
    }
    
    /**
     * @covers ::ormWrappers
     * @covers ::<protected>
     */
    public function testOrmWrappers()
    {
        $this->builderMethodTest('ormWrappers', '\PHPixie\ORM\Wrappers');
    }
    
    protected function builderMethodTest($method, $class)
    {
        $instance = $this->quickMock($class);
        $this->method($this->builder, $method, $instance, array(), 0);
        $this->assertSame($instance, $this->bundle->$method());
    }
    
    protected function bundleMock($methods = array())
    {
        $methods[]= 'buildbuilder';
        
        $bundle = $this->getMockBuilder($this->bundleClass)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
        
        
        $this->method($bundle, 'buildBuilder', $this->builder, array(
            $this->frameworkBuilder
        ), 0);
        
        $bundle->__construct(
            $this->frameworkBuilder
        );
        
        return $bundle;
    }
    
    protected function builderMock()
    {
        return $this->abstractMock($this->builderClass);
    }
}