
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-0 py-4">
                    <div class="row m-0 mb-4">
                        <div class="col-12 px-4">
                            <h3 class="mb-4">Find Email</h3>
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
                                <button class="btn btn-success" type="button" id="find_email_button" style="min-width: 120px; font-weight: 700;" onclick="find_email_ajax()">
                                Find
                                </button>
                            </div>
                            <span class="invalid-feedback-custom">
                                <strong id="find_error"></strong>
                            </span>
                        </div>
                    </div>
                   <!--  <div id="find_response" style="display:none">
                        <div  class="row m-0 response mt-4" >
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 pl-4">Email</div>
                        <div id="find_email" style="color: grey" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2 text-right"></div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 p-2">Status</div>
                        <div id="find_status" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-right p-2 pr-4"></div>
                    </div>
                    </div> -->
                    
                    <div class="row m-0">
                        <div class="col-12 px-4 pt-0 pb-0" id="find_help_text">
                            Enter first name, last name and the domain name of the email address (for example "ripcordsystems.com").
                        </div>
                    </div>
                    <div class="email-verifier-result">
                        <div id="email-verifier-result-container"></div>
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
								    <!-- <tr>
								      <td>Aneeq Iftikhar</td>
								      <td>Ripcordsystems.com</td>
								      <td>Not Found</td>
								    </tr>
								    <tr>
								       <td>Aneeq Iftikhar</td>
								      <td>dev-rec.com</td>
								      <td>Valid</td>
								    </tr>
								    <tr>
								       <td>Abdul Aleem</td>
								      <td>omno.ai</td>
								      <td>Valid</td>
								    </tr> -->

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
});


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
    	var tableRef = document.getElementById('activity_find_email_table').getElementsByTagName('tbody')[0];
    	if(tableRef.rows.length>=5)
        {
            tableRef.deleteRow(tableRef.rows.length-1);
        }
        var newRow   = tableRef.insertRow(0);


	      newCell  = newRow.insertCell(0);
	      newText  = document.createTextNode(first_name+" "+last_name);
	      newCell.appendChild(newText);

	      newCell  = newRow.insertCell(1);
	      newText  = document.createTextNode(domain);
	      newCell.appendChild(newText);

	      newCell  = newRow.insertCell(2);
		  spinner = document.createElement("i");
	      spinner.className="fa fa-spinner fa-spinner fa-spin";
	      newCell.appendChild(spinner);


        
        $.ajax({
            method: 'POST',
            dataType: 'json', 
            url: 'find_email', 
            data: {'first_name' : first_name,'last_name':last_name,'domain':domain,"_token": "{{ csrf_token() }}"}, 
            success: function(response){ // What to do if we succeed
                console.log(response);
                document.getElementById('email-verifier-result-container').innerHTML=" ";
                document.getElementById('email-verifier-result-container').innerHTML="Proxy: "+response['proxy'];
                document.getElementById('email-verifier-result-container').innerHTML+="<br>";
                for (var i = 0; i<response['logs'].length;i++)
                {
                    document.getElementById('email-verifier-result-container').innerHTML+=response['logs'][i]+"<br>";
                }
                
                

                if('status' in response && 'emails' in response)
                {
                    if(response['status']=="Valid")
                    {	
                    	newRow.style.border= "2px solid rgba(0, 255, 0, 0.3)";
                    	newRow.cells[2].innerHTML='<div style="color:green">Valid</div>';
                    }
                    else if(response['status']=="Catch All")
                    {
                    	newRow.style.border= "2px solid rgba(255, 255, 0, 0.3)";
                    	newRow.cells[2].innerHTML='<div style="color:orange">Catch All</div>';
                    }
                    else
                    {
                    	newRow.style.border= "2px solid rgba(255, 0, 0, 0.3)";
                    	newRow.cells[2].innerHTML='<div style="color:red">Not Found</div>';
                    }
                    if(response['emails']=='' || response['emails']==null || response['emails']==undefined)
                    {
                    	newRow.cells[1].innerHTML='<div>-</div>';
                    }
                    else
                    {
                    	newRow.cells[1].innerHTML='<div>'+response['emails']+'</div>';
                    }
                    document.getElementById('credits_left_span').innerHTML=response['credits_left'];
                    
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                
                if( jqXHR.status === 422 )
                {
                    $errors = jqXHR.responseJSON;

                     $.each( $errors.errors , function( key, value ) {
                            document.getElementById('find_error').innerHTML=value[0];
                        
                    });
                }
                else if( jqXHR.status === 419 )
                {
                    console.log(jqXHR);
                    // if(jqXHR.statusText=="unknown status")
                    // {
                    //     document.getElementById('find_error').innerHTML="Login Again";
                    // }
                    $("#login_again").modal()
                    
                }
                else
                {
                    console.log(jqXHR);
                    if(jqXHR.statusText=="timeout")
                    {
                    	newRow.cells[2].innerHTML='<div style="color:red">Request Time Out</div>';
                        document.getElementById('find_error').innerHTML="Request Timeout.";
                    }
                }
                //first_name_error
            },
            timeout: 600000 // sets timeout to 60 seconds
        });
    }
    
}

</script>
@endpush