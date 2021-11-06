<?php

namespace App\Models;

use App\Models\User\UserNotices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * @property string email
 * @property string password
 * @property string first_name
 * @property string last_name
 * @property string remember_token
 * @property UsersRole|null role
 * @property null|int role_id
 * @property InvestmentIdea|null investment_ideas
 * @property UserNotices[] notices
 */
class User extends Authenticatable
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role_id',
    ];
    protected $primaryKey = 'user_id';

    public function authentication(array $params = null): bool
    {
        return Auth::attempt($params, true);
    }

    public function getFrontendData(): array
    {
        $ar_notice = [];
        foreach ($this->notices as $notice_model) {
            $ar_notice[] = [
                'id' => $notice_model->notice_id,
                'description' => $notice_model->description,
                'viewed' => $notice_model->viewed,
                'title' => $notice_model->title,
                'created' => $notice_model->date_create
            ];
        }
        return [
            'firstName' => ucfirst($this->first_name),
            'lastName' => $this->last_name,
            'role' => $this->role->name,
            'notices' => $ar_notice,
        ];
    }

    public function role(): HasOne
    {
        return $this->hasOne(UsersRole::class, 'role_id', 'role_id');
    }

    public function investment_ideas(): HasMany
    {
        return $this->hasMany(InvestmentIdea::class, 'author_id', 'user_id');
    }

    public function notices(): HasMany
    {
        return $this->hasMany(UserNotices::class, 'user_id');
    }

    public function getFullName(): string
    {
        $first_name = ucfirst($this->first_name);
        $second_name = ucfirst($this->last_name);
        return "$second_name $first_name";
    }
}
