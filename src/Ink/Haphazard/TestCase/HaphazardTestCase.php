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
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
    const LOGIN_ANON = 0;

    private $client;

    protected function assertGet($route, $parameters = [], $status = 200)
    {
        $url = $this->getRouter()->generate($route, $parameters);

        $this->getClient()->request('GET', $url);

        $responseStatus = $this->getClient()->getResponse()->getStatusCode();
        $this->assertSame($status, $responseStatus);
    }

    protected function login($role = self::LOGIN_ANON)
    {
        $session = $this->getClient()->getContainer()->get('session');

        $firewall = 'secured_area';

        if (static::LOGIN_ANON === $role) {
            $session->set('_security_' . $firewall, null);
            return;
        }

        $token = new UsernamePasswordToken('test_user', null, $firewall, [$role]);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->getClient()->getCookieJar()->set($cookie);
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
