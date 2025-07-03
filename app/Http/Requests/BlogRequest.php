<?php

namespace App\Http\Requests;

use Shamaseen\Repository\Utility\Request as Request;
use Illuminate\Validation\Rule;
/**
 * Class BlogRequest.
 */
class BlogRequest extends Request
{
    /**
     * Define all the global rules for this request here.
     *
     * @var array
     */
    protected array $rules = [

    ];

    // Write your methods using {Controller Method Name}Rules, or {HTTP Method}MethodRules syntax.
    // For example, when index method in the controller is called a method called indexRules will be triggered here if it is exists.

    // For POST /blogs
    public function postMethodRules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'slug'        => 'required|string|unique:blogs,slug',
            'image'       => 'nullable|image|max:2048',
        ];
    }

    // For PUT/PATCH /blogs/{id}
    public function patchMethodRules(): array
    {
        $id = $this->route('blog'); // or however your route-model binding works
        return [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'slug'        => ['sometimes','required','string',
                              Rule::unique('blogs','slug')->ignore($id)],
            'image'       => 'nullable|image|max:2048',
        ];
    }
}
