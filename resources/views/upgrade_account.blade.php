@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                	<a id="buy-button" class="btn btn-primary">Buy now!</a>
                </div>
            </div>
        </div>
        
    </div>
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
    window.document.getElementById('buy-button').addEventListener('click', function() {
    TwoCoInlineCart.products.removeAll();
    TwoCoInlineCart.products.add({

        code: "1"

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