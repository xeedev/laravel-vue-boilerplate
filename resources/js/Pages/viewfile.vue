<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

const files = ref([]);
const search = ref("");
const page = ref(1);
const rowsPerPage = ref(5); // page size

// Load files from API
const loadFiles = async () => {
  let res = await axios.get("http://127.0.0.1:8000/api/files");
  files.value = res.data;
};

// Download file
const downloadFile = (id) => {
  window.open(`http://127.0.0.1:8000/api/files/${id}/download`, "_blank");
};

// Preview file (open in new tab)
const previewFile = (id) => {
  window.open(`http://127.0.0.1:8000/storage/uploads/${id}`, "_blank");
};

// Delete file
const deleteFile = async (id) => {
  if (confirm("Are you sure you want to delete this file?")) {
    await axios.delete(`http://127.0.0.1:8000/api/files/${id}`);
    loadFiles();
  }
};

// Filtered + Paginated data
const filteredFiles = computed(() => {
  let result = files.value.filter((f) =>
    f.original_name.toLowerCase().includes(search.value.toLowerCase())
  );
  const start = (page.value - 1) * rowsPerPage.value;
  return result.slice(start, start + rowsPerPage.value);
});

const totalPages = computed(() =>
  Math.ceil(
    files.value.filter((f) =>
      f.original_name.toLowerCase().includes(search.value.toLowerCase())
    ).length / rowsPerPage.value
  )
);

onMounted(loadFiles);
</script>

<template>
  <q-page class="q-pa-md">
    <div class="row items-center justify-between q-mb-md">
      <h4 class="q-my-none">📂 File Manager</h4>
      <q-input
        outlined
        dense
        debounce="300"
        v-model="search"
        placeholder="Search files..."
        class="w-64"
        clearable
      >
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>

    <q-list bordered separator class="rounded-borders">
      <q-item v-for="f in filteredFiles" :key="f.id">
        <q-item-section>{{ f.original_name }}</q-item-section>
        <q-item-section side>
          <q-btn
            dense
            round
            flat
            icon="visibility"
            color="primary"
            @click="previewFile(f.id)"
          />
          <q-btn
            dense
            round
            flat
            icon="cloud_download"
            color="secondary"
            @click="downloadFile(f.id)"
          />
          <q-btn
            dense
            round
            flat
            icon="delete"
            color="negative"
            @click="deleteFile(f.id)"
          />
        </q-item-section>
      </q-item>
    </q-list>

    <!-- Pagination -->
    <div class="row justify-center q-mt-md">
      <q-pagination
        v-model="page"
        :max="totalPages"
        max-pages="6"
        boundary-numbers
        color="primary"
        size="md"
      />
    </div>
  </q-page>
</template>
