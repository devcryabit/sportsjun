@if (count($player_standing))
     <h4><b>{{ config('constants.BASKETBALL_STATS.BASKETBALL_STATISTICS')}}</b></h4>
    <div class=" stats-table" id='teamStatsDiv'>
    <table class="table">
        <thead>
            <tr>
                <th>PLAYER NAME</th>
                <th>TEAM NAME</th>
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.POINTS_1')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.POINTS_2')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.POINTS_3')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.TOTAL_POINTS')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.FOULS')}}</th>
<!--                <th>{{ config('constants.BASKETBALL_STATS.GOALS_SAVED')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.GOALS_ASSIST')}}</th>
                <th>{{ config('constants.BASKETBALL_STATS.GOALS_PENALTIES')}}</th>-->
            </tr>
        </thead>
        <tbody>
            @foreach($player_standing as $statistic)  
            <tr>
                <td><a href='/editsportprofile/{{$statistic->team_id}}' class="text-primary">{{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a></td>
                <td>{{$statistic->matches}}</td>
                <td>{{$statistic->points_1}}</td>
                <td>{{$statistic->points_2}}</td>
                <td>{{$statistic->points_3}}</td>
                <td>{{$statistic->total_points}}</td>
                <td>{{$statistic->fouls}}</td>
<!--                <td>{{$statistic->goals_saved}}</td>
                <td>{{$statistic->goal_assist}}</td>
                <td>{{$statistic->goal_penalties}}</td>-->
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @else
    
    <div class="sj-alert sj-alert-info">
                       {{ trans('message.sports.nostats')}}
</div>
    @endif