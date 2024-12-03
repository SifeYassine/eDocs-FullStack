import { createStore } from "vuex";
import axios from "@/api/axios";

export default createStore({
  state: {
    // Category modal state
    categoryModal: {
      isOpen: false,
      mode: null, // 'add' or 'edit'
      category: null, // empty category
    },

    // Role modal state
    roleModal: {
      isOpen: false,
      mode: null, // 'add' or 'edit'
      role: null, // empty role
    },

    // Permission modal state
    permissionModal: {
      isOpen: false,
      mode: null, // 'add' or 'edit'
      permission: null, // empty permission
    },

    // User modal state
    userModal: {
      isOpen: false,
      user: null,
    },

    // Authentication (token & Ids) state
    tokenIds: {
      token: localStorage.getItem("token") || "",
      user_id: null,
      role_id: null,
    },

    searchQuery: "",
    categories: [],
    documents: [],
    selectedDocument: null,
    userPermissions: [],
    roles: [],
    permissions: [],
    users: [],
  },
  mutations: {
    // Setters
    setTokenIds(state, { token, user_id, role_id }) {
      state.tokenIds.token = token;
      localStorage.setItem("token", token);
      state.tokenIds.user_id = user_id;
      state.tokenIds.role_id = role_id;
    },

    setCategoryModal(state, { isOpen, mode, category }) {
      state.categoryModal.isOpen = isOpen;
      state.categoryModal.mode = mode;
      state.categoryModal.category = category;
    },

    setRoleModal(state, { isOpen, mode, role }) {
      state.roleModal.isOpen = isOpen;
      state.roleModal.mode = mode;
      state.roleModal.role = role;
    },

    setPermissionModal(state, { isOpen, mode, permission }) {
      state.permissionModal.isOpen = isOpen;
      state.permissionModal.mode = mode;
      state.permissionModal.permission = permission;
    },

    setUserModal(state, { isOpen, user }) {
      state.userModal.isOpen = isOpen;
      state.userModal.user = user;
    },

    setSearchQuery(state, query) {
      state.searchQuery = query;
    },

    setCategories(state, categories) {
      state.categories = categories;
    },

    setDocuments(state, documents) {
      state.documents = documents;
    },

    setSelectedDocument(state, document) {
      state.selectedDocument = document;
    },

    setUserPermissions(state, userPermissions) {
      state.userPermissions = userPermissions;
    },

    setRoles(state, roles) {
      state.roles = roles;
    },

    setPermissions(state, permissions) {
      state.permissions = permissions;
    },

    setUsers(state, users) {
      state.users = users;
    },

    // Clear
    clearTokenIds(state) {
      state.tokenIds.token = "";
      localStorage.removeItem("token");
      state.tokenIds.user_id = null;
      state.tokenIds.role_id = null;
    },

    clearCategories(state) {
      state.categories = [];
    },

    clearDocuments(state) {
      state.documents = [];
    },

    clearSelectedDocument(state) {
      state.selectedDocument = null;
    },

    clearUserPermissions(state) {
      state.userPermissions = [];
    },

    clearRoles(state) {
      state.roles = [];
    },

    clearPermissions(state) {
      state.permissions = [];
    },

    clearUsers(state) {
      state.users = [];
    },
  },
  actions: {
    // Authentication
    async register({ commit }, credentials) {
      try {
        const { data } = await axios.post("/auth/register", credentials);
      } catch (error) {
        console.error(error);
        if (error.response) {
          // Throw the response error so that it can be caught in the component
          throw error.response;
        }
      }
    },

    async login({ commit }, credentials) {
      try {
        const { data } = await axios.post("/auth/login", credentials);

        commit("setTokenIds", {
          token: data.access_token,
          user_id: data.user.id,
          role_id: data.user.role_id,
        });
      } catch (error) {
        console.error(error);
        if (error.response) {
          // Throw the response error so that it can be caught in the component
          throw error.response;
        }
      }
    },

    async getMyProfile({ commit, state }) {
      try {
        const { data } = await axios.get("/users/get_profile");

        commit("setTokenIds", {
          token: state.tokenIds.token, // Keep the existing token
          user_id: data.user.id,
          role_id: data.user.role_id,
        });
      } catch (error) {
        console.error("Failed to get profile:", error);
      }
    },

    async getUserPermissions({ commit }, userId) {
      try {
        const { data } = await axios.get(`/users/${userId}/permissions/index`);

        // Extract the labels from the user permissions array
        const permissionLabels = data.permissions.map(
          (permission) => permission.label
        );

        commit("setUserPermissions", permissionLabels);
      } catch (error) {
        console.error("Failed to get user permissions:", error);
      }
    },

    async logout({ commit }) {
      await axios.post("/auth/logout");

      commit("clearTokenIds");
      commit("clearCategories");
      commit("clearDocuments");
      commit("clearSelectedDocument");
      commit("clearUserPermissions");
      commit("clearRoles");
      commit("clearUsers");
    },

    searchQuery({ commit }, query) {
      commit("setSearchQuery", query);
    },

    // Category modal state triggers
    openCategoryModal({ commit }, { mode, category }) {
      commit("setCategoryModal", {
        isOpen: true,
        mode,
        category: category || { name: "" }, // empty category for 'add' mode
      });
    },

    closeCategoryModal({ commit }) {
      commit("setCategoryModal", {
        isOpen: false,
        mode: null,
        category: null,
      });
    },

    // Role modal state triggers
    openRoleModal({ commit }, { mode, role }) {
      commit("setRoleModal", {
        isOpen: true,
        mode,
        role: role || { name: "", description: "" }, // empty role for 'add' mode
      });
    },

    closeRoleModal({ commit }) {
      commit("setRoleModal", {
        isOpen: false,
        mode: null,
        role: null,
      });
    },

    // Permission modal state triggers
    openPermissionModal({ commit }, { mode, permission }) {
      commit("setPermissionModal", {
        isOpen: true,
        mode,
        permission: permission || { label: "", description: "" }, // empty permission for 'add' mode
      });
    },

    closePermissionModal({ commit }) {
      commit("setPermissionModal", {
        isOpen: false,
        mode: null,
        permission: null,
      });
    },

    // User modal state triggers
    openUserModal({ commit }, { user }) {
      commit("setUserModal", {
        isOpen: true,
        user: user,
      });
    },

    closeUserModal({ commit }) {
      commit("setUserModal", {
        isOpen: false,
        user: null,
      });
    },

    // Categories CRUD
    async fetchCategories({ commit }) {
      try {
        const { data } = await axios.get("/categories/index");

        commit("setCategories", data.categories);
      } catch (error) {
        console.error("Failed to fetch categories:", error);
      }
    },

    async addCategory({ commit, state }, category) {
      try {
        const { data } = await axios.post("/categories/create", category);

        // Add the newly created category to the state by appending it to the existing categories
        commit("setCategories", [...state.categories, data.category]);
      } catch (error) {
        console.error("Failed to add category:", error);
      }
    },

    async editCategory({ commit, state }, category) {
      try {
        await axios.put(`/categories/update/${category.id}`, category);

        // Update the state with the edited category by replacing the matching category in the array
        const editedCategories = state.categories.map((categ) =>
          // Replace the category whose Id matches with the updated category, otherwise keep the existing category
          categ.id === category.id ? category : categ
        );
        commit("setCategories", editedCategories);
      } catch (error) {
        console.error("Failed to edit category:", error);
      }
    },

    async deleteCategory({ commit, state }, categoryId) {
      try {
        await axios.delete(`/categories/delete/${categoryId}`);

        // Remove the deleted category from the state by filtering it out
        const deletedCategories = state.categories.filter(
          (category) =>
            // Only keep categories whose Ids don't match the deleted category Id
            category.id !== categoryId
        );
        commit("setCategories", deletedCategories);
      } catch (error) {
        console.error("Failed to delete category:", error);
      }
    },

    // Documents CRUD
    async fetchDocuments({ commit }) {
      try {
        const { data } = await axios.get("/documents/index");

        commit("setDocuments", data.documents);
      } catch (error) {
        console.error("Failed to fetch documents:", error);
      }
    },

    async addDocument({ commit, state }, document) {
      try {
        // Create a FormData object from the document object
        const formData = new FormData();

        // Loop through the document object to append all fields to FormData
        for (const key in document) {
          // If the document does not have a category id, skip it
          if (key === "category_id" && !document[key]) {
            continue;
          }
          formData.append(key, document[key]);
        }

        //Make the API request with the FormData object
        const { data } = await axios.post("/documents/create", formData);

        // Add the newly created document to the state by appending it to the existing documents
        commit("setDocuments", [...state.documents, data.document]);
      } catch (error) {
        console.error("Failed to add document:", error);
      }
    },

    getDocumentById({ commit, state }, id) {
      // Find the document with the matching id in documents array, then set the found document to the state
      const selectedDocument = state.documents.find(
        (document) => document.id === id
      );

      commit("setSelectedDocument", selectedDocument);
    },

    async editDocument({ commit, state }, document) {
      try {
        await axios.put(`/documents/update/${document.id}`, document);

        // Update the state with the edited document by replacing the matching document in the array
        const editedDocuments = state.documents.map((doc) =>
          // Replace the document whose Id matches with the updated document, otherwise keep the existing document
          doc.id === document.id ? document : doc
        );
        commit("setDocuments", editedDocuments);
      } catch (error) {
        console.error("Failed to edit document:", error);
      }
    },

    async deleteDocument({ commit, state }, id) {
      try {
        await axios.delete(`/documents/delete/${id}`);

        // Remove the deleted document from the state by filtering it out
        const deletedDocuments = state.documents.filter(
          (document) =>
            // Only keep documents whose Ids don't match the deleted document Id
            document.id !== id
        );
        commit("setDocuments", deletedDocuments);
      } catch (error) {
        console.error("Failed to delete document:", error);
      }
    },

    // Roles CRUD
    async fetchRoles({ commit }) {
      try {
        const { data } = await axios.get("/roles/index");

        commit("setRoles", data.roles);
      } catch (error) {
        console.error("Failed to fetch roles:", error);
      }
    },

    async addRole({ commit, state }, role) {
      try {
        const { data } = await axios.post("/roles/create", role);

        commit("setRoles", [...state.roles, data.role]);
      } catch (error) {
        console.error("Failed to add role:", error);
      }
    },

    async editRole({ commit, state }, role) {
      try {
        await axios.put(`/roles/update/${role.id}`, role);

        // Update the state with the edited role by replacing the matching role in the array
        const editedRoles = state.roles.map((rle) =>
          // Replace the role whose Id matches with the updated role, otherwise keep the existing role
          rle.id === role.id ? role : rle
        );
        commit("setRoles", editedRoles);
      } catch (error) {
        console.error("Failed to edit role:", error);
      }
    },

    async deleteRole({ commit, state }, id) {
      try {
        await axios.delete(`/roles/delete/${id}`);

        // Remove the deleted role from the state by filtering it out
        const deletedRoles = state.roles.filter(
          (role) =>
            // Only keep roles whose Ids don't match the deleted role Id
            role.id !== id
        );
        commit("setRoles", deletedRoles);
      } catch (error) {
        console.error("Failed to delete role:", error);
      }
    },

    // Users CRUD
    async fetchUsers({ commit }) {
      try {
        const { data } = await axios.get("/users/index");

        commit("setUsers", data.users);
      } catch (error) {
        console.error("Failed to fetch users:", error);
      }
    },

    async editUser({ commit, state }, user) {
      try {
        await axios.put(`/users/update/${user.id}`, user);

        // Update the state with the edited user by replacing the matching user in the array
        const editedUsers = state.users.map((usr) =>
          // Replace the user whose Id matches with the updated user, otherwise keep the existing user
          usr.id === user.id ? user : usr
        );
        commit("setUsers", editedUsers);
      } catch (error) {
        console.error("Failed to edit user:", error);
      }
    },

    async deleteUser({ commit, state }, id) {
      try {
        await axios.delete(`/users/delete/${id}`);

        // Remove the deleted user from the state by filtering it out
        const deletedUsers = state.users.filter(
          (user) =>
            // Only keep users whose Ids don't match the deleted user Id
            user.id !== id
        );
        commit("setUsers", deletedUsers);
      } catch (error) {
        console.error("Failed to delete user:", error);
      }
    },

    // Permissions CRUD
    async fetchPermissions({ commit }) {
      try {
        const { data } = await axios.get("/permissions/index");

        commit("setPermissions", data.permissions);
      } catch (error) {
        console.error("Failed to fetch permissions:", error);
      }
    },

    async addPermission({ commit, state }, permission) {
      try {
        const { data } = await axios.post("/permissions/create", permission);

        commit("setPermissions", [...state.permissions, data.permission]);
      } catch (error) {
        console.error("Failed to add permission:", error);
      }
    },

    async editPermission({ commit, state }, permission) {
      try {
        await axios.put(`/permissions/update/${permission.id}`, permission);

        // Update the state with the edited permission by replacing the matching permission in the array
        const editedPermissions = state.permissions.map((prmssn) =>
          // Replace the permission whose Id matches with the updated permission, otherwise keep the existing permission
          prmssn.id === permission.id ? permission : prmssn
        );
        commit("setPermissions", editedPermissions);
      } catch (error) {
        console.error("Failed to edit permission:", error);
      }
    },

    async deletePermission({ commit, state }, id) {
      try {
        await axios.delete(`/permissions/delete/${id}`);

        // Remove the deleted permission from the state by filtering it out
        const deletedPermissions = state.permissions.filter(
          (permission) =>
            // Only keep permissions whose Ids don't match the deleted permission Id
            permission.id !== id
        );
        commit("setPermissions", deletedPermissions);
      } catch (error) {
        console.error("Failed to delete permission:", error);
      }
    },
  },

  // Getters
  getters: {
    isAuthenticated: (state) => state.tokenIds,
    isAdmin: (state) => state.tokenIds.role_id === 1,
    getCategories: (state) => state.categories,
    getDocuments: (state) => state.documents,
    getSelectedDocument: (state) => state.selectedDocument,
    getCategoryModal: (state) => state.categoryModal,
    getRoleModal: (state) => state.roleModal,
    getPermissionModal: (state) => state.permissionModal,
    getUserModal: (state) => state.userModal,
    getSearchQuery: (state) => state.searchQuery,
    getUserPermissions: (state) => state.userPermissions,
    getRoles: (state) => state.roles,
    getPermissions: (state) => state.permissions,
    getUsers: (state) => state.users,
  },
});
