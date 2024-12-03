<template>
  <div class="flex justify-center items-center mt-[10%] ml-[12%]">
    <vs-button
      @click="openModal({ mode: 'add' })"
      color="primary"
      class="ml-[12%]"
    >
      + New Category
    </vs-button>

    <!-- Add/Edit Category Modal -->
    <vs-dialog v-model="isModalOpen" width="20vw" @close="closeModal">
      <template #header>
        <h4>{{ modalMode === "add" ? "Add" : "Edit" }} Category</h4>
      </template>

      <div class="flex flex-col gap-5 items-center">
        <vs-input
          v-model="currentCategory.name"
          @keyup.enter="submitForm()"
          type="text"
          color="primary"
          label="Name"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> # </template>
        </vs-input>

        <vs-button @click="submitForm()" class="w-[90%]" color="primary">
          {{ modalMode === "add" ? "Add" : "Update" }} Category
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

    const isModalOpen = computed(() => store.getters.getCategoryModal.isOpen);
    const modalMode = computed(() => store.getters.getCategoryModal.mode);
    const currentCategory = computed(
      () => store.getters.getCategoryModal.category
    );

    // Modal triggers
    function openModal(modalState) {
      store.dispatch("openCategoryModal", modalState);
    }

    function closeModal() {
      store.dispatch("closeCategoryModal");
    }

    async function submitForm() {
      try {
        const category = { ...currentCategory.value };
        const actionMode =
          modalMode.value === "add" ? "addCategory" : "editCategory";

        // Call the appropriate action based on the modal mode
        await store.dispatch(actionMode, category);

        closeModal();
      } catch (error) {
        console.error("Failed to submit form:", error);
      }
    }

    return {
      isModalOpen,
      modalMode,
      currentCategory,
      openModal,
      closeModal,
      submitForm,
    };
  },
};
</script>
