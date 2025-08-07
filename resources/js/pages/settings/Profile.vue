<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems = computed<BreadcrumbItem[]>(() => [
    {
        title: trans('settings.header.title'),
        href: route('profile.edit'),
    },
    {
        title: trans('settings.profile.title'),
        href: route('profile.edit'),
    },
]);

const page = usePage<SharedData>();
const user = computed(() => page.props.auth.user as User);
const canUpdateProfile = computed(() => page.props.auth?.permissions?.settings?.profile?.update ?? false);

let oAuthProvider: string | null = null;
switch (user.value.oauth_provider) {
    case 'github':
        oAuthProvider = 'GitHub';
        break;
    case 'google':
        oAuthProvider = 'Google';
        break;
}

const form = useForm({
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="trans('settings.profile.title')" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    :title="trans('settings.profile.form.title')"
                    :description="canUpdateProfile ? trans('settings.profile.form.description') : trans('settings.profile.form.description_oauth')"
                />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">{{ $t('settings.profile.form.name') }}</Label>
                        <Input
                            id="name"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autocomplete="name"
                            :placeholder="trans('settings.profile.form.name')"
                            :disabled="canUpdateProfile !== true"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">{{ $t('settings.profile.form.email') }}</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            :placeholder="trans('settings.profile.form.email')"
                            :disabled="canUpdateProfile !== true"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="text-muted-foreground -mt-4 text-sm">
                            {{ trans('settings.profile.form.email_unverified') }}
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                {{ trans('settings.profile.form.resend_verification_email') }}
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            {{ trans('settings.profile.form.verification_link_sent') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="canUpdateProfile !== true || form.processing">{{ trans('settings.profile.form.action.save') }}</Button>

                        <p v-if="canUpdateProfile !== true" class="text-sm text-neutral-600">
                            {{ trans('settings.profile.form.oauth_linked', { provider: oAuthProvider }) }}
                        </p>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">{{ trans('settings.profile.form.success') }}</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
