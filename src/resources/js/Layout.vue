<template>
  <main class="bg-white h-screen">
    <slot />
  </main>
</template>

<script>
export default {
  props: {
    title: String,
  },
  watch: {
    title: {
      immediate: true,
      handler(title) {
        document.title = title;
      },
    },
  },
};
</script>
