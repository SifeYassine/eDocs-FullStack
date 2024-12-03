<template>
  <div>
    <SideBar />
    <NavBar />
    <CategoriesList />
  </div>
</template>

<script>
import NavBar from "@/components/ui/cores/NavBar.vue";
import SideBar from "@/components/ui/cores/SideBar.vue";
import CategoriesList from "@/components/ui/categories/CategoriesList.vue";

export default {
  name: "Categories",
  components: {
    SideBar,
    NavBar,
    CategoriesList,
  },
};
</script>
