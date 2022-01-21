<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use \InvalidArgumentException;

class Tags extends BaseModel
{
    protected $table = 'tags';

    protected $hidden = ['pivot'];

    protected $collectionFilters = [
        'tags' => [
            'sort' => 'article_count',
            'order' => 'desc'
        ],
        'articles' => [
            'sort' => 'created_at',
            'order' => 'desc'
        ]
    ];

    /**
     * @param $filterName
     * @return mixed|null
     */
    public function getTagsFilter($filterName)
    {
        return $this->getCollectionFilter($filterName, 'tags');
    }

    /**
     * @param $filterName
     * @return mixed|null
     */
    public function getArticlesFilter($filterName)
    {
        return $this->getCollectionFilter($filterName, 'articles');
    }

    /**
     * @param array|null $collectionFilters
     * @return Collection|string
     */
    public function getArticles(array $collectionFilters = null)
    {
        $this->updateCollectionFilters($collectionFilters, 'articles');

        $sort = $this->getArticlesFilter('sort');
        $order = $this->getArticlesFilter('order');

        try {
            return $this->belongsToMany(Articles::class,
                'article_tag',
                'tag_id',
                'article_id')
                ->orderBy($sort, $order)
                ->get();
        } catch (QueryException $exception) {
            return "Unknown column $sort";
        } catch (InvalidArgumentException $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param array|null $collectionFilters
     * @return Collection|string
     */
    public function getTagsCollection(array $collectionFilters = null)
    {
        $this->updateCollectionFilters($collectionFilters, 'tags');

        $sort = $this->getTagsFilter('sort');
        $order = $this->getTagsFilter('order');

        try {
            return self::join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
                ->selectRaw('tags.id, tags.title, count(article_tag.article_id) as article_count')
                ->groupBy('tags.id')
                ->orderBy($sort, $order)
                ->get();
        } catch (QueryException $exception) {
            return "Unknown column $sort";
        } catch (InvalidArgumentException $exception) {
            return $exception->getMessage();
        }
    }
}
