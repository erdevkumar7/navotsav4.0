<?php

use App\Http\Controllers\api\FirebaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\EventController;
use App\Http\Controllers\api\ForgotPasswordController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\TicketPurchaseController;
use App\Http\Controllers\api\WebLeadController;
use App\Http\Controllers\api\PhonePayPaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PhonePeController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebviewController;

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->middleware('isSuspend');


    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [ProfileController::class, 'me'])->middleware('auth:sanctum');
    Route::post('profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');

    Route::post('social-login', [FirebaseAuthController::class, 'login']);
});


// Event info screen API
Route::get('event-screen', [WebviewController::class, 'eventScreenData']);


// stripe webhook

Route::post('/stripe/webhook', [WebhookController::class, 'handle']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::get("/events", [EventController::class, 'index']);
Route::get("/past-events", [EventController::class, 'pastEvents']);
Route::get("/events/search", [EventController::class, 'search']);

Route::get('/event/categories', [EventController::class, 'eventCategories']);
Route::get("/event/{event}", [EventController::class, 'eventDetail'])->whereNumber('event');
Route::get("/event/{event}/collected-amount", [EventController::class, 'collectedAmount'])->whereNumber('event');

Route::get("/blogs", [WebLeadController::class, 'blogs']);

Route::get("/blogs/{id}", [WebLeadController::class, 'blogInfo'])->whereNumber('id');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/register-device-token', [ProfileController::class, 'registerDevice']);

    Route::post('profile/avatar', [ProfileController::class, 'uploadAvatar']);

    Route::get('notifications', [ProfileController::class, 'notifications']);

    Route::prefix('event')->group(function () {

        Route::post("/create", [EventController::class, 'create']);
        Route::post('/{event}/pause', [EventController::class, 'pause']);
        Route::post('/{event}/resume', [EventController::class, 'resume']);

        Route::post("/{event}/favourite", [EventController::class, 'favourite'])->whereNumber('event');

        Route::get("/favourite-events", [EventController::class, 'favouriteEvent']);

        Route::post('/reserve-ticket', [TicketPurchaseController::class, 'reserve']);
        Route::get('/{eventId}/reserve-ticket', [TicketPurchaseController::class, 'reserveTickets'])->whereNumber('event');
        Route::post('/book-ticket', [TicketPurchaseController::class, 'booking']);

        Route::post('/booking-from-pos', [TicketPurchaseController::class, 'bookingFromPos'])->middleware('isSuspend');
        Route::get('/ticket-history', [TicketPurchaseController::class, 'myTickets']);
    });

    Route::post('send-tickets', [ProfileController::class, 'sendTickets']);

    Route::post('claim-prize', [ProfileController::class, 'claimRequest']);
});


Route::post("/contact-lead", [WebLeadController::class, 'contactLead']);


// -----------------------------PhonePayPaymentController-----------------------------

// Route::post('/create-order', [PhonePayPaymentController::class, 'createOrder']);
// Route::post('/verify-payment', [PhonePayPaymentController::class, 'verifyPayment']);
// Route::post('/process-card-payment', [PhonePayPaymentController::class, 'processCardPayment']);
// Route::get('/payment-status/{orderId}', [PhonePayPaymentController::class, 'paymentStatus']);

// Route::post('/generate-upi-qr', [PhonePayPaymentController::class, 'generateUpiQr']);

// // Webhook and callback routes (no auth required)
// Route::post('/payment-webhook', [PhonePayPaymentController::class, 'paymentWebhook']);
// Route::get('/payment-callback', [PhonePayPaymentController::class, 'paymentCallback']);

// Route::post("/phonepe/initiate", [PhonePeController::class, "initiatePayment"]);
// Route::get("/phonepe/status/{transactionId}", [PhonePeController::class, "checkStatus"]);

// -----------------------------PhonePayPaymentController-----------------------------


Route::post('/offline-booking', [PhonePayPaymentController::class, 'offlineBooking']);

Route::post('/get-pass', [PaymentController::class, 'getPassByMobile']);

Route::post('/razorpay/order', [PaymentController::class, 'createOrder']);
Route::post('/razorpay/verify', [PaymentController::class, 'verifyPayment']);
