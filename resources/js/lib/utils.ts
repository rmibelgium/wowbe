import type { Updater } from '@tanstack/vue-table';
import { type ClassValue, clsx } from 'clsx';
import { marked } from 'marked';
import { twMerge } from 'tailwind-merge';
import type { Ref } from 'vue';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function valueUpdater<T extends Updater<any>>(updaterOrValue: T, ref: Ref) {
    ref.value = typeof updaterOrValue === 'function' ? updaterOrValue(ref.value) : updaterOrValue;
}

export function formatDateTime(locale: Intl.LocalesArgument, datetime: number | string) {
    const date = typeof datetime === 'string' ? new Date(datetime) : datetime;

    return Intl.DateTimeFormat(locale, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    }).format(date);
}

export function langURL(lang: string) {
    const currentRoute = route().current();
    const routeParams = route().params;

    return typeof currentRoute !== 'undefined' ? route(currentRoute, { ...routeParams, lang }) : `?lang=${lang}`;
}

export function markdown(text: string): string {
    if (!text) return '';

    marked.setOptions({
        silent: true,
        async: false,
    });

    const result = marked.parseInline(text);
    return typeof result === 'string' ? result : '';
}
