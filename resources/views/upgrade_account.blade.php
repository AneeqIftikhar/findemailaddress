@extends('layouts.app')

@section('content')
<div class="container">
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

<section class="pricing py-5">
  <div class="container">
    <div class="row">
      <!-- Free Tier -->
      <div class="col-lg-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Free</h5>
            <h6 class="card-price text-center">$0<span class="period">/month</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>100</strong> Emails</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Only Pay for Verified Emails</li>
              
              <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Unused credits rollover</li>
              <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Claim Credits on Bounce</li>
              <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Money Back Gaurantee</li>
             <li><span class="fa-li"><i class="fas fa-check"></i></span>Build Contact & Export CSV</li>
            </ul>
            @if ( !empty ( $data ) ) 
              <a href="#" id="disable" class="btn btn-block btn-primary text-uppercase">Cancel Subscription</a>

            @else
              <a id="subscribed" class="btn btn-block btn-danger text-uppercase">Subscribed</a>
            @endif
            
          </div>
        </div>
      </div>
      <!-- Plus Tier -->
      <div class="col-lg-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Small</h5>
            <h6 class="card-price text-center">$29<span class="period">/month</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>1000</strong> 
                Emails
              </li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Only Pay for Verified Emails</li>
              
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Unused credits rollover</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Claim Credits on Bounce</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Money Back Gaurantee</li>
             <li><span class="fa-li"><i class="fas fa-check"></i></span>Build Contact & Export CSV</li>
            </ul>
            @if ( !empty ( $data ) ) 
              @if($data->product_name=="small" || $data->product_name=="Small" || $data->product_name=="basic" || $data->product_name=="Basic")
                <a id="subscribed" class="btn btn-block btn-danger text-uppercase">Subscribed</a>
              @else
                <a href="#" id="select1" class="btn btn-block btn-danger text-uppercase">Select</a>
              @endif

            @else
              <a href="#buy" id="buy1" class="btn btn-block btn-primary text-uppercase">Select</a>
            @endif
            
          </div>
        </div>
      </div>
      <!-- Pro Tier -->
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Medium</h5>
            <h6 class="card-price text-center">$49<span class="period">/month</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>2500</strong> Emails</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Only Pay for Verified Emails</li>
              
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Unused credits rollover</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Claim Credits on Bounce</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Money Back Gaurantee</li>
             <li><span class="fa-li"><i class="fas fa-check"></i></span>Build Contact & Export CSV</li>
            </ul>
            @if ( !empty ( $data ) ) 
              @if($data->product_name=="medium" || $data->product_name=="Medium" || $data->product_name=="pro" || $data->product_name=="Pro")
                <a id="subscribed" class="btn btn-block btn-danger text-uppercase">Subscribed</a>
              @else
                <a href="#" id="select2" class="btn btn-block btn-primary text-uppercase">Select</a>
              @endif

            @else
              <a href="#" id="buy2" class="btn btn-block btn-primary text-uppercase">Buy Now!</a>
            @endif
            
          </div>
        </div>
      </div>
      <!-- Plus Tier -->
      <div class="col-lg-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Large</h5>
            <h6 class="card-price text-center">$99<span class="period">/month</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>10000</strong> Emails</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Only Pay for Verified Emails</li>
              
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Unused credits rollover</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Claim Credits on Bounce</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Money Back Gaurantee</li>
             <li><span class="fa-li"><i class="fas fa-check"></i></span>Build Contact & Export CSV</li>
            </ul>
            @if ( !empty ( $data ) ) 
              @if($data->product_name=="large" || $data->product_name=="Large" || $data->product_name=="Enterprise" || $data->product_name=="enterprise")
                <a id="subscribed" class="btn btn-block btn-danger text-uppercase">Subscribed</a>
              @else
                <a id="select3" class="btn btn-block btn-primary text-uppercase">Select</a>
              @endif

            @else
              <a href="#" id="buy3" class="btn btn-block btn-primary text-uppercase">Buy Now!</a>
            @endif
            
          </div>
        </div>
      </div>
      

    </div>
  </div>
