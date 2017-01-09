<?php

namespace PHPixie\DefaultBundle\Console;

use PHPixie\DefaultBundle\Builder;
use PHPixie\Console\Command\Implementation;
use PHPixie\BundleFramework\Components;

/**
 * Your base command class
 */
abstract class Command extends Implementation
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @return Components
     */
    protected function components()
    {
        return $this->builder->components();
    }
}