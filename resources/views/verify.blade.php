@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-0 py-4">
                    <div class="row m-0 mb-4">
                        <div class="col-12 px-4">
                            <h3 class="mb-4">Verify Email</h3>
                            <div class="input-group mb-3">
                                <input type="email" id="email-field" class="form-control" placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}" style="height: 52px;">
                            </div>
                            <div class="input-group" style="margin-top: 46px">
                                <button class="btn btn-success" type="button" id="verify_email_button" style="min-width: 120px; font-weight: 700;" onclick="verify_email_ajax()">Verify</button>
                            </div>
                        
                            <span class="invalid-feedback-custom">
                                <strong id="email_error"></strong>
                            </span>
                        </div>
                    </div>
                    
                    <!-- <div id="verify_response" style="display:none">
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
                    </div> -->
                    <div class="row m-0" id="verify_help_text">
                        <div class="col-12 px-4 pt-0 pb-0">
                            Enter an email address to verify its accuracy.
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-0 py-4">
                    <div class="row m-0 mb-4">
                        <div class="col-12 px-4">
                            <h3 class="mb-4">Activity Log</h3>
                            <div class="table-wrapper-scroll-y2 my-custom-scrollbar2">
                                <table id="activity_verify_email_table" class="table">
                                    <thead>
                                        <tr>
                                          <th scope="col">Email</th>
                                          <th scope="col">Server Status</th>
                                          <th scope="col">Email Status</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                   
                                  </tbody>
                                </table>
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
$( document ).ready(function() {
    $("#email-field").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#verify_email_button").click();
          
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
        var tableRef = document.getElementById('activity_verify_email_table').getElementsByTagName('tbody')[0];
        if(tableRef.rows.length>=4)
        {
            tableRef.deleteRow(tableRef.rows.length-1);
            tableRef.deleteRow(tableRef.rows.length-1);
        }
        var newRow   = tableRef.insertRow(0);
        newCell  = newRow.insertCell(0);
        newCell.style.padding="2px";
        newCell.colspan=3;
        newCell.style.border="0px";

        var newRow   = tableRef.insertRow(1);


          newCell  = newRow.insertCell(0);
          newText  = document.createTextNode(email);
          newCell.style.border="0px";
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(1);
          spinner = document.createElement("i");
          spinner.className="fa fa-spinner fa-spinner fa-spin";
          newCell.style.border="0px";
          newCell.appendChild(spinner);

          newCell  = newRow.insertCell(2);
          spinner = document.createElement("i");
          spinner.className="fa fa-spinner fa-spinner fa-spin";
          newCell.style.border="0px";
          newCell.appendChild(spinner);

        $.ajax({
            method: 'POST',
            dataType: 'json',  
            url: 'verify_email', 
            data: {'email' : email,"_token": "{{ csrf_token() }}"}, 
            success: function(response){ // What to do if we succeed

                console.log(response);
                if(response['server_status']=="Valid")
                {
                    newRow.style.border= "2px solid rgba(0, 255, 0, 0.3)";
                    newRow.cells[1].innerHTML='<div style="color:green">'+response['server_status']+'</div>';
                }
                else if(response['server_status']=="Catch All")
                {
                    newRow.style.border= "2px solid rgba(255, 255, 0, 0.3)";
                    newRow.cells[1].innerHTML='<div style="color:orange">'+response['server_status']+'</div>';
                }
                else
                {
                    newRow.style.border= "2px solid rgba(255, 0, 0, 0.3)";
                    newRow.cells[1].innerHTML='<div style="color:red">'+response['server_status']+'</div>';
                }
                if(response['email_status']=="Valid")
                {
                    newRow.style.border= "2px solid rgba(0, 255, 0, 0.3)";
                    newRow.cells[2].innerHTML='<div style="color:green">'+response['email_status']+'</div>';

                }
                else if(response['email_status']=="Catch All")
                {
                    newRow.style.border= "2px solid rgba(255, 255, 0, 0.3)";
                    newRow.cells[2].innerHTML='<div style="color:orange">'+response['email_status']+'</div>';
                }
                else
                {
                    newRow.style.border= "2px solid rgba(255, 0, 0, 0.3)";
                    newRow.cells[2].innerHTML='<div style="color:red">'+response['email_status']+'</div>';
                }
                document.getElementById('credits_left_span').innerHTML=response['credits_left'];
                // document.getElementById('verify_format').style.color = 'green';
                // document.getElementById('verify_format').innerHTML="Valid";
                // document.getElementById('verify_status').innerHTML=response['server_status'];
                // document.getElementById('verify_email_status').innerHTML=response['email_status'];
                // $('#verify_response').css('display','block');
                // $('#verify_help_text').css('display','none');
                // $('#verify_email_button').html('Verify');
                // $('#verify_email_button').attr('disabled',false);

                


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
                    newRow.cells[2].innerHTML='<div style="color:red">'+"-"+'</div>';
                    newRow.cells[1].innerHTML='<div style="color:red">'+"-"+'</div>';
                    console.log(jqXHR);
                }
                // $('#verify_email_button').html('Verify');
                // $('#verify_email_button').attr('disabled',false);
            },
            timeout: 60000
        });
    }
    
    
}

</script>
@endpush