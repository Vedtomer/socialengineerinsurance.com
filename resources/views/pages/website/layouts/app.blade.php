<!DOCTYPE html>
<html
  data-wf-domain=""
  data-wf-page="65b60c5eef338f6b24016869"
  data-wf-site="65b60c5def338f6b24016820"
  lang="en"
>
@include('pages.website.layouts.head')
<body>
    <div class="page-wrapper">
        @include('pages.website.layouts.header')
        <main class="main-wrapper">
            @yield('content')
        </main>
        @include('pages.website.layouts.footer')
    </div>
    @include('pages.website.layouts.scripts')

     <!-- Add the robots meta tag to prevent indexing -->
    <meta name="robots" content="noindex, nofollow">

</body>

</html>
