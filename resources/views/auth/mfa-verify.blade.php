<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Two Step Verification | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
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
                            {{-- <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p> --}}
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                            <i class="ri-mail-line"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2 mt-4">
                                    <div class="text-muted text-center mb-4 mx-lg-3">
                                        <h4>Two-Step Verification</h4>
                                        <p>Please enter the 4 digit code sent to <span
                                                class="fw-semibold">{{ $email }}</span></p>
                                    </div>

                                    <form autocomplete="off">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit1-input" class="visually-hidden">Digit 1</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg bg-light border-light text-center"
                                                        onkeyup="moveToNext(1, event)" maxLength="1" id="digit1-input">
                                                </div>
                                            </div><!-- end col -->

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit2-input" class="visually-hidden">Digit 2</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg bg-light border-light text-center"
                                                        onkeyup="moveToNext(2, event)" maxLength="1" id="digit2-input">
                                                </div>
                                            </div><!-- end col -->

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit3-input" class="visually-hidden">Digit 3</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg bg-light border-light text-center"
                                                        onkeyup="moveToNext(3, event)" maxLength="1" id="digit3-input">
                                                </div>
                                            </div><!-- end col -->

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit4-input" class="visually-hidden">Digit 4</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg bg-light border-light text-center"
                                                        onkeyup="moveToNext(4, event)" maxLength="1" id="digit4-input">
                                                </div>
                                            </div><!-- end col -->
                                        </div>
                                    </form><!-- end form -->

                                    <div class="mt-3">
                                        <button id="otpBtn" type="button" class="btn btn-success w-100">
                                            <span class="spinner-border spinner-border-sm d-none"
                                                id="otpLoader"></span>
                                            <span id="otpBtnText">Confirm</span>
                                        </button>
                                        <small id="otpError" class="text-danger d-none"></small>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Didn't receive a code ? <a id="resendBtn" href="javascript:void(0)"
                                    class="fw-semibold text-primary text-decoration-underline">Resend</a> </p>
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
                                </script> {{ config('app.name') }}
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
    <!-- two-step-verification js -->
    <script src="{{ asset('assets/js/pages/two-step-verification.init.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function moveToNext(current, event) {
            let input = event.target;
            if (input.value.length === 1 && current < 4) {
                document.getElementById('digit' + (current + 1) + '-input').focus();
            }
        }

        $("#otpBtn").on("click", function() {
            let otp = $("#digit1-input").val() + $("#digit2-input").val() + $("#digit3-input").val() + $(
                "#digit4-input").val();
            let btn = $("#otpBtn");
            let loader = $("#otpLoader");
            let btnText = $("#otpBtnText");

            if (otp.length !== 4) {
                $("#otpError").text("Please enter 4 digit OTP").removeClass("d-none");
                return;
            }

            // Show loader
            btn.prop("disabled", true);
            loader.removeClass("d-none");
            btnText.text("Verifying...");

            $.ajax({
                url: "{{ route(routePrefix().'mfa.verify') }}", // 👈 Laravel route
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    otp: otp
                },
                success: function(res) {
                    if (res.status === "success") {
                        window.location.href = res.redirect;
                    } else {
                        $("#otpError").text(res.message).removeClass("d-none");
                        btn.prop("disabled", false);
                        loader.addClass("d-none");
                        btnText.text("Confirm");
                    }
                },
                error: function() {
                    $("#otpError").text("Something went wrong").removeClass("d-none");
                    btn.prop("disabled", false);
                    loader.addClass("d-none");
                    btnText.text("Confirm");
                }
            });
        });

        $("#resendBtn").on("click", function() {


            $.ajax({
                url: "{{ route(routePrefix().'resend.otp') }}", // 👈 Laravel route
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: "{{ $email }}"
                },
                success: function(res) {
                    if (res.status === true) {
                        alert(res.message);
                    } else {
                        $("#otpError").text(res.message).removeClass("d-none");
                        btn.prop("disabled", false);
                        loader.addClass("d-none");
                        btnText.text("Confirm");
                    }
                },
                error: function() {
                    $("#otpError").text("Something went wrong").removeClass("d-none");
                    btn.prop("disabled", false);
                    loader.addClass("d-none");
                    btnText.text("Confirm");
                }
            });
        });
    </script>
</body>

</html>
