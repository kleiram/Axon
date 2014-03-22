<?php
namespace Axon\Tests\Model;

use Axon\Model\Torrent;

class TorrentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldGetAndSetName()
    {
        $torrent = new Torrent();
        $torrent->setName('foo');

        $this->assertEquals('foo', $torrent->getName());
    }

    /**
     * @test
     */
    public function shouldGetAndSetSeedCount()
    {
        $torrent = new Torrent();
        $torrent->setSeedCount(10);

        $this->assertEquals(10, $torrent->getSeedCount());
    }

    /**
     * @test
     */
    public function shouldGetAndSetPeerCount()
    {
        $torrent = new Torrent();
        $torrent->setPeerCount(10);

        $this->assertEquals(10, $torrent->getPeerCount());
    }
}
