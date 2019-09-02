


<div class="row" style="margin-bottom: 5px">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{$item['Product']['ProductName']}}</h5>
        <table>
            <tr>
                <td style="padding-right: 5px;">
                    <span>Start Date</span>
                </td>
                <td>
                    <strong>{{$item['StartDate']}}</strong>
                </td>
            </tr>
            <tr>
                <td style="padding-right: 5px;">
                    <span>Expiration Date</span>
                </td>
                <td>
                    <strong>{{$item['ExpirationDate']}}</strong>
                </td>
            </tr>
            <tr>
                <td style="padding-right: 5px;">
                    <span>Subscription Enabled</span>
                </td>
                <td>
                    <strong>
                    	@if ($item['SubscriptionEnabled'])
						    True
						@else
						    False
						@endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td style="padding-right: 5px;">
                    <span>Recurring Enabled</span>
                </td>
                <td>
                    <strong>
                    	@if ($item['RecurringEnabled'])
						    True
						@else
						    False
						@endif
                    </strong>
                </td>
            </tr>
        </table>
        @if ($item['RecurringEnabled'])
            <form method="POST" action="{{ route('disableRecurringBilling') }}">
                @csrf
                <input name="subscription_ref" type="hidden" value="{{$item['SubscriptionReference']}}">
                <input type="submit" class="btn btn-primary" value="Disable Recurring Billing">
            </form>
        @else
            <form method="POST" action="{{ route('enableRecurringBilling') }}">
                @csrf
                <input name="subscription_ref" type="hidden" value="{{$item['SubscriptionReference']}}">
                <input type="submit" class="btn btn-primary" value="Enable Recurring Billing">
            </form>
        @endif
        
      </div>
    </div>
  </div>
</div>