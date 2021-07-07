@extends('layouts.app')

@section('content')
<div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
            <div class="card card-login card-hidden">
              <div class="card-header card-header-primary text-center">
                <h4 class="card-title">{{ __('Verifique su correo electronico') }}</h4>
                <!-- <div class="social-line">
                  <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                    <i class="fa fa-facebook-square"></i>
                  </a>
                  <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                    <i class="fa fa-twitter"></i>
                  </a>
                  <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                    <i class="fa fa-google-plus"></i>
                  </a>
                </div> -->
              </div>
              <div class="card-body ">
                  
              @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nuevo enlace de verificacion a sido enviado.') }}
                        </div>
                    @endif
                
                    {{ __('Antes de continuar, revise su correo electrónico para obtener un enlace de verificación.') }}
                    {{ __('Si no recibiste el correo electrónico') }},
                                
               
              </div>
              <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-primary btn-link btn-lg">
                    {{ __('haga clic aquí para solicitar otro') }}
                    </button>
              </div>
            </div>
          </form>
        </div>
      </div>
</div>
@endsection
