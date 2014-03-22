<?php
namespace Axon\Tests\Transformer;

use Axon\Transformer\YifyTransformer;

class YifyTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldTransform()
    {
        $item = json_decode(json_encode(array(
            'TorrentUrl'   => 'url',
            'MovieTitle'   => 'name',
            'TorrentHash'  => 'hash',
            'SizeByte'     => 10,
            'TorrentSeeds' => 5,
            'TorrentPeers' => 10,
        )));

        $transformer = new YifyTransformer();
        $torrent     = $transformer->transform($item);

        $this->assertInstanceOf('Axon\Model\Torrent', $torrent);
        $this->assertEquals('url', $torrent->getUrl());
        $this->assertEquals('name', $torrent->getName());
        $this->assertEquals('hash', $torrent->getHash());
        $this->assertEquals(10, $torrent->getSize());
        $this->assertEquals(5, $torrent->getSeedCount());
        $this->assertEquals(10, $torrent->getPeerCount());
    }

    /**
     * @test
     */
    public function shouldHandleNotExistingProperties()
    {
        $item = json_decode(json_encode(array(
            'MovieTitle'   => 'name',
            'TorrentHash'  => 'hash',
            'SizeByte'     => 10,
            'TorrentSeeds' => 5,
            'TorrentPeers' => 10,
        )));

        $transformer = new YifyTransformer();
        $torrent     = $transformer->transform($item);

        $this->assertInstanceOf('Axon\Model\Torrent', $torrent);
        $this->assertEquals(null, $torrent->getUrl());
        $this->assertEquals('name', $torrent->getName());
        $this->assertEquals('hash', $torrent->getHash());
        $this->assertEquals(10, $torrent->getSize());
        $this->assertEquals(5, $torrent->getSeedCount());
        $this->assertEquals(10, $torrent->getPeerCount());
    }
}
