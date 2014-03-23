<?php
namespace Axon\Tests;

use Axon\Axon;
use Axon\Model\Torrent;

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

    /**
     * @test
     */
    public function shouldFilterSearchResults()
    {
        $torrents = array(
            new Torrent(),
            new Torrent(),
            new Torrent(),
            new Torrent(),
        );

        $expected = array(
            $torrents[0],
            $torrents[1],
            $torrents[2],
        );

        $torrents[0]->setHash('foo');
        $torrents[1]->setHash('bar');
        $torrents[2]->setHash('baz');
        $torrents[3]->setHash('baz');

        $axon = new Axon();
        $this->assertEquals($expected, $axon->filter($torrents));
    }
}
