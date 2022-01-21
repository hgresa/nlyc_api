<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticlesController extends Controller
{
    /**
     * @var Articles
     */
    private Articles $articlesModel;

    /**
     * @param Articles $articlesModel
     */
    public function __construct(
        Articles $articlesModel
    ) {
        $this->articlesModel = $articlesModel;
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator|Collection|Response|string
     */
    public function getArticles(Request $request)
    {
        $articles = $this->articlesModel->getArticlesCollection($request->query());

        if (is_string($articles)) {
            return response($articles, 400);
        }

        return $articles;
    }

    /**
     * @param Request $request
     * @param $articleId
     * @return Collection|Response|string
     */
    public function getComments(Request $request, $articleId)
    {
        $comments = Articles::where('id', $articleId)->first()->getComments($request->query());

        if (is_string($comments)) {
            return response($comments, 400);
        }

        return $comments;
    }
}
