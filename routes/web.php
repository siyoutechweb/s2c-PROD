<?php

/*
|--------------------------------------------------------------------------
| ro$routerlication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an ro$routerlication.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/gix',"ExampleController@index");
$router->get('/gix2',"ExampleController@order");

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/testlogin', ['uses' => 'AuthController@testlogin']);
$router->get('/manager/store', ['uses' => 'Store\ShopsController@getManagerStore']);
$router->get('/manager/activate/{id}', ['uses' => 'User\SignUpsController@activateManager']);
$router->get('/infos', ['uses' => 'User\UsersController@getCurrentUser']);
// $router->group(['middleware' => 'role:ShopManager'], function () use ($router) {
//     $router->get('/manager/store', ['uses' => 'Store\ShopsController@getManagerStore']);
// });


//funds routes
$router->post('warehouses/{chain_id}', ['uses' => 'Warehouse\WarehousesController@getWarehouseList']);
$router->get('/Chain/List', ['uses' => 'Store\ChainsController@getChainList']);
$router->get('/chains', ['uses' => 'Store\ChainsController@chainsWithManager']);
$router->get('/Categories/shop', ['uses' => 'Admin\CategoriesController@getCategoriesByShop']);
$router->get('/Categories/shop1', ['uses' => 'Admin\CategoriesController@getCategoriesByShopForWeb']);

$router->get('/Categories/system/shop', ['uses' => 'Admin\CategoriesController@getSystemCategoriesByShop']);
$router->get('/Categories/shop/choosen', ['uses' => 'Admin\CategoriesController@getChoosenCategories']);
$router->get('/paymentmethods', ['uses' => 'Order\OrdersController@getPaymentMethods']);
$router->get('/cachiers/list', ['uses' => 'User\UsersController@getCachiersList']);
$router->post('/category/add', ['uses' => 'Admin\CategoriesController@addCategoryByShop']);
$router->post('/category/update/{id}', ['uses' => 'Admin\CategoriesController@updateCategory']);//siyoumarket.uk
$router->post('/category/update', ['uses' => 'Admin\CategoriesController@updateownCategory']);//siyoumarket.uk
$router->delete('/category/delete/{id}', ['uses' => 'Admin\CategoriesController@deleteOwnSubCategory']);
//new
$router->post('/categorie/updateorder', ['uses' => 'Admin\CategoriesController@updateCategoryOrderByShopOwner']);
//
$router->get('/category/shop/myList', ['uses' => 'Admin\CategoriesController@getOwnCategory']);
$router->get('/suppliers/shop/myList', ['uses' => 'User\SuppliersController@getMySuppliersAdded']);
$router->get('/suppliers/system', ['uses' => 'User\SuppliersController@getSystemSuppliers']);
$router->get('/suppliers/shop/choosen', ['uses' => 'User\SuppliersController@getChoosenSuppliers']);
$router->post('/Member/upload', ['uses' => 'Member\MembersController@uploadMembers']);
$router->get('/suppliers/shopowners', ['uses' => 'User\SuppliersController@getAllShopOwnersSuppliers']);





$router->group(['prefix' => 'payment/method'], function () use ($router) {
    $router->post('/', ['uses' => 'Purchase\FundsController@addPaymentMethod']);
$router->get('/{id}', ['uses' => 'Purchase\FundsController@getPaymentMethodById']);
    $router->get('/', ['uses' => 'Purchase\FundsController@getPaymentMethods']);
$router->post('/{id}', ['uses' => 'Purchase\FundsController@editPaymentMethod']);
 $router->delete('/{id}', ['uses' => 'Purchase\FundsController@deletePaymentMethod']);

});
$router->group(['prefix' => '/quick/purchase'], function () use ($router) {
    $router->post('/', ['uses' => 'Purchase\Quick_purchase@newQuickPurcaseOrder']);
$router->get('/{id}', ['uses' => 'Purchase\Quick_purchase@getQuickOrderById']);
    $router->get('/', ['uses' => 'Purchase\Quick_purchase@getQuickOrders']);
$router->post('/product/delete/{id}', ['uses' => 'Purchase\Quick_purchase@deleteProductFromQuickOrder']);
$router->post('/product/update/{id}', ['uses' => 'Purchase\Quick_purchase@updateProductQuantityOfQuickOrder']);
$router->post('/order/delete/{id}', ['uses' => 'Purchase\Quick_purchase@deleteOrder']);


    $router->post('/confirm/{id}', ['uses' => 'Purchase\Quick_purchase@confirmOrder']);
$router->post('/stock/{id}', ['uses' => 'Purchase\Quick_purchase@confirmOrderAndAddToStock']);

      });

$router->group(['prefix' => 'payment/fund'], function () use ($router) {
    $router->post('/', ['uses' => 'Purchase\FundsController@addFund']);
$router->get('/list/{id}', ['uses' => 'Purchase\FundsController@getFundById']);
$router->get('/list', ['uses' => 'Purchase\FundsController@getFunds1']);
$router->post('/cancel/{id}', ['uses' => 'Purchase\FundsController@cancelFund']);
    $router->get('/', ['uses' => 'Purchase\FundsController@getFunds']);
    $router->post('/{id}', ['uses' => 'Purchase\FundsController@updateFund']);
    $router->delete('/{id}', ['uses' => 'Purchase\FundsController@deleteFund']);
    $router->get('/list1', ['uses' => 'Purchase\FundsController@getFundsForMobile']);
});
//checked and added toexcel
$router->group(['prefix' => 'promotion'], function () use ($router) {
$router->post('/update/{id}', ['uses' => 'Products\DiscountsController@updatePromotion']);
    $router->get('/list/{id}', ['uses' => 'Products\DiscountsController@getDiscountById']);
$router->get('/web', ['uses' => 'Products\DiscountsController@getDiscountProductsForWeb']);
$router->get('/nm', ['uses' => 'Products\DiscountsController@getDiscountProductsNByM']);
    $router->get('/history', ['uses' => 'Products\DiscountsController@getDiscountHistory']);
$router->post('/history/nm', ['uses' => 'Products\DiscountsController@getpromotionHistory']);
    $router->delete('/{id}', ['uses' => 'Products\DiscountsController@cancelPromotion']);
    $router->post('/remove', ['uses' => 'Products\DiscountsController@cancelPromotions']);
    $router->post('/', ['uses' => 'Products\DiscountsController@addPromotion']);
$router->post('/AddPromotion2', ['uses' => 'Products\DiscountsController@addPromotion2']);
$router->post('/bat', ['uses' => 'Products\DiscountsController@batchAddProductDiscount']);
    $router->get('/', ['uses' => 'Products\DiscountsController@getDiscountProducts']);
    $router->get('/products', ['uses' => 'Products\DiscountsController@getProducts']);
    $router->get('/discount/type', ['uses' => 'Products\DiscountsController@discountList']);
});

// $router->get('/key', function () {
//     return \Illuminate\Support\Str::random(32);
// });

// ShopOwner Routes 
$router->get('/db', ['uses' => 'AuthController@getBDAccess']);
$router->post('/SignUp', ['uses' => 'User\SignUpsController@addShopOwner']);
$router->put('/update/user', ['uses' => 'User\UsersController@updateUser']);
$router->post('/store', ['uses' => 'store\ShopsController@createStore']);
$router->post('/login', ['uses' => 'AuthController@login']);

// $router->group(['prefix' => 'Order'], function () use ($router) {
//     $router->get('/', ['uses' => 'Order\OrdersController@getOrders']);
// });

$router->get('/cachier/list', ['uses' => 'Store\CashRegistersController@getCashiersList']);
$router->get('/coupon/download', ['uses' => 'Store\CouponController@download']);
$router->get('/cachier/password', ['uses' => 'Store\CashRegistersController@getCashiersPassword']);
$router->get('/suppliers', ['uses' => 'User\SuppliersController@getSupplierList2']);
$router->delete('/suppliers/delete/{id}', ['uses' => 'User\SuppliersController@deleteOwnSupplier']);
$router->get('/suppliers/all', ['uses' => 'User\SuppliersController@getsuppliers']);
$router->post('/suppliers/assign', ['uses' => 'User\SuppliersController@addSuppliersToShop']);
$router->delete('/suppliers/remove/{id}', ['uses' => 'User\SuppliersController@removeSupplierFromShop']);
$router->get('/supplier/{id}', ['uses' => 'User\SuppliersController@getSupplierById']);
$router->post('/supplier', ['uses' => 'User\SuppliersController@addSupplier']);
$router->put('/supplier/{id}', ['uses' => 'User\SuppliersController@updateSupplier']);
$router->delete('/supplier/{id}', ['uses' => 'User\SuppliersController@deleteSupplier']);
$router->get('/menu', ['uses' => 'User\UsersController@getMenu']);
$router->post('/operator', ['uses' => 'User\SignUpsController@addOperators']);
$router->get('/operator/{id}', ['uses' => 'User\SignUpsController@getOperatorById']);
$router->post('/operator/update/{id}', ['uses' => 'User\SignUpsController@updateOperator']);
$router->get('/operators/list', ['uses' => 'User\UsersController@getOperatorsList']);
$router->group(['middleware' => 'validation'], function () use ($router) {
    $router->get('/status', ['uses' => 'Status\StatusController@getStatus']);
  
    $router->group(['prefix' => 'inventory'], function () use ($router) {
        $router->post('/', ['uses' => 'Warehouse\InventoriesController@newInventory']);
        $router->post('/mobile', ['uses' => 'Warehouse\InventoriesController@newInventoryMobile']);
        $router->post('/batch/number', ['uses' => 'Warehouse\InventoriesController@generateBatchNumber']);
        // $router->post('/tostock', ['uses' => 'Warehouse\InventoriesController@productToStock']);
        $router->get('/list/{warehouse_id}', ['uses' => 'Warehouse\InventoriesController@getInventories']);
        $router->get('/{id}', ['uses' => 'Warehouse\InventoriesController@getInventory']);
    $router->post('/batch', ['uses' => 'Warehouse\InventoriesController@getInventoryByBatchNumber']);
        $router->post('/batch', ['uses' => 'Warehouse\InventoriesController@getInventoryByBatchNumber']);
    $router->post('/batchweb', ['uses' => 'Warehouse\InventoriesController@getInventoryByBatchNumberForWeb']);
    $router->post('/products', ['uses' => 'Warehouse\InventoriesController@getInventoryProductsByBatchNumber']);
    $router->post('/batchlist', ['uses' => 'Warehouse\InventoriesController@getBatchNumberListByChainId']);
    $router->post('/chain', ['uses' => 'Warehouse\InventoriesController@getInventoriesByChain']);
        $router->put('/{id}', ['uses' => 'Warehouse\InventoriesController@updateInventory']);
        $router->post('/delete',['uses'=>'Warehouse\InventoriesController@deleteInventory']);
        $router->post('/stock',['uses'=>'Warehouse\InventoriesController@getInventoryProductList']);
    $router->post('/quick/{id}', ['uses' => 'Warehouse\InventoriesController@quickupdateInventory']);
    });
  
    $router->group(['middleware' => 'role:ShopOwner'], function () use ($router) {

        $router->post('/coupon/add',"Store\CouponController@add");
        $router->get('/dashboard', ['uses' => 'DashboardsController@dashboard']);
        
        $router->get('/store', ['uses' => 'Store\ShopsController@getStore']);
        $router->put('/store', ['uses' => 'Store\ShopsController@updatestore']);
        $router->post('/manager', ['uses' => 'User\SignUpsController@addShopManager']);
        
        $router->post('/cachier', ['uses' => 'User\SignUpsController@addCachiers']);
        //new
    $router->post('/cachier/operatorlog/add', ['uses' => 'Operator\LogsController@add']);
        $router->get('/cachier/operatorlog', ['uses' => 'Operator\LogsController@getOperatorlog']);
    //21-10-2020
    $router->get('/operatorLog', ['uses' => 'Operator\LogsController@getOperatorlog']);
        $router->post('/operatorLog', ['uses' => 'Operator\LogsController@add']);
        $router->post('/bill', ['uses' => 'Operator\BillController@add']);
        $router->get('/bill', ['uses' => 'Operator\BillController@billList']);
        $router->get('/cachier/{id}', ['uses' => 'User\SignUpsController@getCachierById']);
        $router->post('/cachier/update/{id}', ['uses' => 'User\SignUpsController@updateCachiers']);
    $router->delete('/cachier/delete/{id}', ['uses' => 'User\SignUpsController@deleteCachier']);

        //
        $router->get('/manager/{id}', ['uses' => 'User\SignUpsController@getManagerById']);
        $router->post('/manager/update/{id}', ['uses' => 'User\SignUpsController@updateShopManager']);
        $router->post('/manager/deactivate/{id}', ['uses' => 'User\SignUpsController@desactivateManager']);

        //new
        $router->get('/managers/list', ['uses' => 'User\UsersController@getManagersList']);
    $router->get('/managers/list1', ['uses' => 'User\UsersController@getManagersList1']);
    $router->get('/managers/list2', ['uses' => 'User\UsersController@getManagersList2']);

    
       // $router->get('/cachiers/list', ['uses' => 'User\UsersController@getCachiersList']);
        $router->get('/add/manager', ['uses' => 'User\UsersController@getManagerByEmail']);
        $router->get('/members/cart', ['uses' => 'Member\MembersController@generateMemberCode']);
    
        $router->group(['prefix' => 'Chain'], function () use ($router) {
            $router->post('/', ['uses' => 'Store\ChainsController@addChain']);
    $router->get('/List/{id}', ['uses' => 'Store\ChainsController@getChainById']);
    $router->post('/{id}', ['uses' => 'Store\ChainsController@updateChain']);


            
            $router->put('/Manager', ['uses' => 'Store\ChainsController@assignManagerToChain']);
            $router->put('/Cachier', ['uses' => 'Store\ChainsController@assignCachierToChain']);
        });
        

        $router->group(['prefix' => 'level'], function () use ($router) {
            $router->post('/', ['uses' => 'Member\LevelsController@addLevel']);
            $router->get('/list', ['uses' => 'Member\LevelsController@getLevelsList']);
            $router->delete('/{level_id}', ['uses' => 'Member\LevelsController@deleteLevel']);
            $router->get('/{level_id}', ['uses' => 'Member\LevelsController@getLevel']);
            $router->put('/{level_id}', ['uses' => 'Member\LevelsController@updateLevel']);
        });
        //tested
        $router->group(['prefix' => 'warehouses'], function () use ($router) {
            $router->post('/', ['uses' => 'Warehouse\WarehousesController@newWarehouse']);
            $router->get('/list/{id}', ['uses' => 'Warehouse\WarehousesController@getWarehouseById']);

            $router->put('/{id}', ['uses' => 'Warehouse\WarehousesController@updateWarehouse']);
            $router->delete('/{id}', ['uses' => 'Warehouse\WarehousesController@deleteWarehouse']);
            // $router->post('/batch/number', ['uses' => 'Warehouse\WarehousesController@generateBatchNumber']);
            // $router->post('/tostock', ['uses' => 'Warehouse\WarehousesController@productToStock']);
            
            // $router->put('/{level_id}', ['uses' => 'Warehouse\WarehousesController@updateLevel']);
        });
        //tested

        $router->group(['prefix' => 'funds',['middleware' => 'role:ShopOwner']], function () use ($router) {
            $router->get('/cash', ['uses' => 'Funds\FundsController@cashPayment']);
            $router->get('/card', ['uses' => 'Funds\FundsController@cardPayment']);
            $router->get('/check', ['uses' => 'Funds\FundsController@checkPayment']);
            $router->group(['prefix' => 'statistic',['middleware' => 'role:ShopOwner']], function () use ($router) {
                $router->get('/cash', ['uses' => 'Funds\FundsController@cashFund']);
                $router->get('/card', ['uses' => 'Funds\FundsController@cardFund']);
                $router->get('/check', ['uses' => 'Funds\FundsController@checkFund']);
            });
        });
    });

    $router->group(['middleware' => 'role:ShopOwner,ShopManager,operator'], function () use ($router) {
        $router->get('/Cachier', ['uses' => 'User\UsersController@getCachierByEmail']);//
        $router->get('/Categories', ['uses' => 'Products\GetProductsController@getProductsCategories']);
        $router->get('/Chain', ['uses' => 'Store\ChainsController@getChain']);//tested server
        
        $router->group(['prefix' => 'Member'], function () use ($router) {
            $router->post('/', ['uses' => 'Member\MembersController@addMembre']);
            $router->post('/update/{id}', ['uses' => 'Member\MembersController@updateMember']);
            $router->get('/list/{id}', ['uses' => 'Member\MembersController@getMemberById']);
            $router->delete('/{id}', ['uses' => 'Member\MembersController@deleteMember']);
            $router->get('/', ['uses' => 'Member\MembersController@getMembersList']);
            $router->get('/invalid', ['uses' => 'Member\MembersController@getInvalidMembers']);
            $router->put('/{id}', ['uses' => 'Member\MembersController@activateMemberCard']);            });
        //tested
        $router->group(['prefix' => 'Product'], function () use ($router) {
            $router->post('/', ['uses' => 'Products\ProductsController@addProduct']);
    $router->post('/transfert', ['uses' => 'Products\ProductsController@transfertProductBetweenChains']);
    $router->post('/return', ['uses' => 'Products\ProductsController@addProductReturn']);
            $router->post('/upload', ['uses' => 'Products\ProductsController@uploadProducts']);
    $router->delete('/DeleteLabel', ['uses' => 'Products\ProductsController@quickPrintDelete']);//chineese team
    $router->post('/Deletediscount', ['uses' => 'Products\ProductsController@quickDiscountDelete']);//chineese team
    $router->get('/web', ['uses' => 'Products\ProductsController@getProductsByShopOwner']);//chineese team
    $router->post('/return1', ['uses' => 'Products\ProductsController@addProductReturn1']);
            $router->post('/bat', ['uses' => 'Products\ProductsController@batchAddProduct']);//chineese team
            $router->post('/update', ['uses' => 'Products\ProductsController@updateProduct']);
    $router->get('/List2', ['uses' => 'Products\GetProductsController@chainProductList2']);
            $router->get('/List1', ['uses' => 'Products\GetProductsController@chainProductList']);
    $router->get('/List', ['uses' => 'Products\GetProductsController@chainProductList1']);
    $router->get('/all/{id}', ['uses' => 'Products\GetProductsController@getProductById']);
    $router->get('/All1', ['uses' => 'Products\GetProductsController@shopProductList1']);
            $router->get('/all', ['uses' => 'Products\GetProductsController@shopProductList']);
    $router->get('/return', ['uses' => 'Products\ProductsController@getProductsReturn']);
            $router->get('/', ['uses' => 'Products\GetProductsController@getProduct']);
            $router->get('/Barcode', ['uses' => 'Products\GetProductsController@generateBarcode']);
            $router->delete('/', ['uses' => 'Products\ProductsController@deleteProduct']); 
            $router->get('/{id}', ['uses' => 'Products\ProductsController@getProduct']);
    $router->post('/web/{id}', ['uses' => 'Products\ProductsController@getProductweb']);
    $router->post('/expiration', ['uses' => 'Products\GetProductsController@shopProductListExpiration']);
    $router->post('/warn', ['uses' => 'Products\GetProductsController@shopProductListWarningQuantity']);
            $router->delete('/', ['uses' => 'Products\ProductsController@deleteProduct']);
            $router->get('/{id}', ['uses' => 'Products\ProductsController@getProduct']);
            $router->post('/label', ['uses' => 'Products\GetProductsController@getLabels1']);
    $router->post('/label1', ['uses' => 'Products\GetProductsController@getLabels1']);

            $router->delete('/deleteLabels', ['uses' => 'Products\GetProductsController@deleteLabels']);
        });
        //tested
        $router->group(['prefix' => 'quick'], function () use ($router) {
            $router->post('/scan', ['uses' => 'Products\ProductsController@quickScanForPrint']);
            $router->get('/print', ['uses' => 'Products\ProductsController@quickPrint']);
            $router->post('/scan/discount', ['uses' => 'Products\ProductsController@quickScanForDiscount']);
            $router->get('/discount', ['uses' => 'Products\ProductsController@quickDiscount']);
        });

        $router->group(['prefix' => 'orders'], function () use ($router) {
            $router->post('/', ['uses' => 'Order\OrdersController@addOrderszh']);
            $router->post('/test', ['uses' => 'Order\OrdersController@addOrders1']);
            $router->get('/list', ['uses' => 'Order\OrdersController@getOrderList']);
            $router->get('/list1', ['uses' => 'Order\OrdersController@getOrderList2']);
    $router->get('/list2', ['uses' => 'Order\OrdersController@getOrderList2']);

            $router->post('/items', ['uses' => 'Order\OrdersController@getOrderItemsDetail']);
        });
        // $router->group(['prefix' => 'products'], function () use ($router) {
        //     $router->post('/', ['uses' => 'Products\ProductBasesController@addProductBase']);
        //     $router->put('/', ['uses' => 'Products\ProductBasesController@updateProductBase']);
        //     $router->get('/list', ['uses' => 'Products\ProductBasesController@getProductList']);
        //     $router->get('/', ['uses' => 'Products\ProductBasesController@getProduct']);
        //     $router->delete('/', ['uses' => 'Products\ProductBasesController@deleteProductBase']);
        //     $router->get('/Search', ['uses' => 'Products\ProductBasesController@searchProduct']);
        // });
        // $router->get('/Category', ['uses' => 'Products\ProductBasesController@getProductsCategories']);
        
        // $router->group(['prefix' => 'items'], function () use ($router) {
        //     $router->post('/', ['uses' => 'Products\ProductItemsController@addItem']);
        //     $router->post('/criteria', ['uses' => 'Products\ProductItemsController@addItemCriteria']);
        //     $router->put('/', ['uses' => 'Products\ProductItemsController@updateItem']);
        //     $router->put('/criteria', ['uses' => 'Products\ProductItemsController@updateItemCriteria']);
        //     $router->delete('/', ['uses' => 'Products\ProductItemsController@deleteItem']);
        //     $router->delete('/criteria', ['uses' => 'Products\ProductItemsController@deleteItemCriteria']);
        //     $router->get('/', ['uses' => 'Products\ProductItemsController@getItem']);
        //     $router->get('/list', ['uses' => 'Products\ProductItemsController@getProductItemList']);
        // });
        $router->post('/uploadImages', ['uses' => 'Products\ItemImagesController@uploadImages']);
        $router->delete('/deleteImages', ['uses' => 'Products\ItemImagesController@deleteImages']);

       

    });
});




// Admin Routes

$router->group(['middleware' => 'role:SuperAdmin'], function () use ($router) {

    $router->group(['prefix' => 'Accounts'], function () use ($router) {
        $router->get('/Inactive', ['uses' => 'Admin\AccountsController@getInactiveAccount']);
        $router->get('/Active', ['uses' => 'Admin\AccountsController@getactiveAccount']);
        $router->put('/Activate', ['uses' => 'Admin\AccountsController@activeAccount']);
        $router->put('/Desactivate', ['uses' => 'Admin\AccountsController@desactiveAccount']);
    });

    $router->group(['prefix' => 'discount'], function () use ($router) {
        $router->post('/', ['uses' => 'Admin\DiscountTypesController@addType']);
        $router->get('/{type_id}', ['uses' => 'Admin\DiscountTypesController@getType']);
        $router->delete('/{type_id}', ['uses' => 'Admin\DiscountTypesController@deleteType']);
        $router->put('/{type_id}', ['uses' => 'Admin\DiscountTypesController@updateType']);

    });

    $router->group(['prefix' => 'brand'], function () use ($router) {
        $router->post('/', ['uses' => 'Admin\BrandsController@addBrand']);
        $router->put('/', ['uses' => 'Admin\BrandsController@updateBrand']);
        $router->delete('/', ['uses' => 'Admin\BrandsController@deleteBrand']);
    });
    $router->group(['prefix' => 'criteria'], function () use ($router) {
        
        $router->post('/', ['uses' => 'Admin\CriteriaBasesController@addCriteria']);
        $router->post('/unit', ['uses' => 'Admin\CriteriaUnitsController@addCriteriaUnit']);
        $router->put('/{id}/unit', ['uses' => 'Admin\CriteriaUnitsController@updateUnit']);
        $router->put('/{id}', ['uses' => 'Admin\CriteriaBasesController@updateCriteria']);
        $router->delete('/{id}/unit', ['uses' => 'Admin\CriteriaUnitsController@deleteUnit']);
        $router->delete('/{id}', ['uses' => 'Admin\CriteriaBasesController@deleteCriteria']);
        
    });

    $router->group(['prefix' => 'Categories'], function () use ($router) {
        $router->post('/', ['uses' => 'Admin\CategoriesController@addCategory']);
        $router->put('/', ['uses' => 'Admin\CategoriesController@updateCategory']);
        $router->Delete('/{id}', ['uses' => 'Admin\CategoriesController@deleteCategory']);
    });
});
$router->group(['middleware' => 'role:SuperAdmin,ShopOwner'], function () use ($router) {
 
$router->group(['prefix' => 'Categories'], function () use ($router) {
    $router->post('/', ['uses' => 'Admin\CategoriesController@addCategory']);
    $router->post('/assign', ['uses' => 'Admin\CategoriesController@addCategoriesToShop']);
   
$router->delete('/shop/remove/{id}', ['uses' => 'Admin\CategoriesController@removeCategoryFromShop']);
    
});
});
$router->group(['middleware' => 'role:SuperAdmin,ShopOwner,ShopManager'], function () use ($router) {
    $router->get('/brand/list', ['uses' => 'Admin\BrandsController@getBrandList']);
    $router->get('/brand', ['uses' => 'Admin\BrandsController@getBrand']);
    $router->get('/criteria/list', ['uses' => 'Admin\CriteriaBasesController@getCriteriaList']);
    $router->get('/criteria', ['uses' => 'Admin\CriteriaBasesController@getCriteria']);
    $router->get('/criteria/unit', ['uses' => 'Admin\CriteriaUnitsController@getUnit']);
    $router->get('/category/list', ['uses' => 'Admin\CategoriesController@getCategories']);
    $router->get('/categories/{id}', ['uses' => 'Admin\CategoriesController@getCategorieById']);
});

// $router->group(['prefix' => 'Online'], function () use ($router) {
//     $router->get('/products', ['uses' => 'Online\ProductsController@getOnlineProducts']);
//     $router->post('/paypal', ['uses' => 'Order\PaypalPaymentsController@payWithpaypal']);
// });




/**
 * Routes for resource store/-cash-register
 */
