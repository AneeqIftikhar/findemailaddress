@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Find Email History</div>

                <div class="card-body">
                  @if (count($emails)>0)
                      <div class="row" style="margin-bottom: 4px">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6" align="right">
                        
                             
                              <button class="btn btn-primary nav-item dropdown">
                                 <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="padding: 0px; color: white">
                                    <span><i class="fas fa-download fa-lg"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left">
                                <a class="dropdown-item" href="{{URL::route('downloadfoundrecords',['type'=>'csv','records'=>'all'])}}">
                                  Download ALL (CSV)
                                </a>
                                <a class="dropdown-item" href="{{URL::route('downloadfoundrecords',['type'=>'xls','records'=>'all'])}}">
                                  Download ALL (XLS)
                                </a>
                                <a class="dropdown-item" href="{{URL::route('downloadfoundrecords',['type'=>'csv','records'=>'valid'])}}">
                                  Download Valid (CSV)
                                </a>
                                <a class="dropdown-item" href="{{URL::route('downloadfoundrecords',['type'=>'xls','records'=>'valid'])}}">
                                  Download Valid (XLS)
                                </a>
                              </div>
                              </button>
                              
                              <button class="btn btn-primary" value="all" id="show_emails">
                                Show All
                              </button>
                             
                        </div>
                    </div>
                   <table class="table" id="emails_table">
                      <thead class="black white-text">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Domain</th>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!--  @foreach($emails as $email)
                          <tr class="table-row">    
                              <td> {{$email->first_name }} {{$email->last_name}} </td>
                              <td> {{$email->email}} </td>
                              <td> {{$email->status}} </td>
                          </tr>
                         @endforeach -->
                      </tbody>
                    </table>
                  @else
                    <h5 class="card-title">No Emails Found.</h5>    
                  @endif
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
  var data = null;
$(document).ready(function() {
   data = {!! json_encode($emails->toArray(), JSON_HEX_TAG) !!};
   populate_emails('less');
   $( "#show_emails" ).click(function() {
      if($( "#show_emails" ).val()=="all")
      {
        populate_emails('all');
        $("#show_emails").html('Show Valid');
        $("#show_emails").prop('value', 'less');
      }
      else
      {
        populate_emails('less');
        $("#show_emails").html('Show All');
        $("#show_emails").prop('value', 'all');
      }
      
    });
    
});
  
function populate_emails(filter)
{
   var tableRef = document.getElementById('emails_table').getElementsByTagName('tbody')[0];
      for(var i = tableRef.rows.length - 1; i >= 0; i--)
      {
        tableRef.deleteRow(i);
      }
      for(var i =0;i<data.length;i++)
      {
        
        if(filter=="less" && data[i]['status']=='Valid')
        {
          var newRow   = tableRef.insertRow();


          newCell  = newRow.insertCell(0);
          newText  = document.createTextNode(data[i]['first_name']+" "+data[i]['last_name']);
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(1);
          newText  = document.createTextNode(data[i]['domain']);
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(2);
          newText  = document.createTextNode(data[i]['email']);
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(3);

          container = document.createElement("span");
          text = document.createTextNode(data[i]['status']);
          container.style.fontWeight="bold";
          container.appendChild(text);
          container.style.color = "green";

          newCell.appendChild(container);

          newCell  = newRow.insertCell(4);
          if(data[i]['bounce'].length>0)
          {
            container = document.createElement("span");
            text = document.createTextNode(data[i]['bounce'][0]['status']);
            container.style.fontWeight="bold";
            container.appendChild(text);
            container.style.color = "green";

            newCell.appendChild(container);
          }
          else
          {
            button = document.createElement("button");
            button.className="btn btn-primary";
            button.innerHTML ="Report Bounce";
            var id=data[i]['id'];
            button.onclick = function() { report_bounce_modal(id,'find'); };
            newCell.appendChild(button);
          }
          
        }
        else if(filter=="all")
        {
          var newRow   = tableRef.insertRow();


          newCell  = newRow.insertCell(0);
          newText  = document.createTextNode(data[i]['first_name']+" "+data[i]['last_name']);
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(1);
          newText  = document.createTextNode(data[i]['domain']);
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(2);
          newText  = document.createTextNode(data[i]['email']);
          newCell.appendChild(newText);

          newCell  = newRow.insertCell(3);

          container = document.createElement("span");
          text = document.createTextNode(data[i]['status']);
          container.style.fontWeight="bold";
          container.appendChild(text);
          if(data[i]['status']=="Valid")
          {
            container.style.color = "green";
          }
          else if (data[i]['status']=="Catch All")
          {
            container.style.color = "orange";
          }
          else
          {
            container.style.color = "red";
          }
          

          newCell.appendChild(container);


          if(data[i]['status']=="Valid")
          {
            newCell.appendChild(container);
            newCell  = newRow.insertCell(4);
            if(data[i]['bounce'].length>0)
            {
              container = document.createElement("span");
              text = document.createTextNode(data[i]['bounce'][0]['status']);
              container.style.fontWeight="bold";
              container.appendChild(text);
              container.style.color = "green";

              newCell.appendChild(container);
            }
            else
            {
              button = document.createElement("button");
              button.className="btn btn-primary";
              button.innerHTML ="Report Bounce";
              var id=data[i]['id'];
              button.onclick = function() { report_bounce_modal(id,'find'); };
              newCell.appendChild(button);
            }
          }
          else
          {
            newCell.appendChild(container);
            newCell  = newRow.insertCell(4);
            text = document.createTextNode(" ");
            newCell.appendChild(text);
          }
        }
        

       
      }
}

</script>
@endpush
