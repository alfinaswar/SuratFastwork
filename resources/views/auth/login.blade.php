<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>E- SURAT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}assets/images/favicon.ico">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/preloader.min.css" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('') }}assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('') }}assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- <body data-layout="horizontal"> -->
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a href="index.html" class="d-block auth-logo">
                                        <img src="{{ asset('') }}assets/images/logo-sm.svg" alt=""
                                            height="28"> <span class="logo-txt">E-MANAJEMEN SURAT</span>
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">Selamat Datang</h5>
                                        <p class="text-muted mt-2">Silahkan login untuk melanjutkan</p>
                                    </div>
                                    <form class="mt-4 pt-2" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus
                                                placeholder="Email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password"
                                                placeholder="Password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group text-left mb-5">


                                        </div>

                                        <div class="text-center bottom">
                                            <button class="btn btn-primary button-md btn-block w-100" id="nav-sign-tab"
                                                type="submit">Login</button>

                                        </div>
                                    </form>
                                </div>
                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script> A <i class="mdi mdi-heart text-danger"></i> by
                                        Alfin
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end auth full page content -->
                </div>
                <!-- end col -->
                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <div class="auth-bg pt-md-5 p-4 d-flex">
                        <div class="bg-overlay bg-primary"></div>
                        <ul class="bg-bubbles">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>


    <!-- JAVASCRIPT -->
    <script src="{{ asset('') }}assets/libs/jquery/jquery.min.js"></script>
    <script src="{{ asset('') }}assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{ asset('') }}assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('') }}assets/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('') }}assets/libs/feather-icons/feather.min.js"></script>
    <!-- pace js -->
    <script src="{{ asset('') }}assets/libs/pace-js/pace.min.js"></script>
    <!-- password addon init -->
    <script src="{{ asset('') }}assets/js/pages/pass-addon.init.js"></script>

</body>

</html>
