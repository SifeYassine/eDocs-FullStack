<template>
  <div>
    <SideBar />
    <NavBar />
    <UsersList />
  </div>
</template>

<script>
import NavBar from "@/components/ui/cores/NavBar.vue";
import SideBar from "@/components/ui/cores/SideBar.vue";
import UsersList from "@/components/ui/users/UsersList.vue";

export default {
  name: "Users",
  components: {
    SideBar,
    NavBar,
    UsersList,
  },
};
</script>
