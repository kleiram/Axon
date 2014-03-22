<?php
namespace Axon\Provider;

use Buzz\Browser;
use Axon\Model\Torrent;
use Axon\Transformer\YifyTransformer;
use Axon\Exception\ConnectionException;

/**
 * Provider to search YIFY torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class YifyProvider implements ProviderInterface
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var YifyTransformer
     */
    protected $transformer;

    /**
     * Constructor
     *
     * @param Browser $browser
     */
    public function __construct(Browser $browser = null, YifyTransformer $transformer = null)
    {
        $this->browser = $browser ?: new Browser();
        $this->transformer = $transformer ?: new YifyTransformer();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'yfiy-torrents';
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
     * @param string $query
     * @param mixed  $page
     *
     * @return string
     */
    public function getUrl($query, $page)
    {
        $parameters = array(
            'keywords' => $query
        );

        if ($page) {
            $parameters['set'] = (integer) $page;
        }

        return sprintf(
            'http://yts.re/api/list.json?%s',
            http_build_query($parameters)
        );
    }

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     */
    protected function transformResponse($rawResponse)
    {
        $decoded  = json_decode($rawResponse);
        $torrents = array();

        if (isset($decoded->status) && $decoded->status == 'fail') {
            throw new \RuntimeException(sprintf(
                'Encountered the following error "%s"', $decoded->error
            ));
        }

        if (isset($decoded->MovieList)) {
            foreach ($decoded->MovieList as $item) {
                $torrents[] = $this->transformer->transform($item);
            }
        }

        return $torrents;
    }
}
