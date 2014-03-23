<?php
namespace Axon\Transformer;

use Nomnom\Nomnom;
use Axon\Model\Torrent;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Transform a item from Kickass torrents into the Torrent model
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class KickassTransformer
{
    /**
     * Transform a torrent from HTML into the Torrent model
     *
     * @param Crawler $crawler
     *
     * @return Torrent
     */
    public function transform(Crawler $crawler)
    {
        preg_match('/([\d.]+) (.+)/', $crawler->filter('td.nobr')->first()->text(), $size);
        preg_match('/magnet:\?xt=urn:btih:(.+?)&/', $crawler->filter('a.imagnet')->first()->attr('href'), $hash);

        $convert = new Nomnom($size[1]);
        $torrent = new Torrent();
        $torrent->setUrl('http://kickass.to'. $crawler->filter('.plain')->first()->attr('href'));
        $torrent->setName($crawler->filter('.plain')->first()->text());
        $torrent->setHash(strtoupper($hash[1]));
        $torrent->setSize($convert->from($size[2])->to('B'));
        $torrent->setSeedCount($crawler->filter('.green')->first()->text());
        $torrent->setPeerCount(
            $crawler->filter('td.red')->first()->text() +
            $torrent->getSeedCount()
        );

        return $torrent;
    }
}
