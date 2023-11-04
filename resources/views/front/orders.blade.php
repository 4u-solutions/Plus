@extends('front.layouts.app')

@section('main-content')
  <div class="content-body">

    <h3 style="text-transform: capitalize;">{{ Auth::user()->name. ' ' .Auth::user()->lastname }}</h3>
    <p class="mb-2">
        A continuación encontrarás la lista de tus compras:
    </p>
    <!-- Botón nueva venta  -->
    {{-- --}}
    <a href="{{route('home')}}" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#editUser"
    style="margin-bottom:25px; margin-top:15px;">Comprar más boletos</a>
    <!-- fin botón nueva venta -->


      <!-- Tabla -->

      <div class="row" id="table-bordered">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                      <h4 class="card-title">Historial de tus compras</h4>
                  </div>

                  <div class="table-responsive">
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Boleto</th>
                                  <th>Cant.</th>
                                  <th>Venta</th>
                                  <th>Pago</th>
                                  <th>Fecha</th>
                                  <th>Acción</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach($buyings AS $vals)
                              <tr>
                                  <td>{{ $loop->index + 1 }}</td>
                                  <td>{{$vals["typeTicket"]}}</td>
                                  <td>{{$vals["quantity"]}}</td>
                                  <td>Q{{$vals["cost"]}}</td>
                                  <td>{{$vals["typePayment"]}}</td>
                                  <td>{{date("d/m/Y",strtotime($vals["created_at"]))}}</td>
                                  <td>
                                      <div class="dropdown">
                                          <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                              <i data-feather="more-vertical"></i>
                                          </button>
                                          <div class="dropdown-menu dropdown-menu-end">
                                              <a class="dropdown-item" href="#">
                                                  <i data-feather="mail" class="me-50"></i>
                                                  <span>Reenviar boleto</span>
                                              </a>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                            @endforeach
                             </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>

      <!-- Fin de tabla -->


      <!-- Edit User Modal -->
      <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
              <div class="modal-content">
                  <div class="modal-header bg-transparent">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body pb-5 px-sm-5 pt-50">
                      <div class="text-center mb-2">
                          <h1 class="mb-1">Nueva venta: Q 625</h1>
                      </div>
                      <form id="editUserForm" class="row gy-1 pt-75" action="{{route('orders.save')}}" method="post">
                        @method('POST')
                        @csrf
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserFirstName">Número de teléfono</label>
                              <input type="number" id="modalEditUserFirstName" name="phone" class="form-control" placeholder="55667788" data-msg="Ingrese teléfono" />
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserLastName">Nombres</label>
                              <input type="text" id="modalEditUserLastName" name="name" class="form-control" placeholder="Nombres" data-msg="Ingrese nombres" />
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserLastName">Apellidos</label>
                              <input type="text" id="modalEditUserLastName" name="lastname" class="form-control" placeholder="Apellidos" data-msg="Ingrese apellidos" />
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserEmail">Email:</label>
                              <input type="text" id="modalEditUserEmail" name="email" class="form-control" placeholder="example@domain.com" />
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserStatus">Cantidad de boletos</label>
                              <select id="modalEditUserStatus" name="quantity" class="form-select" aria-label="Default select example">
                                  <option selected>Cantidad</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                              </select>
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserStatus">Tipo de Boleto</label>
                              <select id="modalEditUserStatus" name="amount" class="form-select" aria-label="Default select example">
                                  <option selected>Tipo de boleto</option>
                                  <option value="vip_625">VIP Q 625</option>
                                  <option value="general_375">General Q 375</option>
                              </select>
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditTaxID">NIT</label>
                              <input type="text" id="modalEditTaxID" name="nit" class="form-control modal-edit-tax-id" placeholder="NIT" value="CF"/>
                          </div>
                          <div class="col-12 col-md-6">
                              <label class="form-label" for="modalEditUserCountry">País</label>
                              <select id="modalEditUserCountry" name="country" class="select2 form-select">
                                  <option value="">Select Value</option>
                                  <option value="gt">Guatemala</option>
                                  <option value="sv">El Salvador</option>
                                  <option value="hn">Honduras</option>
                                  <option value="ni">Nicaragua</option>
                                  <option value="cr">Costa Rica</option>
                              </select>
                          </div>
                          <div class="col-12 col-md-6">
                              <div class="d-flex align-items-center mt-1">
                                  <div class="form-check form-switch form-check-primary">
                                      <input type="checkbox" class="form-check-input" id="customSwitch10" unchecked
                                        name="paymethod"/>
                                      <label class="form-check-label" for="customSwitch10">
                                          <span class="switch-icon-left"><i data-feather="check"></i></span>
                                          <span class="switch-icon-right"><i data-feather="x"></i></span>
                                      </label>
                                  </div>
                                  <label class="form-check-label fw-bolder" for="customSwitch10">Pago con tarjeta</label>
                              </div>
                          </div>
                          <div class="col-12 text-center mt-2 pt-50">
                              <button type="submit" class="btn btn-primary me-1">Registrar venta</button>
                              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <!--/ Edit User Modal -->


    </div>
@endsection
