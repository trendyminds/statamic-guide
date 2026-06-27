<?php

namespace Trendyminds\Guide;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Statamic\Facades\Markdown;
use Statamic\Facades\YAML;

class Page
{
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly string $path,
        public readonly bool $isIndex,
        protected readonly string $body,
    ) {}

    public static function fromFile(string $path): self
    {
        $filename = pathinfo($path, PATHINFO_FILENAME);

        [$frontMatter, $body] = static::parse((string) file_get_contents($path));

        return new self(
            slug: Str::slug($filename),
            title: $frontMatter['title'] ?? Str::headline($filename),
            path: $path,
            isIndex: $filename === 'index',
            body: $body,
        );
    }

    /**
     * Render the page body. Content is run through Blade first (so authors can
     * use helpers like cp_route(), asset(), Auth::user(), @foreach, @antlers)
     * and then parsed as Markdown.
     */
    public function render(): string
    {
        return Markdown::parse(Blade::render($this->body));
    }

    /**
     * Split YAML front matter from the markdown body.
     *
     * @return array{0: array<string, mixed>, 1: string}
     */
    protected static function parse(string $contents): array
    {
        if (preg_match('/^---\s*\R(.*?)\R---\s*\R?(.*)$/s', $contents, $matches)) {
            return [YAML::parse($matches[1]) ?: [], $matches[2]];
        }

        return [[], $contents];
    }
}
