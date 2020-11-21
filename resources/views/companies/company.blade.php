@extends('layouts.app')
@section('page', $json->name)
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
        <div class="card border-0 shadow rounded p-4 mb-4"
             style="background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,1)); ">
            <div class="row ">
                <div class="col-md-2 px-3 py-3">
                    <img src="{{url('images/company-logo-placeholder.webp')}}"
                         class="img-fluid w-100 border">
                </div>
                <div class="col-md-10 px-0 py-3 pr-3 px-3">
                    <div class="card-block px-0">
                        <h4 class="card-title mb-0">{{$json->name}}&nbsp; <a href="{{$json->linkedin_url}}" class="card-text"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a></h4>

                        <small class="text-muted"><b>Industry: </b>{{$json->industry[0]->name}}</small> &bull; <small
                            class="text-muted"><b>Company Size: </b>{{  round($json->staff_count,-3)}}</small>
                        {{--                        <p class="card-text text-truncate"><b>Description: </b>Lorem ipsum dolor sit amet, consectetur--}}
                        {{--                            adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim--}}
                        {{--                            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo--}}
                        {{--                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu--}}
                        {{--                            fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui--}}
                        {{--                            officia deserunt mollit anim id est laborum.</p>--}}

                        <p class="card-text mb-0">{{preg_replace( "#^[^:/.]*[:/]+#i", "", trim($json->domain, '/') )}}</p>
                        @if($json->founded_on_year != null)
                            <p class="card-text">Founded on Year: <b>{{$json->founded_on_year}}</b></p>
                        @endif
                        @if($json->tagline != null)
                            <p class="card-text font-italic">"{{$json->tagline}}"</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="card border-0 shadow rounded p-4 mb-4">
            <div class="row ">
                <div class="col-md-12 px-0 py-3 pr-3">
                    <div class="card-block px-3">
                        <h4 class="card-title">About</h4>
                        <p class="card-text " style="white-space: pre-wrap">{{$json->description}}</p>

                    </div>
                    @if(count($json->speciality) > 0)
                        <hr>
                        <div class="card-block px-3 mt-3">
                            <h4 class="card-title">Locations</h4>

                            @foreach($json->locations as $locations)
                                <span
                                    class="mb-0 badge badge-pill badge-warning ">{{$locations->city.', '.$locations->country}}</span>
                            @endforeach

                        </div>
                    @endif
                    @if(count($json->speciality) > 0)
                        <hr>
                        <div class="card-block px-3 mt-3">
                            <h4 class="card-title">Specialities</h4>

                            @foreach($json->speciality as $speciality)
                                <span class="mb-0 badge badge-pill badge-primary ">{{$speciality->name}}</span>
                            @endforeach

                        </div>
                    @endif
                </div>
            </div>
        </div>


        <div class="card border-0 shadow rounded p-4 mb-4">
            <div class="card-header bg-white text-dark p-0">
                <h4 class="card-title">{{$json->name."'s"}} Employees</h4>
            </div>
            <div class="row ">
                <div class="col-md-12 px-0 py-3 ">
                    <div class="card-block px-3">
                        @foreach($json->people as  $key => $people)

                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body row">
                                        <div class="col-sm-8">
                                            <a href="{{url('/people')}}/{{$people->slug}}" class="peopleLink"
                                               style="text-decoration: none"> <h5 class="card-title mb-0">{{$key+1}}. {{$people->full_name}}</h5></a>
                                            <small class="text-muted">{{$people->position}}</small>
                                            {{--                                        <p class="card-text"><b>Email: </b><span class="blurry-text">stu@mail.com</span>--}}
                                            {{--                                        </p>--}}
                                        </div>

                                        <div class="col-sm-4 px-0 py-3 pr-3 px-3 d-flex justify-content-center">
                                            <div class=" text-center  align-self-center">
                                                <button class="btn btn-outline-primary btn-sm ">Get {{$people->first_name."'s"}} Email</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
