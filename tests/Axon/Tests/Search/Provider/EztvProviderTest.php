<?php
namespace Axon\Tests\Search\Provider;

use Axon\Search\Provider\EztvProvider;

class EztvProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementProviderInterface()
    {
        $provider = new EztvProvider();

        $this->assertInstanceOf(
            'Axon\Search\Provider\ProviderInterface',
            $provider
        );
    }

    /**
     * @test
     */
    public function shouldHaveName()
    {
        $provider = new EztvProvider();

        $this->assertInternalType('string', $provider->getName());
    }

    /**
     * @test
     */
    public function shouldHaveCanonicalName()
    {
        $provider = new EztvProvider();

        $this->assertInternalType('string', $provider->getCanonicalName());
    }

    /**
     * @test
     */
    public function shouldSearch()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $response
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__.'/../../../../fixtures/search/eztv.html')));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('post')
            ->with(
                'http://eztv.it/search/',
                array(),
                http_build_query(array(
                    'SearchString'  => 'foo',
                    'search'        => 'Search'
                ))
            )
            ->will($this->returnValue($response));

        $provider = new EztvProvider($browser);
        $torrents = $provider->search('foo');

        $this->assertInternalType('array', $torrents);
        $this->assertCount(191, $torrents);

        $this->assertEquals('How I Met Your Mother S09E23-E24 HDTV x264-EXCELLENCE', $torrents[0]->getName());
        $this->assertEquals('HZWZ3U6ZZKQ3MAV4D52YXUWINH5AKCJ7', $torrents[0]->getHash());
        $this->assertEquals(401830000, $torrents[0]->getSize());
        $this->assertNull($torrents[0]->getSeeds());
        $this->assertNull($torrents[0]->getPeers());
    }

    /**
     * @test
     * @expectedException Axon\Search\Exception\ConnectionException
     */
    public function shouldThrowExceptionOnConnectionError()
    {
        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->any())
            ->method('post')
            ->will($this->throwException(new \RuntimeException()));

        $provider = new EztvProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @expectedException Axon\Search\Exception\UnexpectedResponseException
     */
    public function shouldThrowExceptionOnUnexpectedResponse()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(400));

        $response
            ->expects($this->any())
            ->method('getReasonPhrase')
            ->will($this->returnValue('foo'));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $provider = new EztvProvider($browser);
        $provider->search('foo');
    }
}
