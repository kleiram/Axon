<?php
namespace Axon\Search\Provider;

use Buzz\Browser;
use Axon\Search\Model\Torrent;
use Axon\Search\Exception\ConnectionException;
use Axon\Search\Exception\UnexpectedResponseException;

/**
 * Search YIFY torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class YifyProvider implements ProviderInterface
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
     * @var Browser
     */
    protected $browser;

    /**
     * Constructor
     *
     * @param Browser $browser
     */
    public function __construct(Browser $browser = null)
    {
        $this->browser = $browser ?: new Browser();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'yify-torrents';
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConnectionException
     * @throws UnexpectedResponseException
     */
    public function search($query, $page = null)
    {
        $url = $this->getUrl($query, $page);

        try {
            $response = $this->browser->get($url);
        } catch (\Exception $e) {
            throw new ConnectionException(sprintf(
                'Could not connect to "%s"',
                $url
            ), 0, $e);
        }

        if ($response->getStatusCode() != 200) {
            throw new UnexpectedResponseException(sprintf(
                'Unexpected response: %s (%d)',
                $response->getReasonPhrase(),
                $response->getStatusCode()
            ));
        }

        return $this->transformResponse($response->getContent());
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
