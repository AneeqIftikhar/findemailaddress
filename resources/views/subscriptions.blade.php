@extends('layouts.app')

@section('content')
<div class="container">
    
    @if ($data['Items'])
		@each('partials.subscription', $data['Items'], 'item')
	@else
		<div class="row" style="margin-bottom: 5px">
		  <div class="col-sm-12">
		    <div class="card">
		      <div class="card-body">
		        <h5 class="card-title">No Subscriptions Found.</h5>
		        <p> If you have recently Purchased Please visit in 5 minutes.</p>
		       
		      </div>
		    </div>
		  </div>
		</div>
		
	@endif
    
</div>
@endsection
@push('scripts')
<script type="text/javascript">
 
</script>
@endpush