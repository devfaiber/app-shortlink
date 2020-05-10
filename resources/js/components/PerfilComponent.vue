<template>
  <div class="container">
      <h1>Mi perfil</h1>
    <div>

    <h4>ID: {{ usuario.id }}</h4>
    <h5>nombre: {{ usuario.name }}</h5>
    <h5>email {{ usuario.email }}</h5>
    <h5>fecha de registro {{ Date(usuario.created_at) }}</h5>
    <button
      type="button"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#exampleModal"
      @click="edit"
    >Editar Datos</button>
    </div>

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
          <form action method="post" @submit.prevent="validateUpdate">
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
              <div class="alert alert-danger" v-if="!user_model.name || !user_model.email">
                  <p>Porfavor rellene los campos requeridos</p>
              </div>

              <div class="form-group">
                <label for>ID</label>
                <input type="text" class="form-control" v-model="user_model.id" disabled/>
              </div>
              <div class="form-group">
                <label for>Nombre:*</label>
                <input type="text" class="form-control" v-model="user_model.name" required />
              </div>
              <div class="form-group">
                <label for>Email:*</label>
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

                <div class="form-group">
                <label for>confirmar nueva password</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="user_model.password2"
                  placeholder="Establesca la confirmacion de la contraseña"
                />
              </div>
              <div class="alert alert-danger text-center" v-if="user_model.password != user_model.password2">
                  Las dos contraseñas no coinciden porfavor verificar dichas contraseñas
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
      user_model: { id: null, name: null, email: null, password2:null }
    };
  },
  methods: {
    edit(){
        this.responseOk = false;
    },

    validateUpdate(){
        if(!this.user_model.name || !this.user_model.email){
            return;
        }else if(this.user_model.password != this.user_model.password2){
            return;
        }

        this.update();
    },

    update() {
      axios.put("/account/edit/" + this.user.id, this.user_model).then(resp => {

          this.responseOk = true;

            setTimeout(()=>{
                this.responseOk=false;
            },5000)

          this.usuario = resp.data;
      });

    },
  }
};
</script>

<style></style>
