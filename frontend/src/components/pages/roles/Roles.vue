<template>
  <div>
    <SideBar />
    <NavBar />
    <RolesList />
  </div>
</template>

<script>
import NavBar from "@/components/ui/cores/NavBar.vue";
import SideBar from "@/components/ui/cores/SideBar.vue";
import RolesList from "@/components/ui/roles/RolesList.vue";

export default {
  name: "Roles",
  components: {
    SideBar,
    NavBar,
    RolesList,
  },
};
</script>
