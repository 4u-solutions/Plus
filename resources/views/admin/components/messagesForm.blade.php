<script type="text/javascript">

@if ($message = Session::get('success'))
	demo.showNotification("Guardado correctamente",1);
@endif

@if ($message = Session::get('error'))

	demo.showNotification('{{$message}}',3);
@endif

@if ($message = Session::get('neutral'))

	demo.showNotification('{!!$message!!}',3);
@endif


@if ($message = Session::get('warning'))
	demo.showNotification("Borrado correctamente",2);
@endif


@if ($message = Session::get('info'))
demo.showNotification("Actualizado correctamente",0);
@endif


@if ($errors->any())
let kme={{ implode('', $errors->all('<div>:message</div>')) }};
demo.showNotification("Please check the form below for errors<br>"+kme,3);

@endif






</script>
