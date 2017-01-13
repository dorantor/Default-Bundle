<?php

namespace PHPixie\DefaultBundle\HTTP;

use PHPixie\DefaultBundle\Builder;
use PHPixie\BundleFramework\Components;
use PHPixie\DefaultBundle\Processor\HTTP\Actions;
use PHPixie\HTTP\Responses\Response;

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

    /**
     * @return \PHPixie\HTTP\Responses
     */
    protected function responses()
    {
        return $this->builder->components()->http()->responses();
    }

    /**
     * @return \PHPixie\Framework\HTTP
     */
    protected function frameworkHttp()
    {
        return $this->builder->frameworkBuilder()->http();
    }

    /**
     * @param $route
     * @param array $parameters
     * @return Response
     */
    protected function redirect($route, $parameters = array())
    {
        return $this->frameworkHttp()->redirectResponse($route, $parameters);
    }
}