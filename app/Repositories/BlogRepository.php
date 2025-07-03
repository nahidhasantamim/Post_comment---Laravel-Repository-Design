<?php
namespace App\Repositories;

use Shamaseen\Repository\Utility\AbstractRepository as AbstractRepository;
use App\Models\Blog;

/**
 * Class BlogRepository.
 *
 * @extends AbstractRepository<Blog>
 */
class BlogRepository extends AbstractRepository
{
    public array $with = [];

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return Blog::class;
    }
}
