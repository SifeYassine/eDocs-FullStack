<template>
  <div class="flex flex-col items-center justify-center mt-[8vh] font-sans">
    <vs-alert
      v-model="active"
      :progress="progress"
      color="danger"
      class="alert"
    >
      <template #title>
        <div>
          <p v-for="message in alertMessages" :key="message">
            {{ message }}
          </p>
        </div>
      </template>
    </vs-alert>
    <form
      @submit.prevent="submitForm"
      class="form bg-white p-5 pb-8 rounded-[20px] w-full max-w-md"
    >
      <div class="logo flex flex-col items-center">
        <img src="images/logo.png" class="w-[250px]" alt="Logo" />
        <h1 class="text-3xl text-gray-700 font-[500] mt-3 mb-6">Register</h1>
        <p class="text-gray-700 text-base">
          Welcom to eDocs, Start by creating an account
        </p>
      </div>

      <div class="flex flex-col items-center">
        <vs-input
          v-model="userCredentials.username"
          type="text"
          color="primary"
          label="Username"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> <i class="bx bx-user" /> </template>
        </vs-input>

        <vs-input
          v-model="userCredentials.email"
          type="text"
          color="primary"
          label="Email"
          label-float
          required
          style="width: 326px"
        >
          <template #icon> @ </template>
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
          Already have an account?
          <router-link to="/login" class="text-blue-500">Login</router-link>
        </p>
        <vs-button class="w-[90%]">
          <h1 type="submit">Register</h1>
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
    const time = ref(4000);
    const progress = ref(0);
    const alertMessages = ref([]);

    const userCredentials = ref({
      username: "",
      email: "",
      password: "",
    });

    const hasVisiblePassword = ref(false);
    const inputType = computed(() =>
      hasVisiblePassword.value ? "text" : "password"
    );

    async function submitForm() {
      try {
        await store.dispatch("register", userCredentials.value);

        router.push("/login");
      } catch (error) {
        console.error(error);
        // Handle registeration failure (Invalid credentials)
        if (error.status === 400) {
          active.value = true;
          // Flatten all error messages in the errors array returned from the object (array of arrays) into a single array
          alertMessages.value = Object.values(error.data.errors).flat();
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
      alertMessages,
    };
  },
};
</script>

<style scoped>
.form {
  box-shadow: 2px 4px 20px rgba(0, 0, 0, 0.2);
}

.alert {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  border-radius: 10px;
  width: fit-content;
  padding: 10px;
  margin-bottom: 20px;
  min-height: auto;
}
</style>
