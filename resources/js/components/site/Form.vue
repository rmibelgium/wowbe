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
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { cn } from '@/lib/utils';
import { type Site } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';

const { toast } = useToast();

const timezones = ['Europe/Amsterdam', 'Europe/Berlin', 'Europe/Brussels'];

const props = defineProps<{
    site?: Site;
}>();

const form = useForm({
    longitude: props.site?.longitude || '',
    latitude: props.site?.latitude || '',
    altitude: props.site?.altitude || '',
    name: props.site?.name || '',
    timezone: props.site?.timezone || 'Europe/Brussels',
    pincode: props.site && props.site.has_pin_code == true ? props.site.auth_key.split('') : null /*Math.random().toString().slice(2, 8).split('')*/,
    password: props.site && props.site.has_pin_code != true ? props.site.auth_key : '',
});

const submit = () => {
    if (props.site) {
        form.patch(route('site.update', { id: props.site.id }), {
            onSuccess: () => {
                toast({
                    title: 'Site updated',
                    description: `The site "${form.name}" has been updated successfully.`,
                });
            },
        });
    } else {
        form.post(route('site.store'), {
            onSuccess: () => {
                toast({
                    title: 'Site created',
                    description: `The site "${form.name}" has been created successfully.`,
                });
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
            <Input id="name" type="text" required autocomplete="name" v-model="form.name" />
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
                        <ComboboxInput class="h-10 rounded-none border-0 border-b pl-9 focus-visible:ring-0" placeholder="Select timezone..." />
                        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-3">
                            <Search class="text-muted-foreground size-4" />
                        </span>
                    </div>

                    <ComboboxEmpty>No timezone found.</ComboboxEmpty>

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

        <template v-if="!site">
            <Separator />

            <FormItem>
                <h3 class="text-lg font-medium">3. Site authentication</h3>
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
