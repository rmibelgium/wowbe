import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    permissions: {
        [key: string]: { [key: string]: boolean | { [key: string]: boolean } };
    };
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface Obervation {
    id: string;
    dateutc: string;
}

export interface Site {
    id: string;
    short_id: string;
    name: string;
    longitude: number;
    latitude: number;
    altitude: number;
    timezone: string;
    website: string | null;
    brand: string | null;
    software: string | null;
    is_official: boolean;
    created_at: string;
    updated_at: string;
    // observations: Obervation[];
    observations_count: number;
    observations_maxdateutc: string | null;
    auth_key: string;
    has_pin_code: boolean;
}

export interface User {
    id: string;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    oauth_provider: string | null;
    oauth_id: string | null;
}

export interface Media {
    name: string;
    file_name: string;
    uuid: string;
    preview_url: string;
    original_url: string;
    order: number | null;
    custom_properties: array;
    extension: string;
    size: int;
}

export type BreadcrumbItemType = BreadcrumbItem;
