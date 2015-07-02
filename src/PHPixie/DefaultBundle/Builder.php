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
    
    public function templateLocator()
    {
        return $this->instance('templateLocator');
    }
    
    public function filesystemRoot()
    {
        return $this->instance('filesystemRoot');
    }
    
    public function assetsRoot()
    {
        return $this->instance('assetsRoot');
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
    
    protected function buildOrmWrappers()
    {
        return null;
    }
    
    protected function buildConfig()
    {
        $assetsRoot = $this->assetsRoot();
        if($assetsRoot === null) {
            return null;
        }
        
        return $this->components->config()->directory(
            $assetsRoot->path(),
            'config'
        );
    }
    
    
    protected function buildRouteResolver()
    {
        $config = $this->config();
        if($config === null) {
            return null;
        }
        
        $configData = $config->slice('routeResolver');
        if($configData->get('type') === null) {
            return null;
        }
        
        return $this->components->route()->buildResolver($configData);
    }
    
    protected function buildTemplateLocator()
    {
        $config = $this->config();
        if($config === null) {
            return null;
        }
        
        $configData = $config->slice('templateLocator');
        if($configData->get('type') === null) {
            return null;
        }
        
        return $this->components->filesystem()->buildLocator(
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
        
        return $this->components->filesystem()->root(
            $directory
        );
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
        
        return $this->components->filesystem()->root(
            $directory
        );
    }
    
    protected function getRootDirectory()
    {
        return null;
    }
}