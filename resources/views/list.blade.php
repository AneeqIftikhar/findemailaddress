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
                       <!--  @foreach($files as $file)
                          <tr class="table-row" data-href="{{URL::route('emails', ['id' => $file->id])}}" value="{{$file->id}}">        
                              <td> {{$file->title}} </td>
                              <td> {{$file->status}} </td>
                              <td> {{$file->created_at}} </td>
                          </tr>
                         @endforeach -->
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
var interval = null;
function populate_files(data)
{       
        var total_pending=0;
        var tableRef = document.getElementById('files_table').getElementsByTagName('tbody')[0];
        for(var i = tableRef.rows.length - 1; i >= 0; i--)
        {
            tableRef.deleteRow(i);
        }
        for(var i = 0;i<data.length;i++)
        {
            newRow   = tableRef.insertRow();
            newRow.className="table-row";
            var url = '{{ route("emails", ":id") }}';
            url = url.replace(':id', data[i]['id']);
            newRow.setAttribute('data-href',url);
            newRow.setAttribute('value',data[i]['id']);
            // newRow.setAttribute('class',"table-row");
            

            newCell  = newRow.insertCell(0);
            newText  = document.createTextNode(data[i]['title']);
            newCell.appendChild(newText);

            newCell  = newRow.insertCell(1);
            if(data[i]['status']=="Pending Import")
            {
              spinner = document.createElement("i");
              spinner.className="fa fa-spinner fa-spinner fa-spin";
              newCell.appendChild(spinner);
              total_pending++;
              
            }
            else
            {
              newText  = document.createTextNode(data[i]['status']);
              newCell.appendChild(newText);
            }
            

            newCell  = newRow.insertCell(2);
            newText  = document.createTextNode(data[i]['created_at']);
            newCell.appendChild(newText);

               
        }
        if(!interval && total_pending>0)
        {
          interval=setInterval(get_user_files_interval_set,1000);
        }
        else if(interval && total_pending==0)
        {
          get_user_files_interval_stop();
        }  
}
function get_user_files_interval_set()
{
   $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_user_files_ajax', 
          data: {"_token": "{{ csrf_token() }}"}, 
          success: function(response)
          { 
              
              populate_files(response['files']);
              $(".table-row").click(function() {
                  window.document.location = $(this).data("href");
              });
             
              

          },
          error: function(jqXHR, textStatus, errorThrown) {
             
          },
          timeout: 1000 
      });
}
function get_user_files_interval_stop() {
  clearInterval(interval);
}
$(document).ready(function() {
      data = {!! json_encode($files->toArray(), JSON_HEX_TAG) !!};
      populate_files(data); 
      $(".table-row").click(function() {
          window.document.location = $(this).data("href");
      });
         
  });
</script>
@endpush