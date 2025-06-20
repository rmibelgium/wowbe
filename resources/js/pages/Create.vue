<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import MapLibreLocation from '@/components/MapLibreLocation.vue';
import { Button } from '@/components/ui/button';
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from '@/components/ui/combobox';
import { FormItem } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { PinInput, PinInputGroup, PinInputInput } from '@/components/ui/pin-input';
import Separator from '@/components/ui/separator/Separator.vue';
import { toast } from '@/components/ui/toast';
import Toaster from '@/components/ui/toast/Toaster.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';

const timezones = ['Europe/Amsterdam', 'Europe/Berlin', 'Europe/Brussels'];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Register a site',
        href: '/site',
    },
];

const form = useForm({
    longitude: '',
    latitude: '',
    height: '',
    name: '',
    timezone: '',
    auth_key: [],
});

const submit = () => {
    form.post(route('site.store'), {
        onSuccess: () => {
            toast({
                title: 'Site created',
                description: `The site "" has been created successfully.`,
            });
        },
        // onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const handleLocate = (location: GeoJSON.Position, altitude: number | null = null) => {
    form.longitude = location[0].toFixed(6);
    form.latitude = location[1].toFixed(6);
    form.height = altitude !== null ? altitude.toFixed(0) : '';
};
</script>

<template>
    <Toaster />

    <Head title="Register a site" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <form class="w-2/3 space-y-6" autocomplete="off" @submit.prevent="submit">
                <FormItem>
                    <h3 class="text-lg font-medium">1. Choose a location for this site</h3>
                    <p class="text-muted-foreground text-sm">
                        Please enter either a postcode, location, or lat/lon values, to allow us to position your site on the map. Once you've entered
                        a location, you may click and drag the pin to a more accurate location.
                    </p>
                </FormItem>

                <div class="flex gap-6">
                    <div class="basis-1/3 space-y-6">
                        <FormItem>
                            <Label for="longitude">Longitude</Label>
                            <Input id="longitude" type="number" step="0.000001" required autofocus :tabindex="1" v-model="form.longitude" />
                            <InputError :message="form.errors.longitude" />
                        </FormItem>

                        <FormItem>
                            <Label for="latitude">Latitude</Label>
                            <Input id="latitude" type="number" step="0.000001" required autofocus :tabindex="2" v-model="form.latitude" />
                            <InputError :message="form.errors.latitude" />
                        </FormItem>

                        <FormItem>
                            <Label for="height">Height</Label>
                            <Input id="height" type="number" required autofocus :tabindex="3" v-model="form.height" />
                            <InputError :message="form.errors.height" />
                        </FormItem>
                    </div>
                    <div class="basis-2/3">
                        <MapLibreLocation
                            @locate="handleLocate"
                            :longitude="form.longitude"
                            :latitude="form.latitude"
                        />
                    </div>
                </div>

                <Separator />

                <FormItem>
                    <h3 class="text-lg font-medium">2. Site Details</h3>
                    <p class="text-muted-foreground text-sm">
                        Site name is how others will see your Site on WOW. Timezone is also mandatory - all other fields are optional.
                    </p>
                </FormItem>

                <FormItem>
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="4" autocomplete="name" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </FormItem>

                <FormItem>
                    <Label for="timezone">Timezone</Label>
                    <Combobox v-model="form.timezone">
                        <ComboboxAnchor as-child>
                            <ComboboxTrigger as-child>
                                <Button variant="outline" class="h-10 w-full justify-between">
                                    {{ form.timezone.length > 0 ? form.timezone : 'Select timezone' }}
                                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                </Button>
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxList>
                            <div class="relative w-full max-w-sm items-center">
                                <ComboboxInput
                                    class="h-10 rounded-none border-0 border-b pl-9 focus-visible:ring-0"
                                    placeholder="Select timezone..."
                                />
                                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-3">
                                    <Search class="text-muted-foreground size-4" />
                                </span>
                            </div>

                            <ComboboxEmpty> No timezone found. </ComboboxEmpty>

                            <ComboboxGroup>
                                <ComboboxItem v-for="timezone in timezones" :key="timezone" :value="timezone">
                                    {{ timezone }}

                                    <ComboboxItemIndicator>
                                        <Check :class="cn('ml-auto h-4 w-4')" />
                                    </ComboboxItemIndicator>
                                </ComboboxItem>
                            </ComboboxGroup>
                        </ComboboxList>
                    </Combobox>
                    <InputError :message="form.errors.timezone" />
                </FormItem>

                <FormItem>
                    <Label for="auth_key">Authentication Key</Label>
                    <PinInput id="auth_key" required :tabindex="6" v-model="form.auth_key" placeholder="○">
                        <PinInputGroup>
                            <PinInputInput v-for="(id, index) in 6" :key="id" :index="index" />
                        </PinInputGroup>
                    </PinInput>
                    <InputError :message="form.errors.auth_key" />
                </FormItem>

                <Separator />

                <Button type="submit" :disabled="form.processing"> Submit </Button>
            </form>
        </div>
    </AppLayout>
</template>
