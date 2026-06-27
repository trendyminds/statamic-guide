import Guide from './pages/Guide.vue';

Statamic.booting(() => {
    Statamic.$inertia.register('statamic-guide::Guide', Guide);
});
