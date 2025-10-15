<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
                    <Link :href="item.href" :class="[
                        'flex items-center gap-2 rounded-md px-3 py-2 transition-colors duration-200',
                        item.href === page.url
                            ? 'bg-[#2563DC] text-white'
                            : 'text-[#14367B] hover:bg-[#14367B]/10 hover:text-[#14367B]'
                        ]">
                        <component :is="item.icon" />
                        <span class="text-[#14367B]">{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
