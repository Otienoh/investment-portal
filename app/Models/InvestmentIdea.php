<?php

namespace App\Models;

use App\Models\Investment\InvestmentIdeaReaction;
use App\Models\Investment\InvestmentIdeaViewing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int idea_id
 * @property User author
 * @property int author_id
 * @property float price_buy
 * @property float price_sell
 * @property bool is_short
 * @property string stock_name
 */
class InvestmentIdea extends Model
{
    protected $table = 'investment_ideas';

    public function views(): HasMany
    {
        return $this->hasMany(InvestmentIdeaViewing::class, 'idea_id', 'idea_id');
    }

    public function reaction(): HasMany
    {
        return $this->hasMany(InvestmentIdeaReaction::class, 'idea_id', 'idea_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'author_id');
    }

    public function calculatePossibleProfit(): float|int
    {
        if ($this->is_short) {
            return (($this->price_buy - $this->price_sell) / $this->price_sell) * 100;
        }
        return (($this->price_sell - $this->price_buy) / $this->price_buy) * 100;
    }
}