<template>
    <div class="container-fluid">
            <div
      class="d-flex justify-content-center cover-loading"
      v-bind:style="{ display: loading }"
    ></div>
        <div class="row justify-content-center mt-md-5">
            <div class="col col-12 col-sm-12 col-md-6 col-lg-3 p-4 border">
                <div class="alert alert-danger" role="alert" v-show="isVisibleMessage">
                   {{mensajeError}}
                </div>
                <div class="text-center mb-3">
                    <img
                        src="images/Minsa-tableta.jpeg"
                        width="180px;"
                        height="auto;"
                    />
                    <p
                        class="text-center font-weight-bold mt-4"
                        style="font-size:20px;"
                    >
                        DIPOS - DGAIN
                    </p>
                </div>
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input
                        id="username"
                        type="text"
                        class="form-control"
                        v-validate="'required'"
                        data-vv-validate-on="blur"
                        name="username"
                        autofocus
                        placeholder="Ingrese su usuario"
                        v-model="username"
                    />
                    <span class="text-danger text-sm">
                        {{ errors.first("username") }}
                    </span>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        class="form-control"
                        v-validate="'required'"
                        data-vv-validate-on="blur"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Ingrese su contraseña"
                        v-model="password"
                    />
                    <span class="text-danger text-sm">
                        {{ errors.first("password") }}
                    </span>
                </div>
                <button
                    type="submit"
                    class="btn btn-danger btn-block"
                    :disabled="!validateForm"
                    @click="singIn"
                >
                    Ingresar
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import {
    Validator,
    ValidationProvider,
    ValidationObserver
} from 'vee-validate';
import axios from 'axios';
import jwt_decode from 'jwt-decode';

const dict = {
    custom: {
        username: {
            required: "Por favor, el usuario es requerido"
        },
        password: {
            required: "Por favor, la contraseña es requerida"
        }
    }
};
Validator.localize("en", dict);

export default {
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            mensajeError: "",
            username: "",
            password: "",
            isVisibleMessage: false,
            baseUrl: document.querySelector('meta[name="baseUrl"]').content,
            loading: "none !important"
        };
    },
    computed: {
        validateForm() {
            return (
                !this.errors.any() &&
                this.username !== "" &&
                this.password !== ""
            );
        }
    },
    methods: {
        singIn() {
            try {
                this.loading = "block";
                const url = `${this.baseUrl}/api/login`;
                axios
                    .post(url, {
                        username: this.username,
                        password: this.password
                    })
                    .then(res => {
                        this.loading = "none !important";
                        const { user } = jwt_decode(res.data.data);
                        const userDefaults = {
                            uid: user[0].id, // From Auth
                            displayName: user[0].nombre, // From Auth
                            about:
                                "Dessert chocolate cake lemon drops jujubes. Biscuit cupcake ice cream bear claw brownie brownie marshmallow.",
                            status: "online",
                            userRole: "admin"
                        };

                        this.$store.dispatch("setUserInfo", userDefaults);
                        this.$store.dispatch("setToken", res.data.data);
                        this.$router.push({ path: "/" });
                    })
                    .catch(error => {
                      this.loading = "none !important";  
                      this.mensajeError = error.response.data.error;
                      this.isVisibleMessage = true;
                      setTimeout(()=>{
                          this.isVisibleMessage = false;
                      },2000);
                    });
            } catch (err) {
                console.log(err);
            }
        }
    }
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