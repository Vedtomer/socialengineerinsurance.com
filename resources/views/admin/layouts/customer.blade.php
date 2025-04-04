<!DOCTYPE html>
<html data-wf-domain="https://socialengineerinsurance.com" data-wf-page="65b60c5eef338f6b2401686d"
    data-wf-site="65b60c5def338f6b24016820" lang="en">
@include('admin.layouts.head')


<body class="layout-boxed" page="starter-pack">
    {{-- <div class="page-wrapper"> --}}

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    @include('admin.layouts.header')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>


        @include('admin.layouts.sidebar')
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                   
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @yield('breadcrumb')
                            </ol>
                        </nav>

                    </div>

                    @yield('content')
                    <!-- CONTENT AREA -->
                </div>

            </div>
            @include('admin.layouts.footer')
        </div>
    </div>
    {{-- </div> --}}
    @include('admin.layouts.scripts')
    @yield('scripts')
</body>

</html>
