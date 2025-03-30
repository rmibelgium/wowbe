<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue';
// import { type BreadcrumbItem } from '@/types';
// import { Head } from '@inertiajs/vue3';
// import PlaceholderPattern from '../components/PlaceholderPattern.vue';
// import { Button } from '@/components/ui/button'
import { FormItem } from '@/components/ui/form';
// import { Input } from '@/components/ui/input'
// import { toast } from '@/components/ui/toast'

// import { toTypedSchema } from '@vee-validate/zod'
// import { useForm } from 'vee-validate'
// import { h } from 'vue'
// import * as z from 'zod'
// import Toaster from '@/components/ui/toast/Toaster.vue'
// import { router } from '@inertiajs/vue3'

import InputError from '@/components/InputError.vue';
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
        onSuccess: (values) => {
            console.log(values.rememberedState);
            toast({
                title: 'Site created',
                description: `The site "" has been created successfully.`,
            });
        },
        // onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

// const formSchema = toTypedSchema(z.object({
//     longitude: z.number().min(-180).max(180),
//     latitude: z.number().min(-90).max(90),
//     height: z.number().int().min(0),
//     name: z.string(),
//     timezone: z.string(),
//     auth_key: z.string().length(6),
// }))

// const { isFieldDirty, handleSubmit } = useForm({
//     validationSchema: formSchema,
// })

// const onSubmit = handleSubmit((values) => {
//     console.log(values);
//     router.post(route('api.site.store'), values, {
//         headers: {
//             'Content-Type': 'application/json',
//         },
//         onSuccess: (site) => {
//             console.log(site)
//             toast({
//                 title: 'Site created',
//                 description: `The site "" has been created successfully.`,
//             })
//         },
//     })
// })
</script>

<template>
    <Toaster />

    <Head title="Register a site" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <form class="w-2/3 space-y-6" autocomplete="off" @submit.prevent="submit">
                <FormItem>
                    <h3 class="text-lg font-medium">1. Choose a location for this site</h3>
                    <p class="text-sm text-muted-foreground">
                        Please enter either a postcode, location, or lat/lon values, to allow us to position your site on the map. Once you've entered
                        a location, you may click and drag the pin to a more accurate location.
                    </p>
                </FormItem>

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

                <Separator />

                <FormItem>
                    <h3 class="text-lg font-medium">2. Site Details</h3>
                    <p class="text-sm text-muted-foreground">
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
                                    <Search class="size-4 text-muted-foreground" />
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

                <!--

                <FormField v-slot="{ componentField }" name="longitude" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Longitude</FormLabel>
                        <FormControl>
                            <Input type="number" step="0.000001" v-bind="componentField" />
                        </FormControl>
                        <FormDescription>
                            This is your public display name.
                        </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="latitude" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Latitude</FormLabel>
                        <FormControl>
                            <Input type="number" step="0.000001" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="height" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Height above sea level (Metres)</FormLabel>
                        <FormControl>
                            <Input type="number" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Separator />

                <div>
                    <h3 class="text-lg font-medium">2. Site Details</h3>
                    <p class="text-sm text-muted-foreground">Please enter either a postcode, location, or lat/lon
                        values, to allow us to position your site on the map. Once you've entered a location, you may
                        click and drag the pin to a more accurate location.</p>
                </div>

                <FormField v-slot="{ componentField }" name="name" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Site Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="timezone" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Site TimeZone</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="auth_key" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Authentication Key</FormLabel>
                        <FormControl>
                            <Input type="text" minlength="6" maxlength="6" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Separator />

                <div>
                    <h3 class="text-lg font-medium">3. Site Owner Details</h3>
                    <p class="text-sm text-muted-foreground">Here you may provide some details about the site owner, such as the organisation they belong to or if their Site has a website.</p>
                </div>

                <FormField v-slot="{ componentField }" name="website" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Add related website to your observation</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="organization" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Org/School Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="reason" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Reasons for running the site?</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="access" :validate-on-blur="!isFieldDirty">
                    <FormItem v-auto-animate>
                        <FormLabel>Default Access Level</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Separator />

                <div>
                    <h3 class="text-lg font-medium">4. Site Data Preferences</h3>
                    <p class="text-sm text-muted-foreground">Please describe the location attributes of the observation equipment at this site.</p>
                </div>

                <div data-orientation="horizontal" role="separator" class="shrink-0 bg-border relative h-px w-full my-6"></div>

                <div>
                    <h3 class="text-lg font-medium">5. Additional Information</h3>
                    <p class="text-sm text-muted-foreground">Please enter any additional information about this site and its equipment (maximum 500 characters)</p>
                </div>

                -->

                <Separator />

                <Button type="submit" :disabled="form.processing"> Submit </Button>
            </form>
        </div>
    </AppLayout>
</template>
