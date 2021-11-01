<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopicsController extends Controller
{
    public function index(Request $request, Topic $topic)
    {
        $perPage =$request->per_page??null;
        $topics = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ])
            ->paginate($perPage);
        return TopicResource::collection($topics);
//        $query = $topic->query();
//        $perPage =$request->per_page??null;
//        if ($categoryId= $request->category_id){
//            $query->where('category_id', $categoryId);
//        }
//        $topics = $query->with(['user','category'])->withOrder($request->order)->paginate($perPage);
//        return TopicResource::collection($topics);

    }

    //某个用户发布的话题列表
    public function userIndex(User $user)
    {
        $perPage =$request->per_page??null;
        $query = $user->topics()->getQuery();
        $topics = QueryBuilder::for($query)
            ->allowedIncludes(['user','category'])
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied')
            ])
            ->paginate($perPage);
        return TopicResource::collection($topics);
    }
    //
    public function show()
    {

    }
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();
        return new TopicResource($topic);
    }

    public function update(TopicRequest $request, Topic $topic)
    {

        $this->authorize('update', $topic);
        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response(null, 204);
    }
}
