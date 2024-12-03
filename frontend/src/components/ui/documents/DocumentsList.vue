<template>
  <div>
    <PreviewSelectedDocument
      v-model="previewActive"
      :document="selectedDocument"
    />
    <EditDocumentModal v-model="editActive" :document="documentToEdit" />
    <ul
      class="list-none grid gap-4 px-[60px] py-[20px] mt-3 ml-[18.5%] rounded-3xl z-[1000]"
      style="grid-template-columns: repeat(5, 1fr)"
    >
      <li v-for="document in filteredDocuments" :key="document.id">
        <div
          @click="getSelectedDocument(document.id)"
          @contextmenu.prevent="showContextMenu($event, document)"
          class="flex flex-col items-center cursor-pointer"
        >
          <vs-card
            style="width: 100%; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1)"
          >
            <template #title>
              <h2>{{ document.title }}</h2>
            </template>
            <template #img>
              <img
                :src="`/images/${document.format}.png`"
                class="h-[200px] object-fill p-[20px]"
                :alt="`${document.format} icon`"
              />
            </template>
            <template #text> </template>
            <template #interactions>
              <vs-button v-if="document.category_id" color="primary">
                <i class="bx bx-tag" />
                <span class="span" style="margin-left: 5px">
                  {{ document.category_id.name }}
                </span>
              </vs-button>
            </template>
          </vs-card>
        </div>

        <!-- Context Menu -->
        <div
          v-if="contextMenu.visible && contextMenu.documentId === document.id"
          class="context-menu"
          :style="{
            top: contextMenu.top + 'px',
            left: contextMenu.left + 'px',
          }"
        >
          <div
            @click="openEditModal(document)"
            class="context-menu-item edit-item"
          >
            Edit
          </div>

          <div
            class="context-menu-item delete-item"
            @click="deleteDocument(document.id)"
          >
            Delete
          </div>

          <div class="context-menu-item download-item">
            <a
              :href="`${baseUrl}${document.path_url}`"
              :download="document.title"
              target="_blank"
              rel="noopener noreferrer"
            >
              Download
            </a>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import PreviewSelectedDocument from "./PreviewSelectedDocument.vue";
import EditDocumentModal from "./EditDocumentModal.vue";
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

export default {
  components: {
    PreviewSelectedDocument,
    EditDocumentModal,
  },
  setup() {
    const store = useStore();
    const route = useRoute();

    const previewActive = ref(false);
    const editActive = ref(false);

    const baseUrl = "http://127.0.0.1:8000";

    const documents = computed(() => store.getters.getDocuments);
    const selectedDocument = computed(() => store.getters.getSelectedDocument);
    const documentToEdit = ref({});
    const searchQuery = computed(() => store.getters.getSearchQuery);
    const categoryName = ref(route.query.category || "");

    const contextMenu = ref({
      visible: false,
      top: 0,
      left: 0,
      documentId: null,
    });

    async function fetchDocuments() {
      try {
        await store.dispatch("fetchDocuments");
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    }

    async function deleteDocument(id) {
      try {
        await store.dispatch("deleteDocument", id);
      } catch (error) {
        console.error("Error deleting document:", error);
      }
    }

    const filteredDocuments = computed(() => {
      const searchFilter = searchQuery.value.toLowerCase();
      const categoryFilter = categoryName.value.toLowerCase();

      // If no filters are applied, return all documents
      if (!searchFilter && !categoryFilter) {
        return documents.value;
      }

      return documents.value.filter((document) => {
        const documentTitle = document.title
          ? document.title.toLowerCase()
          : "";
        const categoryName = document.category_id?.name
          ? document.category_id.name.toLowerCase()
          : "";

        return (
          documentTitle.includes(searchFilter) &&
          categoryName.includes(categoryFilter)
        );
      });
    });

    function showContextMenu(event, document) {
      contextMenu.value = {
        visible: true,
        top: event.clientY,
        left: event.clientX,
        documentId: document.id,
      };
      event.stopPropagation();
    }

    function hideContextMenu() {
      contextMenu.value.visible = false;
    }

    // Preview Modal Trigger
    function getSelectedDocument(id) {
      store.dispatch("getDocumentById", id);
      previewActive.value = true;
    }

    // Edit Modal Trigger
    function openEditModal(document) {
      documentToEdit.value = { ...document };
      editActive.value = true;
    }

    document.addEventListener("click", hideContextMenu);

    onMounted(() => {
      fetchDocuments();
    });

    return {
      previewActive,
      editActive,
      documents,
      fetchDocuments,
      deleteDocument,
      filteredDocuments,
      getSelectedDocument,
      selectedDocument,
      searchQuery,
      categoryName,
      contextMenu,
      showContextMenu,
      hideContextMenu,
      openEditModal,
      documentToEdit,
      baseUrl,
    };
  },
};
</script>

<style scoped>
img {
  border-radius: 30px !important;
}

.context-menu {
  position: fixed;
  background: white;
  border-radius: 15px;
  padding: 5px 0;
  z-index: 9999;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* General Context Menu Items */
.context-menu-item {
  color: #374151;
  font-weight: 700;
  text-align: center;
  border-radius: 10px;
  padding: 8px 12px;
  cursor: pointer;
}

.delete-item,
.edit-item,
.download-item {
  margin: 5px 10px;
  padding: 10px 15px;
}

/* Specific Styling for the Delete Item */
.delete-item:hover {
  color: white;
  background-color: #fe4646;
}

/* Specific Styling for the Edit Item */
.edit-item:hover {
  color: white;
  background-color: #3881ff;
}

/* Specific Styling for the Download Item */
.download-item:hover {
  color: white;
  background-color: #48c78e;
}
</style>
