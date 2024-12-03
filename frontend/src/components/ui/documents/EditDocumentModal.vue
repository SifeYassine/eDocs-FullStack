<template>
  <vs-dialog width="20vw">
    <template #header>
      <h4>Edit Document</h4>
    </template>
    <div class="p-5">
      <div v-if="document">
        <vs-input
          v-model="document.title"
          @keyup.enter="submitForm()"
          type="text"
          color="primary"
          label="Title"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> # </template>
        </vs-input>

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

        <vs-button @click="submitForm()" color="primary">
          Edit Document
        </vs-button>
      </div>
    </div>
  </vs-dialog>
</template>

<script>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";

export default {
  name: "EditDocumentModal",
  props: {
    document: {
      type: Object,
    },
  },
  setup(props) {
    const active = ref(props.active);
    const store = useStore();
    const categories = computed(() => store.getters.getCategories);
    const category_id = ref(null);

    async function fetchCategories() {
      try {
        await store.dispatch("fetchCategories");
      } catch (error) {
        console.error("Failed to fetch categories:", error);
      }
    }

    async function submitForm() {
      try {
        const updatedDocument = {
          ...props.document,
          category_id: category_id.value,
        };
        await store.dispatch("editDocument", updatedDocument);

        // Fetch updated documents
        await store.dispatch("fetchDocuments");
      } catch (error) {
        console.error("Failed to submit form:", error);
      }
    }

    onMounted(() => {
      fetchCategories();
    });

    return {
      categories,
      category_id,
      submitForm,
    };
  },
};
</script>
