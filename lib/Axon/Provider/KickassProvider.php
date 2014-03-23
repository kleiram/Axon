<?php
namespace Axon\Provider;

use Buzz\Browser;
use Axon\Exception\ConnectionException;
use Axon\Transformer\KickassTransformer;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Provider to search Kickass torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class KickassProvider implements ProviderInterface
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var KickassTransformer
     */
    protected $transformer;

    /**
     * Constructor
     *
     * @param Browser            $browser
     * @param KickassTransformer $transformer
     */
    public function __construct(Browser $browser = null, KickassTransformer $transformer = null)
    {
        $this->browser = $browser ?: new Browser();
        $this->transformer = $transformer ?: new KickassTransformer();
    }

    /**
     * {@inheritDoc}
     */
    public function search($query, $page = null)
    {
        $url = $this->getUrl($query, $page);

        try {
            $response = $this->browser->get($url);
        } catch (\Exception $e) {
            throw new ConnectionException(sprintf(
                'Could not connect to "%s"', $url
            ));
        }

        return $this->transformResponse(gzdecode($response->getContent()));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'kickass-torrents';
    }

    /**
     * @param string       $query
     * @param integer|null $page
     *
     * @return string
     */
    public function getUrl($query, $page)
    {
        $url = sprintf(
            'http://kickass.to/usearch/%s/', $query
        );

        if ($page) {
            $url .= (string) $page;
        }

        return $url;
    }

    /**
     * @param string $response
     *
     * @return Torrent[]
     */
    protected function transformResponse($response)
    {
        $crawler  = new Crawler($response);

        return $crawler->filter('.mainpart table.data tr[id^="torrent"]')->each(function ($node) {
            return $this->transformer->transform($node);
        });
    }
}
