<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use \InvalidArgumentException;
use \ErrorException;

class Articles extends BaseModel
{
    protected $table = 'articles';

    protected $hidden = ['pivot'];

    protected $collectionFilters = [
        'articles' => [
            'sort' => 'created_at',
            'order' => 'desc',
            'limit' => 10,
            'paginate' => null,
        ],
        'comments' => [
            'sort' => 'created_at',
            'order' => 'desc'
        ]
    ];

    /**
     * @param $filterName
     * @return mixed|null
     */
    public function getArticlesFilter($filterName)
    {
        return $this->getCollectionFilter($filterName, 'articles');
    }

    /**
     * @param $filterName
     * @return mixed|null
     */
    public function getCommentsFilter($filterName)
    {
        return $this->getCollectionFilter($filterName, 'comments');
    }

    /**
     * @param array|null $collectionFilters
     * @return Collection|string
     */
    public function getComments(array $collectionFilters = null)
    {
        $this->updateCollectionFilters($collectionFilters, 'comments');

        $sort = $this->getCommentsFilter('sort');
        $order = $this->getCommentsFilter('order');

        try {
            return $this->belongsToMany(Comments::class,
                'article_comment',
                'article_id',
                'comment_id')
                ->orderBy($sort, $order)
                ->get();
        } catch (QueryException $exception) {
            return "Unknown column $sort";
        } catch (InvalidArgumentException $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->belongsToMany(Tags::class,
            'article_tag',
            'article_id',
            'tag_id')
            ->get();
    }

    /**
     * @param array|null $collectionFilters
     * @return LengthAwarePaginator|Collection|string
     */
    public function getArticlesCollection(array $collectionFilters = null)
    {
        $this->updateCollectionFilters($collectionFilters, 'articles');

        $sort = $this->getArticlesFilter('sort');
        $order = $this->getArticlesFilter('order');
        $limit = $this->getArticlesFilter('limit');
        $paginate = $this->getArticlesFilter('paginate');

        try {
            $query = self::join('article_comment', 'articles.id', '=', 'article_comment.article_id')
                ->selectRaw('articles.id,
                                articles.title,
                                articles.created_at,
                                count(article_comment.article_id) as comment_count')
                ->groupBy('articles.id')
                ->orderBy($sort, $order)
                ->take($limit);
        } catch (InvalidArgumentException $exception) {
            return $exception->getMessage();
        }

        try {
            if ($paginate) {
                $query = $query->paginate($paginate);
            } else {
                $query = $query->get();
            }
        } catch (QueryException $exception) {
            return "Unknown column $sort";
        } catch (ErrorException $exception) {
            return $exception->getMessage();
        }

        foreach ($query as $item) {
            $item->setAttribute('tags', $item->getTags());
        }

        return $query;
    }
}
