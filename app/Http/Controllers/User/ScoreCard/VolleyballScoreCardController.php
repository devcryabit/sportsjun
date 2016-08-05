<?php

namespace App\Http\Controllers\User\ScoreCard;

use Illuminate\Http\Request as ObjectRequest;       //get all my requests data as object

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Tournaments;
use App\Model\MatchSchedule;
use App\Model\UserStatistic;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\Sport;
use App\Model\SquashPlayerMatchwiseStats;
use App\Model\SquashPlayerMatchScore;
use App\Model\SquashStatistic;
use App\Model\Photo;
use App\User;
use DB;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;
use DateTime;
use App\Helpers\AllRequests;
use Session;
use Request;

class VolleyballScoreCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

      public function squashScoreCard($match_data,$match,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
    {

       
        $score_a_array=array();
        $score_b_array=array();

        $loginUserId = '';
        $loginUserRole = '';

        if(isset(Auth::user()->id))
            $loginUserId = Auth::user()->id;

        if(isset(Auth::user()->role))
            $loginUserRole = Auth::user()->role;

        //!empty($matchScheduleDetails['tournament_id'])
        //if($match_data[0]['match_status']=='scheduled')//match should be already scheduled
        //{
        $player_a_ids = $match_data[0]['player_a_ids'];
        $player_b_ids = $match_data[0]['player_b_ids'];

        $decoded_match_details = array();
        if($match_data[0]['match_details']!='')
        {
            $decoded_match_details = json_decode($match_data[0]['match_details'],true);
        }

        $a_players = array();

        $team_a_playerids = (!empty($decoded_match_details[$match_data[0]['a_id']]) && !($match_data[0]['scoring_status']=='' || $match_data[0]['scoring_status']=='rejected'))?$decoded_match_details[$match_data[0]['a_id']]:explode(',',$player_a_ids);

        $a_team_players = User::select('id','name')->whereIn('id',$team_a_playerids)->get();

        if (count($a_team_players)>0)
            $a_players = $a_team_players->toArray();

        $b_players = array();

        $team_b_playerids = (!empty($decoded_match_details[$match_data[0]['b_id']]) && !($match_data[0]['scoring_status']=='' || $match_data[0]['scoring_status']=='rejected'))?$decoded_match_details[$match_data[0]['b_id']]:explode(',',$player_b_ids);


        $b_team_players = User::select('id','name')->whereIn('id',$team_b_playerids)->get();

        if (count($b_team_players)>0)
            $b_players = $b_team_players->toArray();

        $team_a_player_images = array();
        $team_b_player_images = array();

        //team a player images
        if(count($a_players)>0)
        {
            foreach($a_players as $a_player)
            {
                $team_a_player_images[$a_player['id']]=Photo::select()->where('imageable_id', $a_player['id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            }
        }

        //team b player images
        if(count($b_players)>0)
        {
            foreach($b_players as $b_player)
            {
                $team_b_player_images[$b_player['id']]=Photo::select()->where('imageable_id', $b_player['id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            }
        }
        if($match_data[0]['schedule_type'] == 'player')//&& $match_data[0]['schedule_type'] == 'player'
        {
            $user_a_name = User::where('id',$match_data[0]['a_id'])->pluck('name');
            $user_b_name = User::where('id',$match_data[0]['b_id'])->pluck('name');
            $user_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $user_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $upload_folder = 'user_profile';
            $is_singles = 'yes';
            if($match=='squash')//Squash
            
            $scores_a = SquashPlayerMatchScore::select()->where('match_id',$match_data[0]['id'])->first();
            $scores_b = SquashPlayerMatchScore::select()->where('match_id',$match_data[0]['id'])->skip(2)->first();
            
            
            if(count($scores_a)>0)
                $score_a_array = $scores_a->toArray();

            if(count($scores_b)>0)
                $score_b_array = $scores_b->toArray();

            $team_a_city = Helper::getUserCity($match_data[0]['a_id']);
            $team_b_city = Helper::getUserCity($match_data[0]['b_id']);
        }else
        {
            $user_a_name = Team::where('id',$match_data[0]['a_id'])->pluck('name');//team details
            $user_b_name = Team::where('id',$match_data[0]['b_id'])->pluck('name');//team details
            $user_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $user_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $upload_folder = 'teams';
            $is_singles = 'no';
            

                $scores_a = SquashPlayerMatchScore::select()->where('match_id',$match_data[0]['id'])->first();
                $scores_b = SquashPlayerMatchScore::select()->where('match_id',$match_data[0]['id'])->skip(1)->first();
            
            if(count($scores_a)>0)
                $score_a_array = $scores_a->toArray();

            if(count($scores_b)>0)
                $score_b_array = $scores_b->toArray();

            $team_a_city = Helper::getTeamCity($match_data[0]['a_id']);
            $team_b_city = Helper::getTeamCity($match_data[0]['b_id']);
        }

        //bye match
        if($match_data[0]['b_id']=='' && $match_data[0]['match_status']=='completed')
        {
            $sport_class = 'Squash_scorcard';
            return view('scorecards.byematchview',array('team_a_name'=>$user_a_name,'team_a_logo'=>$user_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
        }



        //score status
        $score_status_array = json_decode($match_data[0]['score_added_by'],true);


        $rej_note_str='';
        if($score_status_array['rejected_note']!='')
        {
            $rejected_note_array = explode('@',$score_status_array['rejected_note']);
            $rejected_note_array = array_filter($rejected_note_array);
            foreach($rejected_note_array as $note)
            {
                $rej_note_str = $rej_note_str.$note.' ,';
            }
        }
        $rej_note_str = trim($rej_note_str, ",");


        //is valid user for score card enter or edit
        $isValidUser = 0;
        $isApproveRejectExist = 0;
        $isForApprovalExist = 0;
        if(isset(Auth::user()->id)){
            $isValidUser = Helper::isValidUserForScoreEnter($match_data);
            //is approval process exist
            $isApproveRejectExist = Helper::isApprovalExist($match_data);
            $isForApprovalExist = Helper::isApprovalExist($match_data,$isForApproval='yes');
        }

        //ONLY FOR VIEW SCORE CARD
        if($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser)
        {
            
                return view('scorecards.Squashscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city));
            

        }
        else //to view and edit Squash/table Squash score card
        {
          
                //for form submit pass id from controller
                $form_id = 'squash';
                return view('scorecards.squashscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'form_id'=>$form_id));
           
        }

    }

//save or update preferences. 
    public function savePreferences(ObjectRequest $request){
        $match_id=$request->match_id;
        $team_a_name=$request->team_a_name;
        $team_b_name=$request->team_b_name;
        $tournament_id=$request->tournament_id;

        $left_team_id=$request->team_left;
        $right_team_id=$request->team_right;

        $left_team_name=Team::find($left_team_id)->name;
        $right_team_name=Team::find($right_team_id)->name;

        $score_to_win=$request->score_to_win;
        $number_of_sets=$request->number_of_sets;
        $score_to_win=$request->score_to_win;
        $end_point=$request->set_end_point;
        $saving_side=$request->saving_side;
        $enable_two_points=$request->enable_two_points;

        //left players details
        $left_player_1=$request->select_player_1_left;  
        $left_player_1_name=user::find($left_player_1)->name;

        if(!is_null($left_player_2=$request->select_player_2_left)) $left_player_2_name=user::find($left_player_2)->name;
        else $left_player_2_name=null;

        //right players details
        $right_player_1=$request->select_player_1_right; 
        $right_player_1_name=user::find($right_player_1)->name;

        if(!is_null($right_player_2=$request->select_player_2_right)) $right_player_2_name=user::find($right_player_2)->name;
        else $right_player_2_name=null;

       $match_model=MatchSchedule::find($match_id);   

       $match_details=$match_model->match_details;

       if(empty($match_details)){
            $match_details=[
                    "team_a"=>[                         //left team
                        "id"=>$left_team_id,
                        "name"=>$left_team_name,
                        "player_1_id"=>$left_player_1,
                        "player_2_id"=>$left_player_2
                    ],
                    "team_b"=>[                         //right team
                        "id"=>$right_team_id,
                        "name"=>$right_team_name,
                        "player_1_id"=>$right_player_1,
                        "player_2_id"=>$right_player_2
                    ],
                    "preferences"=>[
                        "left_team_id"=>$left_team_id,
                        "right_team_id"=>$right_team_id,
                        "saving_side"=>"left",
                        "number_of_sets"=>3,
                        "enable_two_points"=>"on",
                        "score_to_win"=>0,
                        "end_point"=>0
                    ],
                    "match_details"=>[
                        "set1"=>[
                                "{$left_team_id}_score"=>0,
                                "{$right_team_id}_score"=>0
                            ],
                        "set2"=>[
                                "{$left_team_id}_score"=>0,
                                "{$right_team_id}_score"=>0
                            ],
                        "set3"=>[
                                "{$left_team_id}_score"=>0,
                                "{$right_team_id}_score"=>0
                            ],
                        "set4"=>[
                                 "{$left_team_id}_score"=>0,
                                "{$right_team_id}_score"=>0
                            ],
                        "set5"=>[
                                "{$left_team_id}_score"=>0,
                                "{$right_team_id}_score"=>0
                            ]                     

                    ],
                    "match_type"=>$match_model->match_type,
                    "schedule_type"=>$match_model->schedule_type
                ];

                $match_details=json_encode($match_details);           
            } 

            $match_details=json_decode($match_details) ;     //convert it to object to use.

        //set game preferences
            $match_details->preferences->end_point=$end_point;
            $match_details->preferences->score_to_win=$score_to_win;
            $match_details->preferences->number_of_sets=$number_of_sets;
            $match_details->preferences->saving_side=$saving_side;
            $match_details->preferences->enable_two_points=$enable_two_points;
            $match_details->preferences->left_team_id=$left_team_id;            
            $match_details->right_team_id=$right_team_id;

        //player preferences

        $match_details=json_encode($match_details);

        //enter choosen players in the database
        $this->insertSquashPlayer($left_player_1, $left_player_2, $tournament_id, $left_team_id, $left_team_name, $match_id, $left_player_1_name, $left_player_2_name);

        $this->insertSquashPlayer($right_player_1, $right_player_2, $tournament_id, $right_team_id,$right_team_name, $match_id, $right_player_1_name, $right_player_2_name);

        $match_model->hasSetupSquad=1;
        $match_model->match_details=$match_details;
        $match_model->save();

        return $match_details;
    }

        //insert selected players in the database
    public function insertSquashPlayer($player_1_id, $player_2_id, $tournament_id, $team_id, $team_name, $match_id, $player_1_name, $player_2_name){
         $match_score_model=new SquashPlayerMatchScore;
        
            $match_score_model->user_id_a       =$player_1_id;
            $match_score_model->user_id_b       =$player_2_id;
            $match_score_model->tournament_id   =$tournament_id;
            $match_score_model->team_id         =$team_id;
            $match_score_model->team_name       =$team_name;
            $match_score_model->match_id        =$match_id;
            $match_score_model->player_name_a   =$player_1_name;
            $match_score_model->player_name_b   =$player_2_name;
            $match_score_model->isactive        =1;

            $match_score_model->save();
    }

    //add scores

    public function addScore(ObjectRequest $request){


            $match_id=$request->match_id;
            $table_score_id=$request->table_score_id;
            $team_id=$request->team_id;

            $match_model=MatchSchedule::find($match_id);            //match_schedule data
            $match_details=json_decode($match_model->match_details);
            $preferences=$match_details->preferences;

            $match_score_model=SquashPlayerMatchScore::find($table_score_id);    //scoring team data

            $match_score_model_other=SquashPlayerMatchScore::where('match_id', $match_id)->where('id', '!=', $table_score_id)->first();                             //opponent team data

            $end_point = $preferences->end_point;
            $score_to_win = $preferences->score_to_win;
            $number_of_sets = $preferences->number_of_sets;
            $saving_side = $preferences->saving_side;
            $enable_two_points = $preferences->enable_two_points;          
            
            // Check if set1 is complete

            if($this->checkSet('set1', $match_score_model, $match_score_model_other, $preferences)){
                $match_score_model->set1++;
                $match_score_model->save();
                $match_details->match_details->set1->{$team_id."_score"} ++;   
            }
            else{           //set1 is complete

                if($this->checkSet('set2', $match_score_model, $match_score_model_other, $preferences)){
                $match_score_model->set2++;
                $match_score_model->save();
                $match_details->match_details->set2->{$team_id."_score"} ++;   
                }

                else{       //set2 is complete
                        if($this->checkSet('set3', $match_score_model, $match_score_model_other, $preferences)){
                                $match_score_model->set3++;
                                $match_score_model->save();
                                $match_details->match_details->set3->{$team_id."_score"} ++;   
                            }
                        else{

                            if($number_of_sets>3){

                                    if($this->checkSet('set4', $match_score_model, $match_score_model_other, $preferences)){
                                        $match_score_model->set4++;
                                        $match_score_model->save();
                                        $match_details->match_details->set4->{$team_id."_score"} ++;   
                                    }
                                    else{
                                        if($this->checkSet('set5', $match_score_model, $match_score_model_other, $preferences)){
                                                $match_score_model->set5++;
                                                $match_score_model->save();
                                                $match_details->match_details->set5->{$team_id."_score"} ++;   
                                         }
                                }
                            
                            }
                        }
                }
            }

        $match_details=json_encode($match_details);
        $match_model->match_details=$match_details;
        $match_model->save();
        $match_score_model->save();

       if($match_score_model->match_type=='double')$player_ids=[$match_score_model->user_id_a, $match_score_model->user_id_b];
       else $player_ids=[$match_score_model->user_id_a];

        $this->squashStatistics($player_ids,$match_model->match_type);

        return $match_details;
  }

    //check if a set is full or complete. returns true for not complete returns false for complete

    public function checkSet($set, $match_score_model, $match_score_model_other, $preferences){
            $end_point = $preferences->end_point;
            $score_to_win = $preferences->score_to_win;
            $number_of_sets = $preferences->number_of_sets;
            $saving_side = $preferences->saving_side;
            $enable_two_points = $preferences->enable_two_points;

            $set1_score=$match_score_model->{$set};
            $set1_opponent_score=$match_score_model_other->{$set};

            if($set1_score<$score_to_win && $set1_opponent_score<$score_to_win){
                return true;
            }

            else if($set1_score==$end_point || $set1_score==$end_point){
                return false;
            }

            else if($set1_score>=$score_to_win && $set1_opponent_score>=$score_to_win){
                if($enable_two_points=='on'){
                    if(($set1_score-$set1_opponent_score)>=2) return false;
                    elseif(($set1_opponent_score-$set1_score)>=2) return false;
                    else return true;
                }
                else{
                    return false;
                }
            }
            

    }

   public function squashStatistics($player_ids_array,$match_type,$is_win='')
    {
        //$player_ids_array = explode(',',$player_ids);
        foreach($player_ids_array as $user_id)
        {
            $double_faults_count = '';

            $player_match_details = SquashPlayerMatchScore::where('user_id_a',$user_id)->orWhere('user_id_b', $user_id)->get();

            if($match_type=='singles')
            {
                // $double_faults_count = (!empty($player_match_details[0]['double_faults_count']))?$player_match_details[0]['double_faults_count']:'';
            }

            //check already user id exists or not
            $squash_statistics_array = array();
            $tennisStatistics = SquashStatistic::select()->where('user_id',$user_id)->where('match_type',$match_type)->get();
            if(count($tennisStatistics)>0)
            {
                $squash_statistics_array = $tennisStatistics->toArray();
                $matches = !empty($squash_statistics_array[0]['matches'])?$squash_statistics_array[0]['matches']:0;
                $won = !empty($squash_statistics_array[0]['won'])?$squash_statistics_array[0]['won']:0;
                $lost = !empty($squash_statistics_array[0]['lost'])?$squash_statistics_array[0]['lost']:0;
                SquashStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['matches'=>$player_match_details->count(),'double_faults'=>$double_faults_count]);
                if($is_win=='yes') //win count
                {
                    $won_percentage = number_format((($won+1)/($matches+1))*100,2);
                    SquashStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['won'=>$won+1,'won_percentage'=>$won_percentage]);

                }else if($is_win=='no')//loss count
                {
                    SquashStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['lost'=>$lost+1]);
                }
            }else
            {
                $won='';
                $won_percentage='';
                $lost='';
                if($is_win=='yes') //win count
                {
                    $won = 1;
                    $won_percentage = number_format(100,2);
                }else if($is_win=='no') //lost count
                {
                    $lost=1;
                }
                $tennisStatisticsModel = new SquashStatistic();
                $tennisStatisticsModel->user_id = $user_id;
                $tennisStatisticsModel->match_type = $match_type;
                $tennisStatisticsModel->matches = 1;
                $tennisStatisticsModel->won_percentage = $won_percentage;
                $tennisStatisticsModel->won = $won;
                $tennisStatisticsModel->lost = $lost;
                $tennisStatisticsModel->double_faults = $double_faults_count;
                $tennisStatisticsModel->save();
            }
        }

    }


    //save record manually;
    public function manualScoring(ObjectRequest $request){
            $score_a_id=$request->score_a_id;
            $score_b_id=$request->score_b_id;
            $number_of_sets=$request->number_of_sets;

            $score_a_model=SquashPlayerMatchScore::find($score_a_id);
            $score_b_model=SquashPlayerMatchScore::find($score_b_id);  

            //start scoring

            for($i=1; $i<=$number_of_sets; $i++){
                    $score_a_model->{"set".$i}=$request->{"a_set".$i};
                    $score_b_model->{"set".$i}=$request->{"b_set".$i};
            }

            $score_a_model->save();
            $score_b_model->save();

        return 'match saved';
    }



    public function getSquashDetails(ObjectRequest $request){
        $match_id=$request->match_id;
        $match_model=matchschedule::find($match_id);
        return $match_model->match_details;
    }

    public function SquashStoreRecord(ObjectRequest $Objrequest){

        $loginUserId = Auth::user()->id;
        $request = Request::all();
        $tournament_id = !empty(Request::get('tournament_id'))?Request::get('tournament_id'):NULL;
        $match_id = !empty(Request::get('match_id'))?Request::get('match_id'):NULL;
        $match_type = !empty(Request::get('match_type'))?Request::get('match_type'):NULL;
        $player_ids_a = !empty(Request::get('player_ids_a'))?Request::get('player_ids_a'):NULL;
        $player_ids_b= !empty(Request::get('player_ids_b'))?Request::get('player_ids_b'):NULL;
        $is_singles = !empty(Request::get('is_singles'))?Request::get('is_singles'):NULL;
        $is_winner_inserted = !empty(Request::get('is_winner_inserted'))?Request::get('is_winner_inserted'):NULL;
        $winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):$is_winner_inserted;//winner_id

        $team_a_players = !empty(Request::get('a_player_ids'))?Request::get('a_player_ids'):array();//player id if match type is singles
        $team_b_players = !empty(Request::get('b_player_ids'))?Request::get('b_player_ids'):array();//player id if match type is singles

        $schedule_type = !empty(Request::get('schedule_type'))?Request::get('schedule_type'):NULL;

      
        //get previous scorecard status data
        $scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
        $decode_scorecard_data = json_decode($scorecardDetails,true);

        $modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

        $modified_users = $modified_users.','.$loginUserId;//scorecard changed users

        $added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

        //score card approval process
        $score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'');

        $json_score_status = json_encode($score_status);

 

        //update winner id
        $matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
        if(count($matchScheduleDetails)) {
            $looser_team_id = NULL;
            $match_status = 'scheduled';
            $approved='';
            if(isset($winner_team_id)) {
                if($winner_team_id==$matchScheduleDetails['a_id']) {
                    $looser_team_id=$matchScheduleDetails['b_id'];
                }else{
                    $looser_team_id=$matchScheduleDetails['a_id'];
                }
                $match_status = 'completed';
                $approved = 'approved';
            }

            if(!empty($matchScheduleDetails['tournament_id'])) {
                $tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
                if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) {
                    MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'match_status'=>$match_status,
                        'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
                        'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

                    if(!empty($matchScheduleDetails['tournament_round_number'])) {
                        $this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id);
                    }
                    if($match_status=='completed')
                    {
                        $this->updateStatitics($match_id);

                        //notification code
                    }

                }

            }else if(Auth::user()->role=='admin')
            {

                MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
                    'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
                    'score_added_by'=>$json_score_status,'scoring_status'=>$approved]);
                if($match_status=='completed')
                {
                    $this->updateStatitics($match_id);
                    
                    //notification code
                }
            }
            else
            {
                MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id ,
                    'looser_id'=>$looser_team_id,'score_added_by'=>$json_score_status]);
            }
        }
        //MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id,'match_details'=>$json_match_details_array,'score_added_by'=>$json_score_status ]);
        //if($winner_team_id>0)
        //return redirect()->route('match/scorecard/view', [$match_id])->with('status', trans('message.scorecard.scorecardmsg'));

        return redirect()->back()->with('status', trans('message.scorecard.scorecardmsg'));

    }

    public function updateStatitics($match_id){
        $score_a_model=SquashPlayerMatchScore::where('match_id', $match_id)->first();
        $score_b_model=SquashPlayerMatchScore::where('match_id', $match_id)->skip(1)->first();

        $match_score_model=$score_a_model;
        if($match_score_model->match_type=='double'){
            $player_ids=[
               $match_score_model->user_id_a,
               $match_score_model->user_id_b
               ];
            }                                                             
        else $player_ids=[$match_score_model->user_id_a];
        $this->SquashStatistics($player_ids,$match_model->match_type);

        $match_score_model=$score_b_model;
        if($match_score_model->match_type=='double'){
            $player_ids=[
               $match_score_model->user_id_a,
               $match_score_model->user_id_b
               ];
            }                                                           
        else $player_ids=[$match_score_model->user_id_a];
        $this->SquashStatistics($player_ids,$match_model->match_type);
    }


    public function updatePreferences(ObjectRequest $request){
            $match_id=$request->match_id;

            $match_model=MatchSchedule::find($match_id);
            $match_details=json_decode($match_model->match_details);

            $preferences=$match_details->preferences;

            $preferences->number_of_sets=$request->number_of_sets;
            $preferences->end_point=$request->set_end_point;
            $preferences->score_to_win=$request->score_to_win;
            $preferences->enable_two_points=$request->enable_two_points;

            $match_details->preferences=$preferences;

            $match_details=json_encode($match_details);

            $match_model->match_details=$match_details;

            $match_model->save();

            return "preferences updated";
    }

}


