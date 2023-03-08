<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    public function scopeWithLastLoginAt(Builder $query)
    {
        $query->addSelect(['last_login_at' => Login::select('created_at')
        ->whereColumn('user_id', 'users.id')
        ->latest()
        ->take(1)
    ])
        ->withCasts(['last_login_at' => 'datetime']); // to cast it to a datetime object so  diffForHumans can use it
    }

    public function scopeWithLastLogIpAddress(Builder $query)
    {
        $query->addSelect(['last_login_ip_address' => Login::select('ip_address')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1)
        ]); // to cast it to a datetime object so  diffForHumans can use it
    }

    // making scopes like this for every property you want to get on a related model can be tedious and messy
    // We can use dynamic relationships instead.

    public function lastLogin()
    {
        return $this->hasOne(Login::class)->latest(); // this will mean that we will have to eager load
        // so we are left with the issue of having to eager load all models just to obtain the last one, as explained
        // in the previous commit. this is why using sub-queries to make dynamic loading is so powerful.

    }

    public function scopeWithLastLogin($query)
    {
        $query->addSelect(['last_login_id' => Login::select('id')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1)
        ])->with('lastLogin'); // laravel doesn't know this isn't a real model rel and so will just create it on the fly
    }

    public function last()
    {

    }

    // this may produce a slow query without any indexes
    public function scopeSearch($query, string $terms = null)
    {
        // the user will have to use "" around a term to be used as a complete term.
        collect(str_getcsv($terms, ' ', '"'))->filter()->each(function($term) use ($query) {
            $term = $term.'%';

            $query->whereIn('id', function($query) use ($term) {
                $query->select('id')
                    ->from(function ($query) use ($term) {
                        $query->select('id')
                            ->from('users')
                            ->where('first_name', 'like', $term)
                            ->orWhere('last_name', 'like', $term)
                            ->union(
                                $query->newQuery()
                                ->select('users.id')
                                ->from('users')
                                ->join('companies', 'companies.id', '=', 'users.company_id')
                                ->where('companies.name', 'like', $term)
                            );
                    }, 'matches');
            });
        }); // this now runs as 3 separate queries but each query runs ultra-fast because the query now uses the
        // indexes.
    }
}
