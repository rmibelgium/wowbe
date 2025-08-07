<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import Form from '@/components/site/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SiteLayout from '@/layouts/site/Layout.vue';
import { type BreadcrumbItem, type Media, type Site } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const props = defineProps<{
    timezones: string[];
    site: Site;
    pictures: Media[];
}>();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: trans('dashboard.title'),
        href: route('dashboard'),
    },
    {
        title: trans('form.header.site', { site: props.site.name }),
        href: route('site.edit', { site: props.site.id }),
    },
    {
        title: trans('form.header.update.title'),
        href: route('site.edit', { site: props.site.id }),
    },
]);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="trans('form.header.update.title')" />

        <SiteLayout :site="site">
            <div class="flex flex-col space-y-6">
                <HeadingSmall :title="trans('form.header.update.title')" :description="trans('form.header.update.description')" />

                <Form :timezones="timezones" :site="site" :pictures="pictures" />
            </div>
        </SiteLayout>
    </AppLayout>
</template>
