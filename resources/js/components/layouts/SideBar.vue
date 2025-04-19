<template>
  <div
    :class="method.sideBarStat ? 'w-[250px]' : 'lg:w-20 lg:left-0 -left-1/2'"
    class="fixed top-0 border-r h-screen lg:z-40 transition-all bg-white"
  >
    <div class="h-full w-full z-50 transition-all">
      <div class="h-[90%]">
        <div
          class="h-20 overflow-hidden min-h-20 border-b flex transition-all items-center justify-between gap-2 py-2"
        >
          <div class="flex items-center gap-3 flex-1 h-full px-3">
            <div class="w-12 h-12 rounded-xl border overflow-hidden">
              <img
                :src="'/assets/image/logo.png'"
                class="w-full h-full"
                alt=""
              />
              <!-- <img
                                :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=4e73df&color=ffffff&size=100`"
                                alt=""
                            /> -->
            </div>
            <transition
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="opacity-0 transform duration-300 -translate-x-4"
              enter-to-class="opacity-100 transform translate-x-0"
              leave-active-class="transition-all  ease-in"
              leave-from-class="opacity-100 transform translate-x-0"
              leave-to-class="opacity-0 transform -translate-x-4"
            >
              <div
                v-if="method.sideBarStat"
                class="whitespace-nowrap overflow-hidden"
              >
                <h1 class="font-semibold">
                  {{ logedUser.name }}
                </h1>
                <p class="text-sm text-gray-500">
                  {{ logedUser.roles[0].name }}
                </p>
              </div>
            </transition>
          </div>
        </div>

        <div
          :class="method.sideBarStat ? 'h-[100%]' : 'h-full '"
          class="overflow-y-auto no-scrollbar px-3 divide-y"
        >
          <div
            v-for="menu in filteredMenu"
            :key="menu.heading"
            :class="method.sideBarStat ? 'py-4' : 'py-2'"
            class=""
          >
            <p
              v-if="method.sideBarStat"
              class="font-bold uppercase text-sm ps-4 mb-2"
            >
              {{ menu.heading }}
            </p>

            <Link
              v-for="sub in menu.pages"
              :href="sub.route"
              @click="handleLinkClick"
              :class="[
                sub.route === page.url
                  ? 'bg-violet-50 text-violet-500'
                  : 'text-gray-500',
              ]"
              class="flex items-center gap-3 px-3 py-2 rounded-lg"
            >
              <div
                class="flex-shrink-0 w-8 h-8 flex items-center justify-center"
              >
                <Icon
                  :icon="sub.icon"
                  :ssr="true"
                  :class="[
                    sub.route === page.url ? 'text-primary' : 'text-gray-500',
                  ]"
                  class="text-2xl"
                />
              </div>

              <transition
                enter-active-class="transition-all ease-out"
                enter-from-class="opacity-0 transform -translate-x-4"
                enter-to-class="opacity-100 transform translate-x-0"
                leave-active-class="transition-all ease-in"
                leave-from-class="opacity-100 transform translate-x-0"
                leave-to-class="opacity-0 transform -translate-x-4"
              >
                <h1
                  v-if="method.sideBarStat"
                  class="font-semibold text-sm whitespace-nowrap"
                >
                  {{ sub.heading }}
                </h1>
              </transition>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMethodStore } from '../../stores/method';
import { Link, usePage } from '@inertiajs/vue3';
import MainMenuConfig from '../../../core/config/MainMenuConfig';
import { Icon } from '@iconify/vue/dist/iconify.js';

const page = usePage();
const logedUser = page.props.auth.user;

const permissions = page.props.auth.permissions;

const filteredMenu = MainMenuConfig.map((menu) => {
  // Create a new menu object with filtered pages
  return {
    ...menu,
    pages: menu.pages.filter((page) => permissions[page.permissions]),
  };
}).filter((menu) => menu.pages.length > 0); // Remove menu sections with no accessible pages

const method = useMethodStore();

function handleLinkClick() {
  // Deteksi jika layar kecil (mobile)
  if (window.innerWidth < 1024) {
    method.sideBarStatFnc();
  }
}
</script>

<style lang="scss" scoped></style>
