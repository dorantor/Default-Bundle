<?php

namespace PHPixie\DefaultBundle;

/**
 * Factory
 * @package PHPixie
 */
abstract class Builder
{
    /**
     * BundleFramework builder
     * @param \PHPixie\BundleFramework\Builder $frameworkBuilder
     */
    protected $frameworkBuilder;

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * Constructor
     * @param \PHPixie\BundleFramework\Builder $frameworkBuilder
     */
    public function __construct($frameworkBuilder)
    {
        $this->frameworkBuilder = $frameworkBuilder;
        $this->container();
    }

    /**
     * BundleFramework builder
     * @return \PHPixie\BundleFramework\Builder
     */
    public function frameworkBuilder()
    {
        return $this->frameworkBuilder;
    }

    /**
     * Framework components
     * @return \PHPixie\BundleFramework\Components
     */
    public function components()
    {
        return $this->frameworkBuilder->components();
    }

    /**
     * Configuration options passed to the bundle.
     *
     * Defined in /assets/config/bundles/<bundleName>
     * @return \PHPixie\Slice\Data
     */
    public function config()
    {
        return $this->instance('config');
    }

    /**
     * Configuration options supplied with the bundlee
     *
     * Defined in <bundle root>/assets/config/
     * @return \PHPixie\Slice\Data|null
     */
    public function bundleConfig()
    {
        return $this->instance('bundleConfig');
    }

    /**
     * Processor used for HTTP requests
     * @return \PHPixie\Processors\Processor|null
     */
    public function httpProcessor()
    {
        return $this->instance('httpProcessor');
    }

    /**
     * Route resolver for routing HTTP requests
     * @return \PHPixie\Route\Resolvers\Resolver|null
     */
    public function routeResolver()
    {
        return $this->instance('routeResolver');
    }

    /**
     * Filesystem locator for templates
     * @return \PHPixie\Filesystem\Locators\Locator|null
     */
    public function templateLocator()
    {
        return $this->instance('templateLocator');
    }

    /**
     * Orm config data
     * @return \PHPixie\Slice\Data|null
     */
    public function ormConfig()
    {
        return $this->instance('ormConfig');
    }

    /**
     * ORM wrapper
     * @return \PHPixie\ORM\Wrappers|null
     */
    public function ormWrappers()
    {
        return $this->instance('ormWrappers');
    }

    /**
     * Root directory of the bundle
     * @return \PHPixie\Filesystem\Root|null
     */
    public function filesystemRoot()
    {
        return $this->instance('filesystemRoot');
    }

    /**
     * Bundle assets directory
     * @return \PHPixie\Filesystem\Root|null
     */
    public function assetsRoot()
    {
        return $this->instance('assetsRoot');
    }

    /**
     * User repositories for auth component
     * @return \PHPixie\Auth\Repositories|null
     */
    public function authRepositories()
    {
        return $this->instance('authRepositories');
    }

    /**
     * Bundle web directory
     * @return \PHPixie\Filesystem\Root|null
     */
    public function webRoot()
    {
        return $this->instance('webRoot');
    }

    /**
     * DI container
     * @return Container|null
     */
    public function container()
    {
        return $this->instance('container');
    }

    /**
     * Returns a single instance by name
     * @param string $name
     * @return mixed
     */
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }

        return $this->instances[$name];
    }

    /**
     * @return \PHPixie\Processors\Processor|null
     */
    protected function buildHttpProcessor()
    {
        return null;
    }

    /**
     * @return \PHPixie\Slice\Data
     */
    protected function buildConfig()
    {
        return $this->components()->bundles()->config(
            $this->bundleName()
        );
    }

    /**
     * @return \PHPixie\Slice\Data|null
     */
    protected function buildBundleConfig()
    {
        $assetsRoot = $this->assetsRoot();
        if($assetsRoot === null) {
            return null;
        }

        return $this->components()->config()->directory(
            $assetsRoot->path(),
            'config'
        );
    }

    /**
     * @return \PHPixie\Route\Resolvers\Resolver|null
     */
    protected function buildRouteResolver()
    {
        $config = $this->bundleConfig();
        if($config === null) {
            return null;
        }

        $configData = $config->slice('routeResolver');
        if($configData->get('type') === null) {
            return null;
        }

        return $this->components()->route()->buildResolver($configData);
    }

    /**
     * @return \PHPixie\Filesystem\Locators\Locator|null
     */
    protected function buildTemplateLocator()
    {
        $config = $this->bundleConfig();

        if($config === null) {
            return null;
        }

        $configData = $config->slice('templateLocator');
        if($configData->get('type') === null) {
            return null;
        }

        return $this->components()->filesystem()->buildLocator(
            $configData,
            $this->assetsRoot()
        );
    }

    /**
     * @return \PHPixie\Filesystem\Root|null
     */
    protected function buildFilesystemRoot()
    {
        $directory = $this->getRootDirectory();

        if($directory === null) {
            return null;
        }

        return $this->components()->filesystem()->root(
            $directory
        );
    }

    /**
     * @return \PHPixie\Slice\Data|null
     */
    protected function buildOrmConfig()
    {
        $config = $this->bundleConfig();
        if($config === null) {
            return null;
        }

        return $config->slice('orm');
    }

    /**
     * @return \PHPixie\ORM\Wrappers|null
     */
    protected function buildOrmWrappers()
    {
        return null;
    }

    /**
     * @return \PHPixie\Auth\Repositories|null
     */
    protected function buildAuthRepositories()
    {
        return null;
    }

    /**
     * @return \PHPixie\Filesystem\Root|null
     */
    protected function buildWebRoot()
    {
        return $this->buildPathRoot('web');
    }

    /**
     * @return \PHPixie\Filesystem\Root|null
     */
    protected function buildAssetsRoot()
    {
        return $this->buildPathRoot('assets');
    }

    /**
     * @return \PHPixie\Filesystem\Root|null
     */
    protected function buildPathRoot($path)
    {
        $filesystemRoot = $this->filesystemRoot();
        if($filesystemRoot === null) {
            return null;
        }

        $directory = $this->filesystemRoot()->path($path);

        if(!is_dir($directory)) {
            return null;
        }

        return $this->components()->filesystem()->root(
            $directory
        );
    }

    /**
     * @return \PHPixie\Filesystem\Root|null
     */
    protected function getRootDirectory()
    {
        return null;
    }

    /**
     * @return Container|null
     */
    protected function buildContainer()
    {
        return null;
    }

    /**
     * Bundle name
     * @return string
     */
    abstract public function bundleName();
}
