<template>
  <div class="flex justify-center items-center mt-[10%] ml-[12%]">
    <vs-button @click="active = !active" color="primary">
      + Upload New Document
    </vs-button>
    <vs-dialog v-model="active" @close="closeModal" auto-width>
      <template #header class="mt-6">
        <h4>Upload a Document</h4>
      </template>

      <!-- Upload Document -->
      <div class="flex flex-col">
        <div class="flex">
          <div v-if="previewImageUrl" class="relative mr-4 mt-2.5">
            <img
              :src="previewImageUrl"
              alt="Preview"
              class="max-w-[100px] max-h-[100px] object-cover"
            />
            <button
              class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs cursor-pointer"
              @click="removeDocument"
            >
              X
            </button>
          </div>

          <div
            class="my-4 px-2.5 py-4 w-fit flex items-center bg-gray-100 rounded-lg border-2 border-gray-200"
          >
            <input
              type="file"
              ref="fileInput"
              class="hidden"
              @change="handleDocumentChange"
              accept=".pdf,.docx,.xlsx,.pptx,.txt,.png,.jpg,.jpeg,.mp4,"
            />
            <vs-button @click="triggerDocumentInput" color="primary"
              >Choose File</vs-button
            >
            <span class="ml-3">{{ documentName || "No Document Chosen" }}</span>
          </div>
        </div>

        <div>
          <vs-select v-model="category_id" placeholder="Select Category">
            <vs-option
              v-for="category in categories"
              :key="category.id"
              :label="category.name"
              :value="category.id"
            >
              {{ category.name }}
            </vs-option>
          </vs-select>
        </div>
      </div>

      <template #footer>
        <div class="flex justify-center mt-2 mb-5">
          <vs-button @click="uploadDocument" color="primary" class="w-[95%]">
            Upload
          </vs-button>
        </div>
      </template>
    </vs-dialog>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";

export default {
  setup() {
    const store = useStore();
    const active = ref(false);

    const fileInput = ref(null);
    const file = ref(null);
    const documentName = ref("");
    const previewImageUrl = ref(null);
    const categories = computed(() => store.getters.getCategories);
    const category_id = ref(null);

    async function fetchCategories() {
      try {
        await store.dispatch("fetchCategories");
      } catch (error) {
        console.error("Failed to fetch categories:", error);
      }
    }

    function triggerDocumentInput() {
      fileInput.value.click();
    }

    function handleDocumentChange(event) {
      file.value = event.target.files[0];
      if (file.value) {
        documentName.value = file.value.name;
        updatePreview(file.value);
      }
    }

    function updatePreview(file) {
      const extension = file.name.split(".").pop().toLowerCase();
      previewImageUrl.value = `/images/${extension}.png`;
    }

    function removeDocument() {
      file.value = null;
      documentName.value = "";
      previewImageUrl.value = null;
    }

    async function uploadDocument() {
      try {
        const newDocument = {
          path_url: file.value,
          category_id: category_id.value,
        };

        await store.dispatch("addDocument", newDocument);
        // Refresh the documents list
        await store.dispatch("fetchDocuments");

        // Reset the form
        active.value = false;
        resetForm();
      } catch (error) {
        console.error("Error uploading document:", error);
      }
    }

    function resetForm() {
      file.value = null;
      documentName.value = "";
      category_id.value = null;
      previewImageUrl.value = null;
    }

    function closeModal() {
      active.value = false;
      resetForm();
    }

    onMounted(() => {
      fetchCategories();
    });

    return {
      active,
      fileInput,
      documentName,
      previewImageUrl,
      categories,
      category_id,
      triggerDocumentInput,
      handleDocumentChange,
      removeDocument,
      uploadDocument,
      closeModal,
    };
  },
};
</script>
