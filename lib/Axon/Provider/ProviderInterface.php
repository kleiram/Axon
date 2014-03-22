<?php
namespace Axon\Provider;

use Axon\Model\Torrent;

/**
 * The interface torrent search providers must implement
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
interface ProviderInterface
{
    /**
     * Search the provider using a specified `query`
     *
     * @param string  $query The search query
     * @param integer $page  (optional) The page to search on
     *
     * @return Torrent[]
     */
    public function search($query, $page = null);

    /**
     * Get the name of the provider
     *
     * @return string
     */
    public function getName();
}
