<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import Form from '@/components/site/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SiteLayout from '@/layouts/site/Layout.vue';
import { type BreadcrumbItem, type Site } from '@/types';

const props = defineProps<{
    timezones: string[];
    defaultTimezone: string;
    site: Site;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: `Site "${props.site.name}"`,
        href: route('site.edit', { site: props.site.id }),
    },
    {
        title: 'Update your site information',
        href: route('site.edit', { site: props.site.id }),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Update your site information" />

        <SiteLayout :site="props.site">
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Site information" description="Update your site information" />

                <Form :timezones="timezones" :default-timezone="defaultTimezone" :site="site" />
            </div>
        </SiteLayout>
    </AppLayout>
</template>
