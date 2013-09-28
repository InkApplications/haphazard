Haphazard
=========

Haphazard is a library extension to Symfony's WebTestCase that provides a quick
and dirty method to functional test basic pages.

Installation
------------

You can install Haphazard through composer:

    composer.phar require ink/haphazard

Usage
-----

### Simple page load test

When all you want to test is that the page at *least* loads properly, you can
use the `HaphazardTestCase::assertGet()` method. This will create a web client
and assert that a response code of 200 is returned for the page.

    use Ink\Haphazard\TestCase\HaphazardTestCase;

    class ProductControllerTest extends HaphazardTestCase
    {
        /**
         * Test Index Action
         */
        public function testIndexAction()
        {
            $this->assertGet('index');
        }
    }

### Page with parameters

If the page you're testing requires parameters, you can pass those in as well.

    /**
     * Test View Action
     */
    public function testViewAction()
    {
        $this->assertGet('product-view', ['productId' => 1]);
    }

### Different Status codes

Occasionally you will want to test that pages return a different status code,
for example a 403 / Forbidden status code when an anonymous user should *not*
be able to access a given page.

    /**
     * Test Edit Action
     */
    public function testEditAction()
    {
        $this->assertGet('product-edit', ['productId' => 1], 403);
    }

### Spoof Authentication Role

In order to effectively test that pages are open / closed to the correct users,
this library provides an easy way to make assertions using a specified role.

    /**
     * Test Edit Action
     */
    public function testEditAction()
    {
        // Allow our Admin role
        $this->login('ROLE_ADMIN');
        $this->assertGet('product-edit', ['productId' => 1], 200);

        // Disallow Anonymous users
        $this->login(HaphazardTestCase::LOGIN_CLEAR);
        $this->assertGet('product-edit', ['productId' => 1], 403);
    }
