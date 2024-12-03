<template>
  <footer class="footer w-[85%] bg-[#345BFF] mx-auto my-10 rounded-2xl p-10">
    <div
      class="flex flex-col-reverse justify-between gap-5 lg:flex-row lg:gap-0"
    >
      <div class="flex flex-col items-center gap-16">
        <h3 class="font-bold text-2xl text-white">eDocs</h3>
        <div class="flex justify-center gap-4 lg:justify-start">
          <a href="#">
            <img
              src="https://img.icons8.com/?size=512&id=118497&format=png"
              alt="facebook"
              class="h-8"
            />
          </a>
          <a href="#">
            <img
              src="https://img.icons8.com/?size=512&id=19318&format=png"
              alt="youtube"
              class="h-8"
            />
          </a>
          <a href="#">
            <img
              src="https://img.icons8.com/?size=512&id=13963&format=png"
              alt="twitter"
              class="h-8"
            />
          </a>
          <a href="#">
            <img
              src="https://img.icons8.com/?size=512&id=Xy10Jcu1L2Su&format=png"
              alt="instagram"
              class="h-8"
            />
          </a>
        </div>
      </div>
      <div class="flex justify-around gap-12">
        <div class="flex flex-col gap-3">
          <a href="#" class="text-white no-underline hover:text-purple-300"
            >Home</a
          >
          <a href="#" class="text-white no-underline hover:text-purple-300"
            >About Us</a
          >
        </div>
        <div class="flex flex-col gap-3">
          <a href="#" class="text-white no-underline hover:text-purple-300"
            >FAQ</a
          >
          <a href="#" class="text-white no-underline hover:text-purple-300"
            >Privacy Policy</a
          >
          <a href="#" class="text-white no-underline hover:text-purple-300"
            >Terms of Service</a
          >
        </div>
      </div>
      <div class="flex flex-col justify-between gap-5 lg:w-1/3">
        <form>
          <div class="flex w-[80%] ml-12">
            <input
              type="text"
              class="flex-1 p-2 rounded-l-full border-none outline-none"
              placeholder="Updated in your inbox"
            />
            <button
              class="w-[20%] p-2 bg-[#4F9CE6] text-white rounded-r-full border-none cursor-pointer hover:bg-blue-500"
            >
              Go
            </button>
          </div>
        </form>
        <div class="text-white text-center lg:block">
          Copyright &copy; eDocs 2024, All Rights Reserved
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "Footer",
};
</script>

<style scoped>
.footer {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
</style>
