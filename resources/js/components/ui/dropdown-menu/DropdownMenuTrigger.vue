<script setup lang="ts">
import { DropdownMenuTrigger, useForwardProps, type DropdownMenuTriggerProps } from 'radix-vue';

const props = defineProps<DropdownMenuTriggerProps>();

const forwardedProps = useForwardProps(props);
</script>

<template>
    <DropdownMenuTrigger class="outline-hidden" v-bind="forwardedProps">
        <slot />
    </DropdownMenuTrigger>
</template>
