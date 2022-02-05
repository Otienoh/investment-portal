<?php

namespace App\Http\Modules\Investment\Controllers;

use App\Http\Classes\StockMarket;
use App\Models\Article\Article;
use App\Models\Investment\InvestmentIdea;
use App\Models\Investment\InvestmentIdeaStatuses;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class InvestmentController extends BaseController
{
    public function getPortalData(): JsonResponse
    {
        $count_success_ideas = InvestmentIdea::query()->with('status', fn($query) => $query->where(['name' => InvestmentIdeaStatuses::STATUS_PUBLISHED]))->count();
        $count_fail_ideas = InvestmentIdea::query()->with('status', fn($query) => $query->where(['name' => InvestmentIdeaStatuses::STATUS_FAILED]))->count();

        $popular_articles = Article::mostPopular()->limit(3)->with('author')->get();
        $pk_list = [];
        /** @var Article $article_model */
        foreach ($popular_articles as $article_model) {
            $pk_list[] = $article_model->article_id;
            $articles_popular[] = $article_model->toArray();
        }
        $articles = Article::query()->whereNotIn('article_id', $pk_list)
            ->orderByDesc('created_at')
            ->limit(10)->with(['author'])->get();
        foreach ($articles as $article_model) {
            $articles_simple[] = $article_model->toArray();
        }

        $investment_ideas = InvestmentIdea::mostPopular()->limit(5)->get();
        /** @var InvestmentIdea $idea_model */
        foreach ($investment_ideas as $idea_model) {
            $company_info = $idea_model->company;
            $ar_ideas[] = [
                'id' => $idea_model->idea_id,
                'possibleProfit' => $idea_model->possible_profit,
                'stock' => $company_info->name,
                'logo' => $company_info->logo,
            ];
        }

        return response()->json([
            'stats' => [
                'success' => $count_success_ideas,
                'fail' => $count_fail_ideas,
            ],
            'ideas' => $ar_ideas ?? [],
            'articles' => [
                'popular' => $articles_popular ?? null,
                'simple' => $articles_simple ?? null
            ]
        ]);
    }

    public function getNews(): JsonResponse
    {
        if (!$news = Cache::get("last-news")) {
            $market = new StockMarket();
            $news = array_slice($market->getMarketNews(), 0, 10);
            Cache::put("last-news", $news, now()->addHour());
        }
        return response()->json($news ?? []);
    }
}
