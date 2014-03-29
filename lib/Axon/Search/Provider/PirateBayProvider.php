<?php
namespace Axon\Search\Provider;

use Nomnom\Nomnom;
use Symfony\Component\DomCrawler\Crawler;
use Axon\Search\Model\Torrent;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class PirateBayProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'thepiratebay.se';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/search';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'thepiratebay';
    }

    /**
     * @param string       $query
     * @param integer|null $page
     *
     * @return string
     */
    public function getUrl($query, $page = null)
    {
        if (!is_integer($page)) {
            $page = 1;
        }

        return sprintf(
            'http://%s%s/%s/%d/7/0',
            self::DEFAULT_HOST,
            self::DEFAULT_PATH,
            rawurlencode($query),
            ($page - 1)
        );
    }

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     *
     * @throws UnexpectedResponseException
     */
    protected function transformResponse($rawResponse)
    {
        $crawler = new Crawler($rawResponse);

        return $crawler->filter('#searchResult tr:not(.header)')->each(function ($node) {
            $magnet = $node->filter('a[href^="magnet"]')->attr('href');
            preg_match('/btih:([0-9A-Za-z]+)&/', $magnet, $matches);
            $hash = $matches[1];

            $detDesc = $node->filter('.detDesc')->text();

            preg_match('/Size ([0-9\.]+)/', $detDesc, $matches);
            $size = $matches[1];

            preg_match('/([A-Za-z]+),/', $detDesc, $matches);
            $unit = str_replace('i', '', $matches[1]);

            $converter = new Nomnom($size);
            $torrent = new Torrent();
            $torrent->setName($node->filter('a.detLink')->text());
            $torrent->setHash($hash);
            $torrent->setSize($converter->from($unit)->to('B'));
            $torrent->setSeeds($node->filter('td[align="right"]')->first()->text());
            $torrent->setPeers($node->filter('td[align="right"]')->last()->text());

            return $torrent;
        });
    }
}
