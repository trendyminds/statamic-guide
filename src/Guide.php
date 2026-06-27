<?php

namespace Trendyminds\Guide;

use Illuminate\Support\Collection;

class Guide
{
    /**
     * The directory the guide's markdown files are read from.
     */
    public static function directory(): string
    {
        return resource_path('guide');
    }

    /**
     * Every guide page, ordered alphabetically by title.
     *
     * @return Collection<int, Page>
     */
    public static function pages(): Collection
    {
        $directory = static::directory();

        if (! is_dir($directory)) {
            return collect();
        }

        return collect(glob($directory.'/*.md') ?: [])
            ->map(fn ($path) => Page::fromFile($path))
            ->sortBy('title')
            ->values();
    }

    /**
     * The landing page (index.md), if one exists.
     */
    public static function index(): ?Page
    {
        return static::pages()->first(fn (Page $page) => $page->isIndex);
    }

    /**
     * Pages shown as children in the navigation (everything but the index).
     *
     * @return Collection<int, Page>
     */
    public static function navPages(): Collection
    {
        return static::pages()
            ->reject(fn (Page $page) => $page->isIndex)
            ->values();
    }

    /**
     * Find a single page by its slug.
     */
    public static function find(string $slug): ?Page
    {
        return static::pages()->first(fn (Page $page) => $page->slug === $slug);
    }
}
