<!DOCTYPE html>
<html lang="es">
<head>
	@include('admin.layouts.scripts')
</head>
<body >
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">
		@include('admin.layouts.header')
		@include('admin.layouts.sidebar')
		<!-- BEGIN: Content-->
		<div class="app-content content ">
				<div class="content-overlay"></div>
				<div class="header-navbar-shadow"></div>
				<div class="content-wrapper container-xxl p-0">
					 @section('main-content')
					 @show
 				</div>
		</div>
		@include('admin.layouts.footer')
	@include('admin.layouts.scriptsfooter')
</body>
</html>
