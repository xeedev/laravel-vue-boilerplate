<script setup>
import { ref } from "vue";
import axios from "axios";

const file = ref(null);

const uploadFile = async () => {
  if (!file.value) return;

  let formData = new FormData();
  formData.append("file", file.value);

  try {
    await axios.post("http://127.0.0.1:8000/api/files/upload", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

 
  } catch (error) {
    console.error("Upload failed:", error);
    alert("Upload failed. Check console for details.");
  }
};
</script>

<template>
  <q-page class="q-pa-md">
    <h4>Upload File</h4>

    <q-uploader
      label="Select File"
      accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
      :auto-upload="false"
      @added="files => file.value = files[0]"
    />

    <q-btn
      label="Upload"
      color="primary"
      class="q-mt-md"
      @click="uploadFile"
    />
  </q-page>
</template>
