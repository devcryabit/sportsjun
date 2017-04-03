
@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')

<?php
    $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ;
    $match_id=$match_data[0]['id'];
    $tournament_id=$match_data[0]['tournament_id'];

    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];

    $match_details=json_decode($match_data[0]['match_details']);

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];

    ${'team_'.$match_data[0]['a_id'].'_score'}='0';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0';

    $team_a_info='';
    $team_b_info='';

    if(isset($preferences)){
        $current_set=$match_details->current_set;

        ${'team_'.$team_a_id.'_score'}=$match_details->scores->{$team_a_id.'_score'} .'';
        ${'team_'.$team_b_id.'_score'}=$match_details->scores->{$team_b_id.'_score'} .'';
    } else {
        $current_set=0;
    }

?>

<div class="col_standard soccer_scorecard">
    <div id="team_vs" class="ss_bg">
        <div class="container">
            <div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="team_logo">
                            @if(!empty($team_a_logo))
                                @if($team_a_logo['url']!='')
                                    <!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                    {!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                @else
                                    <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                                    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}

                                        </td>
                                @endif
                            @else
                                <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->
                                {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                            @endif
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
                                <div class="team_city">{{ $team_a_city }}</div>
                                <div class="team_score" id="team_a_score">{{${'team_'.$team_a_id.'_score'} }}</div>

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
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                <div class="team_city">{{ $team_b_city }}</div>
                                <div class="team_score" id="team_b_score">{{${'team_'.$team_b_id.'_score'} }}</div>

                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="team_logo">
                            @if(!empty($team_b_logo))
                                @if($team_b_logo['url']!='')
                                    <!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                    {!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                @else
                                    <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                                        {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                        </td>
                                @endif
                            @else
                                <!--    <img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">  -->
                                    {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}

                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                            <div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                <div class="team_city">{{ $team_b_city }}</div>
                                <div class="team_score" id="team_b_score">{{${'team_'.$team_b_id.'_score'} }}</div>


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
                                <h4 class="team_name">    {{$tournamentDetails['name']}} Tournament </h4>
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
        </div>
    </div>

    <div class="container pull-up">

        <div class="panel panel-default">
            <div class="col-md-12">
                <h5 class="scoreboard_title">Smite Scorecard
                </h5>

                <div class="clearfix"></div>
                <div class="form-inline">
                    @if($match_data[0]['hasSetupSquad'])
                        <div id='end_match_button'>
                            <button class="btn btn-danger " onclick="return SJ.SCORECARD.soccerSetTimes(this)"></i>End Match</button>
                        </div>
                    @endif
                    @if($match_data[0]['winner_id'] > 0)
                        <div class="form-group">
                            <label class="win_head">Winner</label>
                            <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>
                        </div>
                        <br>
                        @if(!empty($match_data[0]['player_of_the_match']))
                            <div class="form-group">
                                <label class="" style="color:red">PLAYER OF THE MATCH</label>
                                <h4 class="win_team">{{ Helper::getUserDetails($match_data[0]['player_of_the_match'])->name }}</h4>

                            </div>
                        @endif

                    @else
                        @if($match_data[0]['is_tied']>0)

                            <div class="form-group">
                                <label>Match Result : </label>
                                <h3 class="win_team">{{ 'Tie' }}</h3>

                            </div>

                        @elseif($match_data[0]['match_result'] == "washout")
                            <div class="form-group">
                                <label>MATCH ENDED DUE TO</label>
                                <h3 class="win_team">Washout</h3>
                            </div>
                        @else

                            <div class="form-group">
                                <label>Winner has not been updated</label>

                            </div>
                        @endif
                    @endif
                    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                    @include('scorecards.share')
                    <p class="match-status">@include('scorecards.scorecardstatusview')</p>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <form id='smiteForm' onsubmit='return manualScoring(this)'>
                        {!!csrf_field()!!}
                        @if($isValidUser)
                            <div class="row">
                                <div class='col-sm-12'>
                                    <span class='pull-right'>
                                    <a href='javascript:void(0)' onclick="enableManualEditing(this)"
                                       style="color:#123456;">edit <i class='fa fa-pencil'></i></a>
                                    <span> &nbsp; &nbsp; </span>
                                    <a href='javascript:void(0)' onclick="updatePreferences(this)"
                                       style='color:#123456;'> settings <i class='fa fa-gear fa-danger'></i></a>
                                    <span> &nbsp; &nbsp; </span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='table-responsive'>
                                    @if(count($smite_match_stats) > 0)
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                            <tr class='team_fall team_title_head'>
                                                <th bgcolor="#84cd93"></th>
                                                @foreach($team_a_players as $player)
                                                    <th bgcolor="#fff" style="color: #84cd93;" >{{$player['name']}}</th>
                                                @endforeach

                                                @foreach($team_b_players as $player)
                                                    <th bgcolor="#84cd93">{{$player['name']}}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($smite_match_stats[0] as $key=>$val)
                                            @if($key == 'user_id')
                                                <?php continue; ?>
                                            @endif
                                            <tr>
                                                <td>{{$key}}</td>
                                                @foreach($team_a_players as $player)
                                                    <?php $found = false; ?>
                                                    <!-- Connect person with stats -->
                                                    @foreach($smite_match_stats as $smite_match)
                                                        @if($smite_match['user_id'] == $player['id'])
                                                        <td>
                                                            <input readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="{{$smite_match[$key]}}" name='a_set-'>
                                                        </td>
                                                        <?php $found = true; ?>
                                                        @endif
                                                    @endforeach
                                                    <!-- If stats were not saved -->
                                                    @if(!$found)
                                                        <td>
                                                            <input readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                        </td>
                                                    @endif
                                                @endforeach

                                                @foreach($team_b_players as $player)
                                                    <?php $found = false; ?>
                                                    @foreach($smite_match_stats as $smite_match)
                                                        <!-- Connect person with stats -->
                                                        @if($smite_match['user_id'] == $player['id'])
                                                         <td>
                                                            <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="{{$smite_match[$key]}}" name='a_set-'>
                                                         </td>
                                                                <?php $found = true; ?>
                                                        @endif
                                                    @endforeach
                                                    <!-- If stats were not saved -->
                                                    @if(!$found)
                                                        <td>
                                                            <input readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                        <tr class='team_fall team_title_head'>
                                            <th bgcolor="#84cd93"></th>
                                            @foreach($team_a_players as $player)
                                                <th bgcolor="#fff" style="color: #84cd93;" >{{$player['name']}}</th>
                                            @endforeach

                                            @foreach($team_b_players as $player)
                                                <th bgcolor="#84cd93">{{$player['name']}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Level</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                <td>
                                                    <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Kills</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Deaths</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Assists</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Gold Earned</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Gold Per Minute</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Magical Damage</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Physical Damage</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='a_set-'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                     @endif
                                </div>


                                <input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
                                <!--
                                <div class="row" id='saveButton'>
                                    <div class='col-sm-12'>
                                        <center> <input type='submit' class="btn btn-primary" value="Save"></center>
                                    </div>
                                </div>
                                -->
                                <br>
                            </div>
                        </div>
                    </form>
                <!-- Selecting Squads Start-->
                    <div class="col-sm-10 col-sm-offset-1">
                        <h3 class="team_bat team_title_head">Playing Squad</h3>

                        <div class='row'>
                            <div class='col-sm-6 col-xs-12'>
                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        <tbody id="team_tr_a" >
                                        @foreach($team_a_players as $player_a)
                                            <tr class="team_a_playing_row " >
                                                <td>
                                                    {{ $player_a['name']   }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class='col-sm-6 col-xs-12'>

                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        <tbody id="team_tr_b" >
                                        @foreach($team_b_players  as $player_b)
                                            <tr class="team_b_playing_row ">
                                                <td>
                                                    {{ $player_b['name']   }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Team A Goals End-->

                    @if(!empty($match_data[0]['match_report']))

                        <div class="clearfix"></div>
                        <div id="match_report_view" class="summernote_wrapper tab-content col-sm-10 col-sm-offset-1">
                            <h3 class="brown1 table_head brown1">Match Report</h3>
                            <div id="match_report_view_inner">
                                {!!$match_data[0]['match_report']!!}
                            </div>
                        </div>
                    @endif
                <!-- if match schedule type is team -->

                    <!-- end -->

                    <div class="sportsjun-forms text-center scorecards-buttons">
                        <input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
                        @if($isValidUser && $isApproveRejectExist)

                            <button style="text-align:center;" type="button" onclick="scoreCardStatus('approved');" class="button green">Approve</button>
                            <button style="text-align:center;" type="button" onclick="scoreCardStatus('rejected');" class="button black">Reject</button><br />

                            <textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
                        @endif
                    </div>

                    @if($isValidUser && $match_data[0]['match_status']=='completed')
                        <div class="sportsjun-forms text-center scorecards-buttons">
                            <input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
                            <br><br>

                            <button class="btn btn-event" type="button" onclick="return  SJ.SCORECARD.allow_match_edit_by_admin({{$match_data[0]['id']}})">
                                Edit Match
                            </button>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    //Send Approve
    var manual = false;

    function enableManualEditing(that)
    {
        if(!manual){
            $.confirm({
                title: "Alert",
                content: "Do you want to enter points manually?",
                confirm: function(){
                    $('.tennis_input_new').removeAttr('readonly');
                    $('.tennis_input_new').focus();
                    $('#real_time_scoring').hide();
                    $('#end_match_button').hide();
                    $('#saveButton').show();
                    manual = true;
                },
                cancel: function(){

                }
            })

        }
        else
        {
            $.confirm({
                title: "Alert",
                content: "Do you want to enter points automatically?",
                confirm:function(){
                    $('.tennis_input_new').attr('readonly', 'readonly');
                    $('#real_time_scoring').show();
                    $('#end_match_button').show();
                    $('#saveButton').hide();
                    manual=false;
                },
                cancel:function(){

                }
            })
        }
    }

    function scoreCardStatus(status)
    {
        var msg = ' Reject ';
        if(status=='approved')
            var msg = ' Approve ';
        $.confirm({
            title: 'Confirmation',
            content: 'Are You Sure You Want To '+msg+' This ScoreCard?',
            confirm: function() {
                match_id = $('#match_id').val();
                rej_note = $('#rej_note').val();
                $.ajax({
                    url: base_url+'/match/scoreCardStatus',
                    type: "post",
                    data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'volleyball'},
                    success: function(data) {
                        if(data.status == 'success') {
                            window.location.href = base_url+'/match/scorecard/edit/'+match_id;
                        }
                    }
                });
            },
            cancel: function() {
                // nothing to do
            }
        });
    }


    function getMatchDetails(){

        var data={
            match_id:{{$match_data[0]['id']}}
        }

        var base_url=base_url || secure_url;
        $.ajax({
            url:  base_url+'/viewpublic/match/getvolleyballDetails',
            type:'get',
            dataType:'json',
            data:data,
            success:function(response){
                var left_team_id=response.preferences.left_team_id;
                var right_team_id=response.preferences.right_team_id;

                $.each(response.match_details, function(key, value){
                    $('.a_'+key).html(value[left_team_id+'_score']);
                    $('.b_'+key).html(value[right_team_id+'_score']);
                })
            }
        })
    }

    function manualScoring(that){
        var data=$('#smiteForm').serialize();
        console.log(data);
        $.ajax({
            url:base_url+"/match/manualScoringvolleyball",
            type:'post',
            data:data,
            success:function(response){
                window.location=window.location;
            }
        })


        return false;
    }
</script>


<!-- Put plus and minus buttons on left and rights of sets -->

@endsection

