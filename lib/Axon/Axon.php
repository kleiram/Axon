<?php
namespace Axon;

use Axon\Model\Torrent;
use Axon\Provider\ProviderInterface;

/**
 * The `Axon` class is the main class for the Axon library. It provides an
 * easy way to access multiple search providers.
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class Axon
{
    /**
     * @var array
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
     * Add a search provider to the provider stack
     *
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[$provider->getName()] = $provider;
    }

    /**
     * Get a list of registered providers
     *
     * @return string[]
     */
    public function getProviders()
    {
        return array_keys($this->providers);
    }

    /**
     * Search using the registered providers
     *
     * @param string  $query
     * @param integer $page
     *
     * @return Torrent[]
     */
    public function search($query, $page = null)
    {
        if (!count($this->providers)) {
            throw new \RuntimeException(
                'You must register at least one search provider before searching'
            );
        }

        $torrents = array();

        foreach ($this->providers as $provider) {
            $torrents = array_merge($torrents, $provider->search($query, $page));
        }

        if (count($this->providers) > 1) {
            return $this->filter($torrents);
        }

        return $torrents;
    }

    /**
     * Remove duplicate torrents from the search results
     *
     * @param Torrent[] $torrents
     *
     * @return Torrent
     */
    public function filter(array $torrents)
    {
        $filtered = array();

        foreach ($torrents as $torrent) {
            $mode = 'add';

            for ($i = 0; $i < count($filtered); $i++) {
                $result = $filtered[$i];

                if ($result->getHash() == $torrent->getHash()) {
                    if ($torrent->getSeedCount() > $result->getSeedCount()) {
                        $mode = 'replace';
                        $index = $i;
                    } else {
                        $mode = 'ignore';
                    }
                }
            }

            switch ($mode) {
                case 'replace':
                    $filtered[$index] = $torrent;
                    break;
                case 'add':
                    $filtered[] = $torrent;
                    break;
                default:
                    continue;
            }
        }

        return $filtered;
    }
}
