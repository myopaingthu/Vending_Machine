<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ProductsController;
use App\DBConnection;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

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


    public function testIndexRetrievesProducts()
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

    //    public function testPurchaseValidatesQuantity()
    //    {
    //        $_POST['quantity'] = -1;
    //
    //        $this->expectException(Exception::class);
    //        $this->expectExceptionMessage('Quantity must be 0 or more.');
    //
    //        $this->productsController->purchase(1);
    //    }
    //
    //    public function testApiPurchaseThrowsExceptionForInvalidProduct()
    //    {
    //        $this->mockProductModel->expects($this->once())
    //            ->method('find')
    //            ->willReturn(null);
    //
    //        $this->expectException(Exception::class);
    //        $this->expectExceptionMessage('Product not found.');
    //
    //        $this->productsController->apiPurchase(1);
    //    }
}
