<template>
  <div class="container overflow-hidden px-5  mb-5">
  
    <TransitionGroup class="row gx-5 mt-5" tag="div" name="cards" appear>
      <div class="col-6 col-md-4 mt-5" :key="1">
        <div class="card" @click.self="formatElement">
          <span class="material-symbols-rounded"> home </span>
          <p>Sites</p>
        </div>
      </div>
      <div class="col-6 col-md-4 mt-5" :key="2">
        <div class="card" @click.self="formatElement">
          <span class="material-symbols-rounded"> auto_fix_off </span>
          <p>Modifications</p>
        </div>
      </div>
      <div class="col-6 col-md-4 mt-5" :key="3">
        <div class="card"  @click.self="formatElement">
          <span class="material-symbols-rounded"> electric_bolt </span>
          <p>Energy</p>
        </div>
      </div>
      <div class="col-6 col-md-4 mt-5" :key="4">
        <div class="card"  @click.self="formatElement">
          <span class="material-symbols-rounded"> signal_disconnected </span>
          <p>NUR</p>
        </div>
      </div>
      <div class="col-6 col-md-4 mt-5" :key="4">
        <div class="card"  @click.self="formatElement">
          <span class="material-symbols-rounded"> group </span>
          <p>Users</p>
        </div>
      </div>
    </TransitionGroup>
  
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  name: "dashboard",
  methods: {
    formatElement(event) {
        console.log(event.target.childNodes[1].innerText);
      event.target.style.boxShadow = "0px 0px 5px #79589f";
      if(event.target.childNodes[1].innerText=="Sites")
      {
         this.$router.push("/sites");

      }
      else if(event.target.childNodes[1].innerText=="NUR")
      {
        this.$router.push("/nur");
      }
       else if(event.target.childNodes[1].innerText=="Modifications")
      {
        this.$router.push("/modifications");
      }
        else if(event.target.childNodes[1].innerText=="Energy")
      {
        this.$router.push("/energy");
      }
         else if(event.target.childNodes[1].innerText=="Users")
      {
        this.$router.push("/users");
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.card {
  background-color: white;
  box-shadow: 1px 3px 5px rgba($color: white, $alpha: 0.1);
  height: 250px;
  width: 100%;
  transition: all 0.5s ease;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  border:solid 2px var(--purple-500);
  span {
    text-align: center;
    font-size: 5rem;
    z-index: 1;
    
      color: var(--purple-500);
  }
  p {
    text-align: center;
    font-size: 2rem;
    color: var(--purple-500);
  }
}

.card:hover {
  transform: scale(1.02);
  transition: all 0.5s ease;
}

.cards-enter-from {
  opacity: 0;

  transform: scale(0.7);
}
.cards-enter-active {
  transition: all 1s ease;
}
</style>