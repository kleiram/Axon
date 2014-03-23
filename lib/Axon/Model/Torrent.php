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
    protected $url;

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
    protected $seedCount;

    /**
     * @var integer
     */
    protected $peerCount;

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = array();
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

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
        $this->hash = strtoupper((string) $hash);
    }

    /**
     * @return string
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
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
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

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return Boolean
     */
    public function hasOption($key)
    {
        return isset($this->options[$key]);
    }

    /**
     * @param string $key
     * @param mixed  $option
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        if ($this->hasOption($key)) {
            return $this->options[$key];
        }

        return $default;
    }
}