</section>
    
</div>
@endsection
@push('scripts')
<script
        id="fsc-api"
        src="https://d1f8f9xcsvx3ha.cloudfront.net/sbl/0.8.1/fastspring-builder.min.js"
        type="text/javascript"
        data-popup-closed="onFSPopupClosed"
        data-storefront="devrec.test.onfastspring.com/popup-devrec">

</script>
<script type="text/javascript">


	$( document ).ready(function() {
  if(document.getElementById('buy1'))
  {
    document.getElementById('buy1').addEventListener('click', function(event) {
    event.preventDefault();
    $('#buy1').html('<i class="fa fa-spinner fa-spin"></i>');
    $('#buy1').attr('disabled',true);
    $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_fastspring_session', 
          data: {'package_name' : "small","_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            console.log(response);
            $('#buy1').html('Buy Now!');
            $('#buy1').attr('disabled',false);
            fastspring.builder.checkout(response['id']);
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $('#buy1').html('Buy Now!');
              $('#buy1').attr('disabled',false);
              console.log(jqXHR);
              
          }
      });
    

    });
  }
	if(document.getElementById('buy2'))
  {
  	document.getElementById('buy2').addEventListener('click', function(event) {
  		event.preventDefault();
      $('#buy2').html('<i class="fa fa-spinner fa-spin"></i>');
      $('#buy2').attr('disabled',true);
  	$.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_fastspring_session', 
          data: {'package_name' : "medium","_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            $('#buy2').html('Buy Now!');
            $('#buy2').attr('disabled',false);
          	fastspring.builder.checkout(response['id']);
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $('#buy2').html('Buy Now!');
              $('#buy2').attr('disabled',false);
              console.log(jqXHR);
              
          }
      });

  	});
  }
  if(document.getElementById('buy3'))
  {
  	document.getElementById('buy3').addEventListener('click', function(event) {
  	event.preventDefault();
    $('#buy3').html('<i class="fa fa-spinner fa-spin"></i>');
    $('#buy3').attr('disabled',true);
  	$.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_fastspring_session', 
          data: {'package_name' : "large","_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            $('#buy3').html('Buy Now!');
            $('#buy3').attr('disabled',false);
          	fastspring.builder.checkout(response['id']);
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $('#buy3').html('Buy Now!');
              $('#buy3').attr('disabled',false);
              console.log(jqXHR);
              
          }
      });
	   });
  }
  if(document.getElementById('select1'))
  {
    document.getElementById('select1').addEventListener('click', function(event) {
    event.preventDefault();
    $('#select1').html('<i class="fa fa-spinner fa-spin"></i>');
    $('#select1').attr('disabled',true);
    $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'update_subscription', 
          data: {'package_name' : "small","_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            console.log(response);

          },
          error: function(jqXHR, textStatus, errorThrown) {

              console.log(jqXHR);
              
          }
      });
    

    });
  }
  if(document.getElementById('select2'))
  {
    document.getElementById('select2').addEventListener('click', function(event) {
      event.preventDefault();
      $('#select2').html('<i class="fa fa-spinner fa-spin"></i>');
      $('#select2').attr('disabled',true);
    $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_fastspring_session', 
          data: {'package_name' : "medium","_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
          },
          error: function(jqXHR, textStatus, errorThrown) {
              console.log(jqXHR);
              
          }
      });

    });
  }
  if(document.getElementById('select3'))
  {
    document.getElementById('select3').addEventListener('click', function(event) {
    event.preventDefault();
    $('#select3').html('<i class="fa fa-spinner fa-spin"></i>');
    $('#select3').attr('disabled',true);
    $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_fastspring_session', 
          data: {'package_name' : "large","_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
          },
          error: function(jqXHR, textStatus, errorThrown) {
              console.log(jqXHR);
              
          }
      });

    });
  }
});

function onFSPopupClosed(orderReference) 
{
  if (orderReference)
  {
    console.log(orderReference.reference);
    fastspring.builder.reset();
    location.reload();
  } 
  else 
  {
    console.log("no order ID");
  }
}


</script>
@endpush