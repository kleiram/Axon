<?php
namespace Axon;

use Axon\Search\Model\Torrent;
use Axon\Search\Provider\ProviderInterface;

/**
 * The search engine for the Axon library
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class Search
{
    /**
     * @var ProviderInterface[]
     */
    protected $providers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->providers = array();
    }

    /**
     * @param ProviderInterface $provider
     */
    public function registerProvider(ProviderInterface $provider)
    {
        $this->providers[$provider->getName()] = $provider;
    }

    /**
     * @param string       $query
     * @param integer|null $page
     *
     * @return Torrent[]
     */
    public function search($query, $page = null)
    {
        $results = array();

        array_walk($this->providers, function ($provider) use ($query, $page, &$results) {
            $results = array_merge($results, $provider->search($query, $page));
        });

        return $this->filter($results);
    }

    /**
     * @param Torrent[] $torrents
     *
     * @return Torrent[]
     */
    public function filter(array $torrents)
    {
        $result = array();

        array_walk($torrents, function ($torrent) use (&$result) {
            $result[$torrent->getHash()] = $torrent;
        });

        usort($result, function ($a, $b) {
            if ($a->getSeeds() == $b->getSeeds()) {
                return 0;
            } elseif ($a->getSeeds() > $b->getSeeds()) {
                return -1;
            } else {
                return 1;
            }
        });

        return $result;
    }
}
