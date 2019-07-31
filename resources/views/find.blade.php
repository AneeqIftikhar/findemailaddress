@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Find Email</h3></div>

                <div class="card-body">
                        <div class="input-group main-input-group">
                            <input autocomplete="off" autofocus="autofocus" class="form-control" id="first-name-field" placeholder="John" required="required" type="text" value="">

                            <input autocomplete="off" autofocus="autofocus" class="form-control" id="last-name-field" placeholder="Doe" required="required" type="text" value="">
                            
                            <input autocomplete="off" class="form-control" id="domain-field" placeholder="company.com" required="required" type="text" value="">

                           
                        </div>
                         <button class="btn btn-success"  onclick="find_email_ajax()">
                                Find
                            </button>
                        <div class="email-finder-result">
                            <div class="email-finder-message">Enter first name, last name and the domain name of the email address (for example "ripcordsystems.com").</div>
                            <div class="email-finder-result-container">
                                
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
function find_email_ajax()
{
    var first_name=document.getElementById("first-name-field").value;
    var last_name=document.getElementById("last-name-field").value;
    var domain=document.getElementById("domain-field").value;
    $.ajax({
        method: 'POST',
        dataType: 'json', 
        url: 'find_email', 
        data: {'first_name' : first_name,'last_name':last_name,'domain':domain,"_token": "{{ csrf_token() }}"}, 
        success: function(response){ // What to do if we succeed
            document.getElementsByClassName("email-finder-result-container")[0].innerHTML="<h4>Result</h4><div class='email-finder-result-status'></div><div class='email-finder-result-emails'></div>";
            document.getElementsByClassName("email-finder-result-status")[0].innerHTML="status: "+response['status'];
            if(response['emails'] && response['emails'].length>0)
            {
                document.getElementsByClassName("email-finder-result-emails")[0].innerHTML="Emails: "+response['emails'];
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
             document.getElementsByClassName("email-finder-result-container")[0].innerHTML="Something Went Wrong";
        },
        timeout: 10000 // sets timeout to 3 seconds
    });
}

</script>