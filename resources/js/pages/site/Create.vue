<script setup lang="ts">
import Form from '@/components/site/Form.vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

defineProps<{
    timezones: string[];
    defaultTimezone: string;
}>();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: trans('form.title.register'),
        href: route('site.create'),
    },
]);
</script>

<template>
    <Toaster />

    <Head :title="trans('form.title.register')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <Form :timezones="timezones" :default-timezone="defaultTimezone" />
        </div>
    </AppLayout>
</template>
