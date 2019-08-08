@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                	<h3>Account Settings</h3>
                </div>

                	

                <div class="card-body">
                	<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">Account Information</h5>
		                    <div class="input-group">
							  
							  <strong>Package: </strong><p class="ml-2 text-muted">{{session('package_name')}}</p>
							</div>
							<div class="input-group mb-2">
							 
							  <strong>Credits: </strong><p class="ml-2 text-muted">{{ Auth::user()->credits }}</p>
							</div>
							
					    
					  </div>
					</div>
                	<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">Personal Information</h5>
		                    <div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" id="full_name" placeholder="Full Name" aria-label="Full Name" aria-describedby="basic-addon2" value="{{ Auth::user()->name }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" id="company_name" placeholder="Company Name" aria-label="Company Name" aria-describedby="basic-addon2" value="{{ Auth::user()->company_name }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" id="phone" placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon2" value="{{ Auth::user()->phone }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2" value="{{ Auth::user()->email }}" disabled>
							</div>
							<span class="invalid-feedback-custom">
                                <strong id="personal_error"></strong>
                            </span>
                            <span class="valid-feedback-custom">
                                <strong id="personal_success"></strong>
                            </span>
							<div class="input-group mb-2 mt-2">
							    <button class="btn btn-primary" type="button" onclick="update_personal_info()">Update Information</button>
							</div>
							
					    
					  </div>
					</div>

					<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">Change Password</h5>
						   
		                    <div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="New Password" aria-label="New Password" aria-describedby="basic-addon2" id="password">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon2" id="c_password">
							  
							</div>
							<span class="invalid-feedback-custom">
                                <strong id="password_error"></strong>
                            </span>
                            <span class="valid-feedback-custom">
                                <strong id="password_success"></strong>
                            </span>
							<div class="input-group">
							    <button class="btn btn-primary" type="button" onclick="update_password()">Update Password</button>
							</div>
					    
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
function update_personal_info()
{
	document.getElementById('personal_error').innerHTML='';
	document.getElementById('password_error').innerHTML='';
	document.getElementById('personal_success').innerHTML='';
	document.getElementById('password_success').innerHTML='';

    var full_name=document.getElementById("full_name").value;
    var company_name=document.getElementById("company_name").value;
    var phone=document.getElementById("phone").value;
    $.ajax({
        method: 'POST',
        dataType: 'json', 
        url: 'update_personal_info', 
        data: {'full_name' : full_name,'company_name':company_name,'phone':phone,"_token": "{{ csrf_token() }}"}, 
        success: function(response){ // What to do if we succeed
            
            console.log(response['status']);
            console.log(response['message']);
            document.getElementById('personal_success').innerHTML=response['message'];

            
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
            
            if( jqXHR.status === 422 )
            {
                $errors = jqXHR.responseJSON;

                 $.each( $errors.errors , function( key, value ) {
                        document.getElementById('personal_error').innerHTML=value[0];
                    
                });
            }
            else
            {
                console.log(jqXHR);
            }

        },
        timeout: 5000 // sets timeout to 5 seconds
    });
}
function update_password()
{
	document.getElementById('personal_error').innerHTML='';
	document.getElementById('password_error').innerHTML='';
	document.getElementById('personal_success').innerHTML='';
	document.getElementById('password_success').innerHTML='';

    var password=document.getElementById("password").value;
    var c_password=document.getElementById("c_password").value;
    if(!password)
    {
    	document.getElementById('password_error').innerHTML="Password Missing";
    }
    else if(!c_password)
    {
    	document.getElementById('password_error').innerHTML="Confirm Password Missing";
    }
    if(password!=c_password)
    {
    	document.getElementById('password_error').innerHTML="Password Does not Match";
    }
    else
    {
    	$.ajax({
        method: 'POST',
        dataType: 'json', 
        url: 'update_password', 
        data: {'password' : password,"_token": "{{ csrf_token() }}"}, 
        success: function(response){ // What to do if we succeed
            
            console.log(response['status']);
            console.log(response['message']);
            if(response['status'] && response['status']=='success')
            {
            	document.getElementById('password_success').innerHTML=response['message'];
            }
            else
            {
            	document.getElementById('password_error').innerHTML=response['message'];
            }
            
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
            
            

        },
        timeout: 5000 // sets timeout to 5 seconds
    });
    }
    
}

</script>
@endpush