<template>
  <div>
    <CategoryFormModal />
    <ul
      class="categoriesList w-3/4 ml-[22%] mt-[2%] rounded-2xl z-[1000] bg-white grid grid-cols-6 gap-y-2.5 list-none p-[50px]"
    >
      <li
        v-for="category in filteredCategories"
        :key="category.id"
        class="font-bold p-1.5"
      >
        <div @contextmenu.prevent="showContextMenu($event, category)">
          <vs-button
            class="w-[85%]"
            color="primary"
            @click="onCategoryClick(category)"
          >
            <i class="bx bx-tag text-[15px]" />
            <p class="text-[15px] ml-[5px]">{{ category.name }}</p>
          </vs-button>
        </div>

        <!-- Context Menu -->
        <div
          v-if="contextMenu.visible && contextMenu.categoryId === category.id"
          class="context-menu"
          :style="{
            top: contextMenu.top + 'px',
            left: contextMenu.left + 'px',
          }"
        >
          <div
            @click="openEditModal({ mode: 'edit', category: category })"
            class="context-menu-item edit-item"
          >
            Edit
          </div>
          <div
            @click="deleteCategory(category.id)"
            class="context-menu-item delete-item"
          >
            Delete
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import CategoryFormModal from "./CategoryFormModal.vue";

export default {
  components: {
    CategoryFormModal,
  },
  setup() {
    const store = useStore();
    const router = useRouter();

    const categories = computed(() => store.getters.getCategories);
    const searchQuery = computed(() => store.getters.getSearchQuery);
    const contextMenu = ref({
      visible: false,
      top: 0,
      left: 0,
      categoryId: null,
    });

    // Edit Modal trigger
    function openEditModal(modalState) {
      store.dispatch("openCategoryModal", modalState);
    }

    async function fetchCategories() {
      try {
        await store.dispatch("fetchCategories");
      } catch (error) {
        console.error("Failed to fetch categories:", error);
      }
    }

    async function deleteCategory(id) {
      try {
        await store.dispatch("deleteCategory", id);
      } catch (error) {
        console.error("Failed to delete category:", error);
      }
    }

    const filteredCategories = computed(() => {
      const searchFilter = searchQuery.value.toLowerCase();

      if (!searchFilter) {
        return categories.value;
      }

      return categories.value.filter((category) =>
        category.name.toLowerCase().includes(searchFilter)
      );
    });

    function onCategoryClick(category) {
      router.push({ path: "/my_docs", query: { category: category.name } });
    }

    function showContextMenu(event, category) {
      contextMenu.value = {
        visible: true,
        top: event.clientY,
        left: event.clientX,
        categoryId: category.id,
      };

      event.stopPropagation();
    }

    function hideContextMenu() {
      contextMenu.value.visible = false;
    }

    async function getMyProfile() {
      try {
        await store.dispatch("getMyProfile");
      } catch (error) {
        console.error("Failed to fetch user profile:", error);
      }
    }

    document.addEventListener("click", hideContextMenu);

    onMounted(() => {
      getMyProfile();
      fetchCategories();
    });

    return {
      filteredCategories,
      onCategoryClick,
      contextMenu,
      showContextMenu,
      openEditModal,
      deleteCategory,
    };
  },
};
</script>

<style scoped>
.categoriesList {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.context-menu {
  position: fixed;
  background: white;
  border-radius: 15px;
  padding: 5px 0;
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* General Context Menu Items */
.context-menu-item {
  color: #374151;
  text-align: center;
  border-radius: 10px;
  padding: 8px 12px;
  cursor: pointer;
}

/* Specific Styling for the Delete Item */
.delete-item {
  margin: 5px 10px;
  padding: 10px 15px;
}

.delete-item:hover {
  color: white;
  background-color: #fe4646;
}

/* Specific Styling for the Edit Item */
.edit-item {
  margin: 5px 10px;
  padding: 10px 15px;
}

.edit-item:hover {
  color: white;
  background-color: #3881ff;
}
</style>
