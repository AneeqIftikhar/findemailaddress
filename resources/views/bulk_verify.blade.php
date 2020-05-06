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
	                        <label for="title" class="col-sm-4 col-form-label text-md-left">{{ __('Title') }}</label>
	                        <div class="col-md-12">
	                            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required autofocus />
	                            @if ($errors->has('title'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('title') }}</strong>
	                                </span>
	                            @endif
	                        </div>
	                    </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <h3>Upload your file (CSV)</h3>
                            </div>
                            {{-- <div class="col-md-2">
                                <p>or</p>
                            </div>
                            <div class="col-md-5">
                                <h3>Enter useing the following format (optional)</h3>
                            </div> --}}
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <p>Your file must use commas as column delimiters.</p>
                                <p>To verify the email addresses, you need:</p>
                                <ul>
                                    <li>
                                        <strong>The email:</strong>
                                        one column with the emails
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('images/csv_bulk_verify.png') }}" style="max-width: 100%; "> 
                            </div>
                            {{-- <div class="col-md-2">
                            </div>
                            <div class="form-group col-md-5">
                                <textarea class="form-control" name="textarea_emails" rows="8" id="textarea_emails" placeholder="bill.gates@microsoft.com
                                donald.trump@trump.com
                                donny.darko@imdb.com">
                                    
                                </textarea>
                            </div> --}}


                        </div>
	                    <div class="form-group row">
                    		<label for="excel_file" class="col-sm-4 col-form-label text-md-left">{{ __('File') }}</label>
                    		<div class="col-md-12">
                                <input type="file" class="form-control-file" name="excel_file" id="excel_file" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid CSV file. Size of file should not be more than 5MB.</small>
                                @if ($errors->has('excel_file'))
								    <div class="error">{{ $errors->first('excel_file') }}</div>
								@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <span class="invalid-feedback-custom">
                                    <strong id="bulk_verify_file_error"></strong>
                                </span>
                                
                            </div>
                            
                        </div>
                        <div class="form-group row">
                        	<div class="col-md-12">
                        		<button id="submit_file_upload_verify" type="submit" class="btn btn-primary">Upload</button>
                        	</div>
                        	
                        </div>
                            
                    </form>
            	</div>
                <div class="card-footer">
                    <ul>
                        <li>Only company emails are allowed to be verified.</li>
                        <li>You can't query rows more than your total credits. Additional rows in the file will be skipped.</li>
                        <li>Some special or unexpected characters may be replaced/deleted in the file.</li>
                        <li>Rows that fail validations will be skipped</li>
                    </ul>
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
  // if()
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
                $('#submit_file_upload_verify').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(data)
            {
                $('#submit_file_upload_verify').html('Upload');
                if(data['status']=='fail')
                {
                    $('#bulk_verify_file_error').html(data['message']);
                }
                else
                {
                    bulk_verify_popup_populate_emails(data['data']);
                    $('#bulk_import_verify_file_id').val(data['file_id']);
                    $('#bulk_verify_modal_button').html('Import '+data['limit']+' Rows');
                    $("#bulk_verify_modal").modal()
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                $('#submit_file_upload_verify').html('Upload');
                if( jqXHR.status === 422 )
                {
                        $errors = jqXHR.responseJSON;

                         $.each( $errors.errors , function( key, value ) {

                                
                                if(value[0].search("The excel file must be a file of type: csv, txt.")!=-1)
                                {
                                    $("#bulk_verify_error").html(e).fadeIn();
                                    $('#bulk_verify_file_error').html("Allowed File Formats: TXT, CSV");
                                }
                                else
                                {
                                   
                                    $("#bulk_verify_error").html(e).fadeIn();
                                    $('#bulk_verify_file_error').html("Something Went Wrong.");
                                }
                                
                            
                        });
                        
                }
                else
                {
                    $("#bulk_verify_error").html(e).fadeIn();
                    $('#bulk_verify_file_error').html("Something Went Wrong.");
                }
            }          
        });
    }));
});

</script>
@endpush