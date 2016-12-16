@extends('layouts.app')
@section('content')
  <style>
    .error{
        color:red;
        font-weight: normal;
    }
    
  </style>
<?php 
    $team_a_name = $user_a_name;
    $team_b_name = $user_b_name;

    $match_data[0]['tournament_id']!=null?$disabled='readonly':$disabled='';
    $match_settings   =   Helper::getMatchSettings($match_data[0]['tournament_id'],$match_data[0]['sports_id']);

    $team_a_id=$match_data[0]['a_id'];
    $team_b_id=$match_data[0]['b_id'];

    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];



    $match_details=json_decode($match_data[0]['match_details']);
    $old_match_details=$match_details;
      if($match_data[0]['game_type']=='rubber'){
          if(count($rubber_details))
            $match_details=json_decode($rubber_details['match_details']);
            isset($match_details->preferences)? $match_details = $match_details: $match_details = $old_match_details;
      }

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];
    

    ${'team_'.$match_data[0]['a_id'].'_score'}='0 sets';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0 sets'; 

    $team_a_info='';
    $team_b_info='';
?>

<style>
   
    input:read-only { 
    background-color: #f4f4f4;

}
.button_add, .button_add:hover, .button_add:active{
   color:green;
  background: none;
  border: 0px #fff inset;
}
.button_remove, .button_remove:hover, .button_add:active{
  color:red;
  background: none;
  border: 0px #fff inset;
}

.main_tour{
    background: #111;
}

td,th{
  height: 60px;
  border: 1px inset black;
}

.border td{
    border: 1px inset #ddd !important;
}

.btn-pink, .btn-pink:active{
    background: #9050ff;
    color: white;
}
.btn-arrow{
    background: #ff8888;
    color: white;
    height:65px;
    border-radius: 50%;
    margin-left: 10px;
    width: 65px;
    padding: 0px !important;
}

.a_s:hover{
  background: #ffdddd;
}
.a_s{
    cursor: pointer;
    font-size: 20px !important;
}


</style>
<div class="col_standard table_tennis_scorcard">
    <div id="team_vs" class="tt_bg">
      <div class="container">
          <div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                        	<div class="team_logo">
                       
						 @if($user_a_logo['url']!='')
							<!--<img class="img-responsive img-circle" width="110" height="110" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
							{!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
							@else
							<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
							{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
						@endif
                        </div>
                        </div>
                       <div class="col-md-8 col-sm-12">
                        	<div class="team_detail">
						@if($match_data[0]['schedule_type']=='player')
                          <div class="team_name"><a href="{{ url('/editsportprofile/'.$match_data[0]['a_id'])}}">{{ $user_a_name }}</a></div>
						@else
							<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $user_a_name }}</a></div>
						@endif
					  
						  <div class="team_city">{!! $team_a_city !!}</div>
              <div class="team_score" id="team_{{$team_a_id}}_score"><span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_a_info;?>"></i></span></div>
						  
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <span class="vs"></span>
                </div>
                <div class="team team_two col-xs-5">
                  <div class="row">
                        <div class="col-md-8 col-sm-12 visible-md visible-lg">
                        <div class="team_detail">
						@if($match_data[0]['schedule_type']=='player')
                          <div class="team_name"><a href="{{ url('/editsportprofile/'.$match_data[0]['b_id'])}}">{{ $user_b_name }}</a></div>
						@else
							<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $user_b_name }}</a></div>
						@endif
							<div class="team_city">{!! $team_b_city !!}</div>
              <div class="team_score" id="team_{{$team_b_id}}_score"> <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_info;?>"></i></span></div>
                            </div>
                        </div>
                              <div class="col-md-4 col-sm-12">
                              	<div class="team_logo">
                                
                                
								
								 @if($user_b_logo['url']!='')
              <!--  <img class="img-responsive img-circle" width="110" height="110" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
		  	{!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
                @else
              <!--  <img  class="img-responsive img-circle"width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
		  {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
              @endif
              </div>
                              </div>
                              <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                         <div class="team_detail">
						@if($match_data[0]['schedule_type']=='player')
                          <div class="team_name"><a href="{{ url('/editsportprofile/'.$match_data[0]['b_id'])}}">{{ $user_b_name }}</a></div>
						@else
							<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $user_b_name }}</a></div>
						@endif
							<div class="team_city">{!! $team_b_city !!}</div>
              <div class="team_score" id="team_{{$team_b_id}}_score"><span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_info;?>"></i></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <center>
                          <a href="/tournaments/groups/{{$tournamentDetails['id']}}">
                                    <h4>    {{$tournamentDetails['name']}} Tournament </h4>
                                  </a>
                                
                       </center>
                    </div>
                </div>
            @endif

            <div class="row">
              <div class="col-xs-12">
                  <div class="match_loc">
                      {{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}
                    </div>
                </div>
            </div>
			<h5 class="scoreboard_title">Archery Scorecard @if($match_data[0]['match_type']!='other')
											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type'])}} , {{ucfirst($match_data[0]['match_category'] )}})</span>
									@endif</h5>

            <h5 class="scoreboard_title"> No of Athlets : {{$match_obj->archery_players()->count()}} </h5>
        </div>
          @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
          @endif
    </div>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
    <div class="form-inline">
    @if($match_data[0]['winner_id']>0)
  <div class="form-group">
        <label class="win_head">Winner</label>
        <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$user_a_name:$user_b_name }}</h3>
    </div>

      @elseif($match_data[0]['match_result'] == "washout")
                                                     <div class="form-group">
                                                         <label>MATCH ENDED DUE TO</label>
                                                         <h3 class="win_team">Washout</h3>
                                                     </div>

  @else

      <div class="form-group">
        <label>Winner is not updated</label>

      </div>

  @endif
    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src=" {{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
        @include('scorecards.share')
        <p class="match-status">@include('scorecards.scorecardstatusview')</p>
    </div>



