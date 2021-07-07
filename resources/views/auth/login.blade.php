@extends('layouts.app')

@section('content')

    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
          <form method="POST" action="{{ route('login') }}">
                        @csrf
            <div class="card card-login card-hidden">
              <div class="card-header card-header-primary text-center">
                <h4 class="card-title">Iniciar Sesion</h4>
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
                <!-- <p class="card-description text-center">Or Be Classical</p> -->
                <span class="bmd-form-group">
                  <div class="input-group">
                      
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">email</i>
                      </span>
                    </div>
                   
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo Electronico" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                     @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                  </div>
                </span>

                
                <span class="bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">lock_outline</i>
                      </span>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  </div>
                </span>
              </div>
              <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-primary btn-link btn-lg">
                       Iniciar
                    </button>
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                           Olvido su contraseña? 
                        </a>
                    @endif
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
@endsection
