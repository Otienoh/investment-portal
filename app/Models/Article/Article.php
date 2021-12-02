<?php

namespace App\Models\Article;

use App\Custom\CustomModel;
use App\Custom\Query\CustomQueryBuilder;
use App\Custom\Relations\CustomHasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/** Article - статьи
 * @property int article_id
 * @property string title
 * @property string content
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string preview_path
 * @property User author
 * @property Collection|ArticleLabels[] labels
 * @property Collection|ArticleViewing[] viewing
 */
class Article extends CustomModel
{
    protected $table = 'articles';
    protected $primaryKey = 'article_id';

    public function __toString()
    {
        return $this->title;
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'author_id');
    }

    public function getFrontend(): array
    {
        $author_model = $this->author;
        return [
            'articleId' => $this->article_id,
            'title' => (string)$this,
            'dateCreate' => $this->created_at->format('Y-m-d H:i:s'),
            'preview' => $this->preview_path,
            'author' => [
                'userId' => $author_model->user_id,
                'fullName' => (string)$author_model,
                'avatar' => $author_model->avatar_path,
            ],
            'views' => $this->viewing()->count() ?? 0
        ];
    }

    public function getView(): array
    {
        $front_end = $this->getFrontend();
        return array_merge($front_end, [
            'labels' => $this->getLabels(),
            'content' => $this->content,
        ]);
    }

    public function viewing(): CustomHasMany
    {
        return $this->hasMany(ArticleViewing::class, 'article_id', 'article_id');
    }

    public static function mostPopular(): Builder|CustomQueryBuilder
    {
        return self::query()->withCount('viewing')->orderBy('viewing_count', 'desc');
    }

    public function labels(): CustomHasMany
    {
        return $this->hasMany(ArticleLabels::class, 'article_id', 'article_id');
    }

    #[Pure] public function getLabels(): array
    {
        $ar_labels = [];
        foreach ($this->labels as $label_model) {
            $label = $label_model->label;
            $ar_labels[] = [
                'icon' => $label->icon,
                'text' => $label_model->text,
            ];
        }
        foreach (['carbon:view-filled', 'bx:bxs-comment-detail'] as $fake_label) {
            $text = match ($fake_label) {
                'carbon:view-filled' => $this->viewing->count(),
                'bx:bxs-comment-detail' => 0,
                default => '',
            };
            $ar_labels[] = [
                'icon' => $fake_label,
                'text' => $text
            ];
        }
        return $ar_labels;
    }
}