<?php
namespace Axon\Provider;

use Buzz\Browser;
use Symfony\Component\DomCrawler\Crawler;
use Axon\Transformer\PirateBayTransformer;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class PirateBayProvider implements ProviderInterface
{
    /**
     * @param Browser              $browser
     * @param PirateBayTransformer $transformer
     */
    public function __construct(Browser $browser = null, PirateBayTransformer $transformer = null)
    {
        $this->browser     = $browser ?: new Browser();
        $this->transformer = $transformer ?: new PirateBayTransformer();
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

        return $this->transformResponse($response->getContent());
    }

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
    public function getUrl($query, $page)
    {
        return sprintf(
            'http://thepiratebay.se/search/%s/%d/7/0',
            urlencode($query),
            $page
        );
    }

    /**
     * @param string $response
     *
     * @return Torrent[]
     */
    protected function transformResponse($response)
    {
        $crawler = new Crawler($response);

        return $crawler->filter('table#searchResult tr[class!="header"]')->each(function ($node) {
            return $this->transformer->transform($node);
        });
    }
}
