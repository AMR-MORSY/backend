<template>
  <button  >
    <span
      class="spinner-border spinner-border-sm"
      role="status"
      aria-hidden="true"
      v-if="showSpinner"
    ></span>
    <slot></slot>
   
  </button>
</template>

<script>
export default {
  name: "spinnerButton",
  props: {
    showSpinner: {
      type: Boolean,
      default: false,
    },
  },
  setup() {
    return {};
  },
};
</script>

<style lang="scss" scoped>
</style>