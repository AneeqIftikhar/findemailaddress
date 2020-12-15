@extends('layouts.app')

@section('page')
    {{ "API" }}
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>API Key</h4>
                </div>
                <div class="card-body">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    @if($errors->any())
                                        <div class="alert alert-danger">{{$errors->first()}}</div>
                                    @endif
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 mb-md-5 pb-3 border-bottom">
                                        <a role="button" href="{{ url('/generate/api/key') }}" class="btn btn-sm btn-success rounded-pill m-0"><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Generate New Key</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm border-top mb-0">
                                    <thead>
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 40%">Token</th>
                                    <th style="width: 20%">Expires At</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($api as $apis)
                                        <tr>
                                            <td>{{$apis->title}}</td>
                                            <td style="word-break: break-all;">{{$apis->api_key}}</td>
                                            <td><?php $date = strtotime($apis->expires_at); echo date('d-M-Y', $date);?></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    if(localStorage.getItem("status"))
    {
        $.toaster({ priority : 'success', title : 'Success', message : localStorage.getItem("message")});
        localStorage.clear();
    }
});

</script>
@endpush
