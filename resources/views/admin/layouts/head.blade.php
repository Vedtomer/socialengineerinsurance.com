<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Admin-Socialengineer Insurance</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('asset/admin/images/favicon.ico') }}" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('asset/admin/layouts/modern-dark-menu/css/light/loader.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('asset/admin/layouts/modern-dark-menu/css/dark/loader.css') }}" rel="stylesheet"
        type="text/css" />
    <script src="{{ asset('asset/admin/layouts/modern-dark-menu/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('asset/admin/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('asset/admin/layouts/modern-dark-menu/css/light/plugins.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('asset/admin/layouts/modern-dark-menu/css/dark/plugins.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->







    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/admin/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/admin/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/admin/plugins/css/light/table/datatable/custom_dt_miscellaneous.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/admin/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/admin/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css') }}">

    <!-- END PAGE LEVEL STYLES -->


    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('asset/admin/src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/admin/src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('asset/admin/src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->


    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('asset/admin/src/assets/css/light/components/list-group.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('asset/admin/src/assets/css/light/users/user-profile.css') }}" rel="stylesheet"
        type="text/css" />

    <link href="{{ asset('asset/admin/src/assets/css/dark/components/list-group.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('asset/admin/src/assets/css/dark/users/user-profile.css') }}" rel="stylesheet"
        type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->
    <!-- Include Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


     <!-- BEGIN THEME GLOBAL STYLES -->
     <link href="{{ asset('asset/admin/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
     <link href="{{ asset('asset/admin/src/plugins/src/noUiSlider/nouislider.min.css') }}" rel="stylesheet" type="text/css">
     <!-- END THEME GLOBAL STYLES -->

     <!--  BEGIN CUSTOM STYLE FILE  -->

     <link href="{{ asset('asset/admin/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
     <link href="{{ asset('asset/admin/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
     <!--  END CUSTOM STYLE FILE  -->


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


       <!-- BEGIN PAGE LEVEL STYLES -->


   <link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
   <link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
   <!-- END PAGE LEVEL STYLES -->


   <link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/src/plugins/css/light/table/datatable/custom_dt_miscellaneous.css') }}">

   <link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/src/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css') }}">

   <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <style>
        body.dark .layout-px-spacing,
        .layout-px-spacing {
            min-height: calc(100vh - 155px) !important;
        }

        .info {
            font-weight: 500;
            margin-bottom: 0;
            color: #e95f2b;
            font-size: 22px;
        }

        .w-info {
            margin-bottom: 0;
            font-size: 18px;
            font-weight: 600;
            color: #bfc9d4;
        }

        body.dark .details-section {
            padding-bottom: 1rem;
            border-bottom: 1px solid;
            margin-bottom: 1rem;
        }

        body.dark .details-section .text-info {
            font-weight: bolder;
        }

        body.dark .details-section:last-child {
            padding-bottom: 0;
            border-bottom: none;
            margin-bottom: 0;
        }

        body.dark .user-profile .widget-content-area .user-info {
            margin-top: 0px !important;
        }


        .select2-container--default .select2-selection--single {
            height: auto;
            font-size: 15px;
            padding: 0.75rem 1.25rem;
            letter-spacing: 1px;
            border: 1px solid #1b2e4b;
            color: #009688;
            background-color: #1b2e4b;
            border-radius: 6px;

            transition: none;
        }


        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #009688 !important;
            line-height: 28px;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #1b2e4b !important;
            color: white;
        }

        .select2-selection__clear span {
            color: white
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: auto;
            position: absolute;
            top: 50%;
            right: 1px;
            width: 20px;
        }

        table#html5-extension {
            min-height: 350px
        }
    </style>
@yield('styles')
</head>
