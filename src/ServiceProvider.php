<?php

namespace Trendyminds\Guide;

use Illuminate\Support\Facades\Route;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use Trendyminds\Guide\Http\Controllers\GuideController;

class ServiceProvider extends AddonServiceProvider
{
    protected $vite = [
        'input' => [
            'resources/js/addon.js',
        ],
        'publicDirectory' => 'resources/dist',
    ];

    public function bootAddon()
    {
        $this
            ->bootRoutes()
            ->bootNav();
    }

    protected function bootRoutes(): self
    {
        $this->registerCpRoutes(function () {
            Route::get('guide', [GuideController::class, 'index'])->name('guide.index');
            Route::get('guide/{slug}', [GuideController::class, 'show'])->name('guide.show');
        });

        return $this;
    }

    protected function bootNav(): self
    {
        Nav::extend(function ($nav) {
            $item = $nav->tools('Guide')
                ->route('guide.index')
                ->icon('docs');

            $children = Guide::navPages()
                ->map(fn (Page $page) => $nav->item($page->title)
                    ->url(cp_route('guide.show', ['slug' => $page->slug])))
                ->all();

            if ($children) {
                $item->children($children);
            }
        });

        return $this;
    }
}
