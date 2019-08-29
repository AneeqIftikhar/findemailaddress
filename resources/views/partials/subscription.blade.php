


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
        <a href="#" class="btn btn-primary">Disable Subscription</a>
      </div>
    </div>
  </div>
</div>