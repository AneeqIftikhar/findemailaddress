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
    <div class="mh-25 text-center beforeText p-0 bg-primary">
        <img src="{{url('images/coverCompany.png')}}" class="img-fluid h-100 p-0" alt="">
    </div>
    <div class="container pb-3 pt-0" style="margin-top: -5em">
        <div class="card border-0 shadow rounded p-4 mb-4" style="background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,1)); ">
            <div class="row ">
                <div class="col-md-2 px-3 py-3">
                    <img src="{{url('images/people.png')}}"
                         class="img-fluid w-100 border">
                </div>
                <div class="col-md-8 px-0 py-3 pr-3 px-3">
                    <div class="card-block px-0">
                        <h4 class="card-title mb-0">{{$json->full_name}}</h4>
                        <small class="text-muted">{{$json->position}} at<b> {{$json->company->name}} </b></small>
                        <p class="card-text">{{$json->geo_region}}</p>
                    </div>
                </div>

                <div class="col-md-2 px-0 py-3 pr-3 px-3 d-flex justify-content-center">
                    <div class="card-block px-0 text-center  align-self-center">
                        <button class="btn btn-outline-primary btn-sm ">Get {{$json->first_name."'s"}} Email</button>
                    </div>
                </div>
            </div>
        </div>
        @if($json->position_description != null)
        <div class="card border-0 shadow rounded p-4 mb-4">
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

@endsection
