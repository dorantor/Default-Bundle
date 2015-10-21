<?php

namespace PHPixie\DefaultBundle;

abstract class Builder
{
    protected $frameworkBuilder;
    
    protected $instances = array();
    
    public function __construct($frameworkBuilder)
    {
        $this->frameworkBuilder = $frameworkBuilder;
    }
    
    public function frameworkBuilder()
    {
        return $this->frameworkBuilder;
    }
    
    public function components()
    {
        return $this->frameworkBuilder->components();
    }
    
    public function config()
    {
        return $this->instance('config');
    }
    
    public function bundleConfig()
    {
        return $this->instance('bundleConfig');
    }
    
    public function httpProcessor()
    {
        return $this->instance('httpProcessor');
    }
    
    public function routeResolver()
    {
        return $this->instance('routeResolver');
    }
    
    public function templateLocator()
    {
        return $this->instance('templateLocator');
    }
    
    public function ormConfig()
    {
        return $this->instance('ormConfig');
    }
    
    public function ormWrappers()
    {
        return $this->instance('ormWrappers');
    }
    
    public function filesystemRoot()
    {
        return $this->instance('filesystemRoot');
    }
    
    public function assetsRoot()
    {
        return $this->instance('assetsRoot');
    }
    
    public function authRepositories()
    {
        return $this->instance('authRepositories');
    }
    
    public function webRoot()
    {
        return $this->instance('webRoot');
    }
    
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }
    
    protected function buildHttpProcessor()
    {
        return null;
    }
    
    protected function buildConfig()
    {
        return $this->components()->bundles()->config(
            $this->bundleName()
        );
    }
    
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
    
    protected function buildOrmConfig()
    {
        $config = $this->bundleConfig();
        if($config === null) {
            return null;
        }
        
        return $config->slice('orm');
    }
    
    protected function buildOrmWrappers()
    {
        return null;
    }
    
    protected function buildAuthRepositories()
    {
        return null;
    }
    
    protected function buildWebRoot()
    {
        return $this->buildPathRoot('web');
    }
    
    protected function buildAssetsRoot()
    {
        return $this->buildPathRoot('assets');
    }
    
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
    
    protected function getRootDirectory()
    {
        return null;
    }
    
    abstract public function bundleName();
}