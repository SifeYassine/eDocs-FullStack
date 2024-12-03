<template>
  <div class="ml-6 mb-6">
    <vs-input
      v-model="query"
      @input="debouncedSearch"
      type="text"
      color="primary"
      label="Search..."
      label-float
      required
      style="width: 350px"
    >
      <template #icon> &#x1F50E;&#xFE0E; </template>
    </vs-input>
  </div>
</template>

<script>
import { ref } from "vue";
import { useStore } from "vuex";

export default {
  name: "SearchBar",
  setup() {
    const store = useStore();
    const query = ref("");
    let debounceTimer = null;

    function debouncedSearch() {
      if (debounceTimer) {
        clearTimeout(debounceTimer);
      }

      debounceTimer = setTimeout(() => {
        store.dispatch("searchQuery", query.value);
      }, 300);
    }

    return {
      query,
      debouncedSearch,
    };
  },
};
</script>
