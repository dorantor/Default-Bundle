<?php

namespace PHPixie\DefaultBundle\HTTP;

use PHPixie\DefaultBundle\Builder;
use PHPixie\BundleFramework\Components;
use PHPixie\DefaultBundle\Processor\HTTP\Actions;

/**
 * Your base command class
 */
abstract class Processor extends Actions
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