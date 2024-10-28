<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// ========================== HOME =============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'about'])->name('about');
Route::get('/products', [ProductsController::class, 'products'])->name('products');
Route::get('/about', [AboutController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
Route::get('/blog', [BlogController::class, 'blog'])->name('blog');
Route::get('/package', [PackageController::class, 'package'])->name('package');
Route::get('/products/{id}', [ProductsController::class, 'detail'])->name('detail');
Route::post('/product/{id}/comment', [ProductsController::class, 'comment'])->name('product.comment');


//cart
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
});

//checkout
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('show');
Route::get('/showbill', [OrderController::class, 'showbill'])->name('showbill');

//booking

Route::get('/booking', [ServiceController::class, 'index'])->name('booking.index');
Route::post('/select-package', [ServiceController::class, 'selectPackage'])->name('select.package');
Route::post('/book-appointment', [BookingController::class, 'booking'])->name('book.appointment');

//user
Route::get('register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('register', [UserController::class, 'register']);
Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::get('/profile/edit/{id}', [UserController::class, 'edit_profile'])->name('edit_profile');
Route::put('/profile/update/{id}', [UserController::class, 'update_profile'])->name('update_profile');
// Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
// Route::post('/forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');


// ========================== ADMIN =============================
Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
Route::get('/search_admin', [AdminController::class, 'search_admin'])->name('search_admin');

//products
Route::get('/productadmin', [AdminController::class, 'productadmin'])->name('productadmin');
Route::post('/admin/products', [AdminController::class, 'add_product'])->name('add_product');
Route::delete('productadmin/{id}', [AdminController::class, 'delete_product'])->name('delete_product');
Route::get('/admin/products/{id}/edit', [AdminController::class, 'edit_product'])->name('edit_product');
Route::put('/admin/products/{id}', [AdminController::class, 'update_product'])->name('update_product');

// category
Route::get('/categoryadmin', [AdminController::class, 'categoryadmin'])->name('categoryadmin');
Route::post('/admin/category', [AdminController::class, 'add_category'])->name('add_category');
Route::delete('categoryadmin/{id}', [AdminController::class, 'delete_category'])->name('delete_category');
Route::get('/admin/category/{id}/edit', [AdminController::class, 'edit_category'])->name('edit_category');
Route::put('/admin/category/{id}', [AdminController::class, 'update_category'])->name('update_category');

//user
Route::get('/useradmin', [AdminController::class, 'useradmin'])->name('useradmin');
Route::post('/admin/useradmin', [AdminController::class, 'add_user'])->name('add_user');
Route::delete('useradmin/{id}', [AdminController::class, 'delete_user'])->name('delete_user');
Route::get('/admin/useradmin/{id}/edit', [AdminController::class, 'edit_user'])->name('edit_user');
Route::put('/admin/useradmin/{id}', [AdminController::class, 'update_user'])->name('update_user');

// category_sv
Route::get('/category_sv_admin', [AdminController::class, 'category_sv_admin'])->name('category_sv_admin');
Route::post('/admin/category_sv', [AdminController::class, 'add_category_sv'])->name('add_category_sv');
Route::delete('category_sv_admin/{id}', [AdminController::class, 'delete_category_sv'])->name('delete_category_sv');
Route::get('/admin/category_sv/{id}/edit', [AdminController::class, 'edit_category_sv'])->name('edit_category_sv');
Route::put('/admin/category_sv/{id}', [AdminController::class, 'update_category_sv'])->name('update_category_sv');

//order
Route::get('/admin/order', [AdminController::class, 'order'])->name('order');
Route::patch('/admin/order/{orderId}/status', [AdminController::class, 'updateStatus'])->name('admin.order.updateStatus');







