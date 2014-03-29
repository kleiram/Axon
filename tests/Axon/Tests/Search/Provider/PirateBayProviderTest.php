<?php
namespace Axon\Tests\Search\Provider;

use Axon\Search\Provider\PirateBayProvider;

class PirateBayProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementProviderInterface()
    {
        $provider = new PirateBayProvider();

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
        $provider = new PirateBayProvider();

        $this->assertEquals('thepiratebay', $provider->getName());
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
            ->will($this->returnValue(file_get_contents(__DIR__.'/../../../../fixtures/search/tpb.html')));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $provider = new PirateBayProvider($browser);
        $torrents = $provider->search('foo');

        $this->assertInternalType('array', $torrents);
        $this->assertCount(30, $torrents);
        $this->assertInstanceOf('Axon\Search\Model\Torrent', $torrents[0]);
        $this->assertEquals('Iron Man 3 (2013) 1080p BrRip x264 - YIFY', $torrents[0]->getName());
        $this->assertEquals('70B487B9E21E2869AF831397851F45A00D3EA7CA', $torrents[0]->getHash());
        $this->assertEquals(1950000000, $torrents[0]->getSize());
        $this->assertEquals(2437, $torrents[0]->getSeeds());
        $this->assertEquals(267, $torrents[0]->getPeers());
    }

    /**
     * @test
     * @expectedException Axon\Search\Exception\ConnectionException
     */
    public function shouldThrowExceptionOnConnectionError()
    {
        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new \Exception()));

        $provider = new PirateBayProvider($browser);
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
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));

        $provider = new PirateBayProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @dataProvider urlProvider
     */
    public function shouldGenerateUrl($query, $page, $expected)
    {
        $provider = new PirateBayProvider();

        $this->assertEquals($expected, $provider->getUrl($query, $page));
    }

    public function urlProvider()
    {
        return array(
            array('foo', null, 'http://thepiratebay.se/search/foo/0/7/0'),
            array('foo', 2, 'http://thepiratebay.se/search/foo/1/7/0'),
            array('foo bar', null, 'http://thepiratebay.se/search/foo%20bar/0/7/0'),
        );
    }
}
