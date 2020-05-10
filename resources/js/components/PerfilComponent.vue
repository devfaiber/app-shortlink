<template>
  <div class="container">
    <h4>ID: {{ usuario.id }}</h4>
    <h5>nombre: {{ usuario.name }}</h5>
    <h5>email {{ usuario.email }}</h5>

    <button
      type="button"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#exampleModal"
      @click="edit"
    >Editar Datos</button>

    <!-- modal del edicion -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action method="post" @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar Perfil</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="alert alert-success" v-if="responseOk">
                <p>Se ha realizo la actualizacion exitosamente</p>
              </div>

              <div class="form-group">
                <label for>ID</label>
                <input type="text" class="form-control" v-model="user_model.id" disabled/>
              </div>
              <div class="form-group">
                <label for>Nombre</label>
                <input type="text" class="form-control" v-model="user_model.name" required />
              </div>
              <div class="form-group">
                <label for>Email</label>
                <input type="text" class="form-control" v-model="user_model.email" required />
              </div>
              <div class="form-group">
                <label for>password</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="user_model.password"
                  placeholder="Establesca la nueva contraseña"
                />
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" :disabled="!user_model.id || !user_model.name || !user_model.email">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import formPerfil from "./formPerfil";

export default {
  props: ["user"],
  components: {
    formPerfil
  },
  mounted() {
    this.usuario = this.user;
    this.user_model = Object.assign({},this.usuario);
  },
  data() {
    return {
        responseOk: false,
      usuario: { id: null, name: null, email: null },
      user_model: { id: null, name: null, email: null }
    };
  },
  methods: {
    edit(){
        this.responseOk = false;
    },
    update() {
      axios.put("/account/edit/" + this.user.id, this.user_model).then(resp => {

          this.responseOk = true;

            console.log(resp);

          this.usuario = resp.data;
      });

    },
  }
};
</script>

<style></style>
