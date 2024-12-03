<template>
  <vs-navbar v-model="active" center-collapsed class="navbar">
    <template #left>
      <SearchBar />
    </template>
  </vs-navbar>
</template>

<script>
import SearchBar from "../cores/SearchBar.vue";

export default {
  name: "NavBar",
  components: {
    SearchBar,
  },
};
</script>

<style scoped>
.navbar {
  width: 75%;
  margin: 2% 0 0 22%;
  border-radius: 20px;
  z-index: 1000;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>
