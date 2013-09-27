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
            $this->assertGet('product-edit', ['productId' => 1]);
        }
