@extends('layouts.app')

@section('content')
<div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        <form method="POST" action="{{ route('password.email') }}">
                        @csrf
            <div class="card card-login card-hidden">
              <div class="card-header card-header-primary text-center">
                <h4 class="card-title">{{ __('Restablecer contraseña') }}</h4>
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
              @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                <!-- <p class="card-description text-center">Or Be Classical</p> -->
                <span class="bmd-form-group">
                  <div class="input-group">
                      
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">email</i>
                      </span>
                    </div>
                   
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Correo Electronico') }}">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                </span>

                
               
              </div>
              <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-primary btn-link btn-lg">
                    {{ __('Restablecer contraseña') }}
                    </button>
              </div>
            </div>
          </form>
        </div>
      </div>
</div>
@endsection
