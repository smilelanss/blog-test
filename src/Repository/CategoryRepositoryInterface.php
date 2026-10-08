<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return list<Category>
     */
    public function findNonEmpty(): array;
}
