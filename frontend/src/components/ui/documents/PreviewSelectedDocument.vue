<template>
  <vs-dialog auto-width not-padding>
    <template #header>
      <h2 class="text-2xl text-gray-800 mt-3">Document Preview</h2>
    </template>
    <div class="p-5 relative">
      <!-- Office Documents Preview -->
      <div
        v-if="document && officeFormats.includes(document.format)"
        class="flex justify-center"
      >
        <iframe
          :src="`${msViewerUrl}${baseUrl}${document.path_url}`"
          frameborder="0"
          class="w-[850px] h-[65vh]"
        ></iframe>
      </div>

      <!-- Google Documents Preview -->
      <div
        v-else-if="document && googleFormats.includes(document.format)"
        class="flex justify-center"
      >
        <iframe
          :src="`${googleViewerUrl}${baseUrl}${document.path_url}&embedded=true`"
          frameborder="0"
          class="w-[850px] h-[65vh]"
        ></iframe>
      </div>

      <!-- Image Preview -->
      <div
        v-else-if="document && imageFormats.includes(document.format)"
        class="flex justify-center"
        style="width: fit-content"
      >
        <img
          :src="`${baseUrl}${document.path_url}`"
          class="w-[700px] h-[400px] object-contain"
          alt="Preview"
        />
      </div>

      <!-- Video Preview -->
      <div
        v-else-if="document && videoFormats.includes(document.format)"
        class="flex justify-center"
      >
        <video
          :src="`${baseUrl}${document.path_url}`"
          class="w-[700px] h-[400px] object-contain"
          controls
          muted
          autoplay
          loop
          alt="Preview"
        />
      </div>
    </div>
  </vs-dialog>
</template>

<script>
export default {
  name: "PreviewSelectedDocument",
  props: {
    document: {
      type: Object,
    },
  },

  setup() {
    // const baseUrl = "https://char-zu-invision-end.trycloudflare.com";
    const baseUrl = "http://127.0.0.1:8000";

    const msViewerUrl = "https://view.officeapps.live.com/op/embed.aspx?src=";
    const googleViewerUrl = "https://docs.google.com/viewer?url=";

    const officeFormats = ["docx", "xlsx", "pptx"];
    const googleFormats = ["pdf", "txt"];
    const imageFormats = ["png", "jpg", "jpeg", "gif"];
    const videoFormats = ["mp4"];

    return {
      msViewerUrl,
      googleViewerUrl,
      baseUrl,
      officeFormats,
      googleFormats,
      imageFormats,
      videoFormats,
    };
  },
};
</script>
