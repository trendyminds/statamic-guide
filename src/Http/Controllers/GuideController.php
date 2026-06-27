<?php

namespace Trendyminds\Guide\Http\Controllers;

use Inertia\Inertia;
use Trendyminds\Guide\Guide;
use Trendyminds\Guide\Page;

class GuideController
{
    public function index()
    {
        if ($page = Guide::index()) {
            return $this->render($page);
        }

        // No index.md — render a list of the available pages, or an empty
        // state with setup instructions when there are no pages at all.
        return Inertia::render('statamic-guide::Guide', [
            'title' => 'Guide',
            'contents' => null,
            'pages' => Guide::navPages()
                ->map(fn (Page $page) => [
                    'title' => $page->title,
                    'url' => cp_route('guide.show', ['slug' => $page->slug]),
                ])
                ->values(),
            'directory' => str(Guide::directory())
                ->after(base_path().DIRECTORY_SEPARATOR)
                ->replace('\\', '/')
                ->toString(),
        ]);
    }

    public function show(string $slug)
    {
        $page = Guide::find($slug);

        abort_unless($page, 404);

        return $this->render($page);
    }

    protected function render(Page $page)
    {
        return Inertia::render('statamic-guide::Guide', [
            'title' => $page->title,
            'contents' => $page->render(),
            'pages' => [],
        ]);
    }
}
