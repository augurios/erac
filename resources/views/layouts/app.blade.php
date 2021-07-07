<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    

    
     <!-- Scripts -->
     <script>
        var user = {}
        var authenticated = false;
        var navUrl = {!! json_encode([
            'site' => url('/'),
            'logout' => url('/logout'),
            'login' => url('/login'),
            'register' => url('/register')
        ]) !!};
    </script>

    @if (!Auth::guest())
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
     <!--     Fonts and icons     -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,300italic,400italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" rel="stylesheet">

    <script>
        authenticated = true
        user = authUser = {!! json_encode(Auth::user()) !!}
          
        let rolesArray = {!! json_encode(Auth::user()->getRoleNames()) !!}
        user.role = rolesArray[0];
    </script>
     <!-- Scripts -->
     <script src="{{ asset('js/app.js') }}" defer></script>

    @else
           <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        
        
        <script src="{{ asset('js/landing.js') }}"></script>
        <script src="{{ asset('js/bootstrap-material-design.min.js') }}"></script>
        
        <script src="{{ asset('js/material-kit.js') }}"></script>
        

         <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif

   
</head>

@auth
 <body class="sidebar-mini sidebar-image">    
@else
 <body class="
  
  @if(!Route::is('register')) login-page @endif 
  @if(Route::is('register')) signup-page @endif 

  sidebar-collapse">
    <nav class="navbar fixed-top navbar-color-on-scroll navbar-transparent navbar-expand-lg">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-translate">
            <a class="navbar-brand" href="/">
                <img src="/images/new_logo.png" alt="">
            </a>
            <button type="button" class="ml-auto navbar-toggler" data-toggle="collapse" data-target="#navigation-example4">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
            </div>
            <div class="collapse navbar-collapse" id="navigation-example4">
            <ul class="navbar-nav navbar-center ml-auto">
                <li class="nav-item">
                <a href="/" class="nav-link">
                    Home
                </a>
                </li>
                <li class="nav-item">
                <a href="#pablo" class="nav-link">
                    Acerca De
                </a>
                </li>
                <li class="nav-item">
                <a href="#pablo" class="nav-link">
                    Productos
                </a>
                </li>
                <li class="nav-item">
                <a href="#pablo" class="nav-link">
                    Contactenos
                </a>
                </li>
            </ul>
            @if (Route::has('login'))
            <ul class="navbar-nav ml-auto">
               
               
                        @auth
                        <li class="nav-item">
                            <a href="{{ url('/home') }}" class="nav-link"><i class="fa fa-tachometer" aria-hidden="true"></i> Panel De Usuario</a>
                        </li>
                        <li class="nav-item">
                                <a href="javascript:logout();" class="nav-link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> cerrar sesion</a>
                            </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link"><i class="fa fa-sign-in" aria-hidden="true"></i> Iniciar Sesion</a>
                        </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Registrarse</a>
                            </li>
                            @endif
                        @endauth
                
            </ul>
            @endif
            </div>
        </div>
    </nav>
    <div class="page-header header-filter" style="background-image: url('/images/bg7.jpg'); background-size: cover; background-position: top center;">
      @endauth

    @yield('content')
    <!-- built files will be auto injected -->

    @auth
       
    @else
    
      <footer class="footer">
        <div class="container">
          <nav class="float-left">
            <ul>
              <li>
                <a href="/">
                  Inicio
                </a>
              </li>
              <li>
                <a href="/about">
                  Acerca De
                </a>
              </li>
              <li>
                <a href="/planes">
                  Planes
                </a>
              </li>
              <li>
                <a href="/contacto">
                  Contacto
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, Hecho con <i class="material-icons">favorite</i> por
            <a href="https://a-valerio.com" target="_blank">A.Valerio</a>.
          </div>
        </div>
      </footer>
    </div>
    @endauth     
  </body>
</html>
