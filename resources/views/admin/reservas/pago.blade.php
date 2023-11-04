
	<div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
	  	<div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
	    	<div class="modal-body p-0">
			        <form id="tarjeta_form" method="POST" action="/admin/generar_pago" onsubmit="event.preventDefault();" enctype="multipart/form-data">
			          	@csrf
			          	<input type="hidden" name="id_evento" value="{{$data->id_evento}}" />
			          	<input type="hidden" name="id_mesa"   value="{{$data->id_mesa}}" />
			          	<input type="hidden" name="id_invitado" value="{{$data->id}}" />

						<h3 class="d-block text-start bg-dark text-light p-1 mb-0">Tu orden</h3>
						@if ($data->es_pagado && !$data->pagado)
							<div class="row mt-1">
								<div class="col-5 text-start fw-bold"> {{$data->evento}} ({{$data->titulo}}) </div>
								<div class="col-3"> Q. {{$data->precio}} </div>
								<div class="col-1 p-0"> x1 </div>
								<div class="col-3 text-start"> Q. {{$monto = $data->precio}} </div>
							</div>

							<div class="row border-bottom pb-1">
								<div class="col-9 text-start fw-bold"> Fee </div>
								<div class="col-3 text-start"> Q. {{$fee = $data->fee}} </div>
							</div>

							@php @$subtotal += $monto + $fee; @endphp
							<input type="hidden" name="rubro[id_evento][id]" value="{{$data->id_evento}}" />
							<input type="hidden" name="rubro[id_evento][total]" value="{{$monto + $fee}}" />
						@endif

						@if ($data->pull && $data->id_pull)
							<div class="row mt-1 border-bottom pb-1">
								<div class="col-5 text-start fw-bold"> Mesa Pull </div>
								<div class="col-3"> Q. {{$data->sexo == 1 ? $pull->monto_hombres : $pull->monto_mujeres}} </div>
								<div class="col-1 p-0"> x1 </div>
								<div class="col-3 text-start"> Q. {{$monto = $data->sexo == 1 ? $pull->monto_hombres : $pull->monto_mujeres}} </div>
							</div>

							@php @$subtotal += $monto; @endphp
							<input type="hidden" name="rubro[id_mesa][id]" value="{{$data->id_mesa}}" />
							<input type="hidden" name="rubro[id_mesa][total]" value="{{$monto}}" />
						@endif

						<div class="row pt-1">
							<div class="col-9 text-start fw-bold"> Total </div>
							<div class="col-3 text-start"> Q. {{@$subtotal}} </div>
						</div>

						<h3 class="d-block text-start bg-dark text-light p-1 mt-2 mb-0">Método de pago</h3>
						<div class="row mt-1">
							<!--
							<div class="col-4">
								<label class="fs-4 d-block text-start"> Tarjeta </label>
							</div>
							<div class="col-1 p-0 form-check">
								<input type="radio" class="form-check-input d-inline-block" name="metodo_pago" id="metodo_tarjeta" value="3" onclick="seleccionarMetodo(1)" checked />
		                    </div>
		                	-->
							<div class="col-5">
								<label class="fs-4 d-block text-start"> Depósito </label>
							</div>
							<div class="col-1 p-0 form-check">
								<input type="radio" class="form-check-input d-inline-block" name="metodo_pago" id="metodo_deposito" value="2" onclick="seleccionarMetodo(2)" checked />
		                    </div>
						</div>
						<div class="row mt-1" id="metodo_1" style="display: none;">
							<div class="col-12">
								<label class="fs-4 d-block text-start control-label"> Número de tarjeta </label>
		                    	<input class="form-control ps-5" id="tarjeta_num" name="tarjeta_num" type="text" placeholder="1234 1234 1234 1234" value="" />
		                    	<input type="hidden" id="tarjeta_nom" name="tarjeta_nom" />
		                    </div>
						</div>
						<div class="row mt-1" id="metodo_1" style="display: none;">
							<div class="col-6">
								<label class="fs-4 d-block text-start control-label"> Vencimiento </label>
		                    	<input class="form-control" id="tarjeta_fv" name="tarjeta_fv" type="text" value="" placeholder="MM/AA" />
		                    </div>
							<div class="col-6">
								<label class="fs-4 d-block text-start control-label"> CVC </label>
		                    	<input class="form-control" id="tarjeta_cvc" name="tarjeta_cvc" type="text" value="" placeholder="CVC" />
		                    </div>
	                    </div>
						<div class="row mt-1" id="metodo_2">
							<!--
							<div class="col-12">
								<label class="fs-4 d-block text-start control-label"> No. autorización </label>
		                    	<input class="form-control" id="no_boleta" name="no_boleta" type="text" value="" />
		                    </div>
		                    -->
							<div class="col-12">
								<label class="fs-4 d-block text-start control-label"> Subir boleta </label>
		                    	<input class="form-control" id="boleta-pago" name="boleta-pago" type="file" />
		                    </div>
						</div>
					</form>
        	</div>
      	</div>
    </div>

    <script type="text/javascript">
    </script>

	@php $background_url = asset('tarjetas/tarjetas.png') @endphp
    <style>
    	.has-error .control-label { color: #a94442; }
    	.has-error .form-control  { border-color: #a94442; -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075); box-shadow: inset 0 1px 1px rgba(0,0,0,.075); }
    	.has-error input          { border-width: 2px; }
    	#tarjeta_num { 
    		background-size: 120px 361px,120px 361px; 
    		background-position: 2px -119px,260px -61px;
    		background-repeat: no-repeat;
    		background-image: url('{{$background_url}}');
    	}
    </style>