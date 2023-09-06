@extends('front.layouts.app')
@section('main-content')
<div class="content-wrapper container-xxl p-0">
  <div class="content-body">
  <!-- Validation -->
  <section class="bs-validation">
      <div class="row">
        <!-- Imagen de concierto -->
        <div class="col-md-6 col-12">
          <img src="{{asset('assetsFront/imgs/cannibal_corpse.jpg')}}" width="100%" alt="cannibal_corpse" style="border-radius: 15px; margin-bottom:25px;">
        </div>
        <!-- Fin de imagen de concierto -->

          <!-- Bootstrap Validation -->
          <div class="col-md-6 col-12">
              <div class="card">

                  <div class="card-header">
                      <h4 class="card-title">Regístrate para poder comprar boletos</h4>
                  </div>
                  <div class="card-body">
                    <div class="col-md-12 col-12" style="margin-bottom:25px;">
                      <p class="card-text">
                          * Una vez registrado, podrás elegir el boleto que desees y comprarlo. Te llegará un email con el (los) boletos que hayas adquirido. Dichos boletos son digitales. Estos se leerán al momento que ingreses al concierto. Puedes imprimirlo o mostrarlo en tu celular.
                      </p>
                    </div>
                    <form method="POST" action="{{ route('register') }}" >
                        @csrf

                          <div class="mb-1">
                              <label class="form-label" for="basic-addon-name">Nombres</label>
                              <input type="text" id="basic-addon-name" class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}" required autocomplete="name" autofocus
                              placeholder="Nombres" aria-label="Nombres"
                              aria-describedby="basic-addon-name" required />
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor escribe tus nombres.</div>
                          </div>
                          <div class="mb-1">
                              <label class="form-label" for="basic-addon-name">Apellidos</label>
                              <input type="text"  class="form-control" placeholder="Apellidos"
                                     aria-label="Apellidos" aria-describedby="basic-addon-name" required
                                     value="{{ old('lastname') }}"
                                     name="lastname"/>
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor escribe tus apellidos.</div>
                          </div>
                          <div class="mb-1">
                              <label class="form-label" for="basic-addon-number">Teléfono</label>
                              <input type="number" id="basic-addon-name" class="form-control" placeholder="Teléfono" aria-label="Teléfono" aria-describedby="basic-addon-name" required
                                    name="phone" value="{{ old('phone') }}"/>
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor escribe tu teléfono.</div>
                          </div>
                          <div class="mb-1">
                              <label class="form-label" for="basic-default-email1">Email</label>
                              <input type="email" id="basic-default-email1" class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                placeholder="john.rocker@email.com" aria-label="john.rocker@email.com" required  value="{{ old('email') }}"/>
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor escribe un email válido</div>
                              @error('email')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                          <div class="mb-1">
                              <label class="form-label" for="basic-default-nit">NIT</label>
                              <input type="text" class="form-control"
                              placeholder="47633-92" aria-label="NIT" required
                              name="nit" value="{{ old('nit') }}"
                              />
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor escribe un email válido</div>
                          </div>


                          <div class="mb-1">
                              <label class="form-label" for="basic-default-password1">Password</label>
                              <input type="password" id="basic-default-password1"
                              class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required autocomplete="new-password"
                              placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                               required />
                              <div class="valid-feedback">Bien!</div>
                              @error('password')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                            </div>
                              <div class="mb-1">
                                  <label class="form-label" for="basic-default-password1">Password</label>
                                  <input type="password" id="basic-default-password1"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                   required
                                   name="password_confirmation" required autocomplete="new-password"
                                   />
                                  <div class="valid-feedback">Bien!</div>
                                  <div class="invalid-feedback">Escribe tu password.</div>
                              </div>
                          <div class="mb-1">
                              <label class="form-label" for="bsDob">Fecha de nacimiento</label>
                              <input type="text" class="form-control picker"
                              name="birth" value="{{ old('birth') }}" id="bsDob" required />
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor escribe la fecha de nacimiento.</div>
                          </div>
                          <div class="mb-1">
                              <label class="form-label" class="d-block">Género</label>
                              <div class="form-check my-50">
                                  <input type="radio" id="validationRadio3"  class="form-check-input" required
                                  value="femenino"
                                  name="gender"/>
                                  <label class="form-check-label" for="validationRadio3">Mujer</label>
                              </div>
                              <div class="form-check">
                                  <input type="radio" id="validationRadio4"  class="form-check-input" required
                                  value="masculino"
                                  name="gender"/>
                                  <label class="form-check-label" for="validationRadio4">Hombre</label>
                              </div>
                          </div>
                          <div class="mb-1">
                              <label class="form-label" for="select-country1">País</label>
                              <select class="form-select" id="select-country1" required
                                      name="country" value="{{ old('country') }}">
                                  <option value="">Elige tu país</option>
                                  <option value="gt">Guatemala</option>
                                  <option value="sv">El Salvador</option>
                                  <option value="hn">Honduras</option>
                                  <option value="ni">Nicaragua</option>
                                  <option value="cr">Costa Rica</option>
                              </select>
                              <div class="valid-feedback">Bien!</div>
                              <div class="invalid-feedback">Por favor elige tu país</div>
                          </div>

                          <div class="mb-1">
                              <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="validationCheckBootstrap" required
                                  name="termsand" value="{{ old('termsand') }}"/>
                                  <label class="form-check-label" for="validationCheckBootstrap">Acepto quedar registrado y recibir eventualmente información sobre este género de música</label>
                                  <div class="invalid-feedback">Debes marcar este ítem.</div>
                              </div>
                          </div>
                          <button type="submit" class="btn btn-primary">Registrar</button>
                      </form>

              </div>
          </div>
          <!-- /Bootstrap Validation -->
      </div>
  </section>
  <!-- /Validation -->

    </div>
</div>

@endsection
