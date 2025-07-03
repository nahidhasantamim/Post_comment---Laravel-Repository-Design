<?php

namespace App\Http\Controllers;

use Shamaseen\Repository\Utility\Controller as Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class CommentController.
 *
 * @property CommentRepository $repository
 */
class CommentController extends Controller
{

    public string $routeIndex = 'comments.index';

    public string $pageTitle = 'Comment';
    public string $createRoute = 'comments.create';

    public string $viewIndex = 'comments.index';
    public string $viewCreate = 'comments.create';
    public string $viewEdit = 'comments.edit';
    public string $viewShow = 'comments.show';
    
	public ?string $resourceClass = CommentResource::class;

	public ?string $collectionClass = CommentCollection::class;
 
	public ?string $policyClass = CommentPolicy::class;
 
	public string $requestClass = CommentRequest::class;


    public function __construct(CommentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(): mixed
    {
        $this->authorize('create', Comment::class);
        $data = $this->request->validate([
            'body'      => 'required|string|max:255',
            'blog_id'   =>  'required'
        ]);

        $data['user_id'] = Auth::id();
        $comment = $this->repository->create($data);

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->store($comment);
        }

        return redirect()
            ->back()
            ->with('success', __('Comment added successfully.'));
    }

    /** Update comment. */
    public function update(int|string $id): mixed
    {
        $comment = $this->repository->findOrFail($id);
        $this->authorize('update', $comment);

         $data = $this->request->validate([
            'body'       => 'required|string|max:255'
        ]);
        $this->repository->update($id, $data);

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->update(true);
        }

        return redirect()
            ->back()
            ->with('success', __('Comment updated successfully.'));
    }

    /** Delete comment. */
    public function destroy(int|string $id): mixed
    {
        $comment = $this->repository->findOrFail($id);
        $this->authorize('delete', $comment);
        $this->repository->delete($id);

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->destroy(true);
        }

        return redirect()
            ->back()
            ->with('success', __('Comment deleted successfully.'));
    }

}
