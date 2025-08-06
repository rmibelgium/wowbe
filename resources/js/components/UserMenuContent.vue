<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { langURL } from '@/lib/utils';
import type { User } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Languages, LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" :href="route('profile.edit')" as="button">
                <Settings class="mr-2 h-4 w-4" />
                {{ $t('menu.user.settings') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <a :href="langURL('fr')">
                <Languages class="mr-2 h-4 w-4" />
                Français
            </a>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <a :href="langURL('nl')">
                <Languages class="mr-2 h-4 w-4" />
                Nederlands
            </a>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <a :href="langURL('en')">
                <Languages class="mr-2 h-4 w-4" />
                English
            </a>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link class="block w-full" method="post" :href="route('logout')" as="button">
            <LogOut class="mr-2 h-4 w-4" />
            {{ $t('menu.user.logout') }}
        </Link>
    </DropdownMenuItem>
</template>
