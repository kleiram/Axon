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
class KickassProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'kickass.to';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/usearch';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'kickass';
    }

    /**
     * @param string       $query
     * @param integer|null $page
     *
     * @return string
     */
    public function getUrl($query, $page)
    {
        if (is_integer($page)) {
            return sprintf(
                'http://%s%s/%s/%s/?field=seeders&order=desc',
                self::DEFAULT_HOST,
                self::DEFAULT_PATH,
                $query,
                $page
            );
        } else {
            return sprintf(
                'http://%s%s/%s/?field=seeders&order=desc',
                self::DEFAULT_HOST,
                self::DEFAULT_PATH,
                $query
            );
        }
    }

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     */
    protected function transformResponse($rawResponse)
    {
        $crawler = new Crawler(gzdecode($rawResponse));

        return $crawler->filter('tr[id^="torrent_"]')->each(function ($node) {
            $magnet = $node->filter('a.imagnet')->attr('href');
            preg_match('/btih:([0-9A-Za-z]+)&/', $magnet, $matches);
            $hash = $matches[1];

            $size = $node->filter('td.nobr')->text();
            preg_match('/([0-9\.]+) ([A-Za-z]+)/', $size, $matches);
            $size = $matches[1];
            $unit = $matches[2];

            $converter = new Nomnom($size);
            $torrent = new Torrent();
            $torrent->setName($node->filter('a.cellMainLink')->text());
            $torrent->setHash($hash);
            $torrent->setSize($converter->from($unit)->to('B'));
            $torrent->setSeeds($node->filter('td.green')->text());
            $torrent->setPeers($node->filter('td.red')->text());

            return $torrent;
        });
    }
}
