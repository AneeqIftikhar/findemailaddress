@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h3>Bulk Find Email</h3></div>

                <div class="card-body">
                	@if ($errors->any())
					        {{ implode('', $errors->all('<div>:message</div>')) }}
					@endif
                   <form id="bulk_find_form" method="POST" action="{{ route('bulk_import_find') }}" enctype="multipart/form-data" aria-label="{{ __('Upload') }}">
                    @csrf
                    	
	                    <div class="form-group row">
	                        <label for="title" class="col-sm-4 col-form-label text-md-right">{{ __('Title') }}</label>
	                        <div class="col-md-6">
	                            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required autofocus />
	                            @if ($errors->has('title'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('title') }}</strong>
	                                </span>
	                            @endif
	                        </div>
	                    </div>
	                    <div class="form-group row">
                    		<label for="excel_file" class="col-sm-4 col-form-label text-md-right">{{ __('File') }}</label>
                    		<div class="col-md-6">
                                <input type="file" class="form-control-file" name="excel_file" id="excel_file" aria-describedby="fileHelp" required>
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid Excel or CSV file. Size of file should not be more than 5MB.</small>
                                @if ($errors->has('excel_file'))
								    <div class="error">{{ $errors->first('excel_file') }}</div>
								@endif
                            </div>
                        </div>
                        <div class="form-group row">
                        	<div class="col-sm-4 col-form-label text-md-right">
                        	</div>
                        	<div class="col-md-6">
                        		<button type="submit" class="btn btn-primary">Submit</button>
                        	</div>
                        	
                        </div>
                            
                    </form>
            	</div>
            </div>
            <div class="modal" tabindex="-1" role="dialog" id="bulk_find_modal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                  
                  <div class="modal-body">
                    <div class="card activity_log" style="height: 100%">
                        <div class="card-header"><h4>Mapping CSV Results</h4></div>
                        <div class="card-body p-0 py-4" style="overflow-y: auto; max-height: 68vh;">
                            <div class="row m-0 mb-4">
                                <div class="col-12" style="padding-left: 1.4rem!important;">
                                    <table id="bulk_find_popup_table" class="table">
                                      <tbody>


                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <button  class="btn btn-success" onClick="map_find">Import</button>
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
function populate_emails(data)
{       
        var tableRef = document.getElementById('bulk_find_popup_table').getElementsByTagName('tbody')[0];
        for(var i = tableRef.rows.length - 1; i >= 0; i--)
        {
            tableRef.deleteRow(i);
        }
        for(var i = 0;i<data.length;i++)
        {
            newRow   = tableRef.insertRow();
            for(var j = 0;j<data[i].length;j++)
            {
                  newCell  = newRow.insertCell(j);
                  newText  = document.createTextNode(data[i][j]);
                  newCell.appendChild(newText);
            }
               
        }
}
$(document).ready(function (e) {
 $("#bulk_find_form").on('submit',(function(e) {
  e.preventDefault();
      $.ajax({
            url: "bulk_import_find",
            type: "POST",
            data:  new FormData(this),
            dataType: 'json', 
            contentType: false,
            cache: false,
            processData:false,
            beforeSend : function()
            {
                $("#bulk_find_form")[0].reset(); 
                $("#bulk_find_error").fadeOut();
            },
            success: function(data)
            {
                console.log(data['data']);
                populate_emails(data['data']);
                $("#bulk_find_modal").modal()
            },
            error: function(e) 
            {
                $("#bulk_find_error").html(e).fadeIn();
            }          
        });
    }));
});
</script>
@endpush