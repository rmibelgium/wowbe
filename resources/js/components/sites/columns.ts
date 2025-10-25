import DataTableDropdown from '@/components/sites/DataTableDropdown.vue';
import Button from '@/components/ui/button/Button.vue';
import { formatDateTime } from '@/lib/utils';
import { type SharedData, type Site } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { createColumnHelper } from '@tanstack/vue-table';
import { trans } from 'laravel-vue-i18n';
import { BadgeAlert, BadgeCheck, BadgeX, ChevronsUpDown, MapPin } from 'lucide-vue-next';
import { DateTime } from 'luxon';
import { h } from 'vue';

const columnHelper = createColumnHelper<Site>();

const page = usePage<SharedData>();

const handleSort = (columnKey: string, currentSortBy?: string, currentSortDirection?: string) => {
    let newSortDirection = 'asc';

    // If we're already sorting by this column, toggle the direction
    if (currentSortBy === columnKey) {
        newSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    }

    // Get current URL parameters to preserve pagination and other filters
    const currentUrl = new URL(page.url, window.location.origin);
    const params = new URLSearchParams(currentUrl.search);

    // Update sorting parameters
    params.set('sort_by', columnKey);
    params.set('sort_direction', newSortDirection);

    // Reset to first page when sorting changes (optional behavior)
    params.set('page', '1');

    router.get(page.url, Object.fromEntries(params), { preserveScroll: true, replace: true });
};

export const columns = [
    columnHelper.accessor('id', {
        header: ({ table }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => handleSort('id', table.options.meta?.sortBy, table.options.meta?.sortDirection),
                },
                () => [trans('dashboard.table.columns.id'), h(ChevronsUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('id')),
    }),
    columnHelper.accessor('short_id', {
        enableSorting: false,
        header: () => h('div', { class: 'text-left' }, trans('dashboard.table.columns.short_id')),
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('short_id')),
    }),
    columnHelper.accessor('name', {
        header: ({ table }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => handleSort('name', table.options.meta?.sortBy, table.options.meta?.sortDirection),
                },
                () => [trans('dashboard.table.columns.name'), h(ChevronsUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('name')),
    }),
    columnHelper.display({
        id: 'location',
        enableSorting: false,
        header: () => h('div', { class: 'text-left' }, trans('dashboard.table.columns.location')),
        cell: ({ row }) => {
            const longitude = row.original.longitude;
            const latitude = row.original.latitude;
            const location = `${longitude}, ${latitude}`;
            return h('div', { class: 'text-left font-medium flex items-center gap-2' }, [h(MapPin, { size: 24 }), location]);
        },
    }),
    columnHelper.accessor('created_at', {
        header: ({ table }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => handleSort('created_at', table.options.meta?.sortBy, table.options.meta?.sortDirection),
                },
                () => [trans('dashboard.table.columns.created_at'), h(ChevronsUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row, table }) => {
            const createdAtTZ = DateTime.fromISO(row.original.created_at);
            const createdAt = formatDateTime(table.options.meta?.locale, createdAtTZ.setZone(row.original.timezone).valueOf());
            return h('time', { class: 'text-left font-medium', datetime: createdAtTZ.toISO() }, createdAt);
        },
    }),
    columnHelper.accessor('observations_count', {
        header: ({ table }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => handleSort('observations_count', table.options.meta?.sortBy, table.options.meta?.sortDirection),
                },
                () => [trans('dashboard.table.columns.observations_count'), h(ChevronsUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-right font-medium' }, row.getValue('observations_count')),
    }),
    columnHelper.display({
        id: 'observations_maxdateutc',
        header: ({ table }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => handleSort('observations_maxdateutc', table.options.meta?.sortBy, table.options.meta?.sortDirection),
                },
                () => [trans('dashboard.table.columns.observations_maxdateutc'), h(ChevronsUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row, table }) => {
            if (row.original.observations_count === 0 || row.original.observations_maxdateutc === null) {
                return h('div', { class: 'text-left font-bold text-gray-500 flex items-center gap-2' }, [
                    h(BadgeX, { size: 24 }),
                    trans('dashboard.table.no_observations'),
                ]);
            } else {
                const latestObservationDateTimeTZ = DateTime.fromSQL(row.original.observations_maxdateutc, { zone: 'UTC' });
                const latestObservationDateTime = formatDateTime(
                    table.options.meta?.locale,
                    latestObservationDateTimeTZ.setZone(row.original.timezone).valueOf(),
                );
                const last24Hours = new Date(row.original.observations_maxdateutc).getTime() >= new Date().setHours(new Date().getHours() - 24);
                return h('time', { class: 'text-left font-medium flex items-center gap-2', datetime: latestObservationDateTimeTZ.toISO() }, [
                    last24Hours === true ? h(BadgeCheck, { size: 24, color: 'green' }) : h(BadgeAlert, { size: 24, color: 'orange' }),
                    latestObservationDateTime,
                ]);
            }
        },
    }),
    columnHelper.display({
        id: 'actions',
        enableSorting: false,
        cell: ({ row }) => {
            const site = row.original;

            return h(
                'div',
                { class: 'relative' },
                h(DataTableDropdown, {
                    site,
                }),
            );
        },
    }),
];
