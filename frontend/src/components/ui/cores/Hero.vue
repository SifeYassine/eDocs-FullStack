<template>
  <main class="w-[92vw] mx-auto mt-[120px] flex flex-col items-center">
    <section class="hero grid mx-7 my-2 lg:grid-cols-2">
      <div class="left text-left mt-[65px] px-16 py-0">
        <h1 class="hero-title text-6xl text-gray-900 font-bold mx-0 my-7">
          Store, <br />View Your Files, Anytime, Anywhere
        </h1>
        <p class="hero-description text-gray-700 text-xl">
          Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quos
          molestias corrupti debitis, esse.
        </p>
        <vs-button color="primary" style="margin-top: 20px">
          <router-link to="/register" class="text-white">
            Get Started
          </router-link>
        </vs-button>
      </div>
      <div class="right p-5">
        <img
          src="images/hero.png"
          alt="Hero Image"
          class="hero-image w-[100%] rounded-xl"
        />
      </div>
    </section>

    <h1 class="text-5xl text-gray-900 font-bold mb-8">Supported Formats</h1>
    <section
      id="formats"
      class="w-[70%] mx-auto rounded-2xl p-5 grid gap-6 gap-y-10 lg:grid-cols-4"
    >
      <article class="flex flex-col items-center">
        <img src="images/pdf.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">PDF</h1>
      </article>

      <article class="flex flex-col items-center">
        <img src="images/docx.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">DOCX</h1>
      </article>

      <article class="flex flex-col items-center">
        <img src="images/xlsx.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">XLSX</h1>
      </article>

      <article class="flex flex-col items-center">
        <img src="images/pptx.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">PPTX</h1>
      </article>

      <article class="flex flex-col items-center">
        <img src="images/txt.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">TXT</h1>
      </article>

      <article class="flex flex-col items-center text-center">
        <img src="images/png.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">
          Images <br />(PNG, JPG, JPEG)
        </h1>
      </article>

      <article class="flex flex-col items-center">
        <img src="images/mp4.png" alt="" class="w-[100px]" />
        <h1 class="text-2xl text-gray-900 ml-5">MP4</h1>
      </article>
    </section>
  </main>
</template>

<script>
export default {
  name: "Hero",
};
</script>
