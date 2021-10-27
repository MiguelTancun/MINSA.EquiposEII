<template>
 <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-danger elevation-4">
    <!-- Brand Logo -->
    <router-link to="/" class="brand-link">
          <img src="images/Minsa-pc.jpeg" alt="LaraVue Logo" 
           style="width:235px; height:auto;">
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
              <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">
             Bienvenido:  {{ activeUserInfo.displayName }}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
           <router-link to="/" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                GeoRIS
              </p>
            </router-link>
          </li>
          <li class="nav-item">
           <router-link to="/home" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Equipos I.I.
              </p>
            </router-link>
          </li>
          <li class="nav-item">
           <router-link to="salud" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Salud
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click="logout" style="cursor: pointer">
                <i class="nav-icon fa fa-power-off"></i>
                <p>
                  Salir
                </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>
<script>
export default {
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    }
  },
  methods: {
    logout () {
      this.$store.dispatch('logout');
      this.$router.push('/login').catch(() => {})
    }
  }
}
</script>