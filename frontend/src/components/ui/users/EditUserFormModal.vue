<template>
  <div class="flex justify-center items-center mt-[10%]">
    <vs-dialog v-model="isModalOpen" width="20vw" @close="closeModal">
      <template #header>
        <h4>Edit User Role</h4>
      </template>

      <div
        v-if="currentUserRole.role_id"
        class="flex flex-col gap-5 items-center"
      >
        <div>
          <vs-select
            v-model="currentUserRole.role_id.id"
            placeholder="Select a Role"
          >
            <vs-option
              v-for="role in roles"
              :key="role.id"
              :label="role.name"
              :value="role.id"
            >
              {{ role.name }}
            </vs-option>
          </vs-select>
        </div>

        <vs-button @click="submitForm()" class="w-[55%]" color="primary">
          Update User Role
        </vs-button>
      </div>
    </vs-dialog>
  </div>
</template>

<script>
import { computed, onMounted } from "vue";
import { useStore } from "vuex";

export default {
  setup() {
    const store = useStore();
    const isModalOpen = computed(() => store.getters.getUserModal.isOpen);
    const roles = computed(() => store.getters.getRoles);
    const currentUserRole = computed(() => store.getters.getUserModal.user);

    function closeModal() {
      store.dispatch("closeUserModal");
    }

    async function fetchRoles() {
      try {
        await store.dispatch("fetchRoles");
      } catch (error) {
        console.error("Failed to fetch roles:", error);
      }
    }

    async function submitForm() {
      try {
        // Overrides the role_id property with just the Id number instead of keeping the whole object role_id: { id: 1, name: "User" }
        const updatedUser = {
          ...currentUserRole.value,
          role_id: currentUserRole.value.role_id.id,
        };

        await store.dispatch("editUser", updatedUser);

        // refresh the users list
        await store.dispatch("fetchUsers");
        // Close the modal
        closeModal();
      } catch (error) {
        console.error("Failed to submit form:", error);
      }
    }

    onMounted(() => {
      fetchRoles();
    });

    return {
      isModalOpen,
      roles,
      currentUserRole,
      closeModal,
      submitForm,
    };
  },
};
</script>
