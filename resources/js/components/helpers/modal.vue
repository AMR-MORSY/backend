<template>
  <div>
    <!-- Modal -->
    <div
      v-if="OpenClose"
      class="modal fade show"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-modal="true"
      role="dialog"
      style="display: block"
      data-bs-backdrop="static"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              @click="OpenCloseFun()"
              class="btn-close"
            ></button>
          </div>
          <div class="modal-body">
            <slot name="body"></slot>
          </div>
          <div class="modal-footer">
            <slot name="footer"></slot>
            <!-- <button
              type="button"
              @click="OpenCloseFun()"
              :class="'btn btn-' + variant"
            >
              Close
            </button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "modal",
  props: {
    visible: Boolean,
    variant: String,
  },
  data() {
    return {
      OpenClose:this.visible,
    };
  },
  methods: {
    OpenCloseFun() {
      this.OpenClose = false;
    },
  },
  watch: {
    visible: function (newVal, oldVal) {
      // watch it
      this.OpenClose = newVal;
      console.log("new" + newVal + "==" + oldVal);
    },
  },
};
</script>

<style lang="scss" scoped>

.modal-body{
  color: red;
  font-weight: 900;
}

</style>
