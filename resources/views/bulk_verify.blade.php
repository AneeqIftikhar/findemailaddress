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
                   <form method="POST" action="{{ route('batch') }}" enctype="multipart/form-data" aria-label="{{ __('Upload') }}">
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
@section('scripts')
  
@endsection