<!DOCTYPE html>
<html lang="es">
<head>
	@include('front.layouts.scripts')
</head>
<body class="horizontal-layout horizontal-menu  navbar-floating footer-static  "
			data-open="hover" data-menu="horizontal-menu" data-col="">
		@include('front.layouts.header')
		<div class="app-content">
			 @section('main-content')
			 @show
		</div>
		@include('front.layouts.footer')
	@include('front.layouts.scriptsfooter')
</body>
</html>
