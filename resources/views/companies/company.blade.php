@extends('layouts.app')
@section('page', $json->name)
@section('content')
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        main.py-4 {
            padding-top: 0 !important;
        }

        .peopleLink {
            color: black;
        }

        .peopleLink:hover {
            color: black;
        }

        .cardSkew {
            /*-webkit-clip-path: polygon(0 0, 100% 0%, 100% 60%, 0 100%);;*/
            /*!*-webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0 80%);*!*/
            /*clip-path: polygon(0 0, 100% 0%, 100% 88%, 0 100%);*/
            border-radius: 15px !important;
        }

        .scrollPeople::-webkit-scrollbar {
            width: 5px;
        }

        .scrollPeople::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollPeople::-webkit-scrollbar-thumb {
            background: #888;
        }

        .scrollPeople::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .nav-link.active.show {
            background-color: #292e4b !important;
            color: #fff !important;
        }

        .about_tab {
            border-top-left-radius: 15px !important;

        }

        .employees_tab {
            border-top-right-radius: 15px !important;
        }

        @media only screen and (max-width: 600px) {
            .employees_tab {
                border-top-right-radius: 0px !important;
            }

            .about_tab {
                border-top-left-radius: 0px !important;

            }
        }

        .modal-confirm {
            color: #636363;
            width: 325px;
            margin: 80px auto 0;
        }

        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
        }

        .modal-confirm .modal-header {
            border-bottom: none;
            position: relative;
        }

        .modal-confirm h4 {
            text-align: center;
            font-size: 26px;
            margin: 30px 0 -15px;
        }

        .modal-confirm .form-control, .modal-confirm .btn {
            min-height: 40px;
            border-radius: 3px;
        }

        .modal-confirm .close {
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .modal-confirm .modal-footer {
            border: none;
            text-align: center;
            border-radius: 5px;
            font-size: 13px;
        }

        .modal-confirm .icon-box {
            color: #fff;
            position: absolute;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: -70px;
            width: 95px;
            height: 95px;
            border-radius: 50%;
            z-index: 9;
            background: #ef513a;
            padding: 15px;
            text-align: center;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
        }

        .modal-confirm .icon-box i {
            font-size: 56px;
            position: relative;
            top: 4px;
        }

        .modal-confirm .btn {
            color: #fff;
            border-radius: 4px;
            background: #ef513a;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            border: none;
        }

        .modal-confirm .btn:hover, .modal-confirm .btn:focus {
            background: #da2c12;
            outline: none;
        }
    </style>

    <div class="container pb-3 pt-0 mt-4" style="">
        <div class="justify-content-center">
            <div>
                <div
                    class="card border-0 shadow rounded p-0 col-md-4 mr-5 cardSkew min-height float-left mb-3">
                    <div class="col-md-12 p-0 ">
                        <img src="{{url('images/companyCover2.jpg')}}" class="img-fluid p-0"
                             style="border-top-left-radius: 15px;border-top-right-radius: 15px" alt="">
                    </div>
                    <div class="row p-4 mb-4">

                        <div class="col-md-6 px-3 py-3 " style="margin-top: -6em">
                            <img src="{{url('images/company-logo-placeholder.webp')}}"
                                 class="img-fluid w-100 border rounded bg-light">
                        </div>

                        <div class="col-md-6 px-0 pt-0 pr-3">
                            @if($staffCount != null)
                                <p class="mb-0 text-center"><small
                                        class="text-dark w-100"><b>Company Size: </b>{{$staffCount}}</small></p>
                            @endif
                            @if($json->founded_on_year != null)
                                <p class="mb-0 text-center"><small class="card-text w-100"><b>Founded on
                                            Year: </b>{{$json->founded_on_year}}</small></p>
                            @endif
                        </div>
                        <div class="col-md-12 px-0 pr-3 px-3 pt-0">
                            <hr class="mb-1 mt-1">
                            <div class="card-block px-0">
                                <h4 class="card-title mb-0">{{$json->name}}&nbsp; <a href="{{$json->linkedin_url}}"
                                                                                     class="card-text"><i
                                            class="fab fa-linkedin-in" aria-hidden="true"></i></a></h4>

                                <small class="text-dark"><b>Industry: </b>{{$json->industry[0]->name}}</small>
                                @if($json->domain != null)
                                <p class="mb-1"><a rel="nofollow" href="{{$json->domain}}" class="card-text mb-0">Visit Website</a></p>
                                @endif
                                @if($json->tagline != null)
                                    <p class="card-text font-italic">"{{$json->tagline}}"</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card border-0 shadow rounded mb-4 col-md-7 mh-100 px-0 pb-3"
                     style="background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,1)); border-radius: 15px !important;">
                    <ul class="nav nav-pills nav-justified " style="border-radius: 15px !important;">
                        <li class="nav-item"><a data-toggle="pill" href="#home"
                                                class="nav-link active show rounded-0 border-top border-bottom about_tab">About</a></li>
                        <li class="nav-item"><a data-toggle="tab" href="#menu1" class="nav-link rounded-0 border">Locations &
                                Specialities</a>
                        </li>
                        <li class="nav-item"><a data-toggle="tab" href="#menu2" class="nav-link rounded-0 border-top border-bottom employees_tab">Employees <span
                                    class="badge badge-success">{{count($json->people)}}</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active show">
                            <div class="col-md-12 px-0 py-3 pr-3">
                                <div class="card-block px-3">
                                    <h4 class="card-title">About {{$json->name}}</h4>
                                    <p class="card-text " style="white-space: pre-wrap">{{$json->description}}</p>

                                </div>


                            </div>
                        </div>
                        <div id="menu1" class="tab-pane fade">

                            @if(count($json->speciality) > 0)
                                <div class="card-block px-3 mt-3">
                                    <h4 class="card-title">Locations</h4>

                                    @foreach($json->locations as $locations)
                                        <span
                                            class="mb-0 badge badge-pill badge-warning ">{{$locations->city.', '.$locations->country}}</span>
                                    @endforeach

                                </div>
                            @endif

                            @if(count($json->speciality) > 0)
                                <div class="card-block px-3 mt-3">
                                    <h4 class="card-title">Specialities</h4>

                                    @foreach($json->speciality as $speciality)
                                        <span class="mb-0 badge badge-pill badge-primary ">{{$speciality->name}}</span>
                                    @endforeach

                                </div>
                            @endif
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            @if(count($json->people) > 0)
                                <div class="container-fluid scrollPeople" style="overflow-y: scroll; height:78.5vh;">
                                    <div class="row ">
                                        <div class="col-md-12 px-0 py-3">
                                            <div class="card-block px-3 mt-3">

                                                @foreach($json->people as  $key => $people)

                                                    <div class="card border-0 shadow-sm mb-3">
                                                        <div class="card-body row">
                                                            <div class="col-sm-6">
                                                                <a href="{{url('/people')}}/{{$people->slug}}"
                                                                   class="peopleLink"
                                                                   style="text-decoration: none"><h5
                                                                        class="card-title mb-0">{{$people->full_name}}</h5></a>
                                                                <small class="text-muted">{{$people->position}}</small>
                                                            </div>

                                                            <div
                                                                class="col-sm-6 px-0 py-3 pr-3 px-3 d-flex justify-content-center">
                                                                <div class=" text-center  align-self-center w-100">
                                                                    @auth
                                                                        <button data-id="{{$people->id}}" data-companyid="{{$json->id}}" data-slug="{{$people->slug}}" data-first="{{$people->first_name}}" data-last="{{$people->last_name}}" data-domain="{{$json->domain}}" class="btn btn-outline-primary btn-sm w-50 getEmail">
                                                                            Get Email
                                                                        </button>
                                                                    @endauth
                                                                    @guest
                                                                        <button data-toggle="modal" data-target=".bd-example-modal-sm" class="btn btn-outline-primary btn-sm w-50">
                                                                            Get Email
                                                                        </button>

                                                                        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                                                                <div class="modal-content p-4">
                                                                                    <p>Login Required!</p>

                                                                                    <div class="col-sm-12">
                                                                                        <a href="{{ route('login') }}" class="btn btn-primary w-100 text-light">Login</a>
                                                                                    </div>
                                                                                    <div class="col-sm-12">

                                                                                        <a href="{{ route('register') }}">Don't have an account? Signup</a>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endguest
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger mt-4" role="alert">
                                    No Employees Found!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="errorModal" class="modal fade">
                    <div class="modal-dialog modal-confirm">
                        <div class="modal-content">
                            <div class="modal-header bg-white text-dark">
                                <div class="icon-box">
                                    <i class="material-icons">&#xE5CD;</i>
                                </div>
                                <h4 class="modal-title">Sorry!</h4>
                            </div>
                            <div class="modal-body">
                                <p class="text-center errorTextModal">Your transaction has failed. Please go back and try again.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger btn-block" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>

            $('.getEmail').click(function () {
                var buttonid = $(this).attr('data-id');
                var slug = $(this).attr('data-slug');
                var companyId = $(this).attr('data-companyid');
                var peopleId = $(this).attr('data-id');
                var buttonOldText = $('button[data-id=' + buttonid + ']').text();

                $('button[data-id=' + buttonid + ']').text('Loading');
                $('button[data-id=' + buttonid + ']').attr('disabled', '');

                $.ajax({
                    method: 'POST',
                    dataType: 'json',
                    data: {'slug': slug, 'companyId': companyId, 'peopleId': peopleId ,"_token": "{{ csrf_token() }}"},
                    url: '{{url('/getEmail')}}',
                    success: function (response) {
                        if ((response['email'] != null) && (response['email_found'] == 'yes') && (response['email_status'] == 'VALID')) {
                            $('button[data-id=' + buttonid + ']').replaceWith('<small class="text-success">' + response['email'] + '</small>');
                            $('#credits_left_span').text(response['credits_left']);
                        } else if ((response['email'] != null) && (response['email_found'] == 'yes') && (response['email_status'] == 'RISKY')) {
                            $('button[data-id=' + buttonid + ']').replaceWith('<small class="text-danger">' + response['email'] + '</small>');
                        } else if ((response['email'] != null) && (response['email_found'] == 'yes') && (response['email_status'] == 'CATCH_ALL')) {
                            $('button[data-id=' + buttonid + ']').replaceWith('<small class="text-warning">' + response['email'] + '</small>');
                        } else if (response['email_found'] == 'no') {
                            $('button[data-id=' + buttonid + ']').removeAttr('disabled');
                            $('button[data-id=' + buttonid + ']').text(buttonOldText);
                            $('.errorTextModal').text('You do not have enough credits.');
                            $('#errorModal').modal('show');
                        }else{
                            $('button[data-id=' + buttonid + ']').replaceWith('<small class="text-danger">Email Not Found</small>');
                        }
                    }
                });
            });

        </script>

@endsection
