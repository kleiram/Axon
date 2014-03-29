<?php
namespace Axon\Search\Provider;

use Buzz\Browser;
use Axon\Search\Model\Torrent;
use Axon\Search\Exception\ConnectionException;
use Axon\Search\Exception\UnexpectedResponseException;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser = null)
    {
        $this->browser = $browser ?: new Browser();
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getName();

    /**
     * {@inheritDoc}
     *
     * @throws ConnectionException
     * @throws UnexpectedResponseException
     */
    public function search($query, $page = null)
    {
        $url = $this->getUrl($query, $page);

        try {
            $response = $this->browser->get($url);
        } catch (\Exception $e) {
            throw new ConnectionException(sprintf(
                'Could not connect to "%s"',
                $url
            ), 0, $e);
        }

        if ($response->getStatusCode() != 200) {
            throw new UnexpectedResponseException(sprintf(
                'Unexpected response: %s (%d)',
                $response->getReasonPhrase(),
                $response->getStatusCode()
            ));
        }

        return $this->transformResponse($response->getContent());
    }

    /**
     * @param string       $query
     * @param integer|null $page
     *
     * @return string
     */
    abstract public function getUrl($query, $page);

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     */
    abstract protected function transformResponse($rawResponse);
}
