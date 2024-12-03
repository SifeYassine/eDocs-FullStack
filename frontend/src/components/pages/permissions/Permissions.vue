<template>
  <div>
    <SideBar />
    <NavBar />
    <PermissionsList />
  </div>
</template>

<script>
import NavBar from "@/components/ui/cores/NavBar.vue";
import SideBar from "@/components/ui/cores/SideBar.vue";
import PermissionsList from "@/components/ui/permissions/PermissionsList.vue";

export default {
  name: "Permissions",
  components: {
    SideBar,
    NavBar,
    PermissionsList,
  },
};
</script>
