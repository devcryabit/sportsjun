<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Model\Followers;
use DB;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\FunctionsApiController as functionsApi;
use App\Http\Controllers\User\TournamentsController as tournamentsApi;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Tournaments;
use Response;

class TournamentApiController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->functionsApi = new functionsApi;
        $this->tournamentsApi = new tournamentsApi;
    }


    public function index($filter = false)
    {
        $tournaments = Tournaments::joinParent();
        $user = \Auth::user();
        if ($filter) {
            if ($user) {
                switch ($filter) {
                    case 'managed':
                        $tournament_ids = $user->getManagedTournamentsIds();
                        $tournaments = $tournaments->whereIn('tournaments.id',$tournament_ids);
                        break;
                    case 'joined':
                        $tournament_ids = $user->getJoinedTournamentsIds();
                        $tournaments = $tournaments->whereIn('tournaments.id',$tournament_ids);
                        break;
                    case 'following':
                        $tournament_ids =  $user->folowers()->tournaments()->lists('type_id');
                        $tournaments = $tournaments->whereIn('tournaments.id',$tournament_ids);
                        break;
                default:
                    $tournaments->where(DB::raw('false'));
                    break;
                }
            }   else {
                $tournaments->where(DB::raw('false'));
            }
        }
        return $this->ApiResponse($tournaments->paginate(20)->toArray());
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $tournament = Tournaments::find($id);
        $tournament->photos;
        $tournament->user;
        return Response::json([
            'status' => 200,
            'data' => $tournament,
            'error' => false
        ]);

    }

    public function parent($id)
    {
        $tournament = Tournaments::find($id);
        $parent_tournament = $tournament->tournamentParent;

        return Response::json([
            'status' => 200,
            'data' => $parent_tournament,
            'error' => false
        ]);
    }

    public function follow_tournament($id)
    {
        return $this->functionsApi->follow_unfollow('tournaments', $id, 1);
    }

    public function unfollow_tournament($id)
    {
        return $this->functionsApi->follow_unfollow('tournaments', $id, 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {

    }

    public function group_stage($id)
    {
        return $this->tournamentsApi->groups($id, 'group', true);
    }

    public function final_stage($id)
    {
        return $this->tournamentsApi->groups($id, 'final', true);
    }

    public function player_standing($id)
    {
        return $this->tournamentsApi->playerStanding($id, true);
    }
}
