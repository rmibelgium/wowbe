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
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import { PinInput, PinInputGroup, PinInputInput } from '@/components/ui/pin-input';
import AppLayout from '@/layouts/AppLayout.vue';
import SiteLayout from '@/layouts/site/Layout.vue';
import { type BreadcrumbItem, type Site } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const props = defineProps<{
    site: Site;
}>();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: trans('dashboard.title'),
        href: route('dashboard'),
    },
    {
        title: `Site "${props.site.name}"`,
        href: route('site.edit', { site: props.site.id }),
    },
    {
        title: trans('form.header.delete.title'),
        href: route('site.delete', { site: props.site.id }),
    },
]);

const form = useForm({
    auth_key: null as string | string[] | null,
});

const deleteSite = (e: Event) => {
    e.preventDefault();

    form.delete(route('site.destroy', { id: props.site.id }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
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
        <Head :title="trans('form.header.delete.title')" />

        <SiteLayout :site="site">
            <div class="flex flex-col space-y-6">
                <HeadingSmall :title="trans('form.header.delete.title')" :description="trans('form.header.delete.description')" />

                <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
                    <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                        <p class="font-medium">{{ trans('form.delete.warning.title') }}</p>
                        <p class="text-sm">{{ trans('form.delete.warning.description') }}</p>
                    </div>
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="destructive">{{ trans('form.action.delete') }}</Button>
                        </DialogTrigger>
                        <DialogContent>
                            <form autocomplete="off" class="space-y-6" @submit="deleteSite">
                                <DialogHeader class="space-y-3">
                                    <DialogTitle>{{ trans('form.delete.dialog.title') }}</DialogTitle>
                                    <DialogDescription>{{ trans('form.delete.dialog.description') }}</DialogDescription>
                                </DialogHeader>

                                <div class="grid gap-2">
                                    <Label for="auth_key">{{ trans('form.menu.authentication') }}</Label>
                                    <PinInput
                                        v-if="site.has_pin_code"
                                        id="auth_key"
                                        required
                                        autofocus
                                        placeholder="○"
                                        v-model="form.auth_key as string[] | null"
                                    >
                                        <PinInputGroup>
                                            <PinInputInput v-for="(id, index) in 6" :key="id" :index="index" />
                                        </PinInputGroup>
                                    </PinInput>
                                    <Input v-else id="auth_key" type="text" required autofocus v-model="form.auth_key as string" />
                                    <InputError :message="form.errors.auth_key" />
                                </div>

                                <DialogFooter class="gap-2">
                                    <DialogClose as-child>
                                        <Button variant="secondary" @click="closeModal">{{ trans('form.action.cancel') }}</Button>
                                    </DialogClose>

                                    <Button variant="destructive" :disabled="form.processing">
                                        <button type="submit">{{ trans('form.action.delete') }}</button>
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
