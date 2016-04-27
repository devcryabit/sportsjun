<html lang="en">
<head>
<script type="text/javascript">
$(document).ready(function(){
	$('.launch-modal').click(function(){
		$('#myModal').modal({
			backdrop: 'static'
		});
	}); 
});
</script>
</head>
<body>
     <!-- Button HTML (to Trigger Modal) -->
    <a href="#" class="launch-modal"><i class="fa fa-info"></i> <b>Match Status</b></a><br/>
	@if($match_data[0]['tournament_id']=='')
		@if($match_data[0]['scoring_status']=='approval_pending')
			<span>{{'Scorecard is pending for approval'}} </span>
		@else
		@if($match_data[0]['scoring_status']=='approved')	
			{{'Scorecard is approved'}}
		@else
		@if($match_data[0]['scoring_status']=='rejected')
			{{'Scorecard is rejected'}}
		@else
		@if($score_status_array['added_by']=='')
			{{ 'Scorecard Data Is Not Entered.' }}
		@else
		@if($match_data[0]['scoring_status']=='' )
			<span >{{'Need to send for approval'}}</span>
		@endif
		@endif
		@endif
		@endif
		@endif
	@endif
	 
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade status-modal">
        <div class="modal-dialog sj_modal sportsjun-forms">
            <div class="modal-content">
                <div  class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Match Status
					</h4>
                </div>
				
            <div class="modal-body">
				<div class="form-group ">
				@if($match_data[0]['tournament_id']!='' || $loginUserRole=='admin')
					@if($score_status_array['added_by']=='')
						{{ 'Scorecard Data Is Not Entered.' }}
					@else
					@if($match_data[0]['match_status']=='completed')	
						{{ 'Match is Completed.' }}
					@else
						@if($score_status_array['added_by']!='')	
						{{ 'Scorecard Data Is Entered.' }}
					@endif
					@endif
					@endif
						
				@else
					@if($score_status_array['added_by']=='')
						{{ 'Score Card Data Is Not Entered.' }}
					@else	
						<p>    
						@if(!empty($score_status_array['added_by']) && $match_data[0]['scoring_status']=='')
							{{ trans('message.scorecard.scorecardSaved') }}
						@else
						
						@if(!empty($score_status_array['added_by']) && $match_data[0]['scoring_status']=='approval_pending')
							{{ trans('message.scorecard.forApproval') }}
						@else
						
						@if(!empty($score_status_array['added_by']) && $match_data[0]['scoring_status']=='approved')
							{{ trans('message.scorecard.approved') }}
						@else
						
						@if(!empty($score_status_array['added_by']) && $match_data[0]['scoring_status']=='rejected')
							{{ trans('message.scorecard.rejected') }}
						@endif	
						@endif	
						
						@endif	
						@endif	
						</p>
						
						<p>
						@if(!empty($rej_note_str) && $rej_note_str!='' && $match_data[0]['scoring_status']!='approved')
							@if(!empty($score_status_array['added_by'])  && $score_status_array['rejected_note']!='')
								<label>Rejected Reasons:</label>{{ $rej_note_str }}
							@endif	
						@endif
						</p>
					@endif	
				@endif	
				</div>
				
			
				
            </div>
               
				
            </div>
        </div>
    </div>
</body>
</html>