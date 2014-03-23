<?php
namespace Axon\Transformer;

use Nomnom\Nomnom;
use Axon\Model\Torrent;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class PirateBayTransformer
{
    /**
     * @param Crawler $node
     *
     * @return Torrent
     */
    public function transform(Crawler $node)
    {
        preg_match('/magnet:\?xt=urn:btih:(.+?)&/', $node->filter('a[title^="Download"]')->first()->attr('href'), $hash);
        preg_match('/Size (.+),/', $node->filter('font.detDesc')->first()->text(), $sizeSearch);
        preg_match('([\d.]+)', $sizeSearch[1], $size);
        preg_match('([A-Za-z]+)', $sizeSearch[1], $unit);

        $convert = new Nomnom($size[0]);
        $torrent = new Torrent();
        $torrent->setUrl('http://thepiratebay.se'. $node->filter('a.detLink')->first()->attr('href'));
        $torrent->setName($node->filter('a.detLink')->first()->text());
        $torrent->setHash($hash[1]);
        $torrent->setSize($convert->from($unit[0])->to('B'));
        $torrent->setSeedCount($node->filter('td[align="right"]')->first()->text());
        $torrent->setPeerCount(
            $node->filter('td[align="right"]')->last()->text() +
            $torrent->getSeedCount()
        );

        return $torrent;
    }
}
