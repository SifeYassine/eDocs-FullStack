<template>
  <vs-sidebar v-model="active" absolute open class="sidebar" color="primary">
    <div class="sidebar-content mt-[20px]">
      <vs-sidebar-item id="home" to="/">
        <template #icon>
          <i class="bx bx-home-alt-2" />
        </template>
        Home
      </vs-sidebar-item>

      <vs-sidebar-item id="my_docs" to="/my_docs">
        <template #icon>
          <i class="bx bx-file-blank" />
        </template>
        My Docs
      </vs-sidebar-item>

      <vs-sidebar-item id="categories" to="/categories">
        <template #icon>
          <i class="bx bx-tag" />
        </template>
        Categories
      </vs-sidebar-item>

      <vs-sidebar-group v-if="isAdmin || hasPermissions.length">
        <template #header>
          <vs-sidebar-item arrow>
            <template #icon>
              <i class="bx bx-book-content" />
            </template>
            Management Panel
          </vs-sidebar-item>
        </template>

        <vs-sidebar-item
          id="users"
          v-if="isAdmin || hasPermissions.includes('Manage Users')"
          to="/users"
        >
          <template #icon>
            <i class="bx bx-group" />
          </template>
          Manage Users
        </vs-sidebar-item>

        <vs-sidebar-item
          id="roles"
          v-if="isAdmin || hasPermissions.includes('Manage Roles')"
          to="/roles"
        >
          <template #icon>
            <i class="bx bx-crown" />
          </template>
          Manage Roles
        </vs-sidebar-item>

        <vs-sidebar-item
          id="permissions"
          v-if="isAdmin || hasPermissions.includes('Manage Permissions')"
          to="/permissions"
        >
          <template #icon>
            <i class="bx bx-check-shield" />
          </template>
          Manage Permissions
        </vs-sidebar-item>
      </vs-sidebar-group>
    </div>

    <!-- Fixed Logout Item -->
    <vs-sidebar-item @click="logout" class="logout">
      <template #icon>
        <i class="bx bx-log-out" />
      </template>
      Logout
    </vs-sidebar-item>
  </vs-sidebar>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";

export default {
  setup() {
    const active = ref("mydocs");
    const store = useStore();
    const route = useRoute();
    const router = useRouter();

    const userId = computed(() => store.getters.isAuthenticated.user_id);
    const isAdmin = computed(() => store.getters.isAdmin);
    const hasPermissions = computed(() => store.getters.getUserPermissions);

    async function getMyProfile() {
      try {
        await store.dispatch("getMyProfile");
      } catch (error) {
        console.error("Failed to fetch user profile:", error);
      }
    }

    async function getMyPermissions() {
      try {
        await store.dispatch("getUserPermissions", userId.value);
      } catch (error) {
        console.error("Failed to get permissions:", error);
      }
    }

    const path = route.path;
    if (path) {
      active.value = path.split("/")[1];
    }

    async function logout() {
      try {
        await store.dispatch("logout");
        router.push("/");
      } catch (error) {
        console.error("Failed to logout:", error);
      }
    }

    onMounted(() => {
      getMyProfile();
      setTimeout(() => {
        getMyPermissions();
      }, 3000);
    });

    return {
      active,
      logout,
      userId,
      isAdmin,
      hasPermissions,
    };
  },
};
</script>

<style scoped>
.sidebar {
  width: 20%;
  height: 80% !important;
  margin: 2% 0 0 2%;
  border-radius: 20px;
  z-index: 1000;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.sidebar-content {
  flex: 1;
}

.logout {
  margin-top: auto;
  margin-bottom: 20px;
}
</style>
