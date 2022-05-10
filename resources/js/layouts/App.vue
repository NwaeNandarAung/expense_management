<template>
  <div class="bgimage">
    <nav-bar></nav-bar>

    <div class="page-wrapper">
      <router-view></router-view>
    </div>

    <footer-view></footer-view>
  </div>
</template>

<script>
import NavBar from "./Navbar";
import FooterView from "./Footer";

export default {
  components: {
    NavBar,
    FooterView,
  },
};
</script>
