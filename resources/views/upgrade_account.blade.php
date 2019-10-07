@extends('layouts.app')

@section('page')
    {{ "Upgrade Account" }}
@endsection

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
            @if ( Auth::user()->package->name=="free" ) 
              <button id="subscribed" class="btn btn-block btn-danger text-uppercase">Subscribed</button>              
            @else
              <button href="#" id="cancel" onClick="cancel_subscription()" class="btn btn-block btn-primary text-uppercase">Cancel Subscription</button>
            @endif
            
          </div>
        </div>
      </div>
      <!-- Plus Tier -->
      <div class="col-lg-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Basic</h5>
            <h6 class="card-price text-center">$29.99<span class="period">/month</span></h6>
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

            @if (Auth::user()->package->name=="free" && $data['pending_status']=="")
              <button href="#buy" id="buy_basic" onClick="buy('basic')" class="btn btn-block btn-primary text-uppercase">Buy Now!</button>
            @elseif(Auth::user()->package->name=="free" && $data['current_pending_package']=="basic" && $data['pending_is_active']==0)
              <button href="#" id="uncancel_basic" onClick="uncancel('basic')" class="btn btn-block btn-primary text-uppercase">Reactivate</button>
            @elseif(Auth::user()->package->name=="basic")
              <button id="subscribed" class="btn btn-block btn-danger text-uppercase">Subscribed</button>
            @elseif(Auth::user()->package->name!="basic" && $data['next_pending_package']=="basic" && $data['pending_status']!="CANCELED")
              <button href="#" id="downgraded_basic" class="btn btn-block btn-danger text-uppercase">PENDING {{$data['pending_status']}}</button>
            @elseif(Auth::user()->package->name!="basic")
              <button href="#" id="select_basic" onClick="select('basic')" class="btn btn-block btn-primary text-uppercase">Select</button>
            @else
              <button href="#" id="select_disabled" class="btn btn-block btn-danger text-uppercase">Select</button>
            @endif
            
          </div>
        </div>
      </div>
      <!-- Pro Tier -->
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">EXTENDED</h5>
            <h6 class="card-price text-center">$49.99<span class="period">/month</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>2500</strong> Emails</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Only Pay for Verified Emails</li>
              
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Unused credits rollover</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Claim Credits on Bounce</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Money Back Gaurantee</li>
             <li><span class="fa-li"><i class="fas fa-check"></i></span>Build Contact & Export CSV</li>
            </ul>
            

             @if (Auth::user()->package->name=="free" && $data['pending_status']=="")
              <button href="#buy" id="buy_extended" onClick="buy('extended')" class="btn btn-block btn-primary text-uppercase">Buy Now!</button>
            @elseif(Auth::user()->package->name=="free" && $data['current_pending_package']=="extended" && $data['pending_is_active']==0)
              <button href="#" id="uncancel_extended" onClick="uncancel('extended')" class="btn btn-block btn-primary text-uppercase">Reactivate</button>
            @elseif(Auth::user()->package->name=="extended")
              <button id="subscribed_extended" class="btn btn-block btn-danger text-uppercase">Subscribed</button>
            @elseif(Auth::user()->package->name!="extended" && $data['next_pending_package']=="extended" && $data['pending_status']!="CANCELED")
              <button href="#" id="downgraded_extended" class="btn btn-block btn-danger text-uppercase">PENDING {{$data['pending_status']}}</button>
            @elseif(Auth::user()->package->name!="extended")
              <button href="#" id="select_extended" onClick="select('extended')" class="btn btn-block btn-primary text-uppercase">Select</button>
            @else
              <button href="#" id="select_disabled_extended" class="btn btn-block btn-danger text-uppercase">Select</button>
            @endif
            
          </div>
        </div>
      </div>
      <!-- Plus Tier -->
      <div class="col-lg-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">CORPORATE</h5>
            <h6 class="card-price text-center">$99.99<span class="period">/month</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>10000</strong> Emails</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Only Pay for Verified Emails</li>
              
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Unused credits rollover</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Claim Credits on Bounce</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Money Back Gaurantee</li>
             <li><span class="fa-li"><i class="fas fa-check"></i></span>Build Contact & Export CSV</li>
            </ul>

            @if (Auth::user()->package->name=="free" && $data['pending_status']=="")
              <button href="#buy" id="buy_corporate" onClick="buy('corporate')" class="btn btn-block btn-primary text-uppercase">Buy Now!</button>
            @elseif(Auth::user()->package->name=="free" && $data['current_pending_package']=="corporate" && $data['pending_is_active']==0)
              <button href="#" id="uncancel_corporate" onClick="uncancel('corporate')" class="btn btn-block btn-primary text-uppercase">Reactivate</button>
            @elseif(Auth::user()->package->name=="corporate")
              <button id="subscribed_corporate" class="btn btn-block btn-danger text-uppercase">Subscribed</button>
            @elseif(Auth::user()->package->name!="corporate" && $data['next_pending_package']=="corporate" && $data['pending_status']!="CANCELED")
              <button href="#" id="downgraded_corporate" class="btn btn-block btn-danger text-uppercase">PENDING {{$data['pending_status']}}</button>
            @elseif(Auth::user()->package->name!="corporate")
              <button href="#" id="select_corporate" onClick="select('corporate')" class="btn btn-block btn-primary text-uppercase">Select</button>
            @else
              <button href="#" id="select_disabled_corporate" class="btn btn-block btn-danger text-uppercase">Select</button>
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
        data-storefront="devrec.onfastspring.com/popup-devrec">

</script>
<script type="text/javascript">

  function cancel_subscription()
  {
    $("#action_modal_title").html('Cancel Subscriptiont');
    $("#action_modal_message").html('Are You Sure You want to Cancel Your Subscription');
    $("#action_button").click(cancel_subscription_ajax);
    $("#action_modal").modal();

    
  }
  function cancel_subscription_ajax()
  {
      $('#action_modal').modal('hide');
      $('#cancel').html('<i class="fa fa-spinner fa-spin"></i>');
      $('#cancel').attr('disabled',true);
      $.ajax({
            method: 'POST',
            dataType: 'json', 
            url: 'cancel_subscription', 
            data: {"_token": "{{ csrf_token() }}"}, 
            success: function(response){ 
              location.reload();
              console.log(response);

            },
            error: function(jqXHR, textStatus, errorThrown) {

                console.log(jqXHR);
                
            }
        });
    
  }
  function uncancel(package_name)
  {
    $("#action_modal_title").html('Reactivate Subscriptiont');
    $("#action_modal_message").html('Are You Sure You Want to Reactivate Your Subscription');
    $("#action_button").click({'package_name': package_name}, uncancel_ajax);
    $("#action_modal").modal();
  }
  function uncancel_ajax(event)
  {
    var package_name=event.data.package_name;
    $('#action_modal').modal('hide');
    $('#uncancel_'+package_name).html('<i class="fa fa-spinner fa-spin"></i>');
    $('#uncancel_'+package_name).attr('disabled',true);
    $.ajax({
          method: 'POST',
          dataType: 'json', 
          url: 'uncancel_subscription', 
          data: {'package_name' : package_name,"_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            location.reload();
            console.log(response);

          },
          error: function(error) {
              $('#uncancel_'+package_name).html('Reactivate');
              $('#uncancel_'+package_name).attr('disabled',false);
              alert("Something Went Wrong");
              console.log(error);
              
          }
      });
  }
  function select(package_name)
  {

    $("#action_modal_title").html('Select Subscriptiont');
    $("#action_modal_message").html('Are You Sure You Want to Select This Subscription');
    $("#action_button").click({'package_name': package_name}, select_ajax);
    $("#action_modal").modal();
    
  }
  function select_ajax(event)
  {
    var package_name=event.data.package_name;
    $('#action_modal').modal('hide');
    $('#select_'+package_name).html('<i class="fa fa-spinner fa-spin"></i>');
    $('#select_'+package_name).attr('disabled',true);
    $.ajax({
          method: 'POST',
          dataType: 'json', 
          url: 'update_subscription', 
          data: {'package_name' : package_name,"_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            location.reload();
            console.log(response);

          },
          error: function(jqXHR, textStatus, errorThrown) {
              $('#select_'+package_name).html('Select');
              $('#select_'+package_name).attr('disabled',false);
              console.log(jqXHR);
              
          }
      });
  }
  function buy($package_name)
  {
    $('#buy_'+$package_name).html('<i class="fa fa-spinner fa-spin"></i>');
    $('#buy_'+$package_name).attr('disabled',true);
    $.ajax({
          method: 'GET',
          dataType: 'json', 
          url: 'get_fastspring_session', 
          data: {'package_name' : $package_name,"_token": "{{ csrf_token() }}"}, 
          success: function(response){ 
            console.log(response);
            $('#buy_'+$package_name).html('Buy Now!');
            $('#buy_'+$package_name).attr('disabled',false);
            fastspring.builder.checkout(response['id']);
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $('#buy1').html('Buy Now!');
              $('#buy1').attr('disabled',false);
              console.log(jqXHR);

              
          }
      });
  }

function onFSPopupClosed(orderReference) 
{
  if (orderReference)
  {
    
    fastspring.builder.reset();
    $("#success_modal_message").html('Order Placed. Please Revisit in 5 Minutes For Changes To Take Effect');
    $("#success_modal").modal();
             
  } 
  else 
  {
    console.log("no order ID");
  }
}


</script>
@endpush