<template>
  <div>
    <RoleFormModal />
    <div class="table w-[50%] ml-[30%] mt-[2%]">
      <vs-table striped>
        <template #thead>
          <vs-tr>
            <vs-th class="font-bold"> Role </vs-th>
            <vs-th class="font-bold"> Description </vs-th>
            <vs-th class="font-bold"> Actions </vs-th>
          </vs-tr>
        </template>
        <template #tbody>
          <vs-tr v-for="(role, i) in roles" :key="i" :data="role">
            <vs-td>
              <vs-button
                :color="
                  role.name === 'Admin'
                    ? 'warn'
                    : role.name === 'User'
                    ? '#7d33ff'
                    : '#3bdec8'
                "
                type="gradient"
                style="cursor: default"
              >
                {{ role.name }}
              </vs-button>
            </vs-td>
            <vs-td>
              {{ role.description }}
            </vs-td>

            <vs-td class="flex">
              <vs-button
                color="primary"
                @click="openEditModal({ mode: 'edit', role: role })"
                >Edit</vs-button
              >

              <vs-button color="danger" @click="deleteRole(role.id)"
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
import RoleFormModal from "./RoleFormModal.vue";

export default {
  components: {
    RoleFormModal,
  },
  setup() {
    const store = useStore();

    const roles = computed(() => store.getters.getRoles);

    function openEditModal(modalState) {
      store.dispatch("openRoleModal", modalState);
    }

    async function fetchRoles() {
      try {
        await store.dispatch("fetchRoles");
      } catch (error) {
        console.error("Failed to fetch roles:", error);
      }
    }

    async function deleteRole(roleId) {
      try {
        await store.dispatch("deleteRole", roleId);
      } catch (error) {
        console.error("Failed to delete role:", error);
      }
    }

    onMounted(() => {
      fetchRoles();
    });

    return {
      roles,
      openEditModal,
      deleteRole,
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
