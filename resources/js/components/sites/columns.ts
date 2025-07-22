import { formatDateTime } from '@/lib/utils';
import { type Site } from '@/types';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown, BadgeAlert, BadgeCheck, BadgeX, MapPin } from 'lucide-vue-next';
import { h } from 'vue';
import Button from '../ui/button/Button.vue';
import DataTableDropdown from './DataTableDropdown.vue';

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
                () => ['ID', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('id')),
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
                () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-left font-medium' }, row.getValue('name')),
    },
    {
        id: 'location',
        header: () => h('div', { class: 'text-left' }, 'Location'),
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
                () => ['Created at', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => {
            const createdAt = formatDateTime(row.getValue('created_at'));
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
                () => ['Count', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
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
                () => ['Latest observation', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => {
            if (row.original.observations_count === 0 || row.original.observations_maxdateutc === null) {
                return h('div', { class: 'text-left font-bold text-gray-500 flex items-center gap-2' }, [h(BadgeX, { size: 24 }), 'No observation']);
            } else {
                const latestObservationDateTime = formatDateTime(row.original.observations_maxdateutc);
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
