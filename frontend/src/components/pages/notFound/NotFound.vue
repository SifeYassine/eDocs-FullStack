<template>
  <div class="flex flex-col items-center justify-center h-screen text-center">
    <h1 class="text-[10rem] text-red-500 font-bold m-0">404</h1>
    <p class="text-xl mt-4 mb-6">
      Oops! The page you're looking for doesn't exist.
    </p>
    <router-link to="/" class="text-lg text-blue-500 hover:underline"
      >Go back to Home</router-link
    >
  </div>
</template>

<script>
export default {
  name: "NotFound",
};
</script>
