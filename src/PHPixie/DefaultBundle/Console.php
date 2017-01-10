<?php

namespace PHPixie\DefaultBundle;

abstract class Console extends \PHPixie\Console\Registry\Provider\Implementation
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Class map for autoloaded commands
     * @var array
     */
    protected $classMap = [];

    /**
     * Constructor
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    public function commandNames()
    {
        return array_keys($this->classMap);
    }

    public function buildCommand($name, $config)
    {
        if(isset($this->classMap[$name])) {
            $class = $this->classMap[$name];
            return new $class($config, $this->builder);
        }

        return parent::buildCommand($name, $config);
    }
}