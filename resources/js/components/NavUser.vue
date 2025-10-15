<script setup lang="ts">
import { DropdownMenu } from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

const page = usePage();
const user = page.props.auth.user as User;
const { isMobile, state } = useSidebar();
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <Link class="block w-full bg-[#FDF0EC] hover:bg-[#FDF0EC] text-[#81290E] font-medium px-4 py-2 rounded-md transition duration-200"
                   method="post" :href="route('logout')" @click="handleLogout" as="button">
                   <i class="fa-solid fa-right-from-bracket"></i> Log out
                </Link>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
