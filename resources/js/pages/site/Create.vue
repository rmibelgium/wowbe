<script setup lang="ts">
import Form from '@/components/site/Form.vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{
    timezones: string[];
    defaultTimezone: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Register a site',
        href: route('site.create'),
    },
];
</script>

<template>
    <Toaster />

    <Head title="Register a site" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <Form :timezones="timezones" :default-timezone="defaultTimezone" />
        </div>
    </AppLayout>
</template>