<!-- Setup Squad -->

   <div class="row">
          <div class="col-sm-12">

      @if(!$match_data[0]['hasSetupSquad'] )     

            <div class="row">
              <div class="col-sm-12">

               <div> 
                  <div class="alert alert-danger"> Match has not Started! </div>
              </div>

              </div>
            </div>



   
<!-- End of Squad Setup -->

  @else 

<!-- Start Scoring -->
  
    <div class="row">

      <div class="col-sm-12">

        <div class="table-responsive">

          <table class="table table-striped table-bordered border" border="1">
            <thead>
              <tr class="thead">
                <th>  </th>
                @foreach($match_obj->archery_rounds as $round)
                <th> {{$round->distance}} Mts </th>
                @endforeach
                <th> Total</th>
              </tr>
            </thead>

            <tbody>
            <?php $p_index=1;?>
              <!-- If Team -->
            
                  @foreach($players_ordered as $player)
                     <tr>
                          <td>{{$player->player_name}}  </td>
                      @foreach($match_obj->archery_rounds as $round)
                        <td class="a_s player_{{$p_index}}_round_{{$round->round_number}} player_{{$player->id}}_round_{{$round->round_number}}" player_id='{{$player->id}}' user_id='{{$player->user_id}}' round_number="{{$round->round_number}}" round_id="{{$round->id}}"> {{$player->{'round_'.$round->round_number} }} </td>

                        <?php $p_index++;?>
                      @endforeach
                        <td class='player_{{$player->id}}_total text-primary' style="font-size:20px">{{$player->total}}</td>
                    </tr>
                  @endforeach
            
            </tbody>
          </table>

        </div>


        <div id='load_round_details' style="display:none">
            @include('scorecards.archery.round_scoring')
        </div>

      </div>
    </div>


    <input type="hidden" id='match_id' value="{{$match_obj->id}}">
    <input type="hidden" id='selected_user_id'  value=''>
    <input type="hidden" id='selected_round_number' value="">
    <input type="hidden" id='selected_round_id' value="">
    <input type="hidden" id='selected_player_id' value="">
    <input type="hidden" id='selected_arrow_number' value="">


