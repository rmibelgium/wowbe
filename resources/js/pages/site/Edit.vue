<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import Form from '@/components/site/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SiteLayout from '@/layouts/site/Layout.vue';
import { type BreadcrumbItem, type Site } from '@/types';

const props = defineProps<{
    site: Site;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Update your site',
        href: route('site.edit', { site: props.site.id }),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Update your site" />

        <SiteLayout :site="props.site">
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Site information" description="Update your site" />

                <Form :site="props.site" />
            </div>
        </SiteLayout>
    </AppLayout>
</template>
