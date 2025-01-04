<template>
  <table class="table table-hover table-bordered">
    <thead>
      <tr class="text-center align-middle" style="background-color: #79589F; color: white">
       <slot name="header"></slot>
      </tr>
    </thead>
    <tbody>
      <slot name="body"></slot>
    </tbody>
  </table>
</template>

<script>
export default {
  name: "helperTable",
};
</script>

<style lang="scss" scoped>
</style>