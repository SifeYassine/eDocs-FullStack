<template>
  <vs-navbar v-model="active" center-collapsed class="navbar">
    <template #left>
      <div class="p-[10px]">
        <img src="images/logo.png" class="h-[70px]" alt="Logo" />
      </div>
    </template>

    <vs-navbar-item id="formats" :active="active === 'formats'">
      <a href="#formats">Supported Formats</a>
    </vs-navbar-item>

    <!-- login, register and logout buttons -->
    <template #right>
      <vs-button v-if="isAuthenticated">
        <router-link to="/my_docs" class="text-white"> My Docs </router-link>
      </vs-button>
      <vs-button type="flat" v-if="!isAuthenticated">
        <router-link to="/login" class="text-white"> Login </router-link>
      </vs-button>
      <vs-button v-if="!isAuthenticated">
        <router-link to="/register" class="text-white"> Register </router-link>
      </vs-button>
    </template>
  </vs-navbar>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default {
  setup() {
    const active = ref("guide");
    const store = useStore();
    const router = useRouter();
    const isAuthenticated = computed(() => store.getters.isAuthenticated.token);
    const isAdmin = computed(() => store.getters.isAdmin);

    function logout() {
      store.dispatch("logout");
      router.push("/login");
    }

    return {
      active,
      isAuthenticated,
      isAdmin,
      logout,
    };
  },
};
</script>

<style scoped>
.navbar {
  width: 90%;
  margin: 2% 0 0 5%;
  border-radius: 20px;
  z-index: 1000;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>
