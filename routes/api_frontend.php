<?php


use App\Http\Controllers\Frontend\BannerController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CollectionController;
use App\Http\Controllers\Frontend\CommonController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\InvoiceController;
use App\Http\Controllers\Frontend\OfferController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReviewController;
use Illuminate\Support\Facades\Route;



// frontend api routes
// * common routes
Route::get('/app-header-data', [CommonController::class, 'appHeaderData']);
Route::get('/app-footer-data', [CommonController::class, 'appFooterData']);
Route::get('/all-category-get-for-manus', [CommonController::class, 'get']);
Route::get('/get-carts/{user_id}', [CommonController::class, 'getCarts']);
Route::post('/product-add-to-cart/{user_id}', [CommonController::class, 'storeInCart']);
Route::post('/cart-product-qty-increment/{id}', [CommonController::class, 'quantityIncrement']);
Route::post('/cart-product-qty-decrement/{id}', [CommonController::class, 'quantityDecrement']);
Route::get('/cart-product-count/{user_id}', [CommonController::class, 'countCartProducts']);
Route::get('/remove-product-in-cart-list/{id}', [CommonController::class, 'removeCartProduct']);
Route::get('/get-favorites/{user_id}', [CommonController::class, 'getFavorites']);
Route::post('/product-add-to-favorite/{user_id}', [CommonController::class, 'storeInFavorite']);
Route::get('/favorite-product-count/{user_id}', [CommonController::class, 'countFavoriteProducts']);
Route::get('/remove-product-in-favorite-list/{id}', [CommonController::class, 'removeFavoriteProduct']);

// * profile routes
Route::get('/profile/{id}', [ProfileController::class, 'get']);
Route::post('/profile-update/{id}', [ProfileController::class, 'update']);

// * home routes
Route::get('/banner', [BannerController::class, 'get']);
Route::get('/our-categories', [CategoryController::class, 'get']);
Route::get('/offer-banners', [OfferController::class, 'get']);
Route::get('/collections', [CollectionController::class, 'get']);
Route::get('/all-products-get', [ProductController::class, 'get']);
Route::get('/modal-product/{id}', [ProductController::class, 'modalDetailsShow']);
Route::get('/product-show/{slug}', [ProductController::class, 'productShow']);
Route::get('/all-offers-get', [OfferController::class, 'offerGet']);
Route::get('/get-category-id', [ProductController::class, 'categoryId']);


// * contact routes
Route::post('/contact-store', [ContactController::class, 'store']);

// * checkout route
Route::post('/checkout/{user_id}', [CheckoutController::class, 'checkOut']);

// * order item route
Route::get('/user-order-details/{user_id}', [OrderController::class, 'getUserOrderDetails']);

// * order history route
Route::get('/user-orders/{user_id}', [OrderController::class, 'userOrders']);
Route::get('/product-reviews/{userId}/{orderId}/{invoiceProductId}', [ReviewController::class, 'reviews']);
Route::post('/product-review-send', [ReviewController::class, 'send']);

// * invoice create route
Route::post('/invoice-create/{user_id}', [InvoiceController::class, 'createInvoice']);
