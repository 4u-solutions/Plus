<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    	@include('admin.layouts.scripts')
   <style>
       .txtPassword{
           -webkit-text-security:disc;
       }
   </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static menu-collapsed main-login" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="row h-100">
        <div class="embed-responsive embed-responsive-1by1">
            <div class="embed-responsive-item h-100">
                <div class="row align-items-center h-100">
                    <div class="col-10 col-sm-6 col-md-4 mx-auto p-0 bg-white text-center shadow">
                        <div class="card-body">
                            <div class="col-6 mx-auto mb-3">
                                <img src="{{asset('img_admin/logo-plus-negro.png')}}" class="w-100">
                            </div>

                            <h4 class="card-title mb-1 fs-1">BIENVENIDO</h4>

                            <form class="auth-login-form mt-2" action="" method="POST">
    							@csrf								
                                <div class="mb-1">
                                    <input type="text" class="form-control" id="usersys" name="usersys" placeholder="Usuario" tabindex="1" autofocus required autocomplete="off" />
                                </div>

                                <div class="mb-1">
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge txtPassword" id="password" name="password" tabindex="2" placeholder="Contraseña" required autocomplete="off" />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <button class="btn btn-dark w-100" tabindex="4">Ingresar</button>
                            </form>

                            <div class="divider my-2">
                                <div class="divider-text">***</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


	@include('admin.layouts.scriptsfooter')
