<?php
namespace Axon\Model;

/**
 * Represents a torrent
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
    protected $seedCount;

    /**
     * @var integer
     */
    protected $peerCount;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return string
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
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param integer $seeders
     */
    public function setSeedCount($seeders)
    {
        $this->seedCount = (integer) $seeders;
    }

    /**
     * @return integer
     */
    public function getSeedCount()
    {
        return $this->seedCount;
    }

    /**
     * @param integer $peers
     */
    public function setPeerCount($peers)
    {
        $this->peerCount = (integer) $peers;
    }

    /**
     * @return integer
     */
    public function getPeerCount()
    {
        return $this->peerCount;
    }
}
