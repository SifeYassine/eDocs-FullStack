<template>
  <div class="flex justify-center items-center mt-[10%] ml-[12%]">
    <vs-button
      @click="openModal({ mode: 'add' })"
      color="primary"
      class="ml-[12%]"
    >
      + New Role
    </vs-button>

    <!-- Add/Edit Role Modal -->
    <vs-dialog v-model="isModalOpen" width="20vw" @close="closeModal">
      <template #header>
        <h4>{{ modalMode === "add" ? "Add" : "Edit" }} Role</h4>
      </template>

      <div class="flex flex-col gap-5 items-center">
        <vs-input
          v-model="currentRole.name"
          @keyup.enter="submitForm()"
          type="text"
          color="primary"
          label="Name"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> <i class="bx bx-crown" /> </template>
        </vs-input>
        <vs-input
          v-model="currentRole.description"
          @keyup.enter="submitForm()"
          type="text"
          color="primary"
          label="Description"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> # </template>
        </vs-input>

        <vs-button @click="submitForm()" class="w-[90%]" color="primary">
          {{ modalMode === "add" ? "Add" : "Update" }} Role
        </vs-button>
      </div>
    </vs-dialog>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

export default {
  setup() {
    const store = useStore();

    const isModalOpen = computed(() => store.getters.getRoleModal.isOpen);
    const modalMode = computed(() => store.getters.getRoleModal.mode);
    const currentRole = computed(() => store.getters.getRoleModal.role);

    function openModal(modalState) {
      store.dispatch("openRoleModal", modalState);
    }

    function closeModal() {
      store.dispatch("closeRoleModal");
    }

    async function submitForm() {
      try {
        const role = { ...currentRole.value };
        const actionMode = modalMode.value === "add" ? "addRole" : "editRole";

        // Call the appropriate action based on the modal mode
        await store.dispatch(actionMode, role);

        // Close the modal
        closeModal();
      } catch (error) {
        console.error(error);
      }
    }

    return {
      isModalOpen,
      modalMode,
      currentRole,
      openModal,
      closeModal,
      submitForm,
    };
  },
};
</script>
