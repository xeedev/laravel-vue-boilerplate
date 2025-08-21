<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const file = ref(null);
const files = ref([]);
const loading = ref(false);

async function loadFiles() {
  const { data } = await axios.get("/files?per_page=50");
  // if using api.php, use "/api/files?per_page=50"
  // when using pagination, data.data contains rows
  files.value = data.data ?? data;
}

async function upload() {
  if (!file.value) return;

  const fd = new FormData();
  fd.append('file', file.value);

  loading.value = true;
  try {
    await axios.post('/files/upload', fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    file.value = null;
    await loadFiles();
  } finally {
    loading.value = false;
  }
}

function viewFile(id) {
  window.open(`/files/view/${id}`, '_blank');
}

function downloadFile(id) {
  window.location.href = `/files/download/${id}`;
}

onMounted(loadFiles);
</script>

<template>
  <div class="q-pa-md">
    <div class="text-h6 q-mb-md">Upload & Manage Files</div>

    <div class="row items-center q-gutter-sm q-mb-md">
      <input type="file" @change="e => file.value = e.target.files[0]" />
      <q-btn color="primary" :loading="loading" label="Upload" @click="upload" />
    </div>

    <q-table
      title="Files"
      :rows="files"
      :columns="[
        { name: 'original_name', label: 'File Name', field: 'original_name', align: 'left' },
        { name: 'size', label: 'Size (KB)', field: row => (row.size/1024).toFixed(2), align: 'right' },
        { name: 'created_at', label: 'Uploaded At', field: 'created_at', align: 'left' },
        { name: 'actions', label: 'Actions', field: 'id', align: 'left' }
      ]"
      row-key="id"
      flat
      bordered
    >
      <template #body-cell-actions="props">
        <q-btn dense flat size="sm" color="primary" label="View" @click="viewFile(props.row.id)" />
        <q-btn dense flat size="sm" color="secondary" label="Download" @click="downloadFile(props.row.id)" />
      </template>
    </q-table>
  </div>
</template>
