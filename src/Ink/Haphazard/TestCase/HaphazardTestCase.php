<?php
/**
 * HaphazardTestCase.php
 *
 * @copyright 2013 (c) Ink Applications LLC. All rights reserved.
 * @license MIT <http://opensource.org/licenses/MIT>
 */

namespace Ink\Haphazard\TestCase;

use Symfony\Bundle\FrameworkBundle\Client;
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
    /**
     * Clear login flag, will unset any roles to the logged in user by
     * completely unsetting the token from the security context.
     */
    const LOGIN_CLEAR = 'HAPHAZARD_LOGIN_CLEAR';

    /**
     * @var Client The browser simulator.
     */
    private $client;

    /**
     * Assert Get
     *
     * Asserts the response code of a web request matches what was expected.
     *
     * @param string $route The name of the controller route to test. such as
     *     'default' or 'index'.
     * @param array $parameters (optional) Parameters to pass into the route
     *     when resolving by name. The route parameters should be in
     *     key => value format.
     *
     *     example:
     *     ~~~
     *         array(
     *             'product_id' => 1,
     *             'page' => 3,
     *         );
     *     ~~~
     * @param int $status (optional) [Default: 200] The Response code that was
     *     expected back from the request. Typically 200 or 403.
     */
    protected function assertGet($route, $parameters = [], $status = 200)
    {
        $url = $this->getRouter()->generate($route, $parameters);

        $this->getClient()->request('GET', $url);

        $responseStatus = $this->getClient()->getResponse()->getStatusCode();
        $this->assertSame($status, $responseStatus);
    }

    /**
     * Login
     *
     * Fakes a logged in role with the application's security context.
     *
     * @param string $role The role id string to give to the user role. This
     *    can be any role identification string, such as 'ROLE_ADMIN'
     */
    protected function login($role = self::LOGIN_CLEAR)
    {
        $session = $this->getClient()->getContainer()->get('session');

        $firewall = 'secured_area';

        if (static::LOGIN_CLEAR === $role) {
            $session->set('_security_' . $firewall, null);
            return;
        }

        $token = new UsernamePasswordToken('test_user', null, $firewall, [$role]);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->getClient()->getCookieJar()->set($cookie);
    }

    /**
     * Get Client
     *
     * Gets the HTTP simulator client, creating it if it does not currently
     * exist.
     *
     * @return Client The HTTP simulator
     */
    protected final function getClient()
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        return $this->client;
    }

    /**
     * Get Router
     *
     * This method uses the HTTP client to get the router service.
     *
     * @return Router The Symfony Routing service
     */
    private function getRouter()
    {
        $client = $this->getClient();
        $container = $client->getContainer();
        $router = $container->get('router');

        return $router;
    }
}
