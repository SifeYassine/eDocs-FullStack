<template>
  <div>
    <EditUserFormModal />
    <div class="table w-[60%] ml-[30%] mt-[5%]">
      <vs-table striped>
        <template #thead>
          <vs-tr>
            <vs-th class="font-bold"> Usernames </vs-th>
            <vs-th class="font-bold"> Emails </vs-th>
            <vs-th class="font-bold"> Permissions </vs-th>
            <vs-th class="font-bold"> Roles </vs-th>
            <vs-th class="font-bold"> Actions </vs-th>
          </vs-tr>
        </template>
        <template #tbody>
          <vs-tr v-for="(user, i) in users" :key="i" :data="user">
            <vs-td>
              {{ user.username }}
            </vs-td>
            <vs-td>
              {{ user.email }}
            </vs-td>

            <vs-td v-if="user.role_id.name === 'Admin'" class="flex"> </vs-td>

            <vs-td v-else class="flex">
              <vs-button color="primary">Assign</vs-button>

              <vs-button color="danger">Revoke</vs-button>
            </vs-td>

            <vs-td>
              <vs-button
                :color="
                  user.role_id.name === 'Admin'
                    ? 'warn'
                    : user.role_id.name === 'User'
                    ? '#7d33ff'
                    : '#3bdec8'
                "
                type="gradient"
                style="cursor: default"
              >
                {{ user.role_id.name }}
              </vs-button>
            </vs-td>

            <vs-td v-if="user.role_id.name === 'Admin'" class="flex"> </vs-td>
            <vs-td v-else class="flex">
              <vs-button color="primary" @click="openEditModal({ user: user })"
                >Edit</vs-button
              >

              <vs-button color="danger" @click="deleteUser(user.id)"
                >Delete</vs-button
              >
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
  </div>
</template>

<script>
import { computed, onMounted } from "vue";
import { useStore } from "vuex";
import EditUserFormModal from "./EditUserFormModal.vue";

export default {
  components: {
    EditUserFormModal,
  },
  setup() {
    const store = useStore();

    const users = computed(() => store.getters.getUsers);

    async function fetchUsers() {
      try {
        await store.dispatch("fetchUsers");
      } catch (error) {
        console.error("Failed to fetch users:", error);
      }
    }

    function openEditModal(modalState) {
      store.dispatch("openUserModal", modalState);
    }

    async function deleteUser(userId) {
      try {
        await store.dispatch("deleteUser", userId);
        fetchRoles();
      } catch (error) {
        console.error("Failed to delete role:", error);
      }
    }

    onMounted(() => {
      fetchUsers();
    });

    return {
      users,
      deleteUser,
      openEditModal,
    };
  },
};
</script>

<style scoped>
.table {
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>
