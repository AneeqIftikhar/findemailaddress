<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('page') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    
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


</head>
<body>
    
    <div style="display: flex;flex-flow: column;height: 100%">
        <div class="modal" tabindex="-1" role="dialog" id="login_again">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
              <div class="modal-header">
                
                <h5 class="modal-title">Session Expired</h5>
                        <button type="button" class="close" data-dismiss="modal" onClick="logout_modal()" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Login Again To Continue.</p>
              </div>
              <div class="modal-footer">
                <button  class="btn btn-success" onClick="logout_modal()" id="ajaxSubmit">Logout</button>
                </div>
            </div>
          </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="report_bounce">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
              <div class="modal-header">
                
                <h5 class="modal-title">Report Bounce</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" action="{{ route('report_bounce') }}" enctype="multipart/form-data" aria-label="{{ __('Report Bounce') }}">
                    @csrf
                        
                        <input type="hidden" name="bounce_email_id" id="bounce_email_id">
                        <input type="hidden" name="bounce_email_type" id="bounce_email_type">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea rows="5" name="bounce_message" placeholder="Place Your Bounce Email Here" class="form-control" id="bounce_message" required>
                                </textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <div class="form-group row">
                                <div class="col-sm-4 col-form-label text-md-right">
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Report</button>
                                </div>
                                
                            </div>
                        </div>
                            
                    </form>
              </div>
              
                <!-- <button  class="btn btn-success" onClick="report_bounce_ajax()" id="ajaxSubmit">Report</button> -->
               
            </div>
          </div>
        </div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="flex:0 1 auto">

            <div class="container">
                <a class="navbar-brand" href="{{URL::route('find')}}">
                    <img src="{{ asset('images/logo-resized.png') }}" style="max-height: 50px;" >
                    <!-- {{ config('app.name', 'Laravel') }} -->
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
                                <a class="nav-link" href="{{URL::route('find')}}"><span class="padding_right"><i class="fas fa-search fa-sm"></i></span>Find</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::route('verify')}}"><span class="padding_right"><i class="fas fa-user-check fa-sm"></i></span>Verify</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="padding_right"><i class="fas fa-server fa-sm"></i></span>History <span class="caret"></span>
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
                                    <span class="padding_right"><i class="fas fa-file-import fa-sm"></i></span>Bulk  <span class="caret"></span>
                                    <!-- <img src="{{ asset('images/coming-soon.png') }}" style="max-height: 100px;"> -->
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Find and Verify Emails (coming soon)
                                        <small id="fileHelp" class="form-text text-muted">Find and verify emails from list of names and domains</small>
                                    </a>
                                    <a class="dropdown-item" href="#">Verify Emails (coming soon)
                                        <small id="fileHelp" class="form-text text-muted">Verify a list of Emails</small></a>
                                    
                                </div>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#"><span class="padding_right"><i class="fas fa-folder-open fa-sm"></i></span>Files (coming soon)</a>
                            </li> -->
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
                                <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre onclick="updateCreditsLeft()">
                                    <table>
                                        <tr>
                                            <td style="padding: 2px 12px;">
                                                <i class="fas fa-user-circle fa-3x"></i>
                                            </td>
                                            <td style="padding: 2px; line-height: 19px;">
                                                <strong >{{ Auth::user()->name }}</strong>
                                                <br>
                                                <span class="badge badge-primary" >{{session('package_name')}} Plan</span>
                                            </td>
                                            <td style="padding: 2px 12px;">
                                                <i class="fas fa-angle-down fa-2x"></i>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    
                                    
                                    
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown" style="right:0px">

                                        <div class="container">
                                            <div class="row" style="padding-bottom: 10px !important;">
                                                <div class="col-12">
                                                    <div class='row mt-2'>
                                                        <div class="col-12">
                                                            <strong>{{session('package_name')}} Plan</strong>
                                                        </div>
                                                    </div>
                                                     <div class="row mt-2">
                                                        <div class="col-12" id="credits_left">
                                                            Credits Left <span id="credits_left_span">{{ Auth::user()->credits }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2 mt-2">
                                                        <div class="col-12">
                                                            <a href="{{URL::route('upgrade_account')}}" style="width:100%"class="btn btn-primary">Upgrade Account</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        
                                    <a class="dropdown-item" href="{{URL::route('subscriptions')}}"><span class="padding_right"><i class="fas fa-file-invoice-dollar fa-sm"></i></span>My Subscription</a>


                                     <a class="dropdown-item" href="{{url('tickets')}}"><span class="padding_right"><i class="fas fa-ticket-alt fa-sm"></i></span>My Tickets</a>
                                    <a class="dropdown-item" href="{{URL::route('account_settings')}}"><span class="padding_right"><i class="fas fa-user-cog fa-sm"></i></span>Account Settings</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <span class="padding_right"><i class="fas fa-sign-out-alt fa-sm"></i></span>{{ __('Logout') }}

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

        <main class="py-4" style="flex:1 1 auto;">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}" ></script> 
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.toaster.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    @stack('scripts')
   
    @yield('footer')
     <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
        function updateCreditsLeft(value)
        {
            // $('.progress-bar-credits').css('width', value+'%').attr('aria-valuenow', value);
        }
        function logout_modal()
        {
            window.location.href = "{{ route('login') }}";
            $('#login_again').modal('hide');
        }
        function report_bounce_modal(id,type)
        {
          $('#bounce_email_id').val(id);
          $('#bounce_email_type').val(type);
          $("#bounce_message").val('');
          $("#report_bounce").modal();          
        }
    </script>
</body>
 
</html>


