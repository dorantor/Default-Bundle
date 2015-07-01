<?php

namespace PHPixie\DefaultBundle;

abstract class Builder
{
    protected $components;
    
    protected $instances = array();
    
    public function __construct($components)
    {
        $this->components = $components;
    }
    
    public function config()
    {
        return $this->instance('config');
    }
    
    public function httpProcessor()
    {
        return $this->instance('httpProcessor');
    }
    
    public function ormWrappers()
    {
        return $this->instance('ormWrappers');
    }
    
    public function routeResolver()
    {
        return $this->instance('routeResolver');
    }
    
    public function temlateLocator()
    {
        return $this->instance('temlateLocator');
    }
    
    public function filesystemRoot()
    {
        return $this->instance('filesystemRoot');
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
    
    protected function buildConfig()
    {
        $assetsDirectory = $this->filesystemRoot()->path('assets/');
        return $this->components->config()->directory(
            $assetsDirectory,
            'config'
        );
    }
    
    protected function buildHttpProcessor()
    {
        return null;
    }
    
    protected function buildOrmWrappers()
    {
        return null;
    }
    
    protected function buildRouteResolver()
    {
        $configData = $this->config()->slice('routeResolver');
        if($configData->get('type') === null) {
            return null;
        }
        
        return $this->components->route()->buildResolver($configData);
    }
    
    protected function buildTemlateLocator()
    {
        $configData = $this->config()->slice('templateLocator');
        if($configData->get('type') === null) {
            return null;
        }
        
        return $this->components->filesystem()->buildLocator(
            $configData,
            $this->filesystemRoot()
        );
    }
    
    protected function buildFilesystemRoot()
    {
        return $this->components->filesystem()->root(
            $this->getRootDirectory()
        );
    }
    
    protected function buildWebRoot()
    {
        $webDirectory = $this->filesystemRoot()->path('web/');
        if(!is_dir($webDirectory)) {
            return null;
        }
        
        return $this->components->filesystem()->root(
            $webDirectory
        );
    }
    
    abstract protected function getRootDirectory();
}