<script setup>
import { ref } from "vue";

const leftDrawerOpen = ref(true);

function toggleDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>

<template>
  <q-layout view="hHh Lpr lFf">

    <!-- Top Bar -->
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-btn flat dense round icon="menu" @click="toggleDrawer" class="q-mr-sm" />
        <q-toolbar-title>
          KingstonSystem
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <!-- Sidebar -->
    <q-drawer v-model="leftDrawerOpen" show-if-above bordered class="bg-grey-2">
      <q-list padding class="column full-height">

        <!-- Upload -->
        <q-item clickable v-ripple>
          <q-item-section avatar><q-icon name="cloud_upload" /></q-item-section>
          <q-item-section>Upload File</q-item-section>
        </q-item>

        <!-- Download -->
        <q-item clickable v-ripple>
          <q-item-section avatar><q-icon name="cloud_download" /></q-item-section>
          <q-item-section>Download File</q-item-section>
        </q-item>

        <!-- View -->
        <q-item clickable v-ripple>
          <q-item-section avatar><q-icon name="folder_open" /></q-item-section>
          <q-item-section>View Files</q-item-section>
        </q-item>

        <!-- Spacer pushes logout to bottom -->
        <q-space />

        <!-- Logout -->
        <q-item clickable v-ripple>
          <q-item-section avatar><q-icon name="logout" /></q-item-section>
          <q-item-section>Logout</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Main Content -->
    <q-page-container>
      <q-page class="q-pa-md">
        <h4>Welcome to KingstonSystem Dashboard 🚀</h4>
        <p>Select an option from the sidebar.</p>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
