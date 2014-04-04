<?php
namespace Axon\Search\Provider;

use Axon\Search\Model\Torrent;
use Axon\Search\Exception\UnexpectedResponseException;

/**
 * Search YIFY torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class YifyProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'yts.re';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/api/list.json';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'YIFY Torrents';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'yts';
    }

    /**
     * Generate the url for a search query
     *
     * @param string       $query
     * @param integer|null $page
     */
    public function getUrl($query, $page = null)
    {
        $url = sprintf(
            'http://%s%s?keywords=%s',
            self::DEFAULT_HOST,
            self::DEFAULT_PATH,
            rawurlencode($query)
        );

        if (is_integer($page)) {
            $url .= sprintf('&set=%d', $page);
        }

        return $url;
    }

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     */
    protected function transformResponse($rawResponse)
    {
        if (!($stdClass = json_decode($rawResponse))) {
            throw new UnexpectedResponseException(
                'Could not parse response'
            );
        }

        return array_map(function ($result) {
            $torrent = new Torrent();
            $torrent->setName($result->MovieTitle);
            $torrent->setHash($result->TorrentHash);
            $torrent->setSize($result->SizeByte);
            $torrent->setSeeds($result->TorrentSeeds);
            $torrent->setPeers($result->TorrentPeers);

            return $torrent;
        }, $stdClass->MovieList);
    }
}
