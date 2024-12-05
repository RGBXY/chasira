<template>
    <div
        :class="
            method.sideBarStat ? 'visible opacity-100' : 'invisible opacity-0'
        "
        class="fixed top-0 bg-black backdrop-blur-sm transition-all duration-500 bg-opacity-40 w-full h-screen z-40"
    >
        <div
            :class="method.sideBarStat ? ' left-0' : '-left-1/2'"
            class="h-full w-[25%] bg-white fixed transition-all duration-500"
        >
            <div class="h-[90%]">
                <div
                    class="h-20 min-h-20 border-b flex items-center justify-between gap-2 px-3 py-2"
                >
                    <div
                        class="flex items-center gap-3 bg-gray-100 flex-1 h-full rounded-full px-2"
                    >
                        <div
                            class="w-12 h-12 rounded-full border overflow-hidden"
                        >
                            <img
                                src="https://steamuserimages-a.akamaihd.net/ugc/2001323196949400655/BD3F4F7C7CD1FE1A97C2F676344BF44F4489D6C2/?imw=512&&ima=fit&impolicy=Letterbox&imcolor=%23000000&letterbox=false"
                                alt=""
                            />
                        </div>
                        <div>
                            <h1 class="font-semibold">{{ logedUser.name }}</h1>
                            <p class="text-sm text-gray-500 font-semibold">
                                Owner
                            </p>
                        </div>
                    </div>
                    <button
                        @click="method.sideBarStatFnc()"
                        class="flex items-center justify-center rounded-full w-[60px] hover:bg-red-100 bg-red-50 h-[60px]"
                    >
                        <PhX class="text-2xl text-red-400" weight="bold" />
                    </button>
                </div>

                <div class="mt-5 overflow-y-auto h-[85%] no-scrollbar">
                    <Link
                        v-for="menu in MainMenuConfig"
                        @click="method.sideBarStatFnc()"
                        :href="menu.route"
                        :class="
                            menu.route === page.url
                                ? 'bg-violet-100 text-violet-500 '
                                : 'text-black '
                        "
                        class="flex items-center font-medium gap-3.5 px-3 py-4"
                    >
                        <div
                            :class="
                                menu.route === page.url
                                    ? 'bg-violet-400 '
                                    : 'bg-gray-200 '
                            "
                            class="h-12 w-12 rounded-full flex justify-center items-center"
                        >
                            <component
                                :is="menu.PhIcon"
                                :class="
                                    menu.route === page.url
                                        ? 'text-white'
                                        : 'text-gray-500'
                                "
                                class="text-2xl"
                            />
                        </div>
                        <h1 class="text-lg">{{ menu.heading }}</h1>
                    </Link>
                </div>
            </div>

            <div class="w-full px-3 py-2 h-[10%] bottom-0 absolute border-t">
                <Link
                    @click="method.sideBarStatFnc"
                    as="button"
                    type="button"
                    method="post"
                    href="/logout"
                    class="flex items-center w-full bg-gray-100 border justify-between gap-3 h-full rounded-full ps-5 pe-2"
                >
                    <h1 class="text-xl font-semibold">Log out</h1>
                    <button
                        class="bg-red-400 p-3 text-2xl rounded-full text-white"
                    >
                        <PhSignOut />
                    </button>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { PhSignOut, PhX } from "@phosphor-icons/vue";
import { useMethodStore } from "../../stores/method";
import { Link, usePage } from "@inertiajs/vue3";
import MainMenuConfig from "../../../core/config/MainMenuConfig";

const page = usePage();
const logedUser = page.props.auth.user;
console.log(logedUser);

const method = useMethodStore();
</script>

<style lang="scss" scoped></style>
