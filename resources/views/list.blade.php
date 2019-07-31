@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Lists</div>

                <div class="card-body">
                    <div class="row" style="margin-bottom: 4px">
                        <div class="col-md-10">
                            <h3>Your Files are Listed Here</h3>
                        </div>
                        <!-- <div class="col-md-2">
                            <button class="btn btn-danger">Delete</button>
                        </div> -->
                    </div>
                   <table class="table" id="files_table">
                      <thead class="black white-text">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date/Time Uploaded</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($files as $file)
                          <tr class="table-row" data-href="{{URL::route('emails', ['id' => $file->id])}}" value="{{$file->id}}">        
                              <td> {{$file->title}} </td>
                              <td> {{$file->status}} </td>
                              <td> {{$file->created_at}} </td>
                          </tr>
                         @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
      $(".table-row").click(function() {
          window.document.location = $(this).data("href");
      });
  });
</script>
@endpush