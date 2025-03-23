<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import Map from '@/components/Map.vue';
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet'
import { ref } from 'vue';
import { GeoJSONFeature, latest } from 'maplibre-gl';
import { SharedData } from '@/types';

const sheetOpen = ref(false);
const sheetTitle = ref('');
const sheetDescription = ref('');
const latestReading = ref(null);

const handleFeatureClick = (feature: GeoJSONFeature) => {
  sheetTitle.value = feature.properties.name;
  sheetDescription.value = new Date(feature.properties.timestamp).toLocaleString();

  latestReading.value = JSON.parse(feature.properties.primary);

  sheetOpen.value = true;
};

</script>

<template>

    <Head title="WOW-BE" />
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                    Log in
                    </Link>
                    <Link :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Register
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full overflow-hidden rounded-lg">
                <Map @feature-click="handleFeatureClick" />
            </main>
        </div>
    </div> 
    <Sheet :open="sheetOpen" @update:open="sheetOpen = $event">
        <SheetContent side="left">
            <SheetHeader>
                <SheetTitle>{{ sheetTitle }}</SheetTitle>
                <SheetDescription>{{ sheetDescription }}</SheetDescription>
                <section v-if="latestReading">
                    <ul>
                        <li>Temperature: {{ latestReading.dt }}°C</li>
                        <li>Wind speed: {{ latestReading.dws }}m/s</li>
                        <li>Wind direction: {{ latestReading.dwd }}°</li>
                        <li>Humidity: {{ latestReading.dh }}%</li>
                        <li>Pressure: {{ latestReading.dm }}hPa</li>
                    </ul>
                </section>
            </SheetHeader>
        </SheetContent>
    </Sheet>
</template>
