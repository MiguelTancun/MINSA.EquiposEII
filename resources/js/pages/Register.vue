<template>
  <div>
    <div
      class="d-flex justify-content-center cover-loading"
      v-bind:style="{ display: loading }"
    ></div>
    <div class="row mt-5">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
        <h5><strong>Nueva asignación de establecimiento</strong></h5>
      </div>
      <div
        class="
          col-xs-12 col-sm-12 col-md-3 col-lg-3
          offset-lg-3
          text-right
          mb-2
        "
      >
        <router-link
          to="salud"
          type="button"
          class="btn btn-dark btn-sm btn-block"
          ><i class="fa fa-angle-left"></i> Regresar</router-link
        >
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="alert alert-danger" role="alert" v-show="isVisibleMessage">
          {{ mensajeError }}
        </div>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 offset-lg-1 mb-3">
        <label for="hospitalFormControlInput" class="form-label"
          >Establecimientos de Salud</label
        >
        <v-select
          id="center"
          v-model="center"
          :options="centers"
          :dropdown-should-open="dropdownShouldOpen"
          v-validate="'required'"
          data-vv-validate-on="blur"
          name="center"
          placeholder="Seleccione el establecimiento de salud"
        />
        <span class="text-danger text-sm">
          {{ errors.first("center") }}
        </span>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 offset-lg-1 mb-3">
        <label for="hospitalFormControlInput" class="form-label"
          >Personal de Salud</label
        >
        <v-select
          id="employee"
          v-model="employee"
          :options="employees"
          :dropdown-should-open="dropdownShouldOpen"
          v-validate="'required'"
          data-vv-validate-on="blur"
          name="employee"
          placeholder="Seleccione el personal de salud"
        />
        <span class="text-danger text-sm">
          {{ errors.first("employee") }}
        </span>
      </div>
    </div>
    <div class="row mt-2">
      <div
        class="col-xs-12 col-sm-12 col-md-4 col-lg-4 offset-lg-4 text-center"
      >
        <button
          type="button"
          class="btn btn-success btn-sm btn-block"
          :disabled="!validateForm"
          @click="saveRelation"
        >
          Grabar
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import {
  Validator,
  ValidationProvider,
  ValidationObserver,
} from "vee-validate";
import store from "../store/store";

const dict = {
  custom: {
    center: {
      required: "Por favor, seleccione un establecimiento de salud",
    },
    employee: {
      required: "Por favor, seleccione un personal de salud",
    },
  },
};
Validator.localize("en", dict);
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      centers: [{ label: "Peru", code: "PE" }],
      center: null,
      employees: [
        { label: "Richard Nick Alvi Ganoza", code: "1" },
        { label: "Miguel Tancun", code: "2" },
      ],
      employee: null,
      loading: "block",
      isVisibleMessage: false,
      mensajeError: "",
      baseUrl: document.querySelector('meta[name="baseUrl"]').content,
    };
  },
  computed: {
    validateForm() {
      console.log(this.center);
      return (
        !this.errors.any() && this.center !== null && this.employee !== null
      );
    },
  },
  mounted() {
    this.getCenter();
    this.getEmployee();
  },
  methods: {
    dropdownShouldOpen(VueSelect) {
      if (this.country !== null) {
        return VueSelect.open;
      }

      return VueSelect.search.length !== 0 && VueSelect.open;
    },
    getCenter() {
      try {
        this.loading = "block";
        axios
          .get(`${this.baseUrl}/api/centro/all`, {
            headers: {
              Authorization: `Bearer ${store.state.token}`,
            },
          })
          .then((res) => {
            this.centers = res.data.data;
            this.loading = "none !important";
          })
          .catch((error) => {
            if (error.response.data.code== 401) {
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
        this.loading = "block";
        axios
          .get(`${this.baseUrl}/api/usuario/all`, {
            headers: {
              Authorization: `Bearer ${store.state.token}`,
            },
          })
          .then((res) => {
            this.employees = res.data.data;
            this.loading = "none !important";
          })
          .catch((error) => {
            if (error.response.data.code == 401) {
              this.$store.dispatch("logout");
              this.$router.push("/login").catch(() => {});
            }
          });
      } catch (err) {
        console.log(err);
      }
    },
    saveRelation() {
      this.loading = "block";
      axios
        .post(
          `${this.baseUrl}/api/equipo/save-relation`,
          {
            hospital: this.center.code,
            usuario: this.employee.code,
          },
          {
            headers: {
              Authorization: `Bearer ${store.state.token}`,
            },
          }
        )
        .then((res) => {
          this.loading = "none !important";
          this.$router.push("/salud").catch(() => {});
        })
        .catch((error) => {
          this.mensajeError = error.response.data.error;
          this.isVisibleMessage = true;
          this.loading = "none !important";
          setTimeout(() => {
            this.isVisibleMessage = false;
          }, 2000);
        });
    },
  },
};
</script>
<style scoped>
.cover-loading {
  position: fixed;
  width: 100%;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.7);
  z-index: 9999;
  display: none;
}
.cover-loading::after {
  content: "";
  display: block;
  position: absolute;
  left: 48%;
  top: 40%;
  width: 40px;
  height: 40px;
  border-style: solid;
  border-color: black;
  border-top-color: transparent;
  border-width: 4px;
  border-radius: 50%;
  -webkit-animation: loading 0.8s linear infinite;
  animation: loading 0.8s linear infinite;
}
@-webkit-keyframes loading {
  from {
    -webkit-transform: rotate(0deg);
  }
  to {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes loading {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>