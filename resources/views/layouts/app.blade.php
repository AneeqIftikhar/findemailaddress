<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}" ></script> 
    <script src="{{ asset('js/app.js') }}" defer></script>
    
   
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet" type="text/css" >

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Place your kit's code here -->
    <!-- <script src="https://kit.fontawesome.com/5099c5b4c2.js"></script> -->


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    @stack('scripts')

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                            
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::route('find')}}"><span style="padding: 5px;"><i class="fas fa-search fa-sm"></i></span>Find</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::route('verify')}}"><span style="padding: 5px;"><i class="fas fa-user-check fa-sm"></i></span>Verify</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span style="padding: 5px;"><i class="fas fa-server fa-sm"></i></span>History <span class="caret"></span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{URL::route('find_history')}}">Find
                                        <small id="fileHelp" class="form-text text-muted">History of Found Emails</small>
                                    </a>
                                    <a class="dropdown-item" href="{{URL::route('verify_history')}}">Verify
                                        <small id="fileHelp" class="form-text text-muted">History of Verified Emails</small></a>
                                    
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span style="padding: 5px;"><i class="fas fa-file-import fa-sm"></i></span>Batch (coming soon) <span class="caret"></span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Find and Verify Emails (coming soon)
                                        <small id="fileHelp" class="form-text text-muted">Find and verify emails from list of names and domains</small>
                                    </a>
                                    <a class="dropdown-item" href="#">Verify Emails (coming soon)
                                        <small id="fileHelp" class="form-text text-muted">Verify a list of Emails</small></a>
                                    
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span style="padding: 5px;"><i class="fas fa-folder-open fa-sm"></i></span>Files (coming soon)</a>
                            </li>
                           <!--  <li class="nav-item">
                                <a class="nav-link" href="#">Advanced Search (coming soon)</a>
                            </li> -->
                           <!--  <li class="nav-item">
                                <a class="nav-link" href="leads">Get Leads</a>
                            </li> -->
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <table>
                                        <tr>
                                            <td style="padding: 2px;">
                                                <i class="fas fa-user-circle fa-3x"></i>
                                            </td>
                                            <td style="padding: 2px;">
                                                <strong>{{ Auth::user()->name }}</strong>
                                                <br>
                                                <span>{{session('package_name')}} Plan</span>
                                            </td>
                                            <td style="padding: 2px;">
                                                <i class="fas fa-angle-down fa-sm"></i>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    
                                    
                                    
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">

                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class='row'>
                                                        <div class="col-12">
                                                            <strong>{{session('package_name')}} Plan</strong>
                                                        </div>
                                                    </div>
                                                     <div class="row">
                                                        <div class="col-12">
                                                            <span>Credits Left {{ Auth::user()->credits }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-primary">Upgrade Account</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>

                                        
                                        
                                        
                                    
                                    <a class="dropdown-item" href="{{URL::route('account_settings')}}"><span style="padding-right: 5px;"><i class="fas fa-user-cog fa-sm"></i></span>Account Settings</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <span style="padding-right: 5px;"><i class="fas fa-sign-out-alt fa-sm"></i></span>{{ __('Logout') }}

                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
