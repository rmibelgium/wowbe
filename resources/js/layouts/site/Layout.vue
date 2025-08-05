<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem, type SharedData, type Site } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const props = defineProps<{
    site: Site;
}>();

const sidebarNavItems = computed<NavItem[]>(() => [
    {
        title: trans('form.menu.edit'),
        href: route('site.edit', { id: props.site.id }),
    },
    {
        title: trans('form.menu.authentication'),
        href: route('site.edit_auth', { id: props.site.id }),
    },
    {
        title: trans('form.menu.delete'),
        href: route('site.delete', { id: props.site.id }),
    },
]);

const page = usePage<SharedData>();

const currentPath = computed(() => (page.props.ziggy?.location ? new URL(page.props.ziggy.location).href : ''));
</script>

<template>
    <div class="px-4 py-6">
        <Heading :title="`Site &quot;${site.name}&quot;`" :description="trans('form.header.update.description')" />

        <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-y-0 lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <nav class="flex flex-col space-y-1 space-x-0">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                        :style="{ color: item.title === 'Delete' ? 'var(--color-destructive)' : '' }"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 md:hidden" />

            <div class="flex-1">
                <slot />
            </div>
        </div>
    </div>
</template>
