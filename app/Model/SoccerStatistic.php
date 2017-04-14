<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class SoccerStatistic extends Model {
    use SoftDeletes,Eloquence;
    //
    protected $table = 'soccer_statistic';
    //protected $fillable = array('user_id', 'following_sports', 'following_teams', 'managing_teams', 'joined_teams', 'following_tournaments', 'managing_tournaments', 'joined_tournaments', 'following_players', 'following_facilities', 'provider_id', 'provider_token', 'avatar',);


    public static function updateUserStatistic($user_id)
    {
        //check already player has record or not
        $user_soccer_details = SoccerStatistic::select()->where('user_id',$user_id)->get();

        $soccer_details = SoccerPlayerMatchwiseStats::selectRaw('count(match_id) as match_count')->selectRaw('sum(yellow_cards) as yellow_cards')->selectRaw('sum(red_cards) as red_cards')->selectRaw('sum(goals_scored) as goals_scored')->where('user_id',$user_id)->groupBy('user_id')->get();
        $yellow_card_cnt = (!empty($soccer_details[0]['yellow_cards']))?$soccer_details[0]['yellow_cards']:0;
        $red_card_cnt = (!empty($soccer_details[0]['red_cards']))?$soccer_details[0]['red_cards']:0;
        $goals_cnt = (!empty($soccer_details[0]['goals_scored']))?$soccer_details[0]['goals_scored']:0;
        if(count($user_soccer_details)>0)
        {
            $match_count = (!empty($soccer_details[0]['match_count']))?$soccer_details[0]['match_count']:0;
            SoccerStatistic::where('user_id',$user_id)->update(['matches'=>$match_count,'yellow_cards'=>$yellow_card_cnt,'red_cards'=>$red_card_cnt,'goals_scored'=>$goals_cnt]);
        }else
        {
            $soccer_statistics = new SoccerStatistic();
            $soccer_statistics->user_id = $user_id;
            $soccer_statistics->matches = 1;
            $soccer_statistics->yellow_cards = $yellow_card_cnt;
            $soccer_statistics->red_cards = $red_card_cnt;
            $soccer_statistics->goals_scored = $goals_cnt;
            $soccer_statistics->save();
        }
    }

}
