<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDateTime } from '@/lib/utils';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Badge, BadgeAlert, BadgeCheck, MapPin } from 'lucide-vue-next';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<SharedData>();
// const user = page.props.auth.user as User;
const sites = page.props.sites as Site[];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <Table>
                    <!-- <TableCaption>A list of your sites.</TableCaption> -->
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Location</TableHead>
                            <TableHead>Created at</TableHead>
                            <TableHead>Latest reading</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="site in sites" :key="site.id">
                            <TableCell>{{ site.id }}</TableCell>
                            <TableCell>{{ site.name }}</TableCell>
                            <TableCell class="flex items-center gap-2">
                                <MapPin />
                                {{ site.longitude }}, {{ site.latitude }}
                            </TableCell>
                            <TableCell>{{ formatDateTime(site.created_at) }}</TableCell>
                            <TableCell>
                                <span v-if="site.latest.length === 0" class="flex items-center gap-2 font-bold text-gray-500">
                                    <Badge />
                                    No reading
                                </span>
                                <span
                                    v-else-if="new Date(site.latest[0].dateutc) < new Date().setHours(new Date().getHours() - 24)"
                                    class="flex items-center gap-2"
                                >
                                    <BadgeAlert color="orange" />
                                    {{ formatDateTime(site.latest[0].dateutc) }}
                                </span>
                                <span v-else class="flex items-center gap-2">
                                    <BadgeCheck color="green" />
                                    {{ formatDateTime(site.latest[0].dateutc) }}
                                </span>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
