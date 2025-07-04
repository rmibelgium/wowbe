<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import MapLibreLocation from '@/components/MapLibreLocation.vue';
import { Button } from '@/components/ui/button';
import { FormItem } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { PinInput, PinInputGroup, PinInputInput } from '@/components/ui/pin-input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Separator from '@/components/ui/separator/Separator.vue';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { type Media, type Site } from '@/types';
import { useForm } from '@inertiajs/vue3';

const { toast } = useToast();

const props = defineProps<{
    timezones: string[];
    defaultTimezone?: string;
    site?: Site;
    pictures?: Media[];
}>();

const form = useForm({
    longitude: props.site?.longitude || '',
    latitude: props.site?.latitude || '',
    altitude: props.site?.altitude || '',
    name: props.site?.name || '',
    timezone: props.site?.timezone || props.defaultTimezone,
    website: props.site?.website || '',
    brand: props.site?.brand || '',
    software: props.site?.software || '',
    picture_add: null as File | null,
    picture_remove: [] as string[],
    pincode: null /*Math.random().toString().slice(2, 8).split('')*/,
    password: '',
});

const submit = () => {
    if (props.site) {
        form.post(route('site.update', { id: props.site.id }), {
            forceFormData: true,
            onSuccess: () => {
                toast({
                    title: 'Site updated',
                    description: `The site "${form.name}" has been updated successfully.`,
                });

                form.picture_add = null; // Reset picture_add after successful update
                form.picture_remove = []; // Reset picture_remove after successful update
            },
        });
    } else {
        form.post(route('site.store'), {
            forceFormData: true,
            onSuccess: () => {
                toast({
                    title: 'Site created',
                    description: `The site "${form.name}" has been created successfully.`,
                });

                form.picture_add = null; // Reset picture_add after successful update
            },
        });
    }
};

