@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header"><h3>Bulk Find Email</h3></div>

                <div class="card-body">
                	@if ($errors->any())
					        {{ implode('', $errors->all('<div>:message</div>')) }}
					@endif
                   <form id="bulk_find_form" method="POST" action="{{ route('bulk_import_find') }}" enctype="multipart/form-data" aria-label="{{ __('Upload') }}">
                    @csrf

	                    <div class="form-group row">
	                        <label for="title" class="col-sm-12 col-form-label text-md-left">{{ __('Title') }}</label>
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
                            <div class="col-md-7">
                                <h3>Upload your file (CSV)</h3>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <p>Your file must use commas as column delimiters.</p>
                                <p>To find the email addresses, you need columns with:</p>
                                <ul>
                                    <li>
                                        <strong>The name:</strong>
                                        first name and last name.
                                    </li>
                                    <li>
                                        <strong>The company:</strong>
                                        one column with the company domain(domain name).
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('images/csv_bulk_find.png') }}" style="max-width: 100%; ">
                            </div>

                        </div>

	                    <div class="form-group row">
                    		<label for="excel_file" class="col-sm-4 col-form-label text-md-left">{{ __('File') }}</label>
                    		<div class="col-md-12">
                                <input type="file" class="form-control-file" name="excel_file" id="excel_file" aria-describedby="fileHelp" required>
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid CSV file. Size of file should not be more than 5MB.</small>
                                @if ($errors->has('excel_file'))
								    <div class="error">{{ $errors->first('excel_file') }}</div>
								@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <span class="invalid-feedback-custom">
                                    <strong id="bulk_find_file_error"></strong>
                                </span>

                            </div>

                        </div>
                        <div class="form-group row">
                        	<div class="col-md-6">

                        		<button type="submit" id="submit_file_upload" class="btn btn-primary">Upload</button>
                        	</div>

                        </div>

                    </form>
            	</div>
                <div class="card-footer">
                    <ul>
                        <li>For better results, it's recommended to use the domain name instead of the URL.</li>
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
                $('#submit_file_upload').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#bulk_find_file_error').html('');
            },
            success: function(data)
            {
                if(data['status']=='fail')
                {
                    $('#submit_file_upload').html('Upload');
                    $('#bulk_find_file_error').html(data['message']);
                }
                else
                {
                    $('#submit_file_upload').html('Upload');
                    bulk_find_popup_populate_emails(data['data']);
                    $('#bulk_import_file_id').val(data['file_id']);
                    $('#bulk_find_modal_button').html('Import '+data['limit']+' Rows');
                    $("#bulk_find_modal").modal()
                }

            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                if( jqXHR.status === 422 )
                {
                        $errors = jqXHR.responseJSON;

                         $.each( $errors.errors , function( key, value ) {


                                if(value[0].search("The excel file must be a file of type: csv, txt.")!=-1)
                                {
                                    $("#bulk_find_error").html(e).fadeIn();
                                    $('#submit_file_upload').html('Upload');
                                    $('#bulk_find_file_error').html("Allowed File Formats: TXT, CSV");
                                }
                                else
                                {

                                    $("#bulk_find_error").html(e).fadeIn();
                                    $('#submit_file_upload').html('Upload');
                                    $('#bulk_find_file_error').html("Something Went Wrong");
                                }


                        });

                }
                else
                {
                    $("#bulk_find_error").html(e).fadeIn();
                    $('#submit_file_upload').html('Upload');
                    $('#bulk_find_file_error').html("Something Went Wrong");
                }

            }
        });
    }));
});

</script>
@endpush
