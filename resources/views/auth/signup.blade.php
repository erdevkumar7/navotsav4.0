<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">


<!-- Mirrored from themesbrand.com/velzon/html/master/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Aug 2025 05:39:23 GMT -->

<head>

    <meta charset="utf-8" />
    <title>Sign In | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

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
                {{-- <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index.html" class="d-inline-block auth-logo">
                                    <img src="assets/images/logo-light.png" alt="" height="20">
                                </a>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">

                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                </div>
                                <div class="p-2 mt-4">
                                    <form method="POST" class="needs-validation" id="signupForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Full Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="username"
                                                placeholder="Enter full name">
                                            <small class="text-danger d-none" id="nameError"></small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" id="useremail"
                                                placeholder="Enter email address">
                                            <small class="text-danger d-none" id="emailError"></small>
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" name="password"
                                                    class="form-control pe-5 password-input"
                                                    placeholder="Enter password" id="password-input">
                                                <small class="text-danger d-none" id="passwordError"></small>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>

                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password
                                                Confirmation</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" name="password_confirmation"
                                                    class="form-control pe-5 password-input"
                                                    placeholder="Enter confirm password">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>

                                                <div class="invalid-feedback">

                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="mb-4">
                                            <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                                Velzon <a href="#"
                                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                    of Use</a></p>
                                        </div> --}}

                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b>
                                            </p>
                                            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter
                                                (a-z)</p>
                                            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b>
                                                letter (A-Z)</p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)
                                            </p>
                                        </div>

                                        <div class="mt-4">
                                            <button id="signupBtn" class="btn btn-success w-100" type="submit">
                                                <span class="spinner-border spinner-border-sm d-none"
                                                    id="signupLoader"></span>
                                                <span id="signupBtnText">Create</span>
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Already have an account ? <a href="{{ route(routePrefix().'login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
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

    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"
        integrity="sha512-TGP4l8JruobzdKs4gMpTvhshQepDREekFl4QKd9b/bwALzzZxoAu9cJacP6m8h924i2pPDya5an4tfZmOLjWUQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $("#signupForm").on("submit", function(e) {
            e.preventDefault();

            let btn = $("#signupBtn");
            let loader = $("#signupLoader");
            let btnText = $("#signupBtnText");

            // reset errors
            $("#emailError, #passwordError, #signupError").addClass("d-none").text("");

            // show loader
            btn.prop("disabled", true);
            loader.removeClass("d-none");
            btnText.text("Creating...");

            $.ajax({
                url: "{{ route(routePrefix().'signup.post') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status === true) {
                        setTimeout(() => {
                            toastr.success(res.message)
                            window.location.href = res.redirect;
                        }, 2000);
                        // e.g., dashboard
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // validation errors
                        let errors = xhr.responseJSON.errors;
                        if (errors.name) $("#nameError").text(errors.name[0]).removeClass("d-none");
                        if (errors.email) $("#emailError").text(errors.email[0]).removeClass("d-none");
                        if (errors.password) $("#passwordError").text(errors.password[0]).removeClass(
                            "d-none");
                    } else if (xhr.status === 401) {
                        toastr.error(xhr.responseJSON.message)
                    } else {
                        $("#signupError").text("Something went wrong, try again.").removeClass(
                            "d-none");
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
