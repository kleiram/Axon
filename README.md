# Axon

Axon is a library for searching various torrent tracker sites for torrents using
a simple API.

## Installation

Installation is really simple when using [Composer](http://getcomposer.org):

```json
{
    "require": {
        "kleiram/axon": "dev-master"
    }
}
```

And you're all set!

## Usage

The following code is an example of how to use Axon:

```php
// Create a new Axon instance
$axon = new Axon\Axon();

// Add a couple of providers to the stack
$axon->addProvider(new Axon\Provider\YifyProvider());
$axon->addProvider(new Axon\Provider\KickassProvider());

// Start searching!
$torrents = $axon->search('Iron Man 3');
```

Torrents are automatically filtered (by hash and seeder count) when more then
one provider is registered so duplicate search results are very rare.

Check the [`lib/Axon/Providers`](https://github.com/kleiram/axon/tree/master/lib/Axon/Providers)
directory for more providers.

### Supported providers

Currently, the following tracker sites are supported:

 - [YIFY Torrents](https://github.com/kleiram/axon/blob/master/lib/Axon/Provider/YifyProvider.php)
 - [Kickass Torrents](https://github.com/kleiram/axon/blob/master/lib/Axon/Provider/KickassProvider.php)
 - [The Pirate Bay](https://github.com/kleiram/axon/blob/master/lib/Axon/Provider/PirateBayProvider.php)
 - And working on more!

### Magnet links and torrents

Axon provides the possibility to automatically create magnet links for search
results:

```php
$magnet = $axon->createMagnet($torrent);
```

To use it properly, you will have to add a list of trackers to Axon. This can
be done using the `addTracker` method:

```php
$axon->addTracker('http://tracker.publicbt.com/announce');
$axon->addTracker('udp://tracker.publicbt.com:80/announce');
```

By default, there is a list of trackers defined in the `Axon\Tracker\DefaultTrackers`
class that can be easily loaded into Axon:

```php
DefaultTrackers::load($axon);
```

It is also possible to get a link to a torrent file using Axon. These are
created using [Torcache](http://torcache.net) so if that site is down, you'll
have to use magnet links as described above. To get a torrent file for a torrent,
use the following example:

```php
$url = $axon->createTorrent($torrent);
```

## License

```
Copyright (c) 2014, Ramon Kleiss <ramonkleiss@gmail.com>
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those
of the authors and should not be interpreted as representing official policies,
either expressed or implied, of the FreeBSD Project.
```
