<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property int $is_admin
 * @property int $active
 * @property string $twofa_secret
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Image $main_image
 * @property Collection $images
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tg_id', 'name', 'surname', 'email', 'password', 'phone', 'balance', 'city_id', 'invited_by', 'balance', "tg_username",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['time'=>time()];
    }

    public function images(): MorphMany
    {
        return $this->morphMany('App\Models\Image','object');
    }

    public function main_image(): MorphOne
    {
        return $this->morphOne('App\Models\Image','object')->where('is_main',1);
    }

    public function purchases()
    {
        return $this->belongsToMany(ShopItem::class,
            "shop_items_users",
            "user_id", "shop_item_id")->withPivot(['cost','count','date']);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class,'invited_by');
    }

    public function friends()
    {
        return $this->hasMany(User::class,'invited_by');
    }

    public function quests()
    {
        return $this->belongsToMany(Quest::class,'quests_users')->withPivot(['finished_at']);
    }

    public function lotteries()
    {
        return $this->belongsToMany(Lottery::class,'lottery_users','user_id','lottery_id')->withPivot(['count']);
    }

    public function prizes()
    {
        return $this->hasMany(Prize::class,'winner_id');
    }

    public function history()
    {
        return $this->hasMany(BalanceHistory::class,'user_id');
    }

    public function loginList()
    {
        return $this->hasMany(LoginList::class, 'user_id');
    }
}
