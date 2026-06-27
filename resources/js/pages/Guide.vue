<script setup>
import { Head, Link } from '@statamic/cms/inertia';

defineProps({
    title: { type: String, required: true },
    contents: { type: String, default: null },
    pages: { type: Array, default: () => [] },
    directory: { type: String, default: 'resources/guide' },
});
</script>

<template>
    <Head :title="title" />

    <ui-header :title="title" icon="docs" />

    <!-- A rendered markdown page -->
    <div v-if="contents" class="card prose" v-html="contents" />

    <!-- Pages exist, but no index.md: list them -->
    <div v-else-if="pages.length" class="card prose">
        <ul>
            <li v-for="page in pages" :key="page.url">
                <Link :href="page.url">{{ page.title }}</Link>
            </li>
        </ul>
    </div>

    <!-- Empty state: tell the developer how to build the guide -->
    <div v-else class="card prose">
        <h2>No guide pages yet</h2>

        <p>
            This section is powered by Markdown files in your project. Add
            files to <code>{{ directory }}</code> and they'll appear here
            automatically &mdash; no rebuild required.
        </p>

        <p>For example, create <code>{{ directory }}/collections.md</code>:</p>

        <pre v-pre><code>---
title: Collections
---

Explain how Collections work on this site for your editors.
You can use Markdown, Blade helpers, and even Antlers here.</code></pre>

        <p>A few things to know:</p>

        <ul>
            <li>Each <code>.md</code> file becomes a page, listed alphabetically by title.</li>
            <li>An optional <code>index.md</code> becomes this landing page.</li>
            <li>Set a custom <code>title</code> via YAML front matter (otherwise it's derived from the filename).</li>
        </ul>
    </div>
</template>
