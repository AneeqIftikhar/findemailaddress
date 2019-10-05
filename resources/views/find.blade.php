
@extends('layouts.app')
@section('page')
    {{ "Find Email" }}
@endsection
@section('content')
<div class="container" style="height: 100%">
    <div class="row justify-content-center" style="height: 100%">
        <div class="col-md-6">
            <div class="card" style="height: 100%">
                <div class="card-header"><h4>Find Email</h4></div>
                <div class="card-body p-0 py-4">
                    <!-- <div style="position: relative;top: 50% !important;transform: translateY(-50%);"> -->
                        <div class="row m-0 mb-4">
                            <div class="col-12 px-4">
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" placeholder="First name" class="form-control" id="first-name-field">
                                </div>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" placeholder="Last name" class="form-control" id="last-name-field">
                                </div>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" placeholder="Domain name" class="form-control" id="domain-field">
                                </div>
                                <div class="input-group">
                                    <button class="btn btn-primary" type="button" id="find_email_button" style="min-width: 120px; font-weight: 700;" onclick="find_email_ajax()">
                                    Find
                                    </button>
                                </div>
                                <span class="invalid-feedback-custom">
                                    <strong id="find_error"></strong>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row m-0">
                            <div class="col-12 px-4 pt-0 pb-0" id="find_help_text">
                                Enter first name, last name and the domain name of the email address (for example "ripcordsystems.com").
                            </div>
                        </div>
                        <div class="email-verifier-result">
                            <div id="email-verifier-result-container"></div>
                        </div>
                    <!-- </div> -->
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card activity_log" style="height: 100%">
                <div class="card-header"><h4>Activity Log</h4></div>
                <div class="card-body p-0 py-4" style="overflow-y: auto; max-height: 68vh;">
                    <div class="row m-0 mb-4">
                        <div class="col-12" style="padding-left: 1.4rem!important;">
                            <div class="table-wrapper-scroll-y my-custom-scrollbar">
	                            <table id="activity_find_email_table" class="table">
	                            	<thead>
                                        <tr>
                                          <th scope="col">Name</th>
                                          <th scope="col">Domain/Email</th>
                                          <th scope="col">Status</th>
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

    var request_counter=0;
    document.cookie = "is_logged_in=true";
$( document ).ready(function() {
    data = {!! json_encode($emails->toArray(), JSON_HEX_TAG) !!};
    populate_emails();
    $("#first-name-field").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#find_email_button").click();
            
        }
    });
    $("#last-name-field").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#find_email_button").click();
            
        }
    });
    $("#domain-field").keyup(function(event) {
        if (event.keyCode === 13) {
        	$("#find_email_button").click();
            
        }
    });
    $('body').tooltip({
        selector: '.tooltip_container'
    });
});
function populate_emails()
{
   var tableRef = document.getElementById('activity_find_email_table').getElementsByTagName('tbody')[0];
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
          newText  = document.createTextNode(data[i]['first_name']+" "+data[i]['last_name']);
          newCell.style.border="0px";
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(1);
          if(data[i]['email'])
          {
            newText  = document.createTextNode(data[i]['email']);
          }
          else
          {
            newText  = document.createTextNode(data[i]['domain']);
          }
          
          newCell.style.border="0px";
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(2);

          container = document.createElement("div");
          text = document.createTextNode(data[i]['status']);
          newCell.style.border="0px";
          container.appendChild(text);
          container.style.fontWeight="bold";
          if(data[i]['status']=="Valid" || data[i]['status']=="Multiple Emails")
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            container.style.color = "green";
          }
          else if (data[i]['status']=="Catch All")
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            container.style.color = "orange";
            container.setAttribute('data-toggle', 'tooltip');
            container.setAttribute('data-placement', 'bottom');
            container.setAttribute('data-html', 'true');
            container.setAttribute('title', 'This will catch emails sent to any email address under this domain');
          }
          else if (data[i]['status']=="Risky")
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            container.style.color = "orange";
            container.setAttribute('data-toggle', 'tooltip');
            container.setAttribute('data-placement', 'bottom');
            container.setAttribute('data-html', 'true');
            container.setAttribute('title', 'Email appears to be valid but you might not be authorised to send email to this domain');
          }
          else
          {
            newRow.style.border= "1px solid var(--main-bg-color)";
            container.style.color = "red";
            if(data[i]['status']=="No Mailbox")
            {
                container.setAttribute('data-toggle', 'tooltip');
                container.setAttribute('data-placement', 'bottom');
                container.setAttribute('data-html', 'true');
                container.setAttribute('title', 'This domain does not have a mail server setup');
            }
          }
          

          newCell.appendChild(container);

       
      }
}

