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
                <SidebarMenuButton as-child :is-active="page.url.startsWith(item.href)" :tooltip="item.title" class="pt-2">
                    <Link :href="item.href" :class="[
                        'flex items-center gap-2 rounded-md pt-2 px-3 py-2 transition-colors',
                        page.url.startsWith(item.href)
                            ? 'bg-[#2563DC] text-white'
                            : 'bg-[#EEF2FC] text-[#14367B] hover:bg-[#14367B]/10 hover:text-white'
                        ]">
                       <i :class="[item.icon, page.url.startsWith(item.href) ? 'text-white' : 'text-[#14367B]']"></i>
                        
                        <span :class="[page.url.startsWith(item.href) ? 'text-white' : 'text-[#14367B]']">
                            {{ item.title }}
                        </span>    
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
