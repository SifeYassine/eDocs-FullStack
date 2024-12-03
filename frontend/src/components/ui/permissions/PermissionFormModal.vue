<template>
  <div class="flex justify-center items-center mt-[10%] ml-[12%]">
    <vs-button
      @click="openModal({ mode: 'add' })"
      color="primary"
      class="ml-[12%]"
    >
      + New Permission
    </vs-button>

    <!-- Add/Edit Permission Modal -->
    <vs-dialog v-model="isModalOpen" width="20vw" @close="closeModal">
      <template #header>
        <h4>{{ modalMode === "add" ? "Add" : "Edit" }} Permission</h4>
      </template>

      <div class="flex flex-col gap-5 items-center">
        <vs-input
          v-model="currentPermission.label"
          @keyup.enter="submitForm()"
          type="text"
          color="primary"
          label="Label"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> <i class="bx bx-check-shield" /> </template>
        </vs-input>
        <vs-input
          v-model="currentPermission.description"
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
          {{ modalMode === "add" ? "Add" : "Update" }} Permission
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

    const isModalOpen = computed(() => store.getters.getPermissionModal.isOpen);
    const modalMode = computed(() => store.getters.getPermissionModal.mode);
    const currentPermission = computed(
      () => store.getters.getPermissionModal.permission
    );

    function openModal(modalState) {
      store.dispatch("openPermissionModal", modalState);
    }

    function closeModal() {
      store.dispatch("closePermissionModal");
    }

    async function submitForm() {
      try {
        const permission = { ...currentPermission.value };
        const actionMode =
          modalMode.value === "add" ? "addPermission" : "editPermission";

        // Call the appropriate action based on the modal mode
        await store.dispatch(actionMode, permission);

        // Close the modal
        closeModal();
      } catch (error) {
        console.error(error);
      }
    }

    return {
      isModalOpen,
      modalMode,
      currentPermission,
      openModal,
      closeModal,
      submitForm,
    };
  },
};
</script>
