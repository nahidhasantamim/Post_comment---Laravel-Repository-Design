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
use Illuminate\Support\Facades\Gate;
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

    /** Show blog */
    public function show(int|string $id): mixed
    {
        $blog = $this->repository->findOrFail($id);
        
        $this->authorize('view', $blog);
        // $this->authorizeAction('view');

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

    /** Create blog */
    public function store(): mixed
    {
        $this->authorizeAction('create');

        // $data = $this->request->all();
        $data = $this->request->except(['_token','_method']);

        if ($this->request->hasFile('image')) {
            $data['image'] = $this->request->file('image')->store('blogs', 'public');
        }

        $data['slug']  = Str::slug( $data['title'] ) . '-' . uniqid();

        $data['user_id'] = Auth::id();

        $entity = $this->repository->create($data);

        return $this->responseDispatcher->store($entity);
    }

    /** Update blog*/
    public function update(int|string $id): mixed
    {
        $blog = $this->repository->findOrFail($id);
        
        $this->authorize('update', $blog);
        // $this->authorizeAction('update');

        // $data = $this->request->all();
        $data = $this->request->except(['_token','_method']);

        if ($this->request->hasFile('image')) {
            $data['image'] = $this->request->file('image')->store('blogs', 'public');
        }

        $data['slug']  = Str::slug( $data['title'] ) . '-' . uniqid();

        $this->repository->update($id, $data);

        return redirect()
            ->route($this->routeIndex)
            ->with('success', __('Blog updated successfully.'));
    }


}
