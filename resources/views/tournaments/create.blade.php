<!-- Modal -->
<div id="subtournament" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content sportsjun-forms">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans('message.tournament.fields.heading') }}</h4>
      </div>

<!--<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>--><!-- end .form-header section -->
         @if( $roletype=='admin')
                    {!! Form::open(['route' => 'admin.tournaments.store','class'=>'form-horizontal','method' => 'POST','id' => 'sub-tournaments']) !!}   
				<div class="form-body">
                         @include ('tournaments._form', ['submitButtonText' => 'Create'])
						 <input type="hidden" name="isParent" id="isParent" value="no">
						 <input type="hidden" name="tournament_parent_id" id="tournament_parent_id" value="{{$parent_id}}">
						 <input type="hidden" name="tournament_parent_name" id="tournament_parent_name" value="{{!empty($tournament_name)?$tournament_name:''}}">
				</div>
                 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Create</button>

                </div>
                    {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#sub-tournaments'); !!}
			      @endif
				  
				  
			      @if( $roletype=='user')
			      {!! Form::open(['route' => 'tournaments.store','class'=>'form-horizontal','method' => 'POST','id' => 'sub-tournaments']) !!}
			      	        <div class="modal-body">
            
            <div class="sportsjun-forms sportsjun-container" >
			  <div class="form-body">
                         @include ('tournaments._form', ['submitButtonText' => 'Create'])
						 <input type="hidden" name="isParent" id="isParent" value="no">
						 <input type="hidden" name="tournament_parent_id" id="tournament_parent_id" value="{{$parent_id}}">
						 <input type="hidden" name="tournament_parent_name" id="tournament_parent_name" value="{{!empty($tournament_name)?$tournament_name:''}}">
					</div>

                    

</div>
    </div>
	      <div class="modal-footer">
		  <button type="submit" class="button btn-primary">Create</button>
        <button type="button" class="button btn-secondary" onclick="resetData();" data-dismiss="modal">Close</button>
      </div>
	  {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#sub-tournaments'); !!}

			     @endif
    </div>

  </div>
</div>
<script type="text/javascript">
function resetData()
{
	$('#sub-tournaments').resetForm(true);
}
							$(function() {
								$.validator.addMethod("greater_startdate", function(value, element) {
									 var startDate = $('[name="start_date"]').val();
									  var endDate = $('[name="end_date"]').val();
									  var startDate = startDate.split('/').reverse().join('-');
									var endDate = endDate.split('/').reverse().join('-');										
									return Date.parse(endDate) >= Date.parse(startDate);
								}, "End Date must be equal to or after Start Date");
								$('[name="end_date"]').rules("add", "greater_startdate");
								
								
								
								$.validator.addMethod("win_loose_points", function(value, element) {
									 var points_win = $('[name="points_win"]').val();
									  var points_loose = $('[name="points_loose"]').val();
									  
									  	if(points_win == '' && points_loose == '')
					        		    return true;
					        	    else
									return (parseInt(points_win)) >= parseInt((points_loose));
								}, " Losing team points must be less than Winning team points");
								$('[name="points_loose"]').rules("add", "win_loose_points");
								
								
								
								
								
								
							});
</script>


