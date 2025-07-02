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
    name: string;
    longitude: number;
    latitude: number;
    altitude: number;
    timezone: string;
    created_at: string;
    updated_at: string;
    latest: Obervation[];
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

export type BreadcrumbItemType = BreadcrumbItem;
