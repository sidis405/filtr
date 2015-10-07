<?php

namespace Filtr\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, PresentableTrait;

    protected $presenter = 'Filtr\Presenters\UserPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function links()
    {
        return $this->hasMany('Filtr\Models\Links');
    }

    public function entities()
    {
        return $this->belongsToMany('Filtr\Models\Entities', 'entity_user', 'user_id', 'entity_id')->withTimestamps();
    }

    public function linksCount()
    {
      return $this->hasOne('Filtr\Models\Links')
        ->selectRaw('user_id, count(*) as aggregate')
        ->groupBy('user_id');
    }
     
    public function getCommentsCountAttribute()
    {
      // if relation is not loaded already, let's do it first
      if ( ! array_key_exists('linksCount', $this->relations)) 
        $this->load('linksCount');
     
      $related = $this->getRelation('linksCount');
     
      // then return the count directly
      return ($related) ? (int) $related->aggregate : 0;
    }
}
