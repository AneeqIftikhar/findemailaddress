@extends('layouts.app')
@section('page')
    {{ "Verify Email" }}
@endsection
@section('content')
<div class="container" style="height: 100%">
    <div class="row justify-content-center" style="height: 100%">
        <div class="col-md-6">
            <div class="card" style="height: 100%">
              <div class="card-header"><h4>Verify Email</h4></div>
                <div class="card-body p-0 py-4">
                  <!-- <div style="position: relative;top: 50% !important;transform: translateY(-50%);"> -->
                    <div class="row m-0 mb-4">
                        <div class="col-12 px-4">
                            <div class="input-group mb-3">
                                <input type="email" id="email-field" class="form-control" placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}" style="height: 51px;">
                            </div>
                            <div class="input-group mt-4">
                                <button class="btn btn-primary" type="button" id="verify_email_button" style="min-width: 120px; font-weight: 700;" onclick="verify_email_ajax()">Verify</button>
                            </div>
                        
                            <span class="invalid-feedback-custom">
                                <strong id="email_error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row m-0" id="verify_help_text">
                        <div class="col-12 px-4 pt-0 pb-0">
                            Enter an email address to verify its accuracy.
                        </div>
                    </div>
                    
                <!-- </div> -->
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card activity_log" style="height: 100%">
              <div class="card-header"><h4>Activity Log</h4></div>
                <div class="card-body p-0 py-4"  style="overflow-y: auto; max-height: 68vh;">
                    <div class="row m-0 mb-4">
                        <div class="col-12" style="padding-left: 1.4rem!important;">
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
    data = {!! json_encode($emails->toArray(), JSON_HEX_TAG) !!};
    populate_emails();
    $("#email-field").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#verify_email_button").click();
          
        }
    });
});
function populate_emails()
{
   var tableRef = document.getElementById('activity_verify_email_table').getElementsByTagName('tbody')[0];
      for(var i = tableRef.rows.length - 1; i >= 0; i--)
      {
        tableRef.deleteRow(i);
      }
      for(var i = 0;i<data.length;i++)
      {
        
        var newRow   = tableRef.insertRow();
        newCell  = newRow.insertCell(0);
        newCell.style.padding="3px";
        newCell.colspan=3;
        newCell.style.border="0px";

        var newRow   = tableRef.insertRow();
          newCell  = newRow.insertCell(0);
          newText  = document.createTextNode(data[i]['email']);
          newCell.style.border="0px";
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(1);
          newText  = document.createTextNode(data[i]['server_status']);
          
          newCell.style.fontWeight="bold";
          newCell.style.border="0px";
          if(data[i]['server_status']=="Valid")
          {
            newCell.style.color = "green";
          }
          else if (data[i]['server_status']=="Catch All")
          {
            newCell.style.color = "orange";
          }
          else
          {
            newCell.style.color = "red";
          }
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(2);
          newCell.style.border="0px";
          container = document.createElement("span");
          text = document.createTextNode(data[i]['status']);

          container.appendChild(text);
          container.style.fontWeight="bold";
          if(data[i]['status']=="Valid")
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            //newRow.style.background="rgba(0,255,0,0.2)";
            container.style.color = "green";
          }
          else if (data[i]['status']=="Catch All")
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            //newRow.style.background="rgba(255,165,0,0.2)";
            container.style.color = "orange";
          }
          else
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            //newRow.style.background="rgba(255,0,0,0.2)";
            container.style.color = "red";
          }
          

          newCell.appendChild(container);

       
      }
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function verify_email_ajax()
{
   
    document.getElementById('email_error').innerHTML='';
    $('#verify_help_text').css('display','block');

    var email=document.getElementById("email-field").value;
    document.getElementById("email-field").value="";
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
        document.getElementById("email-field").value="";
        var tableRef = document.getElementById('activity_verify_email_table').getElementsByTagName('tbody')[0];
        // if(tableRef.rows.length>=4)
        // {
        //     tableRef.deleteRow(tableRef.rows.length-1);
        //     tableRef.deleteRow(tableRef.rows.length-1);
        // }
        var newRow   = tableRef.insertRow(0);
        newCell  = newRow.insertCell(0);
        newCell.style.padding="3px";
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
                    newRow.style.border= "1px solid var(--main-bg-color)";
                    //newRow.style.background="rgba(0,255,0,0.2)";
                    newRow.cells[1].innerHTML='<div style="font-weight:bold;color:green">'+response['server_status']+'</div>';
                }
                else if(response['server_status']=="Catch All")
                {
                    newRow.style.border= "1px solid var(--main-bg-color)";
                    //newRow.style.background="rgba(255,165,0,0.2)";
                    newRow.cells[1].innerHTML='<div style="font-weight:bold;color:orange">'+response['server_status']+'</div>';
                }
                else
                {
                    newRow.style.border= "1px solid var(--main-bg-color)";
                    //newRow.style.background="rgba(255,0,0,0.2)";
                    newRow.cells[1].innerHTML='<div style="font-weight:bold;color:red">'+response['server_status']+'</div>';
                }
                if(response['email_status']=="Valid")
                {
                    newRow.style.border= "1px solid var(--main-bg-color)";
                    //newRow.style.background="rgba(0,255,0,0.2)";
                    newRow.cells[2].innerHTML='<div style="font-weight:bold;color:green">'+response['email_status']+'</div>';

                }
                else if(response['email_status']=="Catch All")
                {
                    newRow.style.border= "1px solid var(--main-bg-color)";
                    //newRow.style.background="rgba(255,165,0,0.2)";
                    newRow.cells[2].innerHTML='<div style="font-weight:bold;color:orange">'+response['email_status']+'</div>';
                }
                else
                {
                    newRow.style.border= "1px solid var(--main-bg-color)";
                    //newRow.style.background="rgba(255,0,0,0.2)";
                    newRow.cells[2].innerHTML='<div style="font-weight:bold;color:red">'+response['email_status']+'</div>';
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
                       if(value[0].search("must be of company domain")==-1)
                        {
                          document.getElementById('email_error').innerHTML=value[0];
                        }
                        else
                        {
                            
                              document.getElementById('email_error').innerHTML="You cannot search for emails at personal domains. Please provide a company domain.";
                        }
                       
                       
                        
                    });
                    tableRef.deleteRow(0);
                    tableRef.deleteRow(0);
                    document.getElementById("email-field").value=email;
                }
                else if( jqXHR.status === 419 )
                {
                    console.log(jqXHR);

                    $("#login_again").modal()
                    document.getElementById("verify_email_button").disabled = false;
                    request_counter--;
                    
                }
                else if (jqXHR.status === 429)
                {

                    tableRef.deleteRow(0);
                    tableRef.deleteRow(0);
                    document.getElementById('email_error').innerHTML="Too Many Requests";
                    request_counter--;
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