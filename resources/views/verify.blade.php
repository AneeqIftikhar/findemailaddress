@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Verify Email</h3></div>

                <div class="card-body">
                        <div class="input-group main-input-group">
                            <input autocomplete="off" autofocus="autofocus" class="form-control" id="email-field" placeholder="johndoe@company.com" required="required" type="email" value="">

                           
                        </div>
                         <button class="btn btn-success"  style="width:80px; height:40px" id="verify_email_button" onclick="verify_email_ajax()">
                                Verify
                            </button>
                        <div class="email-verifier-result">
                            <div class="email-verifier-message">Enter an email address to verify its accuracy.</div>
                            <div class="email-verifier-result-container"></div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
function verify_email_ajax()
{
    $('#verify_email_button').html('<i class="fa fa-refresh fa-spin"></i>');
    $('#verify_email_button').attr('disabled',true);
    var email=document.getElementById("email-field").value;
    document.getElementsByClassName("email-verifier-result-container")[0].innerHTML="<h4>Result</h4>";
    $.ajax({
        method: 'POST', 
        url: 'verify_email', 
        data: {'email' : email,"_token": "{{ csrf_token() }}"}, 
        success: function(response){ // What to do if we succeed
            document.getElementsByClassName("email-verifier-result-container")[0].innerHTML+=response;
            $('#verify_email_button').html('Verify');
            $('#verify_email_button').attr('disabled',false);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('#verify_email_button').html('Verify');
            $('#verify_email_button').attr('disabled',false);
        }
    });
}

</script>