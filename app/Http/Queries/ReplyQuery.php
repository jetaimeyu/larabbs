<?php


namespace App\Http\Queries;


use App\Models\Reply;

class ReplyQuery extends \Spatie\QueryBuilder\QueryBuilder
{

    public function __construct()
    {
        parent::__construct(Reply::query());
        $this->allowedIncludes(['user', 'topic']);

    }
}
