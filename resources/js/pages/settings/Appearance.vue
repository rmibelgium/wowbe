<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const breadcrumbItems = computed<BreadcrumbItem[]>(() => [
    {
        title: trans('settings.header.title'),
        href: route('profile.edit'),
    },
    {
        title: trans('settings.appearance.title'),
        href: route('appearance'),
    },
]);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="trans('settings.appearance.title')" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall :title="trans('settings.appearance.title')" :description="trans('settings.appearance.description')" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
