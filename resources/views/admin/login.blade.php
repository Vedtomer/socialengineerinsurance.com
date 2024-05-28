
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Admin | Social Engineer Insurance </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('asset/admin/images/favicon.ico') }}"/>
    <link href="{{ asset('asset/admin/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('asset/admin/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('asset/admin/js/loader.js') }}" ></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('asset/admin/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('asset/admin/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('asset/admin/css/light/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('asset/admin/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('asset/admin/css/dark/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    
</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
            <form action="" method="POST">
                @csrf
        <div class="row">
            
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                <div class="card mt-3 mb-3">
                    <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    
                                    <h2>Sign In</h2>
                                    <p>Enter your email and password to login</p>
                                    
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3 ">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Password</label>
                                        <input type="text" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                                            <label class="form-check-label" for="form-check-default">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100">SIGN IN</button>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
        </form>

    </div>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('asset/admin/js/bootstrap.bundle.min.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>
</html>