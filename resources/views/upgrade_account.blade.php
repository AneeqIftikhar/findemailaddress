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
            <!-- <a href='#' data-fsc-action="Add,Checkout" data-fsc-item-path-value="small">Purchase "Small Plan"</a> -->
            <button href="#" class="btn btn-block btn-primary text-uppercase" disabled>Buy Now!</button>
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
            <a href="#buy" id="buy1" class="btn btn-block btn-primary text-uppercase">Buy Now!</a>
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
            <a href="#" id="buy2" class="btn btn-block btn-primary text-uppercase">Buy Now!</a>
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
            <a href="#" id="buy3" class="btn btn-block btn-primary text-uppercase">Buy Now!</a>
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
});
function onFSPopupClosed(orderReference) 
{
  if (orderReference)
  {
    console.log(orderReference.reference);
    fastspring.builder.reset();
  } 
  else 
  {
    console.log("no order ID");
  }
}


</script>
@endpush