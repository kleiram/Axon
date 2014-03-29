<?php
namespace Axon\Tests\Search\Provider;

use Axon\Search\Provider\YifyProvider;

class YifyProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementProviderInterface()
    {
        $provider = new YifyProvider();

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
        $provider = new YifyProvider();

        $this->assertEquals(
            'yify-torrents',
            $provider->getName()
        );
    }

    /**
     * @test
     */
    public function shouldSearch()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__.'/../../../../fixtures/search/yify.json')));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $provider = new YifyProvider($browser);
        $torrents = $provider->search('Iron Man');

        $this->assertInternalType('array', $torrents);
        $this->assertCount(1, $torrents);
        $this->assertInstanceOf('Axon\Search\Model\Torrent', $torrents[0]);
        $this->assertEquals('Iron Man 3 (2013) 3D', $torrents[0]->getName());
        $this->assertEquals('48DBBFDCB66409CF7B209A9C560A87EA4CB4459C', $torrents[0]->getHash());
        $this->assertEquals(2093796557, $torrents[0]->getSize());
        $this->assertEquals(227, $torrents[0]->getSeeds());
        $this->assertEquals(42, $torrents[0]->getPeers());
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

        $provider = new YifyProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @expectedException Axon\Search\Exception\UnexpectedResponseException
     */
    public function shouldThrowExceptionOnUnexpectedStatusCode()
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
            ->method('get')
            ->will($this->returnValue($response));

        $provider = new YifyProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @expectedException Axon\Search\Exception\UnexpectedResponseException
     */
    public function shouldThrowExceptionOnInvalidResponse()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue('foo bar baz'));

        $browser = $this->getMock('Buzz\Browser');
        $browser
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $provider = new YifyProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @dataProvider urlProvider
     */
    public function shouldGenerateCorrectUrl($query, $page, $expected)
    {
        $provider = new YifyProvider();

        $this->assertEquals(
            $expected,
            $provider->getUrl($query, $page)
        );
    }

    public function urlProvider()
    {
        return array(
            array('foo', null, 'http://yts.re/api/list.json?keywords=foo'),
            array('foo', 1, 'http://yts.re/api/list.json?keywords=foo&set=1'),
            array('foo bar', null, 'http://yts.re/api/list.json?keywords=foo%20bar'),
        );
    }
}
