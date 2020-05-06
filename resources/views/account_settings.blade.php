@extends('layouts.app')

@section('page')
    {{ "Account Settings" }}
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                	<h4>Account Settings</h4>
                </div>

                	

                <div class="card-body">
                	<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">Account Information</h5>
		                    <div class="input-group">
							  
							  <strong>Package: </strong><p class="ml-2 text-muted">{{strtoupper(Auth::user()->package->name)}}</p>
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
							  <input type="text" class="form-control" autocomplete="phone" id="phone" placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon2" value="{{ Auth::user()->phone }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" id="email" placeholder="Email" aria-label="Email" aria-describedby="Email" value="{{ Auth::user()->email }}" disabled>
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
							  <input type="password" class="form-control" autocomplete="new-password" placeholder="New Password" aria-label="New Password" aria-describedby="basic-addon2" id="password">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon2" id="c_password">
							  
							</div>
							<span class="invalid-feedback-custom">
                                <strong id="password_error"></strong>
                            </span>
                            <span class="valid-feedback-custom">
                                <strong id="password_success"></strong>
                            </span>
							<div class="input-group">
							    <button class="btn btn-primary" id="change_password_button" type="button" onclick="update_password()">Update Password</button>
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
$(document).ready(function(){

    if(localStorage.getItem("status"))
    {
        $.toaster({ priority : 'success', title : 'Success', message : localStorage.getItem("message")});
        localStorage.clear();
    }
});
function update_personal_info()
{
	document.getElementById('personal_error').innerHTML='';
	document.getElementById('password_error').innerHTML='';
	document.getElementById('personal_success').innerHTML='';
	document.getElementById('password_success').innerHTML='';

    var full_name=document.getElementById("full_name").value;
    var company_name=document.getElementById("company_name").value;
    var phone=document.getElementById("phone").value;
    var data={};
    if(full_name ==null || full_name == "")
    {
        document.getElementById('personal_error').innerHTML="Full Name Can Not be Empty";
    }
    else if (company_name && company_name.length>50)
    {
        document.getElementById('personal_error').innerHTML="Company Name too Long";        
    }
    else if (phone && phone.length>20)
    {
       document.getElementById('personal_error').innerHTML="Phone too Long";  
    }
    else
    {
        data['full_name']=full_name;
        if(company_name)
        {
            data['company_name']=company_name;
        }
        if(phone)
        {
            data['phone']=phone;
        }
        data['_token']="{{ csrf_token() }}";
        $.ajax({
            method: 'POST',
            dataType: 'json', 
            url: 'update_personal_info', 
            data: data, 
            success: function(response){ // What to do if we succeed
                
                // console.log(response['status']);
                // console.log(response['message']);
                localStorage.setItem("status","Success");
                localStorage.setItem("message",response['message']);
                window.location.reload();

                
               
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
    
}
function update_password()
{
	document.getElementById('personal_error').innerHTML='';
	document.getElementById('password_error').innerHTML='';
	document.getElementById('personal_success').innerHTML='';
	document.getElementById('password_success').innerHTML='';

    var password=document.getElementById("password").value;
    var c_password=document.getElementById("c_password").value;
    if(password ==null || password == "")
    {
    	document.getElementById('password_error').innerHTML="Password Missing";
    }
    else if(c_password ==null || c_password == "")
    {
    	document.getElementById('password_error').innerHTML="Confirm Password Missing";
    }
    else if(password.length<8)
    {
        document.getElementById('password_error').innerHTML="Password too Short";
    }
    else if(password.length>20)
    {
        document.getElementById('password_error').innerHTML="Password too Long";
    }
    if(password!=c_password)
    {
    	document.getElementById('password_error').innerHTML="Password Does not Match";
    }
    else
    {
        $('#change_password_button').html('<i class="fa fa-spinner fa-spin"></i>');
    	$.ajax({
        method: 'POST',
        dataType: 'json', 
        url: 'update_password', 
        data: {'password' : password,"_token": "{{ csrf_token() }}"}, 
        success: function(response){ // What to do if we succeed
            
            // console.log(response['status']);
            // console.log(response['message']);
            if(response['status'] && response['status']=='success')
            {
                localStorage.setItem("status","Success");
                localStorage.setItem("message",response['message']);
                //window.location.href="{{URL::route('logout')}}";
                //window.location.reload();
                $.ajax
                ({
                    type: 'POST',
                    url: 'logout',
                    data: {"_token": "{{ csrf_token() }}"}, 
                    success: function()
                    {
                        $('#change_password_button').html('Update Password');
                        location.reload();
                    }
                });
            }
            else
            {
                $('#change_password_button').html('Update Password');
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