@extends('layouts.app')
@section('page', $json->name)
@section('content')

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

        .nav-link.active.show{
            background-color: #292e4b !important;
            color: #fff !important;
        }
    </style>

    <div class="container pb-3 pt-0 mt-2" style="">
        <div class="justify-content-center">
            <div
                class="card border-0 shadow rounded p-0 col-md-4 mr-5 cardSkew min-height float-left">
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
                            <p class="mb-1"><a href="{{$json->domain}}" class="card-text mb-0">Visit Website</a></p>

                            @if($json->tagline != null)
                                <p class="card-text font-italic">"{{$json->tagline}}"</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="card border-0 shadow rounded mb-4 col-md-7 mh-100 px-0 pb-3"
                 style="background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,1));">
                <ul class="nav nav-pills nav-justified">
                    <li class="nav-item"><a data-toggle="pill" href="#home"
                                            class="nav-link active show">About</a></li>
                    <li class="nav-item"><a data-toggle="tab" href="#menu1" class="nav-link">Locations &
                            Specialities</a>
                    </li>
                    <li class="nav-item"><a data-toggle="tab" href="#menu2" class="nav-link">Employees <span
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
                                                        <div class="col-sm-8">
                                                            <a href="{{url('/people')}}/{{$people->slug}}"
                                                               class="peopleLink"
                                                               style="text-decoration: none"><h5
                                                                    class="card-title mb-0">{{$key+1}}
                                                                    . {{$people->full_name}}</h5></a>
                                                            <small class="text-muted">{{$people->position}}</small>
                                                        </div>

                                                        <div
                                                            class="col-sm-4 px-0 py-3 pr-3 px-3 d-flex justify-content-center">
                                                            <div class=" text-center  align-self-center w-100">
                                                                <button class="btn btn-outline-primary btn-sm w-100">
                                                                    Get {{$people->first_name."'s"}} Email
                                                                </button>
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
                <div class="row ">


                </div>
            </div>


        </div>
    </div>

@endsection
