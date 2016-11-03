<?php

namespace App;

use App\Helpers\Helper;
use App\Model\Followers;
use App\Model\Organization;
use App\Model\OrganizationRole;
use App\Model\Photo;
use App\Model\Rating;
use App\Model\TournamentParent;
use App\Model\Tournaments;
use Auth;
use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    static $USER_EXISTS = -1;
    static $USER_EMAIL_REQUIRED = -2;


    use Authenticatable,
        Authorizable,
        CanResetPassword,
        SoftDeletes,
        Eloquence;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $morphClass = 'user_photo';

    protected $table = 'users';

    protected $searchableColumns = ['name', 'location'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'name',
        'email',
        'password',
        'dob',
        'gender',
        'location',
        'address',
        'city_id',
        'city',
        'state_id',
        'state',
        'country_id',
        'country',
        'zip',
        'contact_number',
        'about',
        'newsletter',
        'profile_updated',
        'role',
        'verification_key',
        'is_verified',
        'isactive',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    protected $appends = ['logoImage'];
    /** Extra */
    /**
     * @param $orgId
     *
     * @return mixed
     */
    public function roleForOrganization($orgId)
    {
        return $this->staff_role()->wherePivot('organization_id', $orgId)
            ->first();
    }

    /**
     * A user can be staff of many organizations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staffOfOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_staffs',
            'user_id', 'organization_id')
            ->withPivot('organization_role_id', 'status')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staff_role()
    {
        return $this->belongsToMany(OrganizationRole::class,
            'organization_staffs', 'user_id', 'organization_role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class, 'user_id', 'id');
    }


    /**
     *
     * @param type $data
     * @param type $context
     */
    public static function validate($data, $context)
    {

        if ($context == 'edit') {
            $rules = [
                'name' => 'required|max:50',
                'dob' => 'date',
                'contact_number' => 'numeric',
                'zip' => 'numeric',
            ];
            $messages = [
                'name.required' => trans('validation.required'),
                'name.max' => trans('validation.max.numeric'),
                'dob.date' => trans('validation.date'),
                'contact_number.numeric' => trans('validation.numeric',
                    ['attribute' => 'contact number']),
                'zip.numeric' => trans('validation.numeric'),
            ];
        }

        return Validator::make($data, $rules, $messages);
    }

    public function providers()
    {
        return $this->hasMany('App\Model\UserProvider');
    }

    public function usersfollowingsports()
    {
        return $this->hasMany('App\Model\UserStatistic', 'user_id');
    }

    //a user can be a multiple team player
    public function userdetails()
    {
        return $this->hasMany('App\Model\TeamPlayer');
    }

    public function photos()
    {
        return $this->morphMany('App\Model\Photo', 'imageable')
            ->where('is_album_cover', '1');
    }

    public function folowers()
    {
        return $this->hasMany(Followers::class, 'user_id', 'id')->where('deleted_at', null);
    }

    public function tournaments()
    {
        return $this->hasMany('App\Model\Tournaments', 'created_by', 'id');
    }

    public function searchResults($req_params)
    {
        $user_id = Auth::user()->id;
        $offset = !empty($req_params['offset']) ? $req_params['offset'] : 0;
        $limit = !empty($req_params['limit']) ? $req_params['limit']
            : config('constants.LIMIT');
        $query = $this->search($req_params['search_by'])
            ->join('user_statistics', 'user_statistics.user_id', '=',
                'users.id');

        if (trim($req_params['sport']) != '') {
            $sports_arr = explode(",", $req_params['sport']);
            $i = 0;
            foreach ($sports_arr as $sport) {
                $sport = "," . $sport . ",";
                if ($i == 0) {
                    $query = $query->where('user_statistics.following_sports',
                        'LIKE', "%$sport%");
                } else {
                    $query = $query->orwhere('user_statistics.following_sports',
                        'LIKE', "%$sport%");
                }

                $i++;
            }
        }
        if (trim($req_params['gender']) != '') {
            $query = $query->whereIn('users.gender',
                explode(",", $req_params['gender']));
        }
        /* if(trim($req_params['avialability']) != ''){
            $query = $query->whereIn('team_available',explode(",",$req_params['avialability']));
        } */
        //Available to join a team
        if (trim($req_params['joinavialability']) == 1) {
            $sports_arr = explode(",", $req_params['sport']);
            $i = 0;
            foreach ($sports_arr as $sport) {
                $sport = "," . $sport . ",";
                if ($i == 0) {
                    $query =
                        $query->where('user_statistics.allowed_sports', 'LIKE',
                            "%$sport%");
                } else {
                    $query = $query->orwhere('user_statistics.allowed_sports',
                        'LIKE', "%$sport%");
                }

                $i++;
            }


        }

        if (trim($req_params['search_city_id']) != '') {
            $query =
                $query->where('city_id', trim($req_params['search_city_id']));
        }

        $query = $query->where('users.isactive', 1);
        $query = $query->whereNotIn('users.id', [$user_id]);
        $query = $query->whereNull('users.deleted_at');

        $totalresult = $query->get();
        $total = count($totalresult);
        $result = $query->limit($limit)
            ->offset($offset)
            ->orderBy('users.updated_at', 'desc')
            ->get();

        //echo "<pre>";print_r($result);exit;
        $response = ['result' => $result, 'total' => $total];

        return $response;
    }


    public function userscheduleone()
    {
        return $this->hasMany('App\Model\MatchSchedule', 'a_id', 'id');
    }

    public function userscheduletwo()
    {
        return $this->hasMany('App\Model\MatchSchedule', 'b_id', 'id');
    }

    public static function checkPermission($params)
    {
        if ($params['loggedin_user_role'] != 'general'
            || $params['loggedin_user_role'] == $params['owner_user_id']
        ) {
            return true;
        } else {
            return false;
        }
    }

    public static function logoImage($id)
    {
        $logo = Photo::where('imageable_id', $id)
            ->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))
            ->orderBy('id', 'desc')
            ->first();
        if ($logo && $logo->url) {
            return Helper::getImagePath($logo->url, 'user_profile');
        }
    }

    public function getLogoImageAttribute()
    {
        return self::logoImage($this->id);
    }

    public function getJoinedTournamentsIds()
    {
        return array_pluck(\DB::select(\DB::raw('
                        select tg.tournament_id 
                        from tournaments t 
                        inner join tournament_group_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                        where  t.deleted_at is null and ( 
                        (t.schedule_type=\'team\' and tg.team_id in (select team_id from team_players where user_id=? and status=\'accepted\' and deleted_at is null))
                        OR (t.schedule_type=\'individual\' and tg.team_id=?)) 
                        group by tg.tournament_id
                        union 
                        select tg.tournament_id 
                        from tournaments t 
                        inner join tournament_final_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                        where  t.deleted_at is null and ( 
                        (t.schedule_type=\'team\' and tg.team_id in (select team_id from team_players where user_id=? and status=\'accepted\' and deleted_at is null))
                        OR (t.schedule_type=\'individual\' and tg.team_id=?)) 
                        group by tg.tournament_id	
                        '), [$this->id, $this->id, $this->id, $this->id]), 'tournament_id');
    }

    public function getManagedTournamentsIds()
    {
        return DB::table('tournament_parent')
            ->leftjoin('tournaments', 'tournament_parent.id', '=', 'tournaments.tournament_parent_id')
            ->where('tournament_parent.manager_id', $this->id)
            ->orwhere('tournament_parent.owner_id', $this->id)
            ->orwhere('tournaments.manager_id', $this->id)
            ->lists('tournaments.id');
    }

    public function getManagedParentTournamentQuery()
    {
        return TournamentParent
            ::leftJoin('tournaments', 'tournament_parent.id', '=','tournaments.tournament_parent_id')
            ->leftJoin(\DB::raw('users m'),'m.id','=','tournament_parent.manager_id')
            ->leftJoin(\DB::raw('users o'),'o.id','=','tournament_parent.owner_id')
            ->where('tournament_parent.manager_id', $this->id)
            ->orwhere('tournament_parent.owner_id', $this->id)
            ->orwhere('tournaments.manager_id', $this->id)
            ->orderby('tournament_parent.created_at', 'desc')
            ->select(['tournament_parent.*',\DB::raw('m.name as manager'),\DB::raw('o.name as owner')])
            ->groupBy('tournament_parent.id');
    }

}
