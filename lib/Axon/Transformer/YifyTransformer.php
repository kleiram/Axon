<?php
namespace Axon\Transformer;

use Axon\Model\Torrent;

/**
 * The YifyTransformer is used to transform JSON responses from YIFY into
 * proper Torrent models
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class YifyTransformer
{
    /**
     * Transform a torrent from JSON into the torrent format
     *
     * @param stdClass $rawResponse
     *
     * @return Torrent[]
     */
    public function transform($rawResponse)
    {
        die(var_dump($rawResponse));
    }

    /**
     * Get the mapping for the response to Torrent transformation
     *
     * @return array
     */
    public static function getMapping()
    {
        return array(
            'TorrentUrl'   => 'url',
            'MovieTitle'   => 'name',
            'TorrentHash'  => 'hash',
            'SizeByte'     => 'size',
            'TorrentSeeds' => 'seedCount',
            'TorrentPeers' => 'peerCount',
        );
    }
}
