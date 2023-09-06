@extends('admin.layouts.app')

@section('main-content')
  <!-- row opened -->
  <div class="row mt-5">
    <div class="col-xl-12 product">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>
          <div class="card-options ">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="row">
                <div class="col-2">
                  <div class="card box-shadow-0 overflow-hidden">
                    <div class="card-body p-4">
                      <div class="text-center">
                         <i class="fa fa-tasks fa-2x text-primary text-primary-shadow"></i>
                         <h3 class="mt-3 mb-0 ">{{$tickets}}</h3>
                         <small class="text-muted">Boletos vendidos</small>
                      </div>
                    </div>
                    <div class="card-body p-4">
                      <div class="text-center">
                         <i class="fa fa-tasks fa-2x text-primary text-primary-shadow"></i>
                         <h3 class="mt-3 mb-0 ">{{$userby}}</h3>
                         <small class="text-muted">Usuarios registrados</small>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- row closed -->
@endsection
