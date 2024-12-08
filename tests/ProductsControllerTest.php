<?php

use App\Models\User;
use App\DBConnection;
use App\Models\Product;
use App\Helpers\Response;
use App\Models\Transaction;
use PHPUnit\Framework\TestCase;
use App\Controllers\ProductsController;

class ProductsControllerTest extends TestCase
{
    private $productsController;
    private $mockDBConnection;

    protected function setUp(): void
    {
        $this->mockDBConnection = $this->createMock(DBConnection::class);

        $this->productsController = new ProductsController(
            $this->mockDBConnection
        );
    }

    function header($string, $replace = true, $http_response_code = null)
    {
        PHPUnit\Framework\Assert::assertTrue(true);
    }


    public function testProductListPage()
    {
        ob_start();
        $user = User::findByEmail('admin@gmail.com');
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $this->productsController->index();
        $output = ob_get_clean();
        $this->assertStringContainsString('Product List', $output);
        if (ob_get_level() > 0) {
            ob_flush();
        }
    }

    public function testProductCreatePage()
    {
        ob_start();
        $user = User::findByEmail('admin@gmail.com');
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $this->productsController->create();
        $output = ob_get_clean();
        $this->assertStringContainsString('Product Create', $output);
        if (ob_get_level() > 0) {
            ob_flush();
        }
    }

    public function testStoreRedirectsToCreatePageWhenNameIsEmpty()
    {
        $_POST = ['price' => '10.00', 'quantity' => '5'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();

        $this->assertContains("Product name is required.", $_SESSION['errors']);
    }

    public function testStoreRedirectsToCreatePageWhenPriceIsEmpty()
    {
        $_POST = ['name' => 'Test Product', 'quantity' => '5'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertContains("Price is required.", $_SESSION['errors']);
    }

    public function testStoreRedirectsToCreatePageWhenPriceIsNotNumeric()
    {
        $_POST = ['name' => 'Test Product', 'price' => 'abc', 'quantity' => '5'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertContains("Price must be a number.", $_SESSION['errors']);
    }

    public function testStoreRedirectsToCreatePageWhenPriceIsZero()
    {
        $_POST = ['name' => 'Test Product', 'price' => '-1', 'quantity' => '5'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertContains("Price must be at least $0.01.", $_SESSION['errors']);
    }

    public function testStoreRedirectsToCreatePageWhenQuantityIsNotSet()
    {
        $_POST = ['name' => 'Test Product', 'price' => '10.00'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertContains("Quantity is required.", $_SESSION['errors']);
    }

    public function testStoreRedirectsToCreatePageWhenQuantityIsNotNumeric()
    {
        $_POST = ['name' => 'Test Product', 'price' => '10.00', 'quantity' => 'abc'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertContains("Quantity must be a number.", $_SESSION['errors']);
    }

    public function testStoreRedirectsToCreatePageWhenQuantityIsZero()
    {
        $_POST = ['name' => 'Test Product', 'price' => '10.00', 'quantity' => '0'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertContains("Quantity must be 0 or more.", $_SESSION['errors']);
    }

    public function testStoreCreatesProductAndRedirectsWhenValidDataProvided()
    {
        $_POST = ['name' => 'Test Product', 'price' => '10.00', 'quantity' => '5'];
        $_SESSION = ['role' => 'admin'];
        $this->productsController->store();
        $this->assertEmpty($_SESSION['errors'] ?? null);
        $this->assertEquals("Product Created Successfully.", $_SESSION['success']);
    }
}
