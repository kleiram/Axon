<?php
namespace Axon\Transformer;

use Axon\Model\Torrent;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
     * @param stdClass $item
     *
     * @return Torrent
     */
    public function transform($item)
    {
        $torrent  = new Torrent();
        $mapping  = self::getMapping();
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($mapping as $origin => $dest) {
            try {
                $accessor->setValue(
                    $torrent,
                    $dest,
                    $accessor->getValue($item, $origin)
                );
            } catch (\Exception $e) {
                continue;
            }
        }

        return $torrent;
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
