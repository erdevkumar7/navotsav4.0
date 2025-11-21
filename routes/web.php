<?php

use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ClaimPrizeController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\RolePermissionController;
use App\Http\Controllers\admin\SalesTransactionController;
use App\Http\Controllers\admin\TicketController;
use App\Http\Controllers\admin\UserManagementController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WebviewController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminSettingController;
use App\Http\Middleware\CheckEventBookingStartStatus;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

Route::get('/', function () {
    return view('welcome');
});



// Verification notice
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // create this view
})->middleware('auth')->name('verification.notice');

// Verification link
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::find($id);
    if (!$user) {
        abort(404, 'User not found.');
    }
    // Check the URL signature
    if (! URL::hasValidSignature(request())) {
        abort(403, 'Invalid or expired verification link.');
    }

    // Validate the hash (for security)
    if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403, 'Invalid verification hash.');
    }

    // If already verified, just redirect
    if ($user->hasVerifiedEmail()) {
        return redirect(env('WEB_URL'));
    }

    // Mark verified
    $user->markEmailAsVerified();
    event(new Verified($user));

    return "Verified";
})->middleware(['signed'])->name('verification.verify');


// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



Route::get('/verify-email/{token}', [UserManagementController::class, 'verifyEmail'])->name('verify.email');


Route::get('/tickets/{id}/qr', [TicketController::class, 'ticketQr']);


// stripe webhook and payment

Route::get("/payment-success", [StripeController::class, 'paymentSuccess'])->name('payment.success');


Route::get("/event-web-view/{event}", [WebviewController::class, 'eventView'])->name('event.webview');
Route::get("/winner-screen/{event}", [WebviewController::class, 'winnerScreen'])->name('event.winner');


# ----------------- VENDOR -----------------

// Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('vendor')->as('vendor.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::get('mfa-verify', [AuthController::class, 'mfaVerify'])->name('mfa.verify');
    Route::post('/login-post', [AuthController::class, 'loginPost'])->name('login.post');
    Route::post('/signup-post', [AuthController::class, 'signupPost'])->name('signup.post');
    Route::post('/mfa-verify', [AuthController::class, 'verifyOtp'])->name('mfa.verify.post');

    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
    // Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');





    // Forgot Password
    // Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
    // Route::post('/send-reset-otp', [AuthController::class, 'sendResetOtp'])->name('send.reset.otp');

    // Route::get('/reset-otp', [AuthController::class, 'resetOtpPage'])->name('reset.otp.page');
    // Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp'])->name('verify.reset.otp');

    // Route::get('/reset-password', [AuthController::class, 'resetPasswordForm'])->name('reset.password.form');
    // Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');

    // Forgot Password (step 1 - email input)
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('forgot.password');

    // Step 2 - send OTP to email, show OTP page
    Route::post('/forgot-password/send-otp', [AuthController::class, 'sendResetOtp'])
        ->name('forgot.password.sendOtp');

    // Step 3 - verify OTP
    Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyResetOtp'])
        ->name('forgot.password.verifyOtp');

    // Step 4 - show reset password form (after OTP verified)
    Route::get('/forgot-password/reset', [AuthController::class, 'resetPasswordForm'])
        ->name('forgot.password.resetForm');

    // Step 5 - save new password
    Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])
        ->name('forgot.password.reset');



    Route::middleware(['auth.vendor'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AuthController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/profile/change-password', [AuthController::class, 'profileChangePassword'])->name('profile.changePassword');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        //Event Manage
        Route::prefix('event')->group(function () {
            Route::get('/list', [EventController::class, 'index'])->name('event.index')->can('view events');
            Route::post('/events/change-status', [EventController::class, 'changeStatus'])->name('event.changeStatus');
            Route::get('/create', [EventController::class, 'create'])->name('event.create')->can('create events');
            Route::post('/store', [EventController::class, 'store'])->name('event.store')->can('create events');
            Route::get('event/edit/{id}', [EventController::class, 'edit'])->name('event.edit')->can('edit events')->middleware(CheckEventBookingStartStatus::class);
            Route::put('event/update/{id}', [EventController::class, 'update'])->name('event.update')->can('edit events')->middleware(CheckEventBookingStartStatus::class);
            Route::get('/event/view/{id}', [EventController::class, 'show'])->name('event.show');
            Route::get('/event/{id}/tickets/data', [EventController::class, 'ticketsData'])->name('event.tickets.data');


            Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy')->can('delete events')->middleware(CheckEventBookingStartStatus::class);
            Route::get('/events/data', [EventController::class, 'getData'])->name('events.data');


            // Finalize Event & annouced winner

            Route::post("/{event}/finalize-event", [EventController::class, 'finalizeEvent'])->name('event.finalize');
        });

        Route::prefix('event/category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('category.index')->can('view categories');
            Route::get('/create', [CategoryController::class, 'create'])->name('category.create')->can('add category');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store')->can('add category');
            Route::get('/categories/datatable', [CategoryController::class, 'categoryData'])->name('category.data'); // DataTable AJAX
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('category.destroy')->can('delete category'); // Delete
        });

        Route::prefix('users')->middleware([])->group(function () {
            Route::get('/organizers', [UserManagementController::class, 'organizers'])->name('organizers')->can('view organizers');
            Route::get('organizers/data', [UserManagementController::class, 'getData'])->name('organizers.data');     // view users
            Route::post('/{user}/suspend', [UserManagementController::class, 'suspend'])->name('user.suspend');
            Route::post('/{user}/unsuspend', [UserManagementController::class, 'unsuspend'])->name('user.unsuspend');
            Route::post('/{user}/verify', [UserManagementController::class, 'verify'])->name('user.verify');

            Route::get('/buyers', [UserManagementController::class, 'buyers'])->name('buyers')->can('view buyers');
            Route::get('/buyers/data', [UserManagementController::class, 'buyersGetData'])->name('buyers.data');

            Route::get('/', [UserManagementController::class, 'index'])->name('users.index');
            Route::get('/datatable', [UserManagementController::class, 'userData'])->name('users.data');
            Route::get('/create', [UserManagementController::class, 'create'])->name('users.create');
            Route::post('/store', [UserManagementController::class, 'store'])->name('users.store');

            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('users.update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        });

        Route::prefix('ticket')->group(function () {
            Route::get('/list', [TicketController::class, 'ticketList'])->name('ticket.list');
            Route::get('/data', [TicketController::class, 'ticketData'])->name('ticket.data');
        });

        Route::prefix('winners')->group(function () {
            Route::get('/', [TicketController::class, 'winners'])->name('winner.list');
            Route::get('/data', [TicketController::class, 'winnersData'])->name('winner.data');
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index'])->name('roles.index');
            Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
            Route::post('/{role}/update', [RolePermissionController::class, 'update'])->name('roles.update');
        });
    });
});



# ----------------- ADMIN -----------------

