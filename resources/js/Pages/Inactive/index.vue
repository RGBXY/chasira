<template>
  <div class="w-full h-screen flex items-center justify-center">
    <div class="w-full h-full max-w-2xl flex flex-col items-center px-">
      <div class="flex items-center bg-orange-500 justify-between w-full px-7">
        <div class="py-4 my-5 text-white w-full">
          <h1 class="text-3xl font-semibold mb-1">
            Your Account Status is Inactive
          </h1>
          <p class="mb-2.5">
            You cannot proceed further. You may log out or contact us for
            assistance.
          </p>
          <div class="flex items-center gap-2 border-white border-t pt-4">
            <Link
              as="button"
              type="button"
              method="post"
              href="/logout"
              class="bg-white text-sm flex items-center gap-2 text-orange-500 font-semibold py-1 px-3 rounded-md"
            >
              <h1 class="font-semibold">Log out</h1>
              <button class="text-lg">
                <PhSignOut />
              </button>
            </Link>
            <a
              href="mailto:derrenamadeo@gmail.com"
              class="bg-white text-sm flex items-center gap-2 text-orange-500 font-semibold py-1 px-3 rounded-md"
            >
              <h1 class="font-semibold">Contact us</h1>
              <button class="text-lg">
                <PhEnvelopeSimple />
              </button>
            </a>
          </div>
        </div>
        <!-- <div class="flex flex-col justify-center items-center">
                    <img class="w-20" :src="'/assets/image/logo.png'" alt="" />
                    <h1 class="-mt-4 uppercase text-sm font-semibold">
                        Chasira
                    </h1>
                </div> -->
      </div>
      <div class="p-8 w-full h-full border border-black">
        <div class="border-black">
          <p class="mb-5">Dear {{ user_name }},</p>
          <p class="mb-6">
            We hope this message finds you well. After a thorough review of your
            account, our system has detected that your account has been
            <span class="px-2 py-0.5 bg-gray-100 font-semibold rounded"
              >DEACTIVATED</span
            >. As a result, you will no longer be able to access any pages or
            services other than the one you are currently on.
          </p>
          <div class="mb-6">
            <p
              class="bg-orange-400 inline-block px-2 py-0.5 mb-1 text-white font-semibold"
            >
              If You Are a Employee
            </p>
            <p>
              If you are an employee and believe that your account has been
              deactivated by mistake, we kindly ask you to contact the
              appropriate person or department within your organization who is
              responsible for managing your account. They will be able to
              provide further clarification or assist in reactivating your
              account if necessary.
            </p>
          </div>
          <div class="mb-4">
            <p
              class="bg-orange-400 inline-block px-2 py-0.5 mb-1 text-white font-semibold"
            >
              If You Are a Account Holder
            </p>
            <p>
              However, if you are the primary account holder or have
              administrative authority over the system, and you believe that no
              violations have occurred on your part, please do not hesitate to
              reach out directly to our team. We understand the inconvenience
              this may cause, and we are more than willing to assist you in
              resolving the issue promptly. Please contact us, and we will
              investigate the situation and work to find a solution as quickly
              as possible.
            </p>
          </div>
          <p class="mb-4">
            We value your continued trust in our services, and we apologize for
            any disruption caused. Your understanding and cooperation are
            greatly appreciated as we address this matter.
          </p>
          <p class="mb-4">
            Thank you once again for your attention. We look forward to
            assisting you.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { PhEnvelopeSimple, PhSignOut } from '@phosphor-icons/vue';

const page = usePage();
const user_name = page.props.auth.user.name;
</script>
