<template>
  <div class="flex flex-col items-center justify-center mt-[8vh] font-sans">
    <vs-alert
      v-model="active"
      :progress="progress"
      color="danger"
      class="alert"
    >
      <template #title> {{ alertMessage }} </template>
    </vs-alert>
    <form
      @submit.prevent="submitForm"
      class="form bg-white p-5 pb-8 rounded-[20px] w-full max-w-md"
    >
      <div class="logo flex flex-col items-center">
        <img src="images/logo.png" class="w-[250px]" alt="Logo" />
        <h1 class="text-3xl text-gray-700 font-[500] mt-3 mb-6">Login</h1>
        <p class="text-gray-700 text-base">
          Welcome back! Please log in to your account.
        </p>
      </div>

      <div class="flex flex-col items-center">
        <vs-input
          v-model="userCredentials.username"
          type="username"
          color="primary"
          label="Username"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> <i class="bx bx-user" /> </template>
        </vs-input>

        <vs-input
          v-model="userCredentials.password"
          :type="inputType"
          color="primary"
          label="Password"
          label-float
          required
          @click-icon="hasVisiblePassword = !hasVisiblePassword"
          icon-after
          style="width: 326px"
        >
          <template #icon>
            <i v-if="!hasVisiblePassword" class="bx bx-show-alt" />
            <i v-else class="bx bx-hide" />
          </template>
        </vs-input>

        <p class="my-6 text-gray-800">
          Don't have an account yet?
          <router-link to="/register" class="text-blue-500"
            >Register</router-link
          >
        </p>

        <vs-button class="w-[90%]">
          <h1 type="submit">Login</h1>
        </vs-button>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, computed, watch } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default {
  setup() {
    const store = useStore();
    const router = useRouter();

    const active = ref(false);
    const time = ref(2000);
    const progress = ref(0);
    const alertMessage = ref([]);

    // User credentials for login
    const userCredentials = ref({
      username: "",
      password: "",
    });

    const hasVisiblePassword = ref(false);
    const inputType = computed(() =>
      hasVisiblePassword.value ? "text" : "password"
    );

    async function submitForm() {
      try {
        await store.dispatch("login", userCredentials.value);

        router.push("/my_docs");
      } catch (error) {
        console.error(error);
        // Handle login failure (Wrong credentials)
        if (error.status === 400) {
          active.value = true; // Activate alert
          alertMessage.value = error.data.message;
        }
      }
    }

    watch(active, (val) => {
      if (val) {
        const interval = setInterval(() => {
          progress.value++;
        }, time.value / 100);

        setTimeout(() => {
          active.value = false;
          clearInterval(interval);
          progress.value = 0;
        }, time.value);
      }
    });

    return {
      userCredentials,
      submitForm,
      inputType,
      hasVisiblePassword,
      active,
      progress,
      alertMessage,
    };
  },
};
</script>

<style scoped>
.form {
  box-shadow: 2px 4px 20px rgba(0, 0, 0, 0.2);
}

.alert {
  border-radius: 10px;
  width: fit-content;
  height: 65px;
  margin-bottom: 20px;
  padding-top: 5px;
}
</style>