$router->group(['prefix' => 'cash/register',['middleware' => 'role:SuperAdmin,ShopOwner,ShopManager']], function () use ($router) {
    $router->post('/', ['uses' => 'Store\CashRegistersController@addCashRegister']);
    $router->post('/validation', ['uses' => 'Store\CashRegistersController@validateCashRegister']);
    $router->post('/reference', ['uses' => 'Store\CashRegistersController@validateCashRegister']);
});


/**
* Routes for resource purchase/-order
*/
$router->group(['prefix' => 'purchase',['middleware' => 'role:ShopOwner,ShopManager']], function () use ($router) {
$router->get('/order', ['uses' => 'Purchase\OrdersController@getPreOrder']);
$router->post('/upload/order', ['uses' => 'Purchase\OrdersController@preOrderUpload']);
//$router->post('/upload/ordermobile', ['uses' => 'Purchase\OrdersController@preOrderUpload']);
$router->get('/order/{oreder_ref}', ['uses' => 'Purchase\OrdersController@searchPreOrder']);
$router->post('/order/create', ['uses' => 'Purchase\OrdersController@preOrderCreate']);
});

//chineese Team

/**
* routes for shop owener statistics
*/


$router->group(['prefix' => 'stat', ['middleware' => 'role:ShopOwner,ShopManager']], function () use ($router) {

$router->post('/orders', 'StatisticsController@orders');
$router->post('/fund', 'StatisticsController@fund');
$router->post('/fund1', 'StatisticsController@fund1');
$router->post('/fund/purchase', 'StatisticsController@purchasedFunds');
$router->post('/stock', 'StatisticsController@stock');
$router->post('/products', 'StatisticsController@products');
$router->post('/kpi', 'StatisticsController@kpi');
$router->post('/order/amount', 'StatisticsController@orderAmount');
$router->post('/order/amount1', 'StatisticsController@orderAmount1');
$router->post('/order/amount/shop', 'StatisticsController@orderAmountByShop1');
$router->post('/order/amount/cashier', 'StatisticsController@orderAmountByCachier1');
$router->post('/order/amount/type', 'StatisticsController@orderAmountByPaymentMethod');
$router->post('/sale/discount/amount1', 'StatisticsController@discountAmount1');
$router->post('/order/quantity1', 'StatisticsController@orderQuantity1');
$router->post('/order/quantity', 'StatisticsController@orderQuantity');
$router->post('/order/supplier', 'StatisticsController@orderSupplier');
$router->get('/test', "StatisticsController@test");
$router->post('/product/quantity', 'StatisticsController@productQuantity');
$router->post('/product/quantity1', 'StatisticsController@productQuantity1');
$router->post('/sale/quantity', 'StatisticsController@saleQuantity');
$router->post('/sale/discount/amount', 'StatisticsController@discountAmount');
$router->post('/sale/top/products', 'StatisticsController@topProducts');
$router->post('/sale/hot/category', 'StatisticsController@hotCategoryProducts');
$router->post('/sale/top/suppliers', 'StatisticsController@topProductsSuppliers');
$router->post('/sale/turnover', 'StatisticsController@Turnover');
$router->post('/sale/warning/quantity', 'StatisticsController@WarningQuantity');
$router->post('/member/amount', 'StatisticsController@memberAmount');
$router->post('/member/quantity', 'StatisticsController@memberQuantity');
$router->post('/store/quantity', 'StatisticsController@storeQuantity');
$router->post('/store/manager/quantity', 'StatisticsController@storeQuantity');
$router->post('/discounted/quantity','StatisticsController@discountedQuantity');
$router->post('/gross/profit','StatisticsController@grossProfit');
$router->post('/gross/profit1','StatisticsController@grossProfit1');

$router->post('/purchase/quantity','StatisticsController@purchaseQuantity');
$router->post('/purchase/amount','StatisticsController@purchaseAmount');
$router->post('/refund/quantity','StatisticsController@refundQuantity');
$router->post('/refund/amount','StatisticsController@refundAmount');
$router->post('/promotional/quantity','StatisticsController@promotionalQuantity');
$router->post('/promotional/amount','StatisticsController@promotionalAmount');

});// /**
//  * Routes for resource member-controller
//  */
// $router->get('member-controller', 'MemberControllersController@all');
// $router->get('member-controller/{id}', 'MemberControllersController@get');
// $router->post('member-controller', 'MemberControllersController@add');
// $router->put('member-controller/{id}', 'MemberControllersController@put');
// $router->delete('member-controller/{id}', 'MemberControllersController@remove');

