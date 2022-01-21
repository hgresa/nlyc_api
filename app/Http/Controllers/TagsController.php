<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Http\Response;

class TagsController extends Controller
{
    /**
     * @var Tags
     */
    private Tags $tagsModel;

    /**
     * @param Tags $tagsModel
     */
    public function __construct(
        Tags $tagsModel
    ) {
        $this->tagsModel = $tagsModel;
    }

    /**
     * @param Request $request
     * @return Collection|Response|string
     */
    public function getTags(Request $request)
    {
        $tags = $this->tagsModel->getTagsCollection($request->query());

        if (is_string($tags)) {
            return response($tags, 400);
        }

        return $tags;
    }

    /**
     * @param Request $request
     * @param $tagId
     * @return Collection|Response|string
     */
    public function getArticles(Request $request, $tagId)
    {
        $articles = Tags::where('id', $tagId)->first()->getArticles($request->query());

        if (is_string($articles)) {
            return response($articles, 400);
        }

        return $articles;
    }
}
