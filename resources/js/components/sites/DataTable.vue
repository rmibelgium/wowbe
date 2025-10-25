<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { trans } from 'laravel-vue-i18n';

const page = usePage<SharedData>();

interface PaginationData {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    has_more_pages: boolean;
}

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    sortBy?: string;
    sortDirection?: string;
    pagination?: PaginationData;
}>();

const navigateToPage = (p: number) => {
    const currentUrl = new URL(page.url, window.location.origin);
    const params = new URLSearchParams(currentUrl.search);

    params.set('page', p.toString());

    router.get(page.url, Object.fromEntries(params), { preserveScroll: true, replace: true });
};

const table = useVueTable({
    meta: {
        locale: page.props.auth.user.locale,
        sortBy: props.sortBy,
        sortDirection: props.sortDirection,
    },
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
});
</script>

<template>
    <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
        <Table>
            <TableHeader>
                <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                    <TableHead v-for="header in headerGroup.headers" :key="header.id">
                        <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                        </TableCell>
                    </TableRow>
                </template>
                <template v-else>
                    <TableRow>
                        <TableCell :colspan="columns.length" class="h-24 text-center">{{ trans('dashboard.table.no_results') }}</TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>

    <div v-if="pagination" class="flex items-center justify-between space-x-2 py-4">
        <div class="text-muted-foreground flex-1 text-sm">
            <template v-if="pagination.total > 0">
                {{ trans('dashboard.table.showing_entries', { from: pagination.from, to: pagination.to, total: pagination.total }) }}
            </template>
            <template v-else>
                {{ trans('dashboard.table.no_results') }}
            </template>
        </div>
        <div class="flex items-center space-x-2">
            <Button variant="outline" size="sm" :disabled="pagination.current_page <= 1" @click="navigateToPage(pagination.current_page - 1)">
                {{ trans('dashboard.table.previous') }}
            </Button>

            <Button variant="outline" size="sm" :disabled="!pagination.has_more_pages" @click="navigateToPage(pagination.current_page + 1)">
                {{ trans('dashboard.table.next') }}
            </Button>
        </div>
    </div>
</template>
