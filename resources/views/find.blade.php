@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-0 py-4">
                    <div class="row m-0 mb-4">
                        <div class="col-12 px-4">
                            <h3 class="mb-4">Find Email</h3>
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" placeholder="First name" class="form-control" id="first-name-field">
                                <input type="text" placeholder="Last name" class="form-control" id="last-name-field">
                                <input type="text" placeholder="Domain name" class="form-control" id="domain-field">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button" id="find_email_button" style="min-width: 120px; font-weight: 700;" onclick="find_email_ajax()">
                                    Find
                                    </button>
                                </div>
                            </div>
                            <span class="invalid-feedback-custom">
                                <strong id="find_error"></strong>
                            </span>
                        </div>
                    </div>
                    <div id="find_response" style="display:none">
                        <div  class="row m-0 response mt-4" >
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 pl-4">Email</div>
                        <div id="find_email" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 text-right"></div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2">Status</div>
                        <div id="find_status" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-right p-2 pr-4"></div>
                    </div>
                    </div>
                    
                    <div class="row m-0">
                        <div class="col-12 px-4 pt-0 pb-0" id="find_help_text">
                            Enter first name, last name and the domain name of the email address (for example "ripcordsystems.com").
                        </div>
                    </div>
                    <div class="email-verifier-result">
                        <div class="email-verifier-result-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
function isValidDomain(v) {
  if (!v) return false;
  var re = /^(?!:\/\/)([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
  return re.test(v);
}
function find_email_ajax()
{

    
    document.getElementById('find_error').innerHTML='';


    var first_name=document.getElementById("first-name-field").value;
    var last_name=document.getElementById("last-name-field").value;
    var domain=document.getElementById("domain-field").value;
    if(first_name==null || first_name=="")
    {
        document.getElementById('find_error').innerHTML="First Name Can not be Empty";
    }
    else if(first_name.length>50)
    {
        document.getElementById('find_error').innerHTML="First Name too Long";
    }
    else if(last_name==null || last_name=="")
    {
        document.getElementById('find_error').innerHTML="Last Name Can not be Empty";
    }
    else if(last_name.length>50)
    {
        document.getElementById('find_error').innerHTML="Last Name too Long";
    }
    else if(domain==null || domain=="")
    {
        document.getElementById('find_error').innerHTML="Domain Can not be Empty";
    }
    else if(domain.length>50)
    {
        document.getElementById('find_error').innerHTML="Domain too Long";
    }
    else if(!isValidDomain(domain))
    {
        document.getElementById('find_error').innerHTML="Invalid Domain";
    }
    else
    {
        $('#find_email_button').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#find_email_button').attr('disabled',true);
        $('#find_response').css('display','none');
        $.ajax({
            method: 'POST',
            dataType: 'json', 
            url: 'find_email', 
            data: {'first_name' : first_name,'last_name':last_name,'domain':domain,"_token": "{{ csrf_token() }}"}, 
            success: function(response){ // What to do if we succeed
                
                console.log(response['status'] && response['emails']);
                console.log(response['status']);
                console.log(response['emails']);
                if('status' in response && 'emails' in response)
                {
                    $('#find_help_text').css('display','none');
                    $('#find_response').css('display','block');
                    document.getElementById('find_status').innerHTML=response['status'];
                    document.getElementById('find_email').innerHTML=response['emails'][0];
                }
                $('#find_email_button').html('Find');
                $('#find_email_button').attr('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                
                if( jqXHR.status === 422 )
                {
                    $errors = jqXHR.responseJSON;

                     $.each( $errors.errors , function( key, value ) {
                            document.getElementById('find_error').innerHTML=value[0];
                        
                    });
                }
                else
                {
                    console.log(jqXHR);
                }
                //first_name_error
                $('#find_email_button').html('Find');
                $('#find_email_button').attr('disabled',false);
            },
            timeout: 25000 // sets timeout to 25 seconds
        });
    }
    
}

</script>