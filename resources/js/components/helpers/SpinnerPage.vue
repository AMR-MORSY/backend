<template>
     <div :class="{ 'display': displayNone, 'spinner': !displayNone }">
        <ProgressSpinner />
      </div>
</template>

<script>
    export default {
    name:"SpinnerPage",
    props:["displayNone"]
        
    }
</script>

<style lang="scss" scoped>
.display {
  display: none;
}
 .spinner {
    position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 100;
    background-color: rgba($color: #ffff, $alpha: 0.7);
  }

</style>