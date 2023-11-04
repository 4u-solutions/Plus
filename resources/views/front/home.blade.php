@extends('front.layouts.app')

@section('main-content')
      <div class="content-wrapper container-xxl p-0">

          <div class="content-body">
              <!-- Validation -->
              <section class="bs-validation">
                  <div class="row">
                    <!-- Imagen de concierto -->
                    <div class="col-md-12 col-12">


                        <p class="card-text">
                          Elige el tipo de boleto que quieres comprar. Recuerda que el boleto VIP te da derecho a entrar al concierto de Krisiun + Crypta + Nervo Chaos.
                        </p>
                      <button type="submit" value="LjIxNjM5Mzk3M2I1MmYxNWRmNjExYmYxNjQ3NzA0ODk1" onclick="newurl(this);" class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0">Boleto VIP Q620</button>
                      <button type="submit" value="NjY4YmUwMzdiMjY1YS5lMDYwMjhmMjYxNjQ3NzA0OTQy" onclick="newurl(this);" class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0">Boleto General Q370</button>

                      <p class="card-text">
                        <br>
                        Una vez hayas pagado tu boleto, recuerda que te llegará un email en las próximas 24 horas con un archivo el cual es tu entrada. Puedes imprimirlo o mostrarlo en tu teléfono el día del concierto. Es importante que no compartas tu boleto pues es único y si alguien más lo utiliza, ya no podrás ingresar.

                    </div>
                    <!-- Fin de imagen de concierto -->

                      <!-- Bootstrap Validation -->
                      <!-- /Bootstrap Validation -->
                  </div>
              </section>
              <!-- /Validation -->

          </div>
      </div>
      <iframe id="egap" style="width: 100%;border: none; height: 1750px; " title="Payment Button IFrame" src="" scrolling="no">
        <p>Your browser does not support iframes.</p>
      </iframe>
<script type="text/javascript">
  function newurl(estu){
  document.getElementById('egap').src = 'https://checkout.baccredomatic.com/'+estu.value;
  }
</script>
@endsection
