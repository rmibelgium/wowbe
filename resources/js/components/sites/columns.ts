import DataTableDropdown from '@/components/sites/DataTableDropdown.vue';
import Button from '@/components/ui/button/Button.vue';
import { formatDateTime } from '@/lib/utils';
import { type Site } from '@/types';
import { ColumnDef } from '@tanstack/vue-table';
import { trans } from 'laravel-vue-i18n';
import { ArrowUpDown, BadgeAlert, BadgeCheck, BadgeX, MapPin } from 'lucide-vue-next';
import { DateTime } from 'luxon';
import { h } from 'vue';

export const columns: ColumnDef<Site>[] = [
    {
        accessorKey: 'id',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [trans('dashboard.table.columns.id'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('id')),
    },
    {
        accessorKey: 'short_id',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [trans('dashboard.table.columns.short_id'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('short_id')),
    },
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [trans('dashboard.table.columns.name'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('name')),
    },
    {
        id: 'location',
        header: () => h('div', { class: 'text-left' }, trans('dashboard.table.columns.location')),
        cell: ({ row }) => {
            const longitude = row.original.longitude;
            const latitude = row.original.latitude;
            const location = `${longitude}, ${latitude}`;
            return h('div', { class: 'text-left font-medium flex items-center gap-2' }, [h(MapPin, { size: 24 }), location]);
        },
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [trans('dashboard.table.columns.created_at'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row, table }) => {
            const createdAtTZ = DateTime.fromISO(row.original.created_at);
            const createdAt = formatDateTime(table.options.meta?.locale, createdAtTZ.setZone(row.original.timezone).valueOf());
            return h('div', { class: 'text-left font-medium' }, createdAt);
        },
    },
    {
        accessorKey: 'observations_count',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [trans('dashboard.table.columns.count'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-right font-medium' }, row.getValue('observations_count')),
    },
    {
        accessorKey: 'observations_maxdateutc',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [trans('dashboard.table.columns.latest_observation'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
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
                return h('div', { class: 'text-left font-medium flex items-center gap-2' }, [
                    last24Hours === true ? h(BadgeCheck, { size: 24, color: 'green' }) : h(BadgeAlert, { size: 24, color: 'orange' }),
                    latestObservationDateTime,
                ]);
            }
        },
    },
    {
        id: 'actions',
        enableHiding: false,
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
    },
];
