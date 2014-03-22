<?php
namespace Axon\Tests\Provider;

use Axon\Model\Torrent;
use Axon\Provider\YifyProvider;

class YifyProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementProviderInterface()
    {
        $provider = new YifyProvider();

        $this->assertInstanceOf(
            'Axon\Provider\ProviderInterface',
            $provider
        );
    }

    /**
     * @test
     */
    public function shouldHaveName()
    {
        $provider = new YifyProvider();

        $this->assertNotNull($provider->getName());
    }

    /**
     * @test
     */
    public function shouldSearch()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(
                file_get_contents(__DIR__.'/../../../fixtures/search/yify.json')
            ));

        $browser = $this->getMock('Buzz\Browser');
        $browser->expects($this->once())
            ->method('get')
            ->will($this->returnValue(
                $response
            ));

        $transformer = $this->getMock('Axon\Transformer\YifyTransformer');
        $transformer->expects($this->exactly(9))
            ->method('transform')
            ->will($this->returnValue(new Torrent()));

        $provider = new YifyProvider($browser, $transformer);
        $torrents = $provider->search('Iron Man');

        array_walk($torrents, function ($item) {
            $this->assertInstanceOf(
                'Axon\Model\Torrent',
                $item
            );
        });
    }

    /**
     * @test
     * @expectedException Axon\Exception\ConnectionException
     */
    public function shouldThrowExceptionOnConnectionError()
    {
        $browser = $this->getMock('Buzz\Browser');
        $browser->expects($this->once())
            ->method('get')
            ->will($this->throwException(new \RuntimeException()));

        $provider = new YifyProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function shouldThrowExceptionOnApiError()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(json_encode(
                array('status' => 'fail', 'error' => 'foo')
            )));

        $browser = $this->getMock('Buzz\Browser');
        $browser->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $provider = new YifyProvider($browser);
        $provider->search('foo');
    }

    /**
     * @test
     * @dataProvider urlProvider
     */
    public function shouldGenerateUrl($query, $params, $expected)
    {
        $provider = new YifyProvider();

        $this->assertEquals(
            $expected,
            $provider->getUrl($query, $params)
        );
    }

    public function urlProvider()
    {
        return array(
            array(
                'Iron Man',
                null,
                'http://yts.re/api/list.json?keywords=Iron+Man'
            ),
            array(
                'Iron Man',
                10,
                'http://yts.re/api/list.json?keywords=Iron+Man&set=10'
            )
        );
    }
}