<!-- End Scoring -->
   @endif


  <script type="text/javascript">


      function add_round(){
        var distance = $('#distance').val();
        var number_of_arrows = $('#number_of_arrows').val();
        var match_id         = {{$match_data[0]['id']}}
          $.ajax({
              type:'post',
              url:'/match/archery/add_round',
              data:{distance:distance,number_of_arrows:number_of_arrows,match_id:match_id},
              success:function(response){
                  $('#list_rounds').html(response);
                  $('#new_round_modal').modal('hide');
                  $('#distance').val('');
                  $('#number_of_arrows').val('');
              },
              error:function(){

              }
          })
      }


      function start_scoring(){
          $.confirm({
              title:'Alert!',
              content:'Do you want to save and start scoring?',
              confirm:function(){
                  $.ajax({
                      url:'/match/archery/start_scoring',
                      type:'post',
                      data:{ match_id:{{$match_obj->id}} },
                      success:function( ){
                          window.location = window.location;
                      },
                      error:function(){
                          alert('An error occured retry!');
                      }
                  })
              }
          })
      }

        function load_players(){
            var number_of_players = $('#number_of_player').val();       

            var html_a = ''; 
            var html_b = '';
            for(i=1; i<=number_of_players; i++){
                html_a += "<select name='a_player_"+i+"' class='select_players_a form-control' ></select> <br>";
                html_b += "<select name='b_player_"+i+"' class='select_players_b form-control' ></select> <br>";
            }

            $('#select_a_players').html(html_a);
            $('#select_b_players').html(html_b);

            var options_a = $('#a_players_list').html();
            var options_b = $('#b_players_list').html();

            $('.select_players_a').html(options_a);
            $('.select_players_b').html(options_b);

            $('#save_players').show();           
        }

        function insert_players(){
            var data = $('#insert_players').serialize();

            $.ajax({
                url:'/match/archery/insert_players',
                type:'post',
                data:data,
                success:function(){
                    $('#save_players').hide();
                }
            })
        }
  </script>


  <!-- Scoring -->

  <script type="text/javascript">
    
      $('.a_s').click(function(){
          if($(this).hasClass('selected')){
              $(this).removeClass('selected').css({background:'inherit'})
              clear_selected();
              $('#load_round_details').hide();
          }
          else{
              $('.a_s').removeClass('selected').css({background:'inherit'});
              $(this).css({background:'#ff8888'}).addClass('selected');
              $('#selected_arrow_number').val('');

              $('#selected_round_number').val($(this).attr('round_number'))
              $('#selected_user_id').val($(this).attr('user_id'))
              $('#selected_round_id').val($(this).attr('round_id'))
              $('#selected_player_id').val($(this).attr('player_id')) 



              $.ajax({
                  url:'/viewpublic/match/archery/load_arrow',
                  type:'post',
                  data:{round_id:$('#selected_round_id').val(),player_id:$('#selected_player_id').val(),match_id:$('#match_id').val()},
                  success:function(response){
                      $('#load_round_details').html(response);
                      $('#load_round_details').show();
                  }
              }) 
          }

      })




      //$('.player_1_round_1').css({background:'#ff8888'}).addClass('selected');

      //initialization

      function init(){

        var that= $('.player_1_round_1');
              $('.a_s').removeClass('selected').css({background:'inherit'});
              $(that).css({background:'#ff8888'}).addClass('selected');
              $('#selected_arrow_number').val('');

              $('#selected_round_number').val($(that).attr('round_number'))
              $('#selected_user_id').val($(that).attr('user_id'))
              $('#selected_round_id').val($(that).attr('round_id'))
              $('#selected_player_id').val($(that).attr('player_id')) 

              $.ajax({
                  url:'/viewpublic/match/archery/load_arrow',
                  type:'post',
                  data:{round_id:$('#selected_round_id').val(),player_id:$('#selected_player_id').val(),match_id:$('#match_id').val()},
                  success:function(response){
                      $('#load_round_details').html(response);
                      $('#load_round_details').show();
                  }
              }) 
        }

      $(document).ready(function(){
          setTimeout(init,1000)
        //init();
      })


      function clear_selected(){
          $('#selected_round_number').val('');
          $('#selected_user_id').val('');
          $('#selected_round_id').val('');
          $('#selected_player_id').val('');
          $('#selected_arrow_number').val('');
      }

    </script>

@stop