const handleLocate = (location: GeoJSON.Position, altitude: number | null = null) => {
    form.longitude = location[0].toFixed(6);
    form.latitude = location[1].toFixed(6);

    // If altitude is provided, set it; otherwise, use OpenElevation API
    if (altitude !== null) {
        form.altitude = altitude.toFixed(0);
    } else {
        fetch(`https://api.open-elevation.com/api/v1/lookup?locations=${form.latitude},${form.longitude}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.results && data.results.length > 0) {
                    form.altitude = data.results[0].elevation.toFixed(0);
                } else {
                    form.altitude = '';
                }
            })
            .catch(() => {
                form.altitude = '';
            });
    }
};

const removeMedia = (media: Media) => {
    if (form.picture_remove.includes(media.uuid)) {
        return;
    }

    if (confirm('Are you sure you want to delete this image?')) {
        form.picture_remove.push(media.uuid);
    }
};
</script>

<template>
    <form class="w-2/3 space-y-6" autocomplete="off" @submit.prevent="submit">
        <FormItem>
            <h3 class="text-lg font-medium">1. Site location</h3>
            <p class="text-muted-foreground text-sm">
                Please enter either a postcode, location, or lat/lon values, to allow us to position your site on the map. Once you've entered a
                location, you may click and drag the pin to a more accurate location.
            </p>
        </FormItem>

        <div class="flex gap-6">
            <div class="basis-1/3 space-y-6">
                <FormItem>
                    <Label for="longitude">Longitude</Label>
                    <Input id="longitude" type="number" step="0.000001" required v-model="form.longitude" />
                    <InputError :message="form.errors.longitude" />
                </FormItem>

                <FormItem>
                    <Label for="latitude">Latitude</Label>
                    <Input id="latitude" type="number" step="0.000001" required v-model="form.latitude" />
                    <InputError :message="form.errors.latitude" />
                </FormItem>

                <FormItem>
                    <Label for="altitude">Altitude</Label>
                    <Input id="altitude" type="number" required v-model="form.altitude" />
                    <InputError :message="form.errors.altitude" />
                </FormItem>
            </div>
            <div class="basis-2/3">
                <MapLibreLocation @locate="handleLocate" :longitude="form.longitude" :latitude="form.latitude" />
            </div>
        </div>

        <Separator />

        <FormItem>
            <h3 class="text-lg font-medium">2. Site details</h3>
            <p class="text-muted-foreground text-sm">
                Site name is how others will see your Site on WOW. Timezone is also mandatory - all other fields are optional.
            </p>
        </FormItem>

        <FormItem>
            <Label for="name">Name</Label>
            <Input id="name" type="text" required v-model="form.name" />
            <InputError :message="form.errors.name" />
        </FormItem>

        <FormItem>
            <Label for="timezone">Timezone</Label>
            <Select v-model="form.timezone" id="timezone">
                <SelectTrigger class="w-full">
                    <SelectValue placeholder="Select a timezone" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="(timezone, index) in timezones" :key="index" :value="timezone">{{ timezone }}</SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.timezone" />
        </FormItem>

        <FormItem>
            <Label for="website">Website</Label>
            <Input id="website" type="url" v-model="form.website" />
            <InputError :message="form.errors.website" />
        </FormItem>

        <FormItem>
            <Label for="brand">Brand</Label>
            <Input id="brand" type="string" v-model="form.brand" />
            <InputError :message="form.errors.brand" />
        </FormItem>

        <FormItem>
            <Label for="software">Software</Label>
            <Input id="software" type="string" v-model="form.software" />
            <InputError :message="form.errors.software" />
        </FormItem>

        <Separator />

        <FormItem>
            <h3 class="text-lg font-medium">3. Site picture</h3>
            <p class="text-muted-foreground text-sm">
                You can upload a picture for your site.<br />
                The picture should be in JPG or PNG format and should not exceed 5MB in size.
            </p>
        </FormItem>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <div v-for="media in props.pictures" :key="media.uuid" class="relative">
                <img
                    :src="media.preview_url.length > 0 ? media.preview_url : media.original_url"
                    :alt="media.name"
                    class="h-auto w-full rounded-md border-2"
                />
                <div v-if="form.picture_remove.includes(media.uuid)" class="absolute inset-0 rounded-md bg-red-900/75 transition"></div>
                <button
                    type="button"
                    class="bg-opacity-50 absolute top-0 right-0 rounded-tr-md rounded-bl-md border-2 bg-white px-2 py-1 text-xs text-red-500 hover:text-red-700 dark:bg-black"
                    @click="removeMedia(media)"
                    title="Remove this picture"
                >
                    &times;
                </button>
            </div>
        </div>
        <InputError :message="form.errors.picture_remove" />

        <FormItem>
            <Label for="picture">Add picture</Label>
            <Input id="picture" type="file" accept="image/jpeg,image/png" @input="form.picture_add = $event.target.files[0]" />
            <InputError :message="form.errors.picture_add" />
        </FormItem>

        <template v-if="!site">
            <Separator />

            <FormItem>
                <h3 class="text-lg font-medium">4. Site authentication</h3>
                <p class="text-muted-foreground text-sm">
                    You need to define an authentication key for your site. This key is used to authenticate your site while sending observations.<br />
                    You can use a PIN code or a password, but not both at the same time. If you want to change your authentication key later, you can
                    do so.
                </p>
            </FormItem>

            <div class="flex items-center">
                <FormItem class="grow-1">
                    <Label for="auth_key">Authentication Key</Label>
                    <PinInput id="auth_key_pincode" v-model="form.pincode" placeholder="○">
                        <PinInputGroup>
                            <PinInputInput v-for="(id, index) in 6" :key="id" :index="index" />
                        </PinInputGroup>
                    </PinInput>
                    <p class="text-muted-foreground text-sm">Set a 6 digits PIN code as authentication key.</p>
                    <InputError :message="form.errors.pincode" />
                </FormItem>

                <div class="grow-2 text-center">OR</div>

                <FormItem class="grow-1">
                    <Label for="auth_key">Authentication Key</Label>
                    <Input id="auth_key_password" type="text" v-model="form.password" />
                    <p class="text-muted-foreground text-sm">Set a password as authentication key.</p>
                    <InputError :message="form.errors.password" />
                </FormItem>
            </div>
        </template>

        <Button type="submit" :disabled="form.processing">
            {{ site ? 'Save' : 'Submit' }}
        </Button>
    </form>

    <Toaster />
</template>
