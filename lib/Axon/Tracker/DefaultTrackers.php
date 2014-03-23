<?php
namespace Axon\Tracker;

use Axon\Axon;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class DefaultTrackers
{
    /**
     * @param Axon $axon
     */
    public static function load(Axon $axon)
    {
        array_walk(static::getTrackers(), function ($tracker) use ($axon) {
            $axon->addTracker($tracker);
        });
    }

    /**
     * @return array
     */
    protected static function getTrackers()
    {
        return array(
			'udp://denis.stalker.h3q.com:6969/announce',
			'udp://denis.stalker.h3q.com:6969/announce',
			'http://denis.stalker.h3q.com:6969/announce',
			'udp://tracker.publicbt.com:80/announce',
			'http://tracker.publicbt.com/announce',
			'udp://tracker.openbittorrent.com:80/announce',
			'http://tracker.openbittorrent.com/announce',
			'udp://tracker.1337x.org:80/announce',
			'udp://tracker.1337x.org:80/announce',
			'udp://tracker.publicbt.com:80/announce',
			'udp://tracker.openbittorrent.com:80',
			'udp://fr33domtracker.h33t.com:3310/announce',
			'udp://tracker.istole.it:80/announce',
			'http://exodus.desync.com:6969/announce',
			'udp://fr33dom.h33t.com:3310/announce',
			'http://fr33dom.h33t.com:3310/announce',
			'http://erdgeist.org/arts/software/opentracker/announce',
			'http://ipv6.tracker.harry.lu/announce',
			'http://bt.e-burg.org:2710/announce',
			'http://tracker.torrentbay.to:6969/announce',
			'http://tracker.1337x.org/announce',
			'http://cpleft.com:2710/announce',
			'http://tracker.bittorrent.am/announce',
			'http://sline.net:2710/announce',
			'http://retracker.nn.ertelecom.ru/announce',
			'http://cpleft.com:2710/announce',
			'http://tracker.cpleft.com:2710/announce',
			'http://exodus.desync.com/announce',
			'http://tracker.novalayer.org:6969/announce',
			'http://retracker.hq.ertelecom.ru/announce',
			'http://retracker.perm.ertelecom.ru/announce',
			'http://i.bandito.org/announce',
			'http://tracker.tfile.me/announce',
			'http://siambit.com/announce.php',
			'http://announce.torrentsmd.com:6969/announce',
			'http://coppersurfer.tk:6969/announce',
			'http://tracker.coppersurfer.tk:6969/announce',
			'http://tracker.anime-miako.to:6969/announce',
			'http://p2p.lineage2.com.cn:6969/announce',
			'http://tracker.hdcmct.com:2710/announce',
			'http://php.hdcmct.com:2710/announce',
			'http://bigfangroup.org/announce.php',
			'http://thebox.bz:2710/announce',
			'http://tracker.thebox.bz:8080/announce',
			'http://www.total-share.org/announce.php',
			'http://retracker.hotplug.ru:2710/announce',
			'http://announce.partis.si/announce',
			'http://tracker.torrentbox.com:2710/announce',
			'http://jpopsuki.eu:7531/announce',
			'http://masters-tb.com/announce.php',
			'http://www.music-vid.com/announce.php',
			'http://deviloid.net:6969/announce',
			'http://announce.xxx-tracker.com:2710/announce',
			'http://tracker.gaytorrent.ru/announce.php',
			'http://papaja.v2v.cc:6970/announce',
			'http://bttrack.9you.com/announce',
			'http://tracker.torrentleech.org:2710/announce',
			'http://grabthe.info/announce.php',
			'http://tracker.zokio.net:8080/announce',
			'http://torrent.jiwang.cc/announce.php',
			'http://www.elitezones.ro/announce.php',
			'http://elitezones.ro/announce.php',
			'http://www.unlimitz.com/announce.php',
			'http://baconbits.org:34000/announce',
			'http://rds-zone.ro/announce.php',
			'http://craiovatracker.com/announce.php',
        );
    }
}
