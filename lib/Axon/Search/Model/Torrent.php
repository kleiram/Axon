<?php
namespace Axon\Search\Model;

/**
 * Represents a search result for torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class Torrent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var integer
     */
    protected $seeds;

    /**
     * @var integer
     */
    protected $peers;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = (string) $hash;
    }

    /**
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = (integer) $size;
    }

    /**
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param integer $seeds
     */
    public function setSeeds($seeds)
    {
        $this->seeds = (integer) $seeds;
    }

    /**
     * @return integer|null
     */
    public function getSeeds()
    {
        return $this->seeds;
    }

    /**
     * @param integer $peers
     */
    public function setPeers($peers)
    {
        $this->peers = (integer) $peers;
    }

    /**
     * @return integer
     */
    public function getPeers()
    {
        return $this->peers;
    }
}
