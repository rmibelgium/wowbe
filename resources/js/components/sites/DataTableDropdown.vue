<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Link } from '@inertiajs/vue3';
import { MoreHorizontal } from 'lucide-vue-next';

defineProps<{
    site: {
        id: string;
    };
}>();

function copy(id: string) {
    navigator.clipboard.writeText(id);
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem @click="copy(site.id)">Copy site ID</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem as-child>
                <Link :href="route('site.edit', { id: site.id })">Edit</Link>
            </DropdownMenuItem>
            <DropdownMenuItem as-child :style="{ color: 'var(--color-destructive)' }">
                <Link :href="route('site.delete', { id: site.id })">Delete</Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
