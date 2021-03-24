<template>
  <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
      <inertia-link v-if="$page.props.user" href="/releases" class="text-sm text-gray-700 underline">
        Releases
      </inertia-link>

      <template v-else>
        <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
          Log in
        </inertia-link>

        <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
          Register
        </inertia-link>
      </template>
    </div>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        <p class="text-center text-gray-400 font-thin text-8xl mb-6 title">
          Nothing to see here.
        </p>
      </div>

      <div class="flex justify-center mt-4 sm:items-center">
        <p class="text-center text-gray-100 text-lg font-semibold tracking-widest uppercase">
          <a class="no-underline" href="https://monicahq.com">Visit https://monicahq.com instead</a>
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .title {
        font-family: 'Raleway', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', "Segoe UI", 'Roboto', "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }
    }
</style>

<script>
export default {
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
  }
};
</script>
