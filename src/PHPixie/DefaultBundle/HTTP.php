<?php

namespace PHPixie\DefaultBundle;

abstract class HTTP extends Processor\HTTP\Builder
{
    /**
     * @var Builder
     */
    protected $builder;
    protected $classMap = [];

    /**
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    public function processor($name)
    {
        $processor = parent::processor($name);
        if($processor !== null) {
            return $processor;
        }

        if(isset($this->classMap[$name])) {
            $class = $this->classMap[$name];
            $this->processors[$name] = new $class($this->builder);
            return $this->processors[$name];
        }

        return null;
    }
}