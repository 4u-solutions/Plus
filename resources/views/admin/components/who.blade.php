@if (Auth::guard('web')->check())
<p class="text-succes">  Se logueo como usuario</p>
@else
<p class="text-danger">  Usted no está logueado como usuario</p>
@endif


@if (Auth::guard('admin')->check())
<p class="text-succes">  Se logueo como administrador</p>
@else
<p class="text-danger">  Usted no está logueado como administrador </p>
@endif
