@extends('layouts.app')
@section('page')
    {{ "History" }}
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4>Verify Email History</h4></div>

                    <div class="card-body">
                        @if (count($emails)>0)
                            <div class="row">

                                <div class="col-md-12 mb-2" align="right">


                                    <button class="btn btn-primary nav-item dropdown">
                                        <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
                                           style="padding: 0px; color: white">
                                            <span><i class="fas fa-download fa-lg"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            <a class="dropdown-item"
                                               href="{{URL::route('downloadverifiedrecords',['type'=>'csv','records'=>'all'])}}">
                                                Download ALL (CSV)
                                            </a>
                                            <a class="dropdown-item"
                                               href="{{URL::route('downloadverifiedrecords',['type'=>'xls','records'=>'all'])}}">
                                                Download ALL (XLS)
                                            </a>
                                            <a class="dropdown-item"
                                               href="{{URL::route('downloadverifiedrecords',['type'=>'csv','records'=>'valid'])}}">
                                                Download Valid (CSV)
                                            </a>
                                            <a class="dropdown-item"
                                               href="{{URL::route('downloadverifiedrecords',['type'=>'xls','records'=>'valid'])}}">
                                                Download Valid (XLS)
                                            </a>
                                        </div>
                                    </button>

{{--                                    <button class="btn btn-primary" value="all" id="show_emails">--}}
{{--                                        Show All--}}
{{--                                    </button>--}}

                                </div>
                                <div class="col-md-12">
                                    <table class="table" id="emails_table">
                                        <thead class="black white-text">
                                        <tr>
                                            <th scope="col">Email</th>
                                            <th scope="col">Server Status</th>
                                            <th scope="col">Email Status</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        <nav aria-label="">

                                            {!! $emails->render() !!}
                                        </nav>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h5 class="card-title">No Emails Found.</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var data = null;
        $(document).ready(function () {
            data = {!! json_encode($emails->toArray(), JSON_HEX_TAG) !!};
            populate_emails('all');
            $("#show_emails").click(function () {
                if ($("#show_emails").val() == "all") {
                    populate_emails('all');
                    $("#show_emails").html('Show Valid');
                    $("#show_emails").prop('value', 'less');
                } else {
                    populate_emails('less');
                    $("#show_emails").html('Show All');
                    $("#show_emails").prop('value', 'all');
                }

            });

        });

        function populate_emails(filter) {
            var tableRef = document.getElementById('emails_table').getElementsByTagName('tbody')[0];
            for (var i = tableRef.rows.length - 1; i >= 0; i--) {
                tableRef.deleteRow(i);
            }
            for (var i = 0; i < data['data'].length; i++) {

                if (filter == "less" && data['data'][i]['status'] == 'Valid' && data['data'][i]['server_status'] == 'Valid') {
                    var newRow = tableRef.insertRow();


                    newCell = newRow.insertCell(0);
                    newText = document.createTextNode(data['data'][i]['email']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(1);
                    container = document.createElement("span");
                    text = document.createTextNode(data['data'][i]['server_status']);
                    container.appendChild(text);
                    container.style.color = "green";
                    container.style.fontWeight = "bold";
                    newCell.appendChild(container);


                    newCell = newRow.insertCell(2);
                    container = document.createElement("span");
                    text = document.createTextNode(data['data'][i]['status']);
                    container.appendChild(text);
                    container.style.color = "green";
                    container.style.fontWeight = "bold";
                    newCell.appendChild(container);

                    newCell = newRow.insertCell(3);
                    var date = new Date(data['data'][i]['created_at'] + ' UTC');
                    newText = document.createTextNode("" + (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear());
                    //newText  = document.createTextNode(data[i]['created_at']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(4);
                    if (data[i]['bounce'].length > 0) {
                        container = document.createElement("span");
                        text = document.createTextNode(data['data'][i]['bounce'][0]['status']);
                        container.style.fontWeight = "bold";
                        container.appendChild(text);
                        container.style.color = "green";

                        newCell.appendChild(container);
                    } else {
                        button = document.createElement("button");
                        button.className = "btn btn-primary";
                        button.innerHTML = "Report Bounce";
                        var id = data['data'][i]['id'];
                        button.onclick = function () {
                            report_bounce_modal(id, 'verify');
                        };
                        newCell.appendChild(button);
                    }
                } else if (filter == "all") {
                    var newRow = tableRef.insertRow();

                    newCell = newRow.insertCell(0);
                    newText = document.createTextNode(data['data'][i]['email']);
                    newCell.appendChild(newText);


                    newCell = newRow.insertCell(1);

                    container = document.createElement("span");
                    if (data['data'][i]['server_status'] == "No MX Records") {
                        text = document.createTextNode("No Mailbox");
                    } else {
                        text = document.createTextNode(data['data'][i]['server_status']);
                    }


                    container.appendChild(text);
                    if (data['data'][i]['status'] == "Valid") {
                        container.style.color = "green";
                    } else if (data['data'][i]['status'] == "Catch All") {
                        container.style.color = "orange";
                    } else {
                        container.style.color = "red";
                    }
                    container.style.fontWeight = "bold";

                    newCell.appendChild(container);


                    newCell = newRow.insertCell(2);

                    container = document.createElement("span");
                    text = document.createTextNode(data['data'][i]['status']);

                    container.appendChild(text);
                    if (data['data'][i]['status'] == "Valid") {
                        container.style.color = "green";
                    } else if (data['data'][i]['status'] == "Catch All") {
                        container.style.color = "orange";
                    } else {
                        container.style.color = "red";
                    }
                    container.style.fontWeight = "bold";

                    newCell.appendChild(container);


                    newCell = newRow.insertCell(3);
                    var date = new Date(data['data'][i]['created_at'] + ' UTC');
                    newText = document.createTextNode("" + (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear());
                    //newText  = document.createTextNode(data[i]['created_at']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(4);
                    if (data['data'][i]['status'] == "Valid" && data['data'][i]['server_status'] == "Valid") {
                        if (data[i]['bounce'].length > 0) {
                            container = document.createElement("span");
                            text = document.createTextNode(data['data'][i]['bounce'][0]['status']);
                            container.style.fontWeight = "bold";
                            container.appendChild(text);
                            container.style.color = "green";

                            newCell.appendChild(container);
                        } else {
                            button = document.createElement("button");
                            button.className = "btn btn-primary";
                            button.innerHTML = "Report Bounce";
                            var id = data['data'][i]['id'];
                            button.onclick = function () {
                                report_bounce_modal(id, 'verify');
                            };
                            newCell.appendChild(button);
                        }
                    } else {

                        newText = document.createTextNode(" ");
                        newCell.appendChild(newText);
                    }

                }


            }
        }
    </script>
@endpush
