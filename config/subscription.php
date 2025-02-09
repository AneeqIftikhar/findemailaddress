<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Subscription Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for subscription services such
	| as Paypal, CCNow, 2Checkout, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/
	'services' => [
		'ccnow'=>[
			'login'=>'', 
			'key'=>'',
			'url'=>env('APP_URL', '')
		],

		'2checkout'=>[
			'sid'=>env('2CHECKOUT_MERCHANT_CODE', ''),
			'secret'=>env('2CHECKOUT_SECRET_KEY', '')
		],
		
		'paypal'=>[
			'email'=>'', 
			'logo'=>'',
			'auth'=>''
		],
	]

];
