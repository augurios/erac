@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 ml-auto mr-auto">
      <div class="card card-signup">
        <h2 class="card-title text-center">{{ __('Registrarse en E-Rac') }}</h2>
        <div class="card-body">
          <div class="row">
            <!-- <div class="col-md-5 ml-auto">
              <div class="info info-horizontal">
                <div class="icon icon-rose">
                  <i class="material-icons">timeline</i>
                </div>
                <div class="description">
                  <h4 class="info-title">Marketing</h4>
                  <p class="description">
                    We've created the marketing campaign of the website. It was a very interesting collaboration.
                  </p>
                </div>
              </div>
              <div class="info info-horizontal">
                <div class="icon icon-primary">
                  <i class="material-icons">code</i>
                </div>
                <div class="description">
                  <h4 class="info-title">Fully Coded in HTML5</h4>
                  <p class="description">
                    We've developed the website with HTML5 and CSS3. The client has access to the code using GitHub.
                  </p>
                </div>
              </div>
              <div class="info info-horizontal">
                <div class="icon icon-info">
                  <i class="material-icons">group</i>
                </div>
                <div class="description">
                  <h4 class="info-title">Built Audience</h4>
                  <p class="description">
                    There is also a Fully Customizable CMS Admin Dashboard for this product.
                  </p>
                </div>
              </div>
            </div> -->
            <div class="col-md-12 mr-auto">
              <!-- <div class="social text-center">
                    <button class="btn btn-just-icon btn-round btn-twitter">
                      <i class="fa fa-twitter"></i>
                    </button>
                    <button class="btn btn-just-icon btn-round btn-dribbble">
                      <i class="fa fa-dribbble"></i>
                    </button>
                    <button class="btn btn-just-icon btn-round btn-facebook">
                      <i class="fa fa-facebook"> </i>
                    </button>
                    <h4> or be classical </h4>
                  </div> -->
              <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">face</i>
                      </span>
                    </div>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Nombre') }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">face</i>
                      </span>
                    </div>
                    <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus placeholder="{{ __('Apellido') }}">
                    @error('lastname')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">phone</i>
                      </span>
                    </div>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus placeholder="{{ __('Telefono') }}">
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>


                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">mail</i>
                      </span>
                    </div>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Correo Electronico') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">how_to_reg</i>
                      </span>
                    </div>
                    <select class="selectpicker" data-style="select-with-transition" title="Cuenta" data-size="7" name="role">
                      <option disabled>Seleccione el tipo</option>
                      <option value="client">Persona Fisica</option>
                      <option value="company">Empresa</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">featured_video</i>
                      </span>
                    </div>
                    <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" value="{{ old('cedula') }}" required autocomplete="cedula" autofocus placeholder="{{ __('Cedula') }}">
                    @error('cedula')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>


                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">lock_outline</i>
                      </span>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Contraseña') }}" class="form-control" />
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">lock_outline</i>
                      </span>
                    </div>

                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ __('Confirmar Contraseña') }}">
                  </div>
                </div>


                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="" checked required>
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                    Estoy de acuerdo con los  <a href="#something">Terminos y condiciones</a>.
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-round">
                    {{ __('Registrarse') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <style>
    .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
      width: calc(100% - 55px);
    }
  </style>
</div>
@endsection