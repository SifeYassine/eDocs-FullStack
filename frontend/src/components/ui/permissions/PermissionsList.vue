<template>
  <div>
    <PermissionFormModal />
    <div class="table w-[50%] ml-[30%] mt-[2%]">
      <vs-table striped>
        <template #thead>
          <vs-tr>
            <vs-th class="font-bold"> Permission </vs-th>
            <vs-th class="font-bold"> Description </vs-th>
            <vs-th class="font-bold"> Actions </vs-th>
          </vs-tr>
        </template>
        <template #tbody>
          <vs-tr
            v-for="(permission, i) in permissions"
            :key="i"
            :data="permission"
          >
            <vs-td>
              {{ permission.label }}
            </vs-td>
            <vs-td>
              {{ permission.description }}
            </vs-td>

            <vs-td class="flex">
              <vs-button
                color="primary"
                @click="openEditModal({ mode: 'edit', permission: permission })"
                >Edit</vs-button
              >

              <vs-button color="danger" @click="deletePermission(permission.id)"
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
import PermissionFormModal from "./PermissionFormModal.vue";

export default {
  components: {
    PermissionFormModal,
  },
  setup() {
    const store = useStore();

    const permissions = computed(() => store.getters.getPermissions);

    function openEditModal(modalState) {
      store.dispatch("openPermissionModal", modalState);
    }

    async function fetchPermissions() {
      try {
        await store.dispatch("fetchPermissions");
      } catch (error) {
        console.error("Failed to fetch permissions:", error);
      }
    }

    async function deletePermission(permissionId) {
      try {
        await store.dispatch("deletePermission", permissionId);
      } catch (error) {
        console.error("Failed to delete permission:", error);
      }
    }

    onMounted(() => {
      fetchPermissions();
    });

    return {
      permissions,
      openEditModal,
      deletePermission,
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
