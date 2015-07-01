<?php

namespace PHPixie\Tests\DefaultBundle\Processor\HTTP;

/**
 * @coversDefaultClass \PHPixie\DefaultBundle\Processor\HTTP\Builder
 */
class BuilderTest extends \PHPixie\Tests\HTTPProcessors\Processor\Dispatcher\Builder\AttributeTest
{
    protected function dispatcherMock($methods = array())
    {
        return $this->getMock(
            '\PHPixie\DefaultBundle\Processor\HTTP\Builder',
            $methods
        );
    }
}