Route::prefix('admin')->as('admin.')->group(function () {



    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
    Route::post('/settings/save', [AdminSettingController::class, 'store'])->name('settings.save');
    // Route::post('/settings/update/{id}', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/update-all', [AdminSettingController::class, 'updateAll'])->name('settings.updateAll');


    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::get('mfa-verify', [AuthController::class, 'mfaVerify'])->name('mfa.verify');
    Route::post('/login-post', [AuthController::class, 'loginPost'])->name('login.post');
    Route::post('/signup-post', [AuthController::class, 'signupPost'])->name('signup.post');
    Route::post('/mfa-verify', [AuthController::class, 'verifyOtp'])->name('mfa.verify.post');

    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');

    Route::middleware(['auth.admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AuthController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/profile/change-password', [AuthController::class, 'profileChangePassword'])->name('profile.changePassword');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/contact-leads', [DashboardController::class, 'contactList'])->name('contact.list');
        Route::get('/contact-data', [DashboardController::class, 'contactData'])->name('contact.data');
        Route::delete('contact-lead/{id}', [BlogController::class, 'destroy'])->name('contact.destroy');

        //Event Manage
        Route::prefix('event')->group(function () {
            Route::get('/list', [EventController::class, 'index'])->name('event.index')->can('view events');
            Route::post('/events/change-status', [EventController::class, 'changeStatus'])->name('event.changeStatus');
            Route::get('/create', [EventController::class, 'create'])->name('event.create')->can('create events');
            Route::post('/store', [EventController::class, 'store'])->name('event.store')->can('create events');
            Route::get('event/edit/{id}', [EventController::class, 'edit'])->name('event.edit')->can('edit events')->middleware(CheckEventBookingStartStatus::class);
            Route::put('event/update/{id}', [EventController::class, 'update'])->name('event.update')->can('edit events')->middleware(CheckEventBookingStartStatus::class);
            Route::get('/event/view/{id}', [EventController::class, 'show'])->name('event.show');
            Route::get('/event/{id}/tickets/data', [EventController::class, 'ticketsData'])->name('event.tickets.data');


            Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy')->can('delete events')->middleware(CheckEventBookingStartStatus::class);
            Route::get('/events/data', [EventController::class, 'getData'])->name('events.data');


            // finalize event & announce winner
            Route::post("/finalize-event", [EventController::class, 'finalizeEvent'])->name('event.finalize');

            Route::post("/event-screen", [EventController::class, 'updateEventScreen'])->name('event.screen');
        });

        Route::prefix('event/category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('category.index')->can('view categories');
            Route::get('/create', [CategoryController::class, 'create'])->name('category.create')->can('add category');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store')->can('add category');
            Route::get('/categories/datatable', [CategoryController::class, 'categoryData'])->name('category.data'); // DataTable AJAX
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('category.destroy')->can('delete category'); // Delete
        });

        Route::prefix('users')->middleware([])->group(function () {
            Route::get('/organizers', [UserManagementController::class, 'organizers'])->name('organizers')->can('view organizers');
            Route::get('organizers/data', [UserManagementController::class, 'getData'])->name('organizers.data');     // view users
            Route::post('/{user}/suspend', [UserManagementController::class, 'suspend'])->name('user.suspend');
            Route::post('/{user}/unsuspend', [UserManagementController::class, 'unsuspend'])->name('user.unsuspend');
            Route::post('/{user}/verify', [UserManagementController::class, 'verify'])->name('user.verify');

            Route::get('/buyers', [UserManagementController::class, 'buyers'])->name('buyers')->can('view buyers');
            Route::get('/buyers/data', [UserManagementController::class, 'buyersGetData'])->name('buyers.data');

            Route::get('/', [UserManagementController::class, 'index'])->name('users.index');
            Route::get('/datatable', [UserManagementController::class, 'userData'])->name('users.data');
            Route::get('/create', [UserManagementController::class, 'create'])->name('users.create');
            Route::post('/store', [UserManagementController::class, 'store'])->name('users.store');

            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('users.update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        });

        Route::prefix('ticket')->group(function () {
            // Route::get('/list', [TicketController::class, 'ticketList'])->name('ticket.list');

            Route::get('/list', [TicketController::class, 'ticketListOffline'])->name('ticket.list');
            Route::get('/data', [TicketController::class, 'ticketData'])->name('ticket.data');
        });

        Route::prefix('blog')->group(function () {
            Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
            Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
            Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
            Route::put('/update/{id}', [BlogController::class, 'update'])->name('blog.update');
            Route::get('/list', [BlogController::class, 'index'])->name('blog.list');
            Route::get('/data', [BlogController::class, 'listData'])->name('blog.data');
            Route::delete('/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
        });

        Route::prefix('winners')->group(function () {
            Route::get('/', [TicketController::class, 'winners'])->name('winner.list');
            Route::get('/data', [TicketController::class, 'winnersData'])->name('winners.data');
        });


        Route::prefix('roles')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index'])->name('roles.index');
            Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
            Route::post('/{role}/update', [RolePermissionController::class, 'update'])->name('roles.update');
        });

        Route::get('claim-requests', [ClaimPrizeController::class, 'claimRequests'])->name('claim.requests');
        Route::get('claim-requests-data', [ClaimPrizeController::class, 'claimData'])->name('claim.request.data');

        Route::put('claim-approve/{claimId}', [ClaimPrizeController::class, 'approveRequest'])->name('claim.request.approve');

        // Route::prefix('sales')->group(function(){
        //     Route::get('/pos',[SalesTransactionController::class,'posSales'])->name('pos.sales.list');
        //     Route::get('/pos/data',[SalesTransactionController::class,'posSalesData'])->name('pos.sales.data');

        //     Route::get('/online',[SalesTransactionController::class,'onlineSales'])->name('online.sales.list');
        //     Route::get('/online/data',[SalesTransactionController::class,'onlineSalesData'])->name('online.sales.data');

        // });
    });
});
