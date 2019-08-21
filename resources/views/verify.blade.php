@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-0 py-4">
                    <div class="row m-0 mb-4">
                        <div class="col-12 px-4">
                            <h3 class="mb-4">Verify Email</h3>
                            <div class="input-group mb-3">
                                <input type="email" id="email-field" class="form-control" placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}" style="height: 52px;">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button" id="verify_email_button" style="min-width: 120px; font-weight: 700;" onclick="verify_email_ajax()">Verify</button>
                                </div>
                            </div>
                            <span class="invalid-feedback-custom">
                                <strong id="email_error"></strong>
                            </span>
                        </div>
                    </div>
                    
                    <div id="verify_response" style="display:none">
                        <div class="row m-0 response mt-4">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 pl-4">Format</div>
                            <div id="verify_format" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 text-right"></div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2">Type</div>
                            <div id="verify_type" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-right p-2 pr-4">PROFFESSIONAL</div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 pl-4">Server Status</div>
                            <div id="verify_status" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-right p-2"></div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2">Email Status</div>
                            <div id="verify_email_status"class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-right p-2 pr-4"></div>
                        </div>
                    </div>
                    <div class="row m-0" id="verify_help_text">
                        <div class="col-12 px-4 pt-0 pb-0">
                            Enter an email address to verify its accuracy.
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
$( document ).ready(function() {
    $("#email-field").keyup(function(event) {
        if (event.keyCode === 13) {
            if(!$("#verify_email_button").is('[disabled]'))
            {
                $("#verify_email_button").click();
            }
            
        }
    });
});

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function verify_email_ajax()
{
   
    document.getElementById('email_error').innerHTML='';
    $('#verify_response').css('display','none');
    $('#verify_help_text').css('display','block');

    var email=document.getElementById("email-field").value;
    if(email==null || email=="")
    {
        document.getElementById('email_error').innerHTML="Email can not be Empty";
    }
    else if(email.length>50)
    {
        document.getElementById('email_error').innerHTML="Email too Long";
    }
    else if(!validateEmail(email))
    {
        document.getElementById('email_error').innerHTML="Invalid Email Address";
    }
    else
    {
        $('#verify_email_button').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#verify_email_button').attr('disabled',true);
        $('#verify_response').css('display','none');
        $.ajax({
            method: 'POST',
            dataType: 'json',  
            url: 'verify_email', 
            data: {'email' : email,"_token": "{{ csrf_token() }}"}, 
            success: function(response){ // What to do if we succeed

                console.log(response);
                if(response['server_status']=="Valid")
                {
                    document.getElementById('verify_status').style.color = 'green';
                }
                else if(response['server_status']=="Catch All")
                {
                    document.getElementById('verify_status').style.color = 'orange';
                }
                else
                {
                    document.getElementById('verify_status').style.color = 'red';
                }
                if(response['email_status']=="Valid")
                {
                    document.getElementById('verify_email_status').style.color = 'green';
                }
                else if(response['email_status']=="Catch All")
                {
                    document.getElementById('verify_email_status').style.color = 'orange';
                }
                else
                {
                    document.getElementById('verify_email_status').style.color = 'red';
                }
                document.getElementById('verify_format').style.color = 'green';
                document.getElementById('verify_format').innerHTML="Valid";
                document.getElementById('verify_status').innerHTML=response['server_status'];
                document.getElementById('verify_email_status').innerHTML=response['email_status'];
                $('#verify_response').css('display','block');
                $('#verify_help_text').css('display','none');
                $('#verify_email_button').html('Verify');
                $('#verify_email_button').attr('disabled',false);

                


            },
            error: function(jqXHR, textStatus, errorThrown) {
                if( jqXHR.status === 422 )
                {
                    $errors = jqXHR.responseJSON;

                     $.each( $errors.errors , function( key, value ) {
                        if(key=='email')
                        {   
                            document.getElementById('email_error').innerHTML=value[0];
                        }
                       
                        
                    });
                }
                else
                {
                    // document.getElementsByClassName("email-verifier-result-container")[0].innerHTML="Something Went Wrong";
                    console.log(jqXHR);
                }
                $('#verify_email_button').html('Verify');
                $('#verify_email_button').attr('disabled',false);
            },
            timeout: 60000
        });
    }
    
    
}

</script>
@endpush