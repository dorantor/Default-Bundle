<?php

namespace PHPixie\DefaultBundle;

/**
 * Optional dependency container
 */
abstract class Container extends \PHPixie\DI\Container\Root
{
    /**
     * @var Builder
     */
    protected $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
        parent::__construct();
    }

    /**
     * Configure container
     */
    protected function configure()
    {
        $this->value('builder', $this->builder);
        $this->value('frameworkBuilder', $this->builder->frameworkBuilder());
        $this->value('components', $this->builder->components());
    }
}
