<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $posts=PostResource::collection(Post::get());
        return $this->apiResponse($posts,'ok',200);
    }

    public function show($id)
    {
        $post=Post::find($id);
        if($post)
        {
            return $this->apiResponse(new PostResource($post), 'ok', 200);
        }
        return $this->apiResponse($post, 'this post not found', 404);
    }


    public function store(Request $request)
    {

        $validation=Validator::make($request->all(),[
            'title'=>'required|unique:posts|max:255',
            'body'=>'required',
        ]);

        if($validation->fails())
        {
            return $this->apiResponse(null,$validation->errors(),201);
        }
        $post=Post::create($request->all());

        if($post)
        {
            return $this->apiResponse(new PostResource($post), 'this post saved',201);
        }
        return $this->apiResponse(null,'this post not saved',400);
    }

    

    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->apiResponse(null, $validation->errors(), 201);
        }

        $post=Post::find($id);
        $post->update($request->all());
        if(!$post)
        {
            return $this->apiResponse($post, 'this post not found', 404);
        }
        if($post)
        {
        return $this->apiResponse(new PostResource($post),'this post is aupdated',401);
        }

        return $this->apiResponse(null,'post not updated',404);
    }

    public function destroy(Request $request,$id)
    {
        $post=Post::find($id);
        if (!$post) {
            return $this->apiResponse($post, 'this post not found', 404);
        }
        $post->delete($id);
        if($post)
        {
        return $this->apiResponse(null,'this post is deleted',401);
        }
    }
}
