@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h3>Errors in Skipped Rows</h3></div>

                <div class="card-body">
                    <div class="row" style="margin-bottom: 4px">
                        <!-- <div class="col-md-2">
                            <button class="btn btn-danger">Delete</button>
                        </div> -->
                    </div>
                   <table class="table" id="error_table">
                      <thead class="black white-text">
                        <tr>
                            <th scope="col">Row #</th>
                            <th scope="col">Attribute</th>
                            <th scope="col">Description</th>
                            <th scope="col">Values</th>
                        </tr>
                      </thead>
                      <tbody>

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
function populate_errors(data)
{       
        var tableRef = document.getElementById('error_table').getElementsByTagName('tbody')[0];
        for(var i = tableRef.rows.length - 1; i >= 0; i--)
        {
            tableRef.deleteRow(i);
        }
        for(var i = 0;i<data.length;i++)
        {
            newRow   = tableRef.insertRow();
            
            // newRow.setAttribute('class',"table-row");
            

            newCell  = newRow.insertCell(0);
            newText  = document.createTextNode(data[i]['row']);
            newCell.appendChild(newText);


            newCell  = newRow.insertCell(1);
            newText  = document.createTextNode(data[i]['attribute']);
            newCell.appendChild(newText);

            errors=data[i]['errors'];
            errors_array=errors.split("[");
            error=errors_array[1].split('"');
            error=error[1];
            var error_text=error.replace("0", "attribute");
            error_text=error_text.replace("1", "attribute");
            error_text=error_text.replace("2", "attribute");

            newCell  = newRow.insertCell(2);
            newText  = document.createTextNode(error_text);
            newCell.appendChild(newText);

            newCell  = newRow.insertCell(3);
            newText  = document.createTextNode(data[i]['values']);
            newCell.appendChild(newText);
                
        }

}
function get_user_files_interval_set()
{
   $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_user_files_errors_ajax', 
          data: {"_token": "{{ csrf_token() }}"}, 
          success: function(response)
          { 
              
              populate_errors(response['errors']);

          },
          error: function(jqXHR, textStatus, errorThrown) {
             
          },
          timeout: 1000 
      });
}

$(document).ready(function() {
      data = {!! json_encode($errors->toArray(), JSON_HEX_TAG) !!};
      populate_errors(data); 
         
  });
</script>
@endpush