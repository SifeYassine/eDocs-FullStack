<template>
  <div>
    <Header />
    <Hero />
    <Footer />
  </div>
</template>

<script>
import "@/assets/style.css";
import Header from "@/components/ui/cores/Header.vue";
import Hero from "@/components/ui/cores/Hero.vue";
import Footer from "@/components/ui/cores/Footer.vue";

export default {
  name: "Home",
  components: {
    Header,
    Hero,
    Footer,
  },
};
</script>
