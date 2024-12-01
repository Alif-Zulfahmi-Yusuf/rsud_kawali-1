<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Phoenix</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/backend/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}">
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}">
    <link rel="manifest" href="{{ asset('assets/backend/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/backend/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/backend/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/config.js') }}"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{ asset('assets/backend/vendors/choices/choices.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/vendors/dhtmlx-gantt/dhtmlxgantt.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/backend/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="{{ asset('assets/backend/css/theme-rtl.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/backend/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/backend/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet"
        id="user-style-rtl">
    <link href="{{ asset('assets/backend/css/user.min.css') }}" type="text/css" rel="stylesheet"
        id="user-style-default">
    <script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
    }
    </script>
</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="px-3">
            <div class="row min-vh-100 flex-center p-5">
                <div class="col-12 col-xl-10 col-xxl-8">
                    <div class="row justify-content-center g-5">
                        <div class="col-12 col-lg-6 text-center order-lg-1"><img class="img-fluid w-lg-100 d-light-none"
                                src="assets/img/spot-illustrations/500-illustration.png" alt="" width="400" /><img
                                class="img-fluid w-md-50 w-lg-100 d-dark-none"
                                src="assets/img/spot-illustrations/dark_500-illustration.png" alt="" width="540" />
                        </div>
                        <div class="col-12 col-lg-6 text-center text-lg-start"><img
                                class="img-fluid mb-6 w-50 w-lg-75 d-dark-none"
                                src="assets/img/spot-illustrations/500.png" alt="" /><img
                                class="img-fluid mb-6 w-50 w-lg-75 d-light-none"
                                src="assets/img/spot-illustrations/dark_500.png" alt="" />
                            <h2 class="text-body-secondary fw-bolder mb-3">Unknow error!</h2>
                            <p class="text-body mb-5">But relax! Our cat is here to play you some music.</p><a
                                class="btn btn-lg btn-primary" href="{{ route('dashboard') }}">Go Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('assets/backend/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/phoenix.js') }}"></script>

</body>

</html>