<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";

const leftDrawerOpen = ref(true);
const activePage = ref("home");

// Upload state
const file = ref(null);
const uploadFile = async () => {
  if (!file.value) return;
  let formData = new FormData();
  formData.append("file", file.value);

  await axios.post("http://127.0.0.1:8000/api/files/upload", formData, {
    headers: { "Content-Type": "multipart/form-data" },
  });

 
  activePage.value = "view";
  loadFiles();
};

// Files state
const files = ref([]);
const loadFiles = async () => {
  let res = await axios.get("http://127.0.0.1:8000/api/files");
  files.value = res.data;
};

// File actions
const downloadFile = (id) => {
  window.open(`http://127.0.0.1:8000/api/files/${id}/download`, "_blank");
};
const previewFile = (id) => {
  window.open(`http://127.0.0.1:8000/api/files/${id}/view`, "_blank");
};
const deleteFile = async (id) => {
  if (!confirm("Are you sure you want to delete this file?")) return;
  await axios.delete(`http://127.0.0.1:8000/api/files/${id}`);

  loadFiles();
};

// Search & Pagination
const search = ref("");
const page = ref(1);
const rowsPerPage = 5;

const filteredFiles = computed(() =>
  files.value.filter((f) =>
    f.original_name.toLowerCase().includes(search.value.toLowerCase())
  )
);

const maxPages = computed(() =>
  Math.ceil(filteredFiles.value.length / rowsPerPage)
);

const paginatedFiles = computed(() => {
  const start = (page.value - 1) * rowsPerPage;
  return filteredFiles.value.slice(start, start + rowsPerPage);
});

onMounted(() => {
  loadFiles();
});
</script>

<template>
  <q-layout view="hHh Lpr lFf">
    <!-- Top Bar -->
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-btn flat dense round icon="menu" @click="leftDrawerOpen = !leftDrawerOpen" class="q-mr-sm" />
        <q-toolbar-title>KingstonSystem</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <!-- Sidebar -->
    <q-drawer v-model="leftDrawerOpen" show-if-above bordered class="bg-grey-2">
      <q-list padding class="column full-height">
        <q-item clickable v-ripple @click="activePage = 'upload'">
          <q-item-section avatar><q-icon name="cloud_upload" /></q-item-section>
          <q-item-section>Upload File</q-item-section>
        </q-item>

        <q-item clickable v-ripple @click="activePage = 'view'; loadFiles()">
          <q-item-section avatar><q-icon name="folder_open" /></q-item-section>
          <q-item-section>View Files</q-item-section>
        </q-item>

        <q-space />

        <q-item clickable v-ripple>
          <q-item-section avatar><q-icon name="logout" /></q-item-section>
          <q-item-section>Logout</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Main Content -->
    <q-page-container>
      <q-page class="q-pa-md">

        <!-- Home -->
        <div v-if="activePage === 'home'">
          <h4>Welcome to KingstonSystem Dashboard 🚀</h4>
          <p>Select an option from the sidebar.</p>
        </div>

        <!-- Upload -->
        <div v-if="activePage === 'upload'">
          <h4>Upload File</h4>
          <q-uploader
            label="Select File"
            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
            @added="files => file = files[0]"
            :auto-upload="false"
          />
          <q-btn label="Upload" color="primary" class="q-mt-md" @click="uploadFile" />
        </div>

      <!-- View Files -->
<div v-if="activePage === 'view'">
  <h4 class="q-mb-md">📂 Files</h4>

  <!-- Search Bar -->
  <q-input
    filled
    v-model="searchQuery"
    label="Search files..."
    dense
    clearable
    debounce="300"
    class="q-mb-md"
    @input="filterFiles"
  >
    <template v-slot:append>
      <q-icon name="search" />
    </template>
  </q-input>

  <!-- Grid of Files -->
  <div class="row q-col-gutter-md">
    <div
      v-for="f in paginatedFiles"
      :key="f.id"
      class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
    >
      <q-card class="q-hoverable cursor-pointer">
        <q-card-section class="flex flex-center column text-center">
          <!-- Folder/File Icon -->
          <q-icon name="folder" size="64px" color="primary" />

          <!-- File Name -->
          <div class="text-subtitle1 q-mt-sm ellipsis">
            {{ f.original_name }}
          </div>
        </q-card-section>

        <q-separator />

        <!-- Action Buttons (horizontal row) -->
        <q-card-actions align="around">
          <q-btn flat round dense icon="visibility" color="primary" @click="previewFile(f.id)" />
          <q-btn flat round dense icon="cloud_download" color="secondary" @click="downloadFile(f.id)" />
          <q-btn flat round dense icon="delete" color="negative" @click="deleteFile(f.id)" />
        </q-card-actions>
      </q-card>
    </div>
  </div>

  <!-- Pagination -->
  <div class="q-mt-lg flex justify-center">
    <q-pagination
      v-model="currentPage"
      :max="totalPages"
      boundary-numbers
      direction-links
      color="primary"
    />
  </div>
</div>


      </q-page>
    </q-page-container>
  </q-layout>
</template>
