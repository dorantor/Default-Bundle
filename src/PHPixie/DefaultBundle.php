<?php

namespace PHPixie;

use PHPixie\Bundles\Bundle\Provides;

abstract class DefaultBundle implements Provides\HTTPProcessor,
    Provides\ORM,
    Provides\RouteResolver,
    Provides\TemplateLocator,
    Provides\WebRoot
{
    protected $builder;
    
    public function __construct($bundleFrameworkBuilder)
    {
        $this->builder = $this->buildBuilder($bundleFrameworkBuilder);
    }
    
    public function httpProcessor()
    {
        return $this->builder->httpProcessor();
    }
    
    public function routeResolver()
    {
        return $this->builder->routeResolver();
    }
    
    public function templateLocator()
    {
        return $this->builder->templateLocator();
    }
    
    public function ormConfig()
    {
        return $this->builder->ormConfig();
    }
        
    public function ormWrappers()
    {
        return $this->builder->ormWrappers();
    }
    
    public function webRoot()
    {
        return $this->builder->webRoot();
    }
    
    abstract protected function buildBuilder($bundleFrameworkBuilder);
}