@extends('layouts.app')

@section('content')
<div class="container">
    

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
            <button href="#" class="btn btn-block btn-primary text-uppercase" disabled>Buy Now!</button>
          </div>
        </div>
      </div>
      <!-- Plus Tier -->
      <div class="col-lg-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Small</h5>
            <h6 class="card-price text-center">$25<span class="period">/month</span></h6>
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
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>5000</strong> Emails</li>
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
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Find & Verify <strong>20000</strong> Emails</li>
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
<script type="text/javascript">

    var user = {!! auth()->user() !!};
    (function (document, src, libName, config) {
        var script             = document.createElement('script');
        script.src             = src;
        script.async           = true;
        var firstScriptElement = document.getElementsByTagName('script')[0];
        script.onload          = function () {
            for (var namespace in config) {
                if (config.hasOwnProperty(namespace)) {
                    window[libName].setup.setConfig(namespace, config[namespace]);
                }
            }
            window[libName].register();
        };

        firstScriptElement.parentNode.insertBefore(script, firstScriptElement);
    })(document, 'https://secure.avangate.com/checkout/client/twoCoInlineCart.js', 'TwoCoInlineCart',{"app":{"merchant":"250183608226"},"cart":{"host":"https:\/\/secure.2checkout.com","customization":"inline"}});
  $( document ).ready(function() {
    document.getElementById('buy1').addEventListener('click', function(event) {
    event.preventDefault();
    TwoCoInlineCart.products.removeAll();
    TwoCoInlineCart.products.add({

        code: "1",
        quantity: 1

      });
    TwoCoInlineCart.cart.setReturnMethod({
        type: 'redirect',
        url : 'http://localhost/email_finder_verifier/return_url'
      });

      TwoCoInlineCart.cart.setCustomerReference(user['two_checkout_user_reference']);
      console.log(TwoCoInlineCart.products.getAll());
      TwoCoInlineCart.cart.checkout();

    });
    document.getElementById('buy2').addEventListener('click', function(event) {
      event.preventDefault();
    TwoCoInlineCart.products.removeAll();
    TwoCoInlineCart.products.add({

        code: "2",
        quantity: 1

      });
    TwoCoInlineCart.cart.setReturnMethod({
        type: 'redirect',
        url : 'http://localhost/email_finder_verifier/return_url'
      });

      TwoCoInlineCart.cart.setCustomerReference(user['two_checkout_user_reference']);

      TwoCoInlineCart.cart.checkout();

    });
    document.getElementById('buy3').addEventListener('click', function(event) {
      event.preventDefault();
    TwoCoInlineCart.products.removeAll();
    TwoCoInlineCart.products.add({

        code: "3",
        quantity: 1

      });
    TwoCoInlineCart.cart.setReturnMethod({
        type: 'redirect',
        url : 'http://localhost/email_finder_verifier/return_url'
      });

      TwoCoInlineCart.cart.setCustomerReference(user['two_checkout_user_reference']);

      TwoCoInlineCart.cart.checkout();

    });
});

</script>
@endpush