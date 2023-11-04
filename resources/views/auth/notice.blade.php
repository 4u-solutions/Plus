@extends('front.layouts.app')

@section('main-content')
<div class="content-body">
      <div class="row">
        <div class=" col-7">
        <div class="card">
          <div class="card-body">
              <h1>Verifica tu correo</h1>
              @if (session('resent'))
                  <div class="alert alert-success" role="alert">
                      Te hemos enviado un correo para activar tu cuenta.
                  </div>
              @endif
              Antes de continuar, por favor revisa el link de verificacion que te enviamos a tu correo.
              <br>
              Si no recibiste el correo,
              <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="d-inline btn btn-link p-0">
                      click aquí para enviar otro
                  </button>.
              </form>
            </div>
          </div>
        </div>
      </div>
</div>


@endsection
