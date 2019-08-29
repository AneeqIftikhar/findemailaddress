@extends('layouts.app')

@section('content')
<div class="container">
    

	@each('partials.subscription', $data['Items'], 'item')
    
</div>
@endsection
@push('scripts')
<script type="text/javascript">
 
</script>
@endpush