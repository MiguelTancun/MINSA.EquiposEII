<template>
  <div>
    <navbar ref="navbar"></navbar>
    <app-sidebar></app-sidebar>
    <div class="content-wrapper">
      <div class="content">
        <router-view></router-view>
      </div>
    </div>
    <app-footer></app-footer>
  </div>
</template>

<script>
import Navbar from "../../components/Navbar";
import Header from "../../components/Header";
import Footer from "../../components/Footer";
import Sidebar from "../../components/Sidebar.vue";

export default {
  components: {
    navbar: Navbar,
    "app-header": Header,
    "app-footer": Footer,
    "app-sidebar": Sidebar,
  },
};
</script>
