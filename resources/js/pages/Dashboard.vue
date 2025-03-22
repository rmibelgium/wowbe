<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import { Badge, BadgeAlert, BadgeCheck, MapPin } from 'lucide-vue-next';
import { formatDateTime } from '@/lib/utils';
import { now } from '@vueuse/core';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<SharedData>();
// const user = page.props.auth.user as User;
const sites = page.props.sites as Site[];

console.log(sites);

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
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
                            <TableCell class="flex gap-2 items-center">
                                <MapPin />
                                {{ site.longitude }}, {{ site.latitude }}
                            </TableCell>
                            <TableCell>{{ formatDateTime(site.created_at) }}</TableCell>
                            <TableCell>
                                <span v-if="site.latest.length === 0" class="flex gap-2 items-center text-gray-500 font-bold">
                                    <Badge />
                                    No reading
                                </span>
                                <span v-else-if="new Date(site.latest[0].dateutc) < (new Date().setHours(new Date().getHours() - 24))" class="flex gap-2 items-center">
                                    <BadgeAlert color="orange" />
                                    {{ formatDateTime(site.latest[0].dateutc) }}
                                </span>
                                <span v-else class="flex gap-2 items-center">
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