// /**
//  * Routes for resource statistics
//  */
// // $router->get('statistics', 'StatisticsController@all');
// // $router->get('statistics/{id}', 'StatisticsController@get');
// // $router->post('statistics', 'StatisticsController@add');
// // $router->put('statistics/{id}', 'StatisticsController@put');
// // $router->delete('statistics/{id}', 'StatisticsController@remove');

// /**
//  * Routes for resource store/-coupon-controller
//  */
// $router->get('store/-coupon-controller', 'Store/CouponController@all');
// $router->get('store/-coupon-controller/{id}', 'Store/CouponController@get');
// $router->post('store/-coupon-controller', 'Store/CouponController@add');
// $router->put('store/-coupon-controller/{id}', 'Store/CouponController@put');
// $router->delete('store/-coupon-controller/{id}', 'Store/CouponController@remove');

/**
* Routes for resource access-rights
*/
// $app->get('access-rights', 'AccessRightsController@all');
// $app->get('access-rights/{id}', 'AccessRightsController@get');
// $app->post('access-rights', 'AccessRightsController@add');
// $app->put('access-rights/{id}', 'AccessRightsController@put');
// $app->delete('access-rights/{id}', 'AccessRightsController@remove');



//advertisement routes available for shop_owner and super_admin

$router->group(['prefix' => 'ads',['middleware' => 'role:SuperAdmin,ShopOwner']], function () use ($router) {
$router->post('/', ['uses' => 'AdvertisementsController@newAdvertisement']);
$router->get('/', ['uses' => 'AdvertisementsController@getAdvertisementList']);
$router->get('/{id}', ['uses' => 'AdvertisementsController@getAdvertisementById']);
$router->delete('/{id}', ['uses' => 'AdvertisementsController@deleteAdvertisement']);

});
$router->group(['prefix' => 'licence',['middleware' => 'role:SuperAdmin']], function () use ($router) {
$router->post('/', ['uses' => 'LicencesController@newLicence']);
$router->get('/', ['uses' => 'LicencesController@getLicencesList']);
$router->get('/{id}', ['uses' => 'LicencesController@getLicenceById']);
$router->put('/{id}', ['uses' => 'LicencesController@updateLicence']);

$router->delete('/{id}', ['uses' => 'LicencesController@deleteLicence']);

});
$router->get('/licences', ['uses' => 'LicencesController@getLicencesList1']);
$router->get('/shopowners', ['uses' => 'LicencesController@getAllShopOwners']);
$router->get('/user/licence', ['uses' => 'LicencesController@getMyLicense']);
$router->get('/shop/chain/get', ['uses' => 'Store\ShopChainFunctionController@getShopChainFunction']);
$router->post('/shop/chain/save', ['uses' => 'Store\ShopChainFunctionController@add']);
$router->get('/version','Download\GetAppController@download');
$router->post('/version','Download\GetAppController@update');
