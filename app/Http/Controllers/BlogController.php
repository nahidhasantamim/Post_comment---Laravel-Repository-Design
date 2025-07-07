<?php

namespace App\Http\Controllers;

use Shamaseen\Repository\Utility\Controller as Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Policies\BlogPolicy;
use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
/**
 * Class BlogController.
 *
 * @property BlogRepository $repository
 */
class BlogController extends Controller
{

    public string $routeIndex = 'blogs.index';

    public string $pageTitle = 'Blog';
    public string $createRoute = 'blogs.create';

    public string $viewIndex = 'blogs.index';
    public string $viewCreate = 'blogs.create';
    public string $viewEdit = 'blogs.edit';
    public string $viewShow = 'blogs.show';
    
	public ?string $resourceClass = BlogResource::class;

	public ?string $collectionClass = BlogCollection::class;
 
	public ?string $policyClass = BlogPolicy::class;
 
	public string $requestClass = BlogRequest::class;


    public function __construct(BlogRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index(): mixed
    {
        $this->repository->scope(fn($q) => $q->where('user_id', Auth::id()));
        return parent::index();
    }

    /** Create blog */
    public function store(): mixed
    {
        $this->authorizeAction('create');

        // $data = $this->request->validate([
        //     'title'       => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'image'       => 'nullable|image|max:2048',
        // ]);

        $data = [
                'title'       => $this->request->input('title'),
                'description' => $this->request->input('description'),
            ];

        if ($this->request->hasFile('image')) {
            $data['image'] = $this->request->file('image')->store('blogs', 'public');
        }

        $data['slug']    = Str::slug( $data['title']) . '-' . uniqid();
        // $data['slug']    = Str::slug($data['title']) . '-' . uniqid();
        $data['user_id'] = Auth::id();

        $entity = $this->repository->create($data);

        return $this->responseDispatcher->store($entity);
    }

    /** Show blog */
    public function show(int|string $id): mixed
    {
        $blog = $this->repository->findOrFail($id);
        $this->authorizeAction('view');

        $comments = $blog->comments()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->show($blog);
        }

        return view($this->viewShow, [
            'blog' => $blog,
            'comments' => $comments
        ]);
    }

    /** Edit blog */
    public function edit(int|string $id): mixed
    {
        $blog = $this->repository->findOrFail($id);
        // $this->authorize('update', $blog);
        $this->authorizeAction('update');

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->edit($blog);
        }

        return view($this->viewEdit, [
            'blog' => $blog
        ]);
    }

    /** Update blog*/
    public function update(int|string $id): mixed
    {
        $blog = $this->repository->findOrFail($id);
        // $this->authorize('update', $blog);
        $this->authorizeAction('update');
        // $data = $this->request->validate([
        //     'title'       => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'image'       => 'nullable|image|max:2048',
        // ]);

        $data = [
                'title'       => $this->request->input('title'),
                'description' => $this->request->input('description'),
            ];

        if ($this->request->hasFile('image')) {
            $data['image'] = $this->request->file('image')->store('blogs', 'public');
        }

        $this->repository->update($id, $data);

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->update(true);
        }

        return redirect()
            ->route($this->routeIndex)
            ->with('success', __('Blog updated successfully.'));
    }

    /** Delete blog */
    public function destroy(int|string $id): mixed
    {
        $blog = $this->repository->findOrFail($id);
        // $this->authorize('delete', $blog);
        $this->authorizeAction('delete');
        $this->repository->delete($id);

        if ($this->request->expectsJson()) {
            return $this->responseDispatcher->destroy(true);
        }

        return redirect()
            ->route($this->routeIndex)
            ->with('success', __('Blog deleted.'));
    }


}
