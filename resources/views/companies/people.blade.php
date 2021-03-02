@extends('layouts.app')
@section('page', $json->full_name."'s Email | ".$json->company->name."'s ".$json->position.' Email')
@section('content')
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        main.py-4 {
            padding-top: 0 !important;
        }

        .blurry-text {
            color: transparent;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .peopleLink {
            color: black;
        }

        .peopleLink:hover {
            color: black;
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
        <div class="justify-content-center ">
            <div
                class="card border-0 shadow p-0 col-md-4 mr-5 cardSkew min-height float-left mb-3">
                <div class="col-md-12 p-0 ">
                    <img src="{{url('images/companyCover2.jpg')}}" class="img-fluid p-0"
                         style="border-top-left-radius: 15px;border-top-right-radius: 15px" alt="">
                </div>
                <div class="row p-4 mb-4">

                    <div class="col-md-6 px-3 py-3 " style="margin-top: -6em">
                        <img src="{{url('images/people.png')}}"
                             class="img-fluid w-100 border rounded bg-light">
                    </div>

                    <div class="col-md-12 px-0 pr-3 px-3 pt-0">
                        <hr class="mb-1 mt-1">
                        <div class="card-block px-0">
                            <h4 class="card-title mb-0">{{$json->full_name}}</h4>

                            <small class="text-muted">{{$json->position}} at<b> <a href="{{url('/company').'/'.$json->company->slug}}">{{$json->company->name}}</a> </b></small>
                            <p class="card-text">{{$json->geo_region}}</p>

                            @auth
                                <button data-id="{{$json->id}}" data-companyid="{{$json->company->id}}" data-slug="{{$json->slug}}" data-first="{{$json->first_name}}" data-last="{{$json->last_name}}" data-domain="{{$json->company->domain}}" class="btn btn-outline-primary btn-sm w-100 getEmail">
                                    Get Email
                                </button>
                            @endauth
                            @guest
                                <button data-toggle="modal" data-target=".bd-example-modal-sm" class="btn btn-outline-primary btn-sm w-100">
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



                <div class="card border-0 shadow rounded p-4 col-md-7" style="border-radius: 15px !important;">
                    <div class="row ">

                        <div class="col-md-12 px-3 py-3">
                            <div class="card-block px-0">
                                @if($json->position_description != null)
                                <h4 class="card-title">Position Description</h4>
                                <p class="card-text" style="white-space: pre-wrap">{{$json->position_description}}</p>
                                <hr>
                                @endif
                                <h4 class="card-title">Company</h4>
                                <p class="card-text"><a href="{{url('/company').'/'.$json->company->slug}}">{{$json->company->name}}</a></p>
                            </div>
                        </div>
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
                        <p class="text-center errorTextModal">Your request has failed. Please go back and try again.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-block" data-dismiss="modal">OK</button>
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
