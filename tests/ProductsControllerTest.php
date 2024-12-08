<?php

use App\Controllers\AuthController;
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
    private $authController;
    private $mockDBConnection;
    private $user;

    protected function setUp(): void
    {
        $this->mockDBConnection = $this->createMock(DBConnection::class);

        $this->productsController = new ProductsController(
            $this->mockDBConnection
        );
        $this->authController = new AuthController(
            $this->mockDBConnection
        );
    }

    public function testProductListPage()
    {
        ob_start();
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'Admin';
        $_SESSION['role'] = 'admin';

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
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'Admin';
        $_SESSION['role'] = 'admin';

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

    public function testApiIndexReturnsProducts()
    {
        $_POST = ['email' => 'user@gmail.com', 'password' => 'password'];
        ob_start();
        $this->authController->apiLogin();
        $token_output = ob_get_clean();
        $token_obj = json_decode($token_output);
        $token = $token_obj->token;
        $url = 'http://127.0.0.1/api/products';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('data', $responseData);
    }
}
