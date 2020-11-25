@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header"><h3>Emails</h3></div>

                    <div class="card-body">
                        <div class="row" style="margin-bottom: 4px">
                            <div class="col-md-6">
                                <h3>{{$data['file']['title']}}</h3>
                            </div>
                            <div class="col-md-6" align="right">


                                {{-- <a href="{{URL::route('list')}}">
                                  <button class="btn btn-primary">
                                    <i class="fas fa-undo fa-lg"></i>
                                  </button>
                                </a> --}}

                                <button class="btn btn-primary nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
                                       style="padding: 0px; color: white">
                                        <span><i class="fas fa-download fa-lg"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a class="dropdown-item"
                                           href="{{URL::route('downloadfoundrecordsfile',['id' => $data['file']['id'],'type'=>'csv','records'=>'all'])}}">
                                            Download ALL (CSV)
                                        </a>
                                        <a class="dropdown-item"
                                           href="{{URL::route('downloadfoundrecordsfile',['id' => $data['file']['id'],'type'=>'xls','records'=>'all'])}}">
                                            Download ALL (XLS)
                                        </a>
                                        <a class="dropdown-item"
                                           href="{{URL::route('downloadfoundrecordsfile',['id' => $data['file']['id'],'type'=>'csv','records'=>'valid'])}}">
                                            Download Valid (CSV)
                                        </a>
                                        <a class="dropdown-item"
                                           href="{{URL::route('downloadfoundrecordsfile',['id' => $data['file']['id'],'type'=>'xls','records'=>'valid'])}}">
                                            Download Valid (XLS)
                                        </a>
                                    </div>
                                </button>

{{--                                <button class="btn btn-primary" value="all" id="show_emails">--}}
{{--                                    Show All--}}
{{--                                </button>--}}

                            </div>
                        </div>
                        <table class="table" id="emails_table">
                            <thead class="black white-text">
                            <tr>
                                @if($data['file']['type']=='find')
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Domain</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Status</th>
                                @else
                                    <th scope="col">#</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Status</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <nav aria-label="">
{{--                            <a href="" id="previous_page_link" style="display: none">Previous</a>&nbsp;--}}
{{--                            <a href="" id="next_page_link" style="display: none">Next</a>--}}
                            {!! $data['emails']->render() !!}
                            </nav>
                        </div>
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
            data = {!! json_encode($data, JSON_HEX_TAG) !!};
            if (data['file']['type'] == "find") {
                populate_emails_find('all');
                $("#show_emails").click(function () {
                    if ($("#show_emails").val() == "all") {
                        populate_emails_find('all');
                        $("#show_emails").html('Show Valid');
                        $("#show_emails").prop('value', 'less');
                    } else {
                        populate_emails_find('less');
                        $("#show_emails").html('Show All');
                        $("#show_emails").prop('value', 'all');
                    }

                });
            } else {
                populate_emails_verify('all');

                $("#show_emails").click(function () {
                    if ($("#show_emails").val() == "all") {
                        populate_emails_verify('all');
                        $("#show_emails").html('Show Valid');
                        $("#show_emails").prop('value', 'less');
                    } else {
                        populate_emails_verify('less');
                        $("#show_emails").html('Show All');
                        $("#show_emails").prop('value', 'all');
                    }

                });
            }


        });

        function populate_emails_find(filter) {
            var tableRef = document.getElementById('emails_table').getElementsByTagName('tbody')[0];
            for (var i = tableRef.rows.length - 1; i >= 0; i--) {
                tableRef.deleteRow(i);
            }
            var table_index = 0;
            for (var i = 0; i < data['emails']['data'].length; i++) {

                if (filter == "less" && data['emails']['data'][i]['status'] == 'Valid') {
                    var newRow = tableRef.insertRow();

                    newCell = newRow.insertCell(0);
                    // newText = document.createTextNode(table_index + 1);
                    newText = document.createTextNode(data['emails']['from']+i);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(1);
                    newText = document.createTextNode(data['emails']['data'][i]['first_name'] + " " + data['emails']['data'][i]['last_name']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(2);
                    newText = document.createTextNode(data['emails']['data'][i]['domain']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(3);
                    newText = document.createTextNode(data['emails']['data'][i]['email']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(4);
                    newText = document.createTextNode(data['emails']['data'][i]['status']);
                    newCell.appendChild(newText);
                    table_index++;
                } else if (filter == "all") {
                    var newRow = tableRef.insertRow();

                    newCell = newRow.insertCell(0);
                    // newText = document.createTextNode(table_index + 1);
                    newText = document.createTextNode(data['emails']['from']+i);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(1);
                    newText = document.createTextNode(data['emails']['data'][i]['first_name'] + " " + data['emails']['data'][i]['last_name']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(2);
                    newText = document.createTextNode(data['emails']['data'][i]['domain']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(3);
                    newText = document.createTextNode(data['emails']['data'][i]['email']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(4);
                    if (data['emails']['data'][i]['status'] == 'Unverified') {
                        newText = document.createTextNode('Processing');
                    } else {
                        newText = document.createTextNode(data['emails']['data'][i]['status']);
                    }

                    newCell.appendChild(newText);
                    table_index++;
                }


            }

        }

        function populate_emails_verify(filter) {

            var tableRef = document.getElementById('emails_table').getElementsByTagName('tbody')[0];
            for (var i = tableRef.rows.length - 1; i >= 0; i--) {
                tableRef.deleteRow(i);
            }
            for (var i = 0; i < data['emails']['data'].length; i++) {

                if (filter == "less" && data['emails']['data'][i]['status'] == 'Valid') {
                    var newRow = tableRef.insertRow();

                    newCell = newRow.insertCell(0);
                    // newText = document.createTextNode(i + 1);
                    newText = document.createTextNode(data['emails']['from']+i);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(1);
                    newText = document.createTextNode(data['emails']['data'][i]['email']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(2);
                    newText = document.createTextNode(data['emails']['data'][i]['status']);
                    newCell.appendChild(newText);
                } else if (filter == "all") {
                    var newRow = tableRef.insertRow();

                    newCell = newRow.insertCell(0);
                    // newText = document.createTextNode(i + 1);
                    newText = document.createTextNode(data['emails']['from']+i);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(1);
                    newText = document.createTextNode(data['emails']['data'][i]['email']);
                    newCell.appendChild(newText);

                    newCell = newRow.insertCell(2);
                    if (data['emails']['data'][i]['status'] == 'Unverified') {
                        newText = document.createTextNode('Processing');
                    } else {
                        newText = document.createTextNode(data['emails']['data'][i]['status']);
                    }
                    newCell.appendChild(newText);
                }


            }

        }
    </script>
@endpush
