<template>
  <div class="unauthorized">
    <p>Unauthorized</p>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  name: "unauthorized",
};
</script>

<style lang="scss" scoped>
.unauthorized{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%);
}
</style>