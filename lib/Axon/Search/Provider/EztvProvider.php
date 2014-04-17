<?php
namespace Axon\Search\Provider;

use Buzz\Browser;
use Nomnom\Nomnom;
use Symfony\Component\DomCrawler\Crawler;
use Axon\Search\Model\Torrent;
use Axon\Search\Exception\ConnectionException;
use Axon\Search\Exception\UnexpectedResponseException;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class EztvProvider implements ProviderInterface
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'eztv.it';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/search';

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
        return 'EZTV';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'eztv';
    }

    /**
     * {@inheritDoc}
     */
    public function search($query, $page = null)
    {
        try {
            $response = $this->browser->post(
                $this->getUrl(),
                array(),
                $this->getQuery($query)
            );
        } catch (\Exception $e) {
            throw new ConnectionException(sprintf(
                'Could not connect to "%s"',
                $this->getUrl()
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
     * @return string
     */
    public function getUrl()
    {
        return sprintf('http://%s%s/', self::DEFAULT_HOST, self::DEFAULT_PATH);
    }

    /**
     * @param string $html
     *
     * @return Torrent[]
     */
    protected function transformResponse($html)
    {
        $crawler = new Crawler($html);

        return $crawler->filter('tr.forum_header_border')->each(function ($node) {
            $magnet = $node->filter('a.magnet')->first()->attr('href');
            preg_match('/btih:([0-9A-Za-z]+)&/', $magnet, $matches);
            $hash = $matches[1];

            $size = $node->filter('a.epinfo')->attr('title');
            preg_match('/\(([0-9\.]+) ([A-Za-z]+)\)/', $size, $matches);
            $size = $matches[1];
            $unit = $matches[2];

            $converter = new Nomnom($size);

            $torrent = new Torrent();
            $torrent->setName($node->filter('td.forum_thread_post')->eq(1)->text());
            $torrent->setHash($hash);
            $torrent->setSize($converter->from($unit)->to('B'));

            return $torrent;
        });
    }

    /**
     * @param string $query
     *
     * @return string
     */
    protected function getQuery($query)
    {
        return http_build_query(array(
            'SearchString'  => $query,
            'SearchString1' => null,
            'search'        => 'Search'
        ));
    }
}
