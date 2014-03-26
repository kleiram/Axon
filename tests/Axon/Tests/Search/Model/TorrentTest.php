<?php
namespace Axon\Tests\Search\Model;

use Axon\Search\Model\Torrent;

class TorrentTest extends \PHPUnit_Framework_TestCase
{
    protected $torrent;

    public function setup()
    {
        $this->torrent = new Torrent();
    }

    /**
     * @test
     */
    public function shouldBeNulledOnWhenInitialized()
    {
        $this->assertNull($this->torrent->getName());
        $this->assertNull($this->torrent->getHash());
        $this->assertNull($this->torrent->getSize());
        $this->assertNull($this->torrent->getSeeds());
        $this->assertNull($this->torrent->getPeers());
    }

    /**
     * @test
     */
    public function shouldGetAndSetName()
    {
        $this->torrent->setName('foo');

        $this->assertEquals('foo', $this->torrent->getName());
    }

    /**
     * @test
     */
    public function shouldGetAndSetHash()
    {
        $this->torrent->setHash('foo');

        $this->assertEquals('foo', $this->torrent->getHash());
    }

    /**
     * @test
     */
    public function shouldGetAndSetSize()
    {
        $this->torrent->setSize(1337);

        $this->assertEquals(1337, $this->torrent->getSize());
    }

    /**
     * @test
     */
    public function shouldGetAndSetSeeds()
    {
        $this->torrent->setSeeds(1);

        $this->assertEquals(1, $this->torrent->getSeeds());
    }

    /**
     * @test
     */
    public function shouldGetAndSetPeers()
    {
        $this->torrent->setPeers(1);

        $this->assertEquals(1, $this->torrent->getPeers());
    }
}
