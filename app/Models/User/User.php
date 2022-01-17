<?php

namespace App\Models\User;

use App\Custom\CustomModel;
use App\Custom\Relations\CustomHasMany;
use App\Models\Article\ArticleComments;
use App\Models\Investment\InvestmentIdea;
use App\Models\Investment\InvestmentIdeaComments;
use App\Models\Other\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int user_id
 * @property string email
 * @property string password
 * @property string first_name
 * @property string last_name
 * @property string remember_token
 * @property UsersRole|null role
 * @property null|int role_id
 * @property InvestmentIdea|Collection|null investment_ideas
 * @property UserNotices[]|Collection notices
 * @property string avatar_path
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Country country
 * @property string sex
 * @property Collection|ArticleComments[] commentsArticles
 * @property Collection|UserSubscriptions[] subscriptions
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role_id', 'avatar_path'
    ];
    protected $primaryKey = 'user_id';

    #[Pure] public function __toString()
    {
        return $this->getFullName();
    }

    public function authentication(array $params = null): bool
    {
        return Auth::attempt($params, true);
    }

    #[Pure] public function getFrontendData(): array
    {
        foreach ($this->notices()->orderByDesc('created_at')->get() as $notice_model) {
            $ar_notice[] = [
                'id' => $notice_model->notice_id,
                'description' => $notice_model->description,
                'viewed' => $notice_model->viewed,
                'title' => $notice_model->title,
                'created' => $notice_model->created_at->format('Y-m-d H:i:s'),
            ];
        }
        return [
            'userId' => $this->user_id,
            'fullName' => $this->getFullName(),
            'role' => $this->role->name,
            'notices' => $ar_notice ?? [],
            'avatar' => $this->avatar_path,
        ];
    }

    public function getProfile(): array
    {
        $country_model = $this->country;
        return [
            'userId' => $this->user_id,
            'name' => [
                'fullName' => $this->getFullName(),
                'firstName' => $this->first_name,
                'lastName' => $this->last_name,
            ],
            'fullName' => $this->getFullName(),
            'dateCreate' => $this->created_at->format('Y-m-d'),
            'allComments' => $this->commentsIdeas()->count() + $this->commentsArticles()->count(),
            'avatar' => $this->avatar_path,
            'roleName' => (string)$this->role,
            'country' => [
                'country_id' => $country_model?->country_id,
                'code' => $country_model?->code,
                'name' => $country_model?->name,
            ],
            'sex' => $this->sex
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

    public function commentsIdeas(): HasMany
    {
        return $this->hasMany(InvestmentIdeaComments::class, 'user_id', 'user_id');
    }

    public function commentsArticles(): HasMany
    {
        return $this->hasMany(ArticleComments::class, 'user_id', 'user_id');
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'country_id', 'country_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscriptions::class, 'user_id', 'user_id');
    }
}
