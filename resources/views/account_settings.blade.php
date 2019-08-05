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
							  <input type="text" class="form-control" placeholder="Full Name" aria-label="Full Name" aria-describedby="basic-addon2" value="{{ Auth::user()->name }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="Company Name" aria-label="Company Name" aria-describedby="basic-addon2" value="{{ Auth::user()->company_name }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon2" value="{{ Auth::user()->phone }}">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2" value="{{ Auth::user()->email }}" disabled>
							</div>
							<div class="input-group mb-2 mt-2">
							    <button class="btn btn-primary" type="button">Update Information</button>
							</div>
					    
					  </div>
					</div>

					<div class="card mb-2">
					  <div class="card-body">
					    <h5 class="card-title">Change Password</h5>
						   
		                    <div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="New Password" aria-label="New Password" aria-describedby="basic-addon2">
							  
							</div>
							<div class="input-group mb-2 mt-2">
							  <input type="text" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon2">
							  
							</div>
							<div class="input-group">
							    <button class="btn btn-primary" type="button">Update Password</button>
							</div>
					    
					  </div>
					</div>
                	

					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
