<!DOCTYPE html>
<html
  data-wf-domain="https://socialengineerinsurance.com"
  data-wf-page="65b60c5eef338f6b2401686d"
  data-wf-site="65b60c5def338f6b24016820"
  lang="en"
>
@include('admin.layouts.head')
<body>
    <div class="page-wrapper">
        @include('admin.layouts.header')

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">

            <div class="overlay"></div>
            <div class="search-overlay"></div>
                @include('admin.layouts.sidebar')
                <div id="content" class="main-content">
                    @yield('content')
                </div>
                @include('admin.layouts.footer')
        </div>
    </div>
    @include('admin.layouts.scripts')

     <!-- Add the robots meta tag to prevent indexing -->
    <meta name="robots" content="noindex, nofollow">

</body>

</html>