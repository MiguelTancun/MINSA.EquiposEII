<template>
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mt-5">
      <div class="info-box">
        <span class="info-box-icon bg-primary"
          ><i class="fa fa-building"></i
        ></span>

        <div class="info-box-content">
          <span class="info-box-text">Establecimientos</span>
           <div class="spinner-border" role="status" v-show="loadingCenter"></div>
          <span class="info-box-number" v-show="!loadingCenter">{{countCenter}}</span>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mt-5">
      <div class="info-box">
        <span class="info-box-icon bg-success"><i class="fa fa-user"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Personal</span>
          <div class="spinner-border" role="status" v-show="loadingEmployee"></div>
          <span class="info-box-number"  v-show="!loadingEmployee">{{countEmployee}}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import store from "../store/store";

export default {
  data() {
    return {
      centers: {},
      countCenter: 0,
      employees: {},
      countEmployee: 0,
      loadingCenter: false,
      loadingEmployee: false
    };
  },
  mounted() {
      this.getCenter()
      this.getEmployee()
  },
  methods: {
    getCenter() {
      try {
        this.loadingCenter = true;
        axios
          .get("/api/centro/all", {
            headers: 
            { Authorization: `Bearer ${store.state.token}`}
          })
          .then((res) => {
            this.loadingCenter = false;
            this.centers = res.data.data;
            this.countCenter = this.centers.length;
          })
          .catch((error) => {
            if (error.response.data.code  == 401) {
              this.$store.dispatch("logout");
              this.$router.push("/login").catch(() => {});
            }
          });
      } catch (err) {
        console.log(err);
      }
    },
    getEmployee() {
      try {
        this.loadingEmployee = true;
        axios
          .get("/api/usuario/all", {
            headers: {
              Authorization: `Bearer ${store.state.token}`,

            },
          })
          .then((res) => {
            this.loadingEmployee = false;
            this.employees = res.data.data;
            this.countEmployee = this.employees.length;
          })
          .catch((error) => {
            if (error.response.data.code  == 401) {
              this.$store.dispatch("logout");
              this.$router.push("/login").catch(() => {});
            }
          });
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>
