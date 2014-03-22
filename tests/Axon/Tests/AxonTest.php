<?php
namespace Axon\Tests;

use Axon\Axon;

class AxonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAddProvider()
    {
        $provider = $this->getMock('Axon\Provider\ProviderInterface');
        $provider->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $axon = new Axon();
        $axon->addProvider($provider);

        $this->assertEquals(array('foo'), $axon->getProviders());
    }

    /**
     * @test
     */
    public function shouldSearchUsingProviders()
    {
        $providers = array(
            $this->getMock('Axon\Provider\ProviderInterface'),
            $this->getMock('Axon\Provider\ProviderInterface'),
        );

        $providers[0]->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $providers[0]->expects($this->once())
            ->method('search')
            ->with('foo', null)
            ->will($this->returnValue(array('foo')));

        $providers[1]->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('bar'));

        $providers[1]->expects($this->once())
            ->method('search')
            ->with('foo', null)
            ->will($this->returnValue(array('bar', 'baz')));

        $axon = new Axon();
        $axon->addProvider($providers[0]);
        $axon->addProvider($providers[1]);
        $torrents = $axon->search('foo');

        $this->assertEquals(array('foo', 'bar', 'baz'), $torrents);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function shouldThrowExceptionWhenSearchingWithNoProviders()
    {
        $axon = new Axon();
        $axon->search('foo');
    }
}
