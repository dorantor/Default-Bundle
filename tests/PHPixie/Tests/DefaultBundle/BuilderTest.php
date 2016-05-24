<?php

namespace PHPixie\Tests\DefaultBundle;

/**
 * @coversDefaultClass \PHPixie\DefaultBundle\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $frameworkBuilder;

    protected $builderMock;

    protected $components;

    public function setUp()
    {
        $this->frameworkBuilder  = $this->quickMock('\PHPixie\Framework\Builder');

        $this->components = $this->quickMock('\PHPixie\BundleFramework\Components');
        $this->method($this->frameworkBuilder, 'components', $this->components, array());

        $this->builderMock = $this->builderMock();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\DefaultBundle\Builder::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::frameworkBuilder
     * @covers ::<protected>
     */
    public function testFrameworkBuilder()
    {
        $this->assertSame($this->frameworkBuilder, $this->builderMock->frameworkBuilder());
    }

    /**
     * @covers ::components
     * @covers ::<protected>
     */
    public function testComponents()
    {
        $this->assertSame($this->components, $this->builderMock->components());
    }

    /**
     * @covers ::httpProcessor
     * @covers ::<protected>
     */
    public function testHttpProcessor()
    {
        $this->instanceTest('httpProcessor');
    }

    /**
     * @covers ::ormWrappers
     * @covers ::<protected>
     */
    public function testOrmWrappers()
    {
        $this->instanceTest('ormWrappers');
    }

    /**
     * @covers ::container
     * @covers ::<protected>
     */
    public function testContainer()
    {
        $this->instanceTest('container');
    }

    /**
     * @covers ::authRepositories
     * @covers ::<protected>
     */
    public function testAuthRepositories()
    {
        $this->instanceTest('authRepositories');
    }

    /**
     * @covers ::bundleConfig
     * @covers ::<protected>
     */
    public function testBundleConfig()
    {
        $this->bundleConfigTest();
        $this->bundleConfigTest(true);
    }

    /**
     * @covers ::config
     * @covers ::<protected>
     */
    public function testConfig()
    {
        $builderMock = $this->builderMock();
        $this->method($builderMock, 'bundleName', 'pixie', array(), 0);

        $bundles = $this->prepareComponent('bundles');

        $configData = $this->getSliceData();
        $this->method($bundles, 'config', $configData, array('pixie'), 0);

        $this->assertSame($configData, $builderMock->config());
    }

    protected function bundleConfigTest($assetsExists = false)
    {
        $this->builderMock = $this->builderMock(array(
            'assetsRoot'
        ));

        $assetsRoot = $assetsExists ? $this->getFilesystemRoot() : null;
        $this->method($this->builderMock, 'assetsRoot', $assetsRoot, array());

        $configData = null;

        if($assetsExists) {
            $this->method($assetsRoot, 'path', '/pixie', array());

            $config = $this->prepareComponent('config');
            $configData = $this->getSliceData();
            $this->method($config, 'directory', $configData, array('/pixie', 'config'));
        }

        for($i=0; $i<2; $i++) {
            $this->assertSame($configData, $this->builderMock->bundleConfig());
        }
    }

    /**
     * @covers ::ormConfig
     * @covers ::<protected>
     */
    public function testOrmConfig()
    {
        $this->ormConfigTest();
        $this->ormConfigTest(true);
    }

    protected function ormConfigTest($configExists = false)
    {
        $this->builderMock = $this->builderMock(array(
            'bundleConfig'
        ));

        $config = $configExists ? $this->getSliceData() : null;
        $this->method($this->builderMock, 'bundleConfig', $config, array());

        if($configExists) {
            $ormConfig = $this->getSliceData();
            $this->method($config, 'slice', $ormConfig, array('orm'));

        }else{
            $ormConfig = null;
        }

        for($i=0; $i<2; $i++) {
            $this->assertSame($ormConfig, $this->builderMock->ormConfig());
        }
    }

    /**
     * @covers ::routeResolver
     * @covers ::<protected>
     */
    public function testRouteResolver()
    {
        $this->routeResolverTest();
        $this->routeResolverTest(true);
        $this->routeResolverTest(true, true);
    }

    protected function routeResolverTest($configExists = false, $resolverExists = false)
    {
        $this->builderMock = $this->builderMock(array(
            'getRootDirectory',
            'bundleConfig'
        ));

        $config = $configExists ? $this->getSliceData() : null;
        $this->method($this->builderMock, 'bundleConfig', $config, array());

        $resolver = null;

        if($configExists) {
            $routeConfig = $this->getSliceData();
            $this->method($config, 'slice', $routeConfig, array('routeResolver'));

            $type = $resolverExists ? 'pattern' : null;
            $this->method($routeConfig, 'get', $type, array('type'), 0);

            if($resolverExists) {
                $route = $this->prepareComponent('route');
                $resolver = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
                $this->method($route, 'buildResolver', $resolver, array($routeConfig), 0);
            }
        }

        for($i=0; $i<2; $i++) {
            $this->assertSame($resolver, $this->builderMock->routeResolver());
        }
    }

    /**
     * @covers ::templateLocator
     * @covers ::<protected>
     */
    public function testTemplateLocator()
    {
        $this->templateLocatorTest();
        $this->templateLocatorTest(true);
        $this->templateLocatorTest(true, true);
    }

    protected function templateLocatorTest($configExists = false, $locatorExists = false)
    {
        $this->builderMock = $this->builderMock(array(
            'bundleConfig',
            'assetsRoot',
        ));


        $config = $configExists ? $this->getSliceData() : null;
        $this->method($this->builderMock, 'bundleConfig', $config, array());

        $locator = null;

        if($configExists) {
            $locatorConfig = $this->getSliceData();
            $this->method($config, 'slice', $locatorConfig, array('templateLocator'), 0);

            $type = $locatorExists ? 'directory' : null;
            $this->method($locatorConfig, 'get', $type, array('type'), 0);

            if($locatorExists) {
                $assetsRoot = $this->prepareInstance('assetsRoot', '\PHPixie\Filesystem\Root');

                $filesystem = $this->prepareComponent('filesystem');
                $locator = $this->quickMock('\PHPixie\Filesystem\Locators\Locator');
                $this->method($filesystem, 'buildLocator', $locator, array(
                    $locatorConfig,
                    $assetsRoot
                ), 0);
            }
        }

        for($i=0; $i<2; $i++) {
            $this->assertSame($locator, $this->builderMock->templateLocator());
        }
    }

    /**
     * @covers ::filesystemRoot
     * @covers ::<protected>
     */
    public function testBuildFilesystemRoot()
    {
        $this->filesystemRootTest();
        $this->filesystemRootTest(true);
    }

    protected function  filesystemRootTest($exists = false)
    {
        $this->builderMock = $this->builderMock(array(
            'getRootDirectory'
        ));

        $directory = $exists ? '/pixie' : null;
        $this->method($this->builderMock, 'getRootDirectory', $directory, array(), 'once');
        $filesystemRoot = null;

        if($exists) {
            $filesystem = $this->prepareComponent('filesystem');
            $filesystemRoot = $this->getFilesystemRoot();
            $this->method($filesystem, 'root', $filesystemRoot, array('/pixie'), 'once');
        }

        for($i=0; $i<2; $i++) {
            $this->assertSame($filesystemRoot, $this->builderMock->filesystemRoot());
        }
    }

    /**
     * @covers ::filesystemRoot
     * @covers ::<protected>
     */
    public function testFilesystemRoot()
    {
        $this->assertSame(null, $this->builderMock->filesystemRoot());
    }

    /**
     * @covers ::webRoot
     * @covers ::<protected>
     */
    public function testWebRoot()
    {
        $this->pathRootTest('web');
        $this->pathRootTest('web', true);
        $this->pathRootTest('web', true, true);
    }

    /**
     * @covers ::assetsRoot
     * @covers ::<protected>
     */
    public function testAssetsRoot()
    {
        $this->pathRootTest('assets');
        $this->pathRootTest('assets', true);
        $this->pathRootTest('assets', true, true);
    }

    protected function pathRootTest($name, $rootExists = false, $exists = false)
    {
        $this->builderMock = $this->builderMock(array(
            'filesystemRoot'
        ));

        $filesystemRoot = $rootExists ? $this->getFilesystemRoot() : null ;
        $this->method($this->builderMock, 'filesystemRoot', $filesystemRoot, array());

        $pathRoot = null;

        if($rootExists) {
            $directory = $exists ? sys_get_temp_dir().'/phpixie_bundle_test' : null;
            $this->method($filesystemRoot, 'path', $directory, array($name), 0);

            if($exists) {
                if(is_dir($directory)) {
                    rmdir($directory);
                }
                mkdir($directory);
                $filesystem = $this->prepareComponent('filesystem');
                $pathRoot = $this->getFilesystemRoot();
                $this->method($filesystem, 'root', $pathRoot, array($directory), 'once');
            }
        }

        $method = $name.'Root';

        for($i=0; $i<2; $i++) {
            $this->assertSame($pathRoot, $this->builderMock->$method());
        }

        if($exists) {
            rmdir($directory);
        }
    }

    protected function instanceTest($method, $class = null, $properties = array())
    {
        $instance = $this->builderMock->$method();
        if($class !== null) {
            $this->assertInstance($instance, $class, $properties);

        }else{
            $this->assertSame(null, $instance);
        }

        $this->assertSame($instance, $this->builderMock->$method());
    }

    protected function prepareInstance($method, $class)
    {
        $instance = $this->quickMock($class);
        $this->method($this->builderMock, $method, $instance, array());
        return $instance;
    }

    protected function prepareComponent($name)
    {
        $instance = $this->quickMock('\PHPixie\\'.ucfirst($name));
        $this->method($this->components, $name, $instance, array());
        return $instance;
    }

    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }

    protected function getFilesystemRoot()
    {
        return $this->quickMock('\PHPixie\Filesystem\Root');
    }

    protected function builderMock($methods = array())
    {
        $methods[]= 'bundleName';
        return $this->getMock(
            '\PHPixie\DefaultBundle\Builder',
            $methods,
            array($this->frameworkBuilder)
        );
    }
}
