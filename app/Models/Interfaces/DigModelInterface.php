<?php

namespace App\Models\Interfaces;

use Illuminate\Support\Collection;

interface DigModelInterface
{
    public function initInfo(): array;

    public function index($queryParams);

    public function page($ids, $view): Collection;

    public function show(array $validated);

    public function carousel(string $id);

    public function destroyItem(string $id);
}
