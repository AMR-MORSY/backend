<template>
<div class="not-found">
    <div id="not-found">Not Found</div>

</div>
    
</template>
<script>
export default {
    name:"notFound",
}
</script>

<style lang="scss" scoped>

.not-found{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba($color: #000000, $alpha: 1);
    z-index: 2;
    display: flex;
    justify-content: center;
    align-items: center;
    #not-found{
        position: absolute;
        z-index: 3;
    }

}

</style>