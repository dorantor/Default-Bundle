<?php

namespace PHPixie;

use PHPixie\Bundles\Bundle\Provides;
use PHPixie\DefaultBundle\Builder;
use PHPixie\Filesystem\Root;

/**
 * Default base bundle
 * @package PHPixie
 */
abstract class DefaultBundle implements Provides\HTTPProcessor,
    Provides\ORM,
    Provides\RouteResolver,
    Provides\TemplateLocator,
    Provides\Auth,
    Provides\Console,
    Provides\WebRoot
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Constructor
     * @param BundleFramework\Builder $frameworkBuilder
     */
    public function __construct($frameworkBuilder)
    {
        $this->builder = $this->buildBuilder($frameworkBuilder);
    }

    /**
     * Name of the bundle
     * @return string
     */
    public function name()
    {
        return $this->builder->bundleName();
    }

    /**
     * Processor used for HTTP requests
     * @return Processors\Processor|null
     */
    public function httpProcessor()
    {
        return $this->builder->httpProcessor();
    }

    /**
     * Route resolver for routing HTTP requests
     * @return Route\Resolvers\Resolver|null
     */
    public function routeResolver()
    {
        return $this->builder->routeResolver();
    }

    /**
     * Filesystem locator for templates
     * @return Filesystem\Locators\Locator|null
     */
    public function templateLocator()
    {
        return $this->builder->templateLocator();
    }

    /**
     * Orm config data
     * @return Slice\Data|null
     */
    public function ormConfig()
    {
        return $this->builder->ormConfig();
    }

    /**
     * Configuration options passed to the bundle.
     *
     * Defined in /assets/config/bundles/<bundle name>
     * @return Slice\Data
     */
    public function config()
    {
        return $this->builder->config();
    }

    /**
     * ORM wrapper
     * @return ORM\Wrappers|null
     */
    public function ormWrappers()
    {
        return $this->builder->ormWrappers();
    }

    /**
     * User repositories for auth component
     * @return Auth\Repositories|null
     */
    public function authRepositories()
    {
        return $this->builder->authRepositories();
    }
        
    /**
     * Console command provider
     * @return Console\Registry\Provider|null
     */
    public function consoleProvider()
    {
        return $this->builder->consoleProvider();
    }

    /**
     * Web folder root
     * @return Root|null
     */
    public function webRoot()
    {
        return $this->builder->webRoot();
    }

    /**
     * Build bundle builder
     * @param BundleFramework\Builder $frameworkBuilder
     * @return Builder
     */
    abstract protected function buildBuilder($frameworkBuilder);
}
