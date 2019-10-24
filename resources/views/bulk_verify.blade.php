@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h3>Bulk Verify Emails</h3></div>

                <div class="card-body">
                	@if ($errors->any())
					        {{ implode('', $errors->all('<div>:message</div>')) }}
					@endif
                   <form id="bulk_verify_form" method="POST" action="{{ route('bulk_import_verify') }}" enctype="multipart/form-data" aria-label="{{ __('Upload') }}">
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
                                <input type="file" class="form-control-file" name="excel_file" id="excel_file" aria-describedby="fileHelp">
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
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">


$(document).ready(function (e) {
    
 $("#bulk_verify_form").on('submit',(function(e) {
  e.preventDefault();
      $.ajax({
            url: "bulk_import_verify",
            type: "POST",
            data:  new FormData(this),
            dataType: 'json', 
            contentType: false,
            cache: false,
            processData:false,
            beforeSend : function()
            {
                $("#bulk_verify_form")[0].reset(); 
                $("#bulk_verify_error").fadeOut();
            },
            success: function(data)
            {
                bulk_verify_popup_populate_emails(data['data']);
                $('#bulk_import_verify_file_id').val(data['file_id']);
                $('#bulk_verify_modal_button').html('Import '+data['limit']+' Rows');
                $("#bulk_verify_modal").modal()
            },
            error: function(e) 
            {
                $("#bulk_verify_error").html(e).fadeIn();
            }          
        });
    }));
});

</script>
@endpush