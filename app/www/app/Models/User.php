<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Scaffolders\UserScaffolder;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
    ];

    protected $cachedPermissions = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }


    public function getPermissionsAttribute(): array
    {
        if(!empty($this->cachedPermissions)) {
            return $this->cachedPermissions;
        }
        $permissions = [];

        foreach ($this->roles as $role) {
            if($role->permissions) {
                foreach ($role->permissions as $module => $actions) {
                    if ($actions){
                        foreach ($actions as $action => $allowed) {
                            if ($allowed) {
                                $permissions[$module][$action] = true;
                            }
                        }
                    }
                }
            }
        }
        \Log::info("Peerrrrrrrrrrr");
        $this->cachedPermissions = $permissions;
        return $this->cachedPermissions;
    }

     /**
     * Get the display name for the user.
     *
     * @return string
     */

    /**
     * Get the sample models authored by this user.
     */
    public function sampleModels(): BelongsToMany
    {
        return $this->belongsToMany(SampleModel::class, 'sample_model_user', 'user_id', 'sample_model_id');
    }

    /**
     * Get all roles assigned to this user.
     *
     * Many-to-many relationship with UserRole.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'user_role_user', 'user_id', 'role_id');
    }

    /**
     * Get the minimal examples associated with this user.
     */
    public function minimalExamples(): BelongsToMany
    {
        return $this->belongsToMany(MinimalExample::class);
    }


    public function examples(): HasMany
    {
        return $this->hasMany(MinimalExample::class,  'author_id', 'id');
    }
}
