<template>
  <div class="container overflow-hidden px-5 mb-5">
    <TransitionGroup class="row gx-5 mt-5" tag="div" name="cards" appear>
      <div class="col-6 col-md-4 mt-5" v-for="card in cards" :key="card.id">
        <div class="card" @click.self="formatElement(card.id)">
          <span class="material-symbols-rounded"> {{ card.icon }} </span>
          <p>{{ card.path }}</p>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cards: null,
    };
  },
  created() {
    this.cards = [
      { id: 1, icon: "search", path: "Search" },
      { id: 2, icon: "add_circle", path: "New Sheet" },
     
    ];
  },
  name: "energy",
  methods: {
    formatElement(id) {
      let card = this.cards.filter((element) => {
        return element.id == id;
      });
      console.log(card);
      if (card[0].path == "Search") {
        this.$router.push("/energy/index");
      } else if (card[0].path == "New Sheet") {
        this.$router.push("/energy/sheet");
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
  span {
    text-align: center;
    font-size: 5rem;
    z-index: 1;
    color: gray;
  }
  p {
    text-align: center;
    font-size: 2rem;
    color: gray;
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