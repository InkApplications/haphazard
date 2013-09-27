<?php
/**
 * HaphazardTestCase.php
 *
 * @copyright 2013 (c) Ink Applications LLC. All rights reserved.
 * @license MIT <http://opensource.org/licenses/MIT>
 */

namespace Ink\Haphazard\TestCase;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Haphazard Test Case
 *
 * This is a functional test-case for quick, and dirty testing of pages.
 * It provides a way to assert that page requests simply load with the correct
 * status codes as expected.
 *
 * @author Maxwell Vandervelde <Max@MaxVandervelde.com>
 */
abstract class HaphazardTestCase extends WebTestCase
{
    private $client;

    protected function assertGet($route, $parameters = [], $status = 200)
    {
        $url = $this->getRouter()->generate($route, $parameters);

        $this->getClient()->request('GET', $url);

        $responseStatus = $this->getClient()->getResponse()->getStatusCode();
        $this->assertSame($status, $responseStatus);
    }

    protected final function getClient()
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        return $this->client;
    }

    /**
     * @return Router
     */
    private function getRouter()
    {
        $client = $this->getClient();
        $container = $client->getContainer();
        $router = $container->get('router');

        return $router;
    }
}
