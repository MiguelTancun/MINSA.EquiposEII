<template>
  <div>
    <div class="row mt-5">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
        <h5><strong>Listado de Establecimientos</strong></h5>
      </div>
      <div
        class="
          col-xs-12 col-sm-12 col-md-3 col-lg-3
          offset-lg-3
          text-right
          mb-2
        "
      >
        <button
          type="button"
          class="btn btn-primary btn-sm btn-block dropdown-toggle"
          data-toggle="dropdown"
        >
          Acciones
        </button>
        <div class="dropdown-menu">
          <router-link to="asignacion" class="dropdown-item">
            Crear nuevo registro
          </router-link>
          <a
            v-if="checked.length > 0"
            href="#"
            onclick="confirm('¿Estas seguro de elimar el siguiente registro?') || event.stopImmediatePropagation()"
            class="dropdown-item"
            type="button"
            @click.prevent="deleteRecords"
          >
            Eliminar
            <span class="badge bg-primary">{{ checked.length }}</span>
          </a>

          <a
            v-if="checked.length > 0"
            class="dropdown-item"
            type="button"
            v-auth-href
            :href="url"
          >
            Exportar
            <span class="badge bg-primary">{{ checked.length }}</span>
          </a>
        </div>
      </div>
    </div>
    <div
      class="
        col-xs-12 col-sm-12 col-md-12 col-lg-12
        mt-2
        mb-2
        alert alert-primary
      "
      v-if="selectPage"
    >
      <div v-if="selectAll || relations.data.total == checked.length">
        Haz seleccionado
        <strong>{{ checked.length }}</strong> registros, que son la totalidad.
      </div>
      <div v-else>
        Tú tienes seleccionado <strong>{{ checked.length }}</strong> registros,
        ¿Quieres seleccionar los registros de todas las páginas?
        <strong>{{ relations.data.total }}</strong>
        <a @click.prevent="selectAllRecords" href="#" class="ml-2 text-dark"
          >Seleccionar todo</a
        >
      </div>
    </div>
    <div class="row">
      <div
        class="col-xs-12 col-sm-12 col-md-1 col-lg-1 align-items-center mt-4"
      >
        <select v-model="paginate" class="form-control form-control-sm">
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="30">30</option>
        </select>
      </div>
      <div
        class="col-xs-12 col-sm-12 col-md-3 col-lg-3 align-items-center mt-4"
      >
        <select v-model="selectedClass" class="form-control form-control-sm">
          <option v-for="item in classes" :key="item.id" :value="item.id">
            {{ item.name }}
          </option>
        </select>
      </div>
      <div
        class="
          col-xs-12 col-sm-12 col-md-8 col-lg-8
          mt-4
          input-group input-group-sm
        "
      >
        <input
          v-model.lazy="search"
          type="search"
          class="form-control"
          placeholder="Buscar por "
        />
      </div>
    </div>
    <div class="card-body table-responsive p-0 mt-4">
      <table class="table table-hover">
        <tbody>
          <tr>
            <th><input type="checkbox" v-model="selectPage" /></th>
            <th>#</th>
            <th>Código</th>
            <th>Establecimiento de Salud</th>
            <th>Personal</th>
            <th>Acciones</th>
          </tr>
          <tr v-show="notFound">
            <td colspan="5">
              <p class="text-center">No se encontraron registros</p>
            </td>
          </tr>
          <tr
            v-for="relation in relations.data"
            :key="relation.id"
            :class="isChecked(relation.id) ? 'table-primary' : ''"
            v-show="!loading && !notFound"
          >
            <td>
              <input type="checkbox" :value="relation.id" v-model="checked" />
            </td>
            <td>{{ relation.id }}</td>
            <td>{{ relation.codeHospital }}</td>
            <td>{{ relation.hospital }}</td>
            <td>{{ relation.personal }}</td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic example">
                <button
                  onclick="confirm('¿Estas seguro de elimar el siguiente registro?') || event.stopImmediatePropagation()"
                  class="btn btn-danger btn-sm"
                  @click="deleteSingleRecord(relation.id)"
                >
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status" v-show="loading"></div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-sm-6 offset-5">
        <pagination
          :data="relations"
          @pagination-change-page="getEquipos"
        ></pagination>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import store from "../store/store";
export default {
  data() {
    return {
      paginate: 10,
      selectedClass: "1",
      search: "",
      classes: [
        { id: 1, name: "Establecimiento de Salud" },
        { id: 2, name: "Personal" },
      ],
      relations: {},
      selectPage: false,
      selectAll: false,
      getEquiposUrl: "",
      getEquiposUrlWithoutPaginate: "",
      loading: true,
      notFound: false,
      checked: [],
      selectPage: false,
      url: "",
      token: store.state.token,
      baseUrl: document.querySelector('meta[name="baseUrl"]').content,
    };
  },
  mounted() {
    this.getEquipos();
  },
  methods: {
    isChecked(relation_id) {
      return this.checked.includes(relation_id);
    },
    selectAllRecords() {
      axios
        .get(this.getEquiposUrlWithoutPaginate, {
          headers: {
            Authorization: `Bearer ${store.state.token}`,
          },
        })
        .then((response) => {
          // console.log(response.data);
          this.checked = [];
          response.data.data.data.forEach((relation) => {
            this.checked.push(relation.id);
          });
          this.selectAll = true;
        });
    },
    getEquipos(page = 1) {
      this.loading = true;
      this.notFound = false;
      this.getEquiposUrlWithoutPaginate =
      `${this.baseUrl}/api/equipo/all?`+
        "type=" +
        this.selectedClass +
        "&search=" +
        this.search;

      this.getEquiposUrl = this.getEquiposUrlWithoutPaginate.concat(
        "&paginate=" + this.paginate + "&page=" + page
      );
      axios
        .get(this.getEquiposUrl, {
          headers: {
            Authorization: `Bearer ${store.state.token}`,
          },
        })
        .then((res) => {
          this.relations = res.data.data;
          this.loading = false;
          if (this.relations.total == 0) {
            this.notFound = true;
          } else {
            this.notFound = false;
          }
        })
        .catch((error) => {
          if (error.response.data.code == 401) {
            this.$store.dispatch("logout");
            this.$router.push("/login").catch(() => {});
          }
        });
    },
    deleteSingleRecord(relation_id) {
      axios
        .delete(`${this.baseUrl}/api/equipo/delete/` + relation_id, {
          headers: {
            Authorization: `Bearer ${store.state.token}`,
          },
        })
        .then((res) => {
          this.checked = this.checked.filter((id) => id != relation_id);
          this.getEquipos();
        });
    },
    deleteRecords() {
      axios
        .delete(`${this.baseUrl}/api/equipo/all-delete/` + this.checked, {
          headers: {
            Authorization: `Bearer ${store.state.token}`,
          },
        })
        .then((res) => {
          this.checked = [];
          this.getEquipos();
        });
    },
  },
  watch: {
    paginate: function (value) {
      this.getEquipos();
    },
    search: function (value) {
      this.getEquipos();
    },
    selectPage: function (value) {
      this.checked = [];
      if (value) {
        this.relations.data.forEach((relation) => {
          this.checked.push(relation.id);
        });
      } else {
        this.checked = [];
        this.selectAll = false;
      }
    },
    checked: function (value) {
      this.url = `${this.baseUrl}/api/equipo/export/` + this.checked;
    },
  },
};
</script>