function isValidDomain(v) {
  if (!v) return false;
  var re = /^(?!:\/\/)([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
  return re.test(v);
}
function isValidUrl(userInput) {
    var regexQuery = "^(https?://)?(www\\.)?([-a-z0-9]{1,63}\\.)*?[a-z0-9][-a-z0-9]{0,61}[a-z0-9]\\.[a-z]{2,6}(/[-\\w@\\+\\.~#\\?&/=%]*)?$";
    var url = new RegExp(regexQuery,"i");
    return url.test(userInput);
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
        else if(!isValidUrl(domain))
        {
            document.getElementById('find_error').innerHTML="Invalid Domain/URL";
        }
        else
        {
            request_counter++;
            document.getElementById("first-name-field").value="";
            document.getElementById("last-name-field").value="";
            document.getElementById("domain-field").value="";
            var tableRef = document.getElementById('activity_find_email_table').getElementsByTagName('tbody')[0];
            // if(tableRef.rows.length>=16)
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
              newText  = document.createTextNode(first_name+" "+last_name);
              newCell.style.border="0px";
              newCell.appendChild(newText);

              newCell  = newRow.insertCell(1);
              newText  = document.createTextNode(domain);
              newCell.style.border="0px";
              newCell.appendChild(newText);

              newCell  = newRow.insertCell(2);
              spinner = document.createElement("i");
              spinner.className="fa fa-spinner fa-spinner fa-spin";
              newCell.style.border="0px";
              newCell.appendChild(spinner);


            
            $.ajax({
                method: 'POST',
                dataType: 'json', 
                url: 'find_email', 
                data: {'first_name' : first_name,'last_name':last_name,'domain':domain,"_token": "{{ csrf_token() }}"}, 
                success: function(response){ // What to do if we succeed
                    console.log(response);
                    // document.getElementById('email-verifier-result-container').innerHTML=" ";
                    // document.getElementById('email-verifier-result-container').innerHTML="Proxy: "+response['proxy'];
                    // document.getElementById('email-verifier-result-container').innerHTML+="<br>";
                    // for (var i = 0; i<response['logs'].length;i++)
                    // {
                    //     document.getElementById('email-verifier-result-container').innerHTML+=response['logs'][i]+"<br>";
                    // }
                    
                    

                    if('status' in response && 'emails' in response)
                    {
                        
                        var container = document.createElement("div");
                        container.className="tooltip_container";
                        var text = document.createTextNode(response['status']);
                        newRow.deleteCell(2);
                        var newCell  = newRow.insertCell(2);
                        newCell.style.border="0px";
                        
                        container.appendChild(text);
                        container.style.fontWeight="bold";
                        if(response['status']=="Valid" || response['status']=="Multiple Emails")
                        {   
                            newRow.style.border= "1px solid var(--main-bg-color)";
                            container.style.color = "green";

                        }
                        else if(response['status']=="Catch All")
                        {
                            newRow.style.border= "1px solid var(--main-bg-color)";
                            //newRow.style.background="rgba(255,165,0,0.2)";
                            // newRow.cells[2].innerHTML='<div style="font-weight: bold; color: orange;">'+response['status']+'</div>';

                            container.style.color = "orange";
                            container.setAttribute('data-toggle', 'tooltip');
                            container.setAttribute('data-placement', 'top');
                            container.setAttribute('data-html', 'true');
                            container.setAttribute('title', 'This will catch emails sent to any email address under this domain');

                            


                        }
                        else if(response['status']=="Risky")
                        {
                            newRow.style.border= "1px solid var(--main-bg-color)";
                            //newRow.style.background="rgba(255,165,0,0.2)";
 

                            container.style.color = "orange";
                            container.setAttribute('data-toggle', 'tooltip');
                            container.setAttribute('data-placement', 'top');
                            container.setAttribute('data-html', 'true');
                            container.setAttribute('title', 'Email appears to be valid but you might not be authorised to send email to this domain');

                        }
                        else
                        {
                            newRow.style.border= "1px solid var(--main-bg-color)";
                            //newRow.style.background="rgba(255,0,0,0.1)";
                            container.style.color = "red";
                            if(response['status']=="No Mailbox")
                            {

                                container.setAttribute('data-toggle', 'tooltip');
                                container.setAttribute('data-placement', 'bottom');
                                container.setAttribute('data-html', 'true');
                                container.setAttribute('title', 'This domain does not have a mail server setup');
                            }


                        }
                        newCell.appendChild(container);
                        if(response['emails']=='' || response['emails']==null || response['emails']==undefined)
                        {
                        }
                        else
                        {
                            newRow.cells[1].innerHTML='<div>'+response['emails']+'</div>';
                        }
                        document.getElementById('credits_left_span').innerHTML=response['credits_left'];
                        

                        
                    }
                    document.getElementById("find_email_button").disabled = false;
                    request_counter--;

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    
                    if( jqXHR.status === 422 )
                    {
                        $errors = jqXHR.responseJSON;

                         $.each( $errors.errors , function( key, value ) {

                                if(value[0].search("must be of company domain")==-1)
                                {
                                    document.getElementById('find_error').innerHTML=value[0];
                                }
                                else
                                {
                                    document.getElementById('find_error').innerHTML="You cannot search for emails at personal domains. Please provide a company domain.";
                                    
                                }
                                
                            
                        });
                        tableRef.deleteRow(0);
                        tableRef.deleteRow(0);
                        request_counter--;
                        document.getElementById("find_email_button").disabled = false;
                        document.getElementById("first-name-field").value=first_name;
                        document.getElementById("last-name-field").value=last_name;
                        document.getElementById("domain-field").value=domain;
                    }
                    else if( jqXHR.status === 419 )
                    {
                        console.log(jqXHR);
                        // if(jqXHR.statusText=="unknown status")
                        // {
                        //     document.getElementById('find_error').innerHTML="Login Again";
                        // }
                        $("#login_again").modal()
                        document.getElementById("find_email_button").disabled = false;
                        request_counter--;
                        
                    }
                    else if (jqXHR.status === 429)
                    {

                        tableRef.deleteRow(0);
                        tableRef.deleteRow(0);
                        document.getElementById('find_error').innerHTML="Too Many Requests";
                        document.getElementById("find_email_button").disabled = false;
                        request_counter--;
                    }
                    else if(jqXHR.status === 403)
                    {
                        tableRef.deleteRow(0);
                        tableRef.deleteRow(0);
                        $("#login_again").modal()
                        document.getElementById("find_email_button").disabled = false;
                        request_counter--;
                    }
                    else
                    {
                        console.log(jqXHR);
                        if(jqXHR.statusText=="timeout")
                        {
                            newRow.cells[2].innerHTML='<div style="color:red">Request Time Out</div>';
                            document.getElementById('find_error').innerHTML="Request Timeout.";
                        }
                        document.getElementById("find_email_button").disabled = false;
                        request_counter--;
                    }
                },
                timeout: 600000 // sets timeout to 60 seconds
            });
        }

        if(request_counter==4)
        {
            document.getElementById("find_email_button").disabled = true;
        }
  
    
}

</script>
@endpush