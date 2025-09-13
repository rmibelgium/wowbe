<script setup lang="ts">
import { columns } from '@/components/sites/columns';
import DataTable from '@/components/sites/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData, type Site } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: trans('dashboard.title'),
        href: route('dashboard'),
    },
]);

const page = usePage<SharedData>();

const sites = page.props.sites as Site[];
const filters = page.props.filters as { sort_by?: string; sort_direction?: string };
const pagination = page.props.pagination as {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    has_more_pages: boolean;
};
</script>

<template>
    <Head :title="trans('dashboard.title')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <DataTable
                :columns="columns"
                :data="sites"
                :sortBy="filters?.sort_by"
                :sortDirection="filters?.sort_direction"
                :pagination="pagination"
            />
        </div>
    </AppLayout>
</template>
