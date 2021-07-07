<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>E-rac</title>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
         <!-- Scripts -->
         
         <script src="{{ asset('js/landing.js') }}"></script>
        <script src="{{ asset('js/bootstrap-material-design.min.js') }}"></script>
        <script src="{{ asset('js/material-kit.js') }}"></script>
        

         <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script>
            var logout = function() {
                axios.post('/logout')
                .then((response) => {
                    location.reload();
                })
                .catch((error) => {
                    console.log('error',error);
                })
            }
        </script>
       
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            

            </div>

            
            <nav class="navbar fixed-top navbar-color-on-scroll navbar-transparent navbar-expand-lg">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-translate">
                    <a class="navbar-brand" href="/">
                        <img src="images/new_logo.png" alt="">
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
                        <a href="#pablo" class="nav-link">
                            Home
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="#pablo" class="nav-link">
                            Acerca de
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
                                    <a href="{{ url('/app#/dashboard') }}" class="nav-link"><i class="fa fa-tachometer" aria-hidden="true"></i> Panel De Usuario</a>
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



            <div class="page-header header-filter" data-parallax="true" style="background-image: url('images/home.jpg')">
                <div class="container">
                <div class="row">
                    <div class="col-md-6">
                    <h1 class="title">Bienvenidos a E-Rac.</h1>
                    <h4>Every landing page needs a small description after the big bold title, that&apos;s why we added this text here. Add here all the information that can make you or your product create the first impression.</h4>
                    <br>
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank" class="btn btn-danger btn-raised btn-lg">
                        <i class="fa fa-play"></i> Watch video
                    </a>
                    </div>
                </div>
                </div>
            </div>
            <div class="main main-raised">
                <div class="container">
                    <div class="section text-center">
                        <div class="row">
                        <div class="col-md-8 ml-auto mr-auto">
                            <h2 class="title">Let&apos;s talk product</h2>
                            <h5 class="description">This is the paragraph where you can write more details about your product. Keep you user engaged by providing meaningful information. Remember that by this time, the user is curious, otherwise he wouldn&apos;t scroll to get here. Add a button if you want the user to see more.</h5>
                        </div>
                        </div>
                        <div class="features">
                        <div class="row">
                            <div class="col-md-4">
                            <div class="info">
                                <div class="icon icon-info">
                                <i class="material-icons">chat</i>
                                </div>
                                <h4 class="info-title">Free Chat</h4>
                                <p>Divide details about your product or agency work into parts. Write a few lines about each one. A paragraph describing a feature will be enough.</p>
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="info">
                                <div class="icon icon-success">
                                <i class="material-icons">verified_user</i>
                                </div>
                                <h4 class="info-title">Verified Users</h4>
                                <p>Divide details about your product or agency work into parts. Write a few lines about each one. A paragraph describing a feature will be enough.</p>
                            </div>
                            </div>
                            <div class="col-md-4">
                            <div class="info">
                                <div class="icon icon-danger">
                                <i class="material-icons">fingerprint</i>
                                </div>
                                <h4 class="info-title">Fingerprint</h4>
                                <p>Divide details about your product or agency work into parts. Write a few lines about each one. A paragraph describing a feature will be enough.</p>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer footer-default">
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
        
    </body>
</html>
