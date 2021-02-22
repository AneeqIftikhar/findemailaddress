@extends('layouts.app')
@section('page', $json->full_name)
@section('content')
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

                            <small class="text-muted">{{$json->position}} at<b> {{$json->company->name}} </b></small>
                            <p class="card-text">{{$json->geo_region}}</p>

                            <button class="btn btn-outline-primary btn-sm w-100">Get {{$json->first_name."'s"}} Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            @if($json->position_description != null)
                <div class="card border-0 shadow rounded p-4 col-md-7" style="border-radius: 15px !important;">
                    <div class="row ">

                        <div class="col-md-12 px-3 py-3">
                            <div class="card-block px-0">
                                <h4 class="card-title">Position Description</h4>
                                <p class="card-text" style="white-space: pre-wrap">{{$json->position_description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
