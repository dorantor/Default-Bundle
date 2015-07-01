<?php

namespace PHPixie\Tests\DefaultBundle\Processor\HTTP;

/**
 * @coversDefaultClass \PHPixie\DefaultBundle\Processor\HTTP\Actions
 */
class ActionsTest extends \PHPixie\Tests\HTTPProcessors\Processor\Actions\AttributeTest
{
    protected function processorMock($methods = array())
    {
        return $this->quickMock(
            '\PHPixie\DefaultBundle\Processor\HTTP\Actions',
            $methods
        );
    }
}