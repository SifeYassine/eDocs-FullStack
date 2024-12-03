import { createRouter, createWebHistory } from "vue-router";
import store from "@/store"; // Import the Vuex store to check authentication state

import Home from "@/components/pages/home/Home.vue";
import Login from "@/components/pages/auth/LoginForm.vue";
import Register from "@/components/pages/auth/RegisterForm.vue";
import Categories from "@/components/pages/categories/Categories.vue";
import Documents from "@/components/pages/documents/Documents.vue";
import Users from "@/components/pages/users/Users.vue";
import Roles from "@/components/pages/roles/Roles.vue";
import Permissions from "@/components/pages/permissions/Permissions.vue";
import NotFound from "@/components/pages/notFound/NotFound.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/login", component: Login },
  { path: "/register", component: Register },
  { path: "/categories", component: Categories },
  { path: "/my_docs", component: Documents },
  { path: "/users", component: Users },
  { path: "/roles", component: Roles },
  { path: "/permissions", component: Permissions },
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const needsAuthPages = [
    "/my_docs",
    "/categories",
    "/users",
    "/roles",
    "/permissions",
  ];

  const isAuthenticated = store.getters.isAuthenticated.token;

  // If route is not found (doesn't exist), go to not found page
  if (to.name === "NotFound") {
    return next();
  }

  // If the route requires authentication and user is not authenticated, redirect to /register
  if (needsAuthPages.includes(to.path) && !isAuthenticated) {
    return next("/register");
  }

  // Otherwise, proceed to the route
  next();
});

export default router;
