<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';

interface Props {
    oAuthProvider?: string;
}

const { oAuthProvider } = defineProps<Props>();

const form = useForm({
    password: '',
    delete_data: false,
});

const deleteUser = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
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
    <div class="space-y-6">
        <HeadingSmall :title="$t('settings.profile.delete.title')" :description="$t('settings.profile.delete.description')" />

        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">{{ $t('settings.profile.delete.warning.title') }}</p>
                <p class="text-sm">{{ $t('settings.profile.delete.warning.description') }}</p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive">{{ $t('settings.profile.delete.action.delete') }}</Button>
                </DialogTrigger>
                <DialogContent>
                    <form class="space-y-6" @submit="deleteUser">
                        <DialogHeader class="space-y-3">
                            <DialogTitle>{{ $t('settings.profile.delete.dialog.title') }}</DialogTitle>
                            <DialogDescription>{{ $t('settings.profile.delete.dialog.description') }}</DialogDescription>
                        </DialogHeader>

                        <div v-if="oAuthProvider === null" class="grid gap-2">
                            <Label for="password" class="sr-only">{{ $t('settings.profile.delete.dialog.password') }}</Label>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                v-model="form.password"
                                :placeholder="$t('settings.profile.delete.dialog.password')"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="items-top flex gap-x-2">
                            <Checkbox id="delete_data" v-model:checked="form.delete_data" />
                            <div class="grid gap-1.5 leading-none">
                                <label
                                    for="delete_data"
                                    class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    {{ $t('settings.profile.delete.dialog.delete_data.title') }}
                                </label>
                                <p class="text-muted-foreground text-sm">{{ $t('settings.profile.delete.dialog.delete_data.description') }}</p>
                            </div>
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" @click="closeModal">{{ $t('settings.profile.delete.dialog.action.cancel') }}</Button>
                            </DialogClose>

                            <Button variant="destructive" :disabled="form.processing">
                                <button type="submit">{{ $t('settings.profile.delete.dialog.action.delete') }}</button>
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
