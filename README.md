# 📔 Statamic Guide

An in-control-panel guide that helps content editors find their way around your Statamic website. It adds a **Guide** section to the control panel navigation, built entirely from markdown files you define in your project so every site can document its own collections, blocks, workflows, and quirks.

## Installation

```bash
composer require trendyminds/statamic-guide
```

The addon auto-registers via package discovery, and its compiled control panel assets are published to `public/vendor/statamic-guide` automatically by Statamic's `statamic:install` hook.

## Defining your guide

Your guide is built from markdown files in your project. The addon reads them from `resources/guide`:

```
resources/guide/
├── index.md            # the "Guide" landing page (optional)
├── assets.md
├── blocks.md
└── collections.md
```

- **`index.md`** becomes the landing page shown when you click "Guide" in the nav. If it's omitted, the landing page lists the other pages automatically.
- **Every other `.md` file** becomes a child page under "Guide", listed alphabetically by title.
- **Titles** are derived from the filename (`seo-pro.md` → "Seo Pro") but can be set explicitly via front matter.

### Front matter

Each file may include optional YAML front matter:

```markdown
---
title: SEO Pro
---

SEO Pro is an addon used to manage your website's SEO information…
```

| Key     | Description                                                       |
| ------- | ----------------------------------------------------------------- |
| `title` | Nav label and page heading. Defaults to a title-cased filename.   |

The page's URL slug is always derived from its filename (`seo-pro.md` → `seo-pro`).

### Blade & Antlers in markdown

Guide files are rendered through **Blade and then Markdown**, so you can use Laravel helpers, Blade directives, and even Antlers right inside your content:

```markdown
## Logging in

- **CMS Login:** [{{ url(config('statamic.cp.route')) }}]({{ url(config('statamic.cp.route')) }})
- **Username:** {{ Auth::user()->email }}

![Dashboard]({{ asset('img/guide/dashboard.png') }})
```

> Because content is compiled by Blade, wrap any literal `@`, `{{ }}`, or example code that should _not_ be evaluated in `@verbatim … @endverbatim`. Reference your own images however you like — e.g. files you place in the app's `public` directory.

## How it renders

Guide pages are rendered as a Vue page component through Statamic 6's Inertia-powered control panel, so navigating between pages feels instant and consistent with the rest of the CP.

Markdown is purely the **data layer**: on each request the addon reads the relevant `.md` file, compiles it (Blade → Markdown) into HTML on the server, and passes that HTML to the Vue component as a prop. **Adding, editing, or removing markdown files never requires a rebuild** — only changes to the addon's own JavaScript do.

## Development

The compiled control panel assets are committed under `resources/dist`, so installing the addon via Composer just works — there's nothing for consumers to build.

If you change the addon's JavaScript (`resources/js`), iterate with the Vite dev server for hot module replacement:

```bash
composer install   # provides @statamic/cms for the npm build
npm install
npm run dev        # Vite dev server with HMR
```

When you're done, build the production assets and commit the output so Composer installs work with no build step:

```bash
npm run build      # outputs resources/dist
```

## License

MIT
