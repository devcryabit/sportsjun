@extends('layouts.organisation')


@section('content')

	<style>
		.pay_options {
			display: none;
		}
		
		.flex {
			display: flex;
			text-align: left;
			-webkit-align-items: center;
			align-items: center;
		}
		
		/*.flex input[type="checkbox"] {
			width: 100px;
		}*/
		
		.flex span {
			width: 250px;
			font-size: 16px;
		}
		
	/*	.form .input-container .flex input[type="text"] {
			border: 1px solid rgba(0, 0, 0, 0.2);
			box-shadow: none;
			height: inherit;
			font-size: 16px;
		}*/
	</style>


			<div class="container-fluid col-sm-12">
	<div class="sportsjun-forms sportsjun-container wrap-1">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>New Coaching Sessions</h4></div>
					<form action="/organization/{{$organisation->id}}/coaching/add" class="form form-horizontal" method="post">
									<div class="form-body">
						{!! csrf_field() !!}
						<div class="row">	
<div class="col-sm-12">					
			<div class="section">
			    	<label class="form_label">Title<span  class='required'>*</span> </label>
				<label class="field ">
					{!! Form::text('title', null, array('required','class'=>'gui-input','placeholder'=> 'News title')) !!}
					@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
			       
				</label>
			</div>
			</div>	

			<div class="col-sm-12" 	>
										
			@include ('common.upload')
			@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'photos','fieldname'=>'Choose  Image'])
			</div>
			

				<div class="col-sm-6">
					<div class="section">
					    	<label class="form_label">Category </label>
						<label class="field select">

						{!! Form::select('category_id',Helper::getAllSports()->lists('sports_name','id'),null, array('class'=>'gui-input','id'=>'team')) !!}
						@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
					     <i class="arrow double"></i>      
						</label>
					</div>
		</div>
		<div class="col-sm-6">
						<div class="section">
						    	<label class="form_label">Coach </label>
							<label class="field select">

							<select class="" name="coach_id">
								@foreach($organisation->staff as $staff)
									@if($staff->roleForOrganization($organisation->id)->name=='Coach')
									<option value="{{$staff->id}}">{{$staff->name}}</option>
									@endif
								@endforeach
								</select>
								 <i class="arrow double"></i>      
						</label>
							</div>
				</div>
					
		
        <div class="col-sm-6">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.startdate') }} <span  class='required'>*</span></label>         
                <label class='field' >
                    <div class="input-group date" id='startdate'>
                        {!! Form::text('start_date', null, array('class'=>'gui-input datepicker','placeholder'=>trans('message.tournament.fields.startdate'))) !!}
                        <span class="input-group-addon">
        	                <span class="glyphicon glyphicon-calendar"></span>
    	                </span>
                    </div>
                    @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                </label>
            
            </div>
        </div>
       
         <div class="col-sm-6">
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.enddate') }}  <span  class='required'>*</span></label>		
                        <label class='field'>
                        	<div class='input-group date' id='enddate'>
                                {!! Form::text('end_date', null, array('class'=>'gui-input datepicker','placeholder'=>trans('message.tournament.fields.enddate'))) !!}
                                <span class="input-group-addon">
    	                            <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        	</div>
                        @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
                        </label>
                        
                </div>
         </div>
    						 					 
   <div class="col-sm-12">					
			<div class="section">
			    	<label class="form_label">Number of Players<span  class='required'>*</span> </label>
				<label class="field prepend-icon">
					{!! Form::text('number_of_players', null, array('required','class'=>'gui-input','placeholder'=> 'Number of Players')) !!}
					@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
			       
				</label>
			</div>
	</div>	
	 <div class="col-sm-12">					
			<div class="section">
									<label class="form_label">Payment method by</label>
									<label class="field select">

									<select class="pmethod" name="payment_method" required="">
										<option value="">Choose payment method</option>
										@foreach($types as $key=>$type)
											<option value="{{$key}}">{{$type}}</option>
										@endforeach
									</select>
										 <i class="arrow double"></i>      
						</label>
							</div>
			</div>
	
							@foreach($types as $key=>$type)
							<div class="pay_options input-container {{$key}}">
								<h5>Options to pay</h5>

								@foreach(Helper::get_subscription_methods($key) as $sm)
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" name="{{$key}}_choose_{{$sm->id}}"  /> <span for="full_payment" checked>{{$sm->title}}</span>
												<input type="text" placeholder="Amount" class="form-control"  name="{{$key}}_amount_{{$sm->id}}" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" checked="" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>

												<input type="hidden" name="{{$key}}_index_{{$sm->id}}" value="{{$sm->id}}" >
										</div>
									</div>
								</div>



								@endforeach
							</div>

							@endforeach
							<center>
								<input type="checkbox" id="parental_info" />
								<label for="parental_info">Parental information is mandatory for registration. </label>
								<div class="clearfix"></div>
								<button type="submit" class="btn btn-primary">Create</button>
							</center>
						</div>
					</form>
				</div>
			</div>
		


@stop


@section('end_scripts')

<script type="">
$(document).ready(function () {
			$("select.pmethod").change(function () {
				$(this).find("option:selected").each(function () {
					var optionValue = $(this).attr("value");
					if (optionValue) {
						$(".pay_options").not("." + optionValue).hide();
						$("." + optionValue).show();
					}
					else {
						$(".pay_options").hide();
					}
				});
			}).change();
		});
</script>

<script type="text/javascript">
	  $(".datepicker").datepicker();

	  $(document).ready(function(){
	  		$('[type="checkbox"]').iCheck();
	  })
   
</script>

@stop