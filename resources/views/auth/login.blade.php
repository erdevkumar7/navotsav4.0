<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Sign In | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index.html" class="d-inline-block auth-logo">
                                    <img src="assets/images/logo-light.png" alt="" height="20">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    {{-- <p class="text-muted">Sign in to continue to Velzon.</p> --}}
                                </div>
                                <div class="p-2 mt-4">
                                    <form id="loginForm" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email" class="form-control" id="email"
                                                placeholder="Enter Email">
                                            <small class="text-danger d-none" id="emailError"></small>
                                        </div>

                                        <div class="mb-3">
                                            @if (routePrefix() === 'vendor.')
                                                <div class="float-end">
                                                    <a href="{{ route(routePrefix() . 'forgot.password') }}"
                                                        class="text-muted">Forgot
                                                        password?</a>
                                                </div>
                                            @endif
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" name="password"
                                                    class="form-control pe-5 password-input"
                                                    placeholder="Enter password" id="password">
                                                <small class="text-danger d-none" id="passwordError"></small>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auth-remember-check"
                                                name="remember">
                                            <label class="form-check-label" for="auth-remember-check">Remember
                                                me</label>
                                        </div>

                                        <div class="mt-4">
                                            <button id="loginBtn" class="btn btn-success w-100" type="submit">
                                                <span class="spinner-border spinner-border-sm d-none"
                                                    id="loginLoader"></span>
                                                <span id="loginBtnText">Sign In</span>
                                            </button>
                                        </div>
                                        <small class="text-danger d-none" id="loginError"></small>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->

                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            @if (routePrefix() === 'vendor.')
                                <p class="mb-0">Don't have an account ? <a href="{{ route('vendor.signup') }}"
                                        class="fw-semibold text-primary text-decoration-underline"> Signup </a> As a
                                    organizer
                                </p>
                            @endif

                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ config('app.name') }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    <!-- particles js -->
    <script src="{{ asset('assets/libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ asset('assets/js/pages/particles.app.js') }}"></script>
    <!-- password-addon init -->
    <script src="{{ asset('assets/js/pages/password-addon.init.js') }}"></script>

    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"
        integrity="sha512-TGP4l8JruobzdKs4gMpTvhshQepDREekFl4QKd9b/bwALzzZxoAu9cJacP6m8h924i2pPDya5an4tfZmOLjWUQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $("#loginForm").on("submit", function(e) {
            e.preventDefault();

            let btn = $("#loginBtn");
            let loader = $("#loginLoader");
            let btnText = $("#loginBtnText");

            // reset errors
            $("#emailError, #passwordError, #loginError").addClass("d-none").text("");

            // show loader
            btn.prop("disabled", true);
            loader.removeClass("d-none");
            btnText.text("Signing in...");

            $.ajax({
                url: "{{ route(routePrefix() . 'login.post') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status === true) {
                        window.location.href = res.redirect; // e.g., dashboard
                    } else {
                        $("#loginError").text(res.message).removeClass("d-none");
                        btn.prop("disabled", false);
                        loader.addClass("d-none");
                        btnText.text("Sign In");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // validation errors
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) $("#emailError").text(errors.email[0]).removeClass("d-none");
                        if (errors.password) $("#passwordError").text(errors.password[0]).removeClass(
                            "d-none");
                    } else if (xhr.status === 401) {
                        toastr.error(xhr.responseJSON.message)
                    } else {
                        $("#loginError").text("Something went wrong, try again.").removeClass("d-none");
                    }

                    btn.prop("disabled", false);
                    loader.addClass("d-none");
                    btnText.text("Sign In");
                }
            });
        });
    </script>
</body>

</html>
