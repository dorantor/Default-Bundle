<?php

namespace PHPixie\Tests\DefaultBundle;

/**
 * @coversDefaultClass \PHPixie\DefaultBundle\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $components;
    
    protected $builderMock;
    
    public function setUp()
    {
        $this->components  = $this->quickMock('\PHPixie\Framework\Components');
        $this->builderMock = $this->builderMock(array('getRootDirectory'));
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\DefaultBundle\Builder::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    protected function builderMock($methods = array())
    {
        $this->getMock(
            '\PHPixie\DefaultBundle\Builder',
            $methods,
            array($this->components)
        );
    }
}