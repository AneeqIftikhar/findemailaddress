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
                	<h4>API Information</h4>
                </div>

                	

                <div class="card-body">
                	<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">API secret keys</h5>
		                    <div class="input-group">
							  
							
					    
					  </div>
					</div>
                	<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">API Overview</h5>
		                    
							
					    
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