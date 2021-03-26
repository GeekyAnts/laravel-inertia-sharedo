<template>
  <div class="py-2 mt-5 flex space-x-4">
    <div class="flex items-center">
      <div
        class="inline-block flex icon-users items-center justify-center text-white h-9 w-9 rounded-full ring-2 ring-white"
      >
        {{ user.name.charAt(0).toUpperCase() }}
      </div>
    </div>
    <div class="flex flex-col w-1/2">
      <div class="text-sm font-medium">{{ user.name }}</div>
      <div class="text-sm h-full color-75">
        {{ user.email }}
      </div>
    </div>
    <div
      class="text-sm flex text-gray-500 flex-1 justify-end items-center font-bold"
    >
      Owner
    </div>
  </div>
</template>
<script>
export default {
  props: {
    user: {},
  },
};
</script>
<style>
.icon-users {
  background-color: #318dd0;
}
</style>

