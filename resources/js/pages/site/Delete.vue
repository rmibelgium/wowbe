<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import Label from '@/components/ui/label/Label.vue';
import { PinInput, PinInputGroup, PinInputInput } from '@/components/ui/pin-input';
import AppLayout from '@/layouts/AppLayout.vue';
import SiteLayout from '@/layouts/site/Layout.vue';
import { type BreadcrumbItem, type Site } from '@/types';
import { ref } from 'vue';

const props = defineProps<{
    site: Site;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: `Site "${props.site.name}"`,
        href: route('site.edit', { site: props.site.id }),
    },
    {
        title: 'Delete your site',
        href: route('site.delete', { site: props.site.id }),
    },
];

const authKeyInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    auth_key: [],
});

const deleteSite = (e: Event) => {
    e.preventDefault();

    form.delete(route('site.destroy', { id: props.site.id }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        // onError: () => authKeyInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Delete your site" />

        <SiteLayout :site="props.site">
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Delete your site" description="Delete your site and all of its observations" />

                <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
                    <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                        <p class="font-medium">Warning</p>
                        <p class="text-sm">Please proceed with caution, this cannot be undone.</p>
                    </div>
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="destructive">Delete site</Button>
                        </DialogTrigger>
                        <DialogContent>
                            <form class="space-y-6" @submit="deleteSite">
                                <DialogHeader class="space-y-3">
                                    <DialogTitle>Are you sure you want to delete your site?</DialogTitle>
                                    <DialogDescription>
                                        Once your site is deleted, all of its data will also be permanently deleted. Please type the authentication
                                        key of your site to confirm you would like to permanently delete it.
                                    </DialogDescription>
                                </DialogHeader>

                                <div class="grid gap-2">
                                    <Label for="auth_key">Authentication Key</Label>
                                    <PinInput id="auth_key" required :tabindex="6" ref="authKeyInput" v-model="form.auth_key" placeholder="○">
                                        <PinInputGroup>
                                            <PinInputInput v-for="(id, index) in 6" :key="id" :index="index" />
                                        </PinInputGroup>
                                    </PinInput>
                                    <InputError :message="form.errors.auth_key" />
                                </div>

                                <DialogFooter class="gap-2">
                                    <DialogClose as-child>
                                        <Button variant="secondary" @click="closeModal">Cancel</Button>
                                    </DialogClose>

                                    <Button variant="destructive" :disabled="form.processing">
                                        <button type="submit">Delete site</button>
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>
        </SiteLayout>
    </AppLayout>
</template>
