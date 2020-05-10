<template>
  <div class="container">
    <button @click="create()" data-toggle="modal" data-target="#exampleModal" class="btn btn-md btn-primary float-right" >Nuevo link</button>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-sm-3" v-for="item in links" :key="item.id">
        <div>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title align.item-center d-flex justify-content-between">
                  {{ item.token }}
                    <span class="icon" data-toggle="tooltip" data-placement="top" title="copiar" @click="copy(item.token)">
                        <img src="images/icon-url.svg">
                    </span>
                </h5>
              <h6 class="card-subtitle mb-2 text-muted">{{ item.url}}</h6>
              <p class="card-text">{{ item.descripcion }}</p>
              <a
                href="#"
                class="btn btn btn-sm btn-primary"
                @click.prevent="edit(item)"
                data-toggle="modal"
                data-target="#exampleModal"

              >edit</a>
              <a href="#" class="btn btn-danger btn-sm" @click.prevent="remove(item)">delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal de creacion y edicion -->
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
          <form action method="post" @submit.prevent="save()">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                <span v-if="link.id">Editar link</span>
                <span v-if="!link.id">Crear link</span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="alert alert-success" v-if="statusResponse==201">
                <p>Se ha realizo la actualizacion exitosamente ya puedes acceder al link que genero la aplicacion</p>
              </div>
              <div class="alert alert-danger" v-else-if="statusResponse!=201 && statusResponse!= 0">
                <p>Hubo un error al intentar diseñar el link porfavor intente mas tarde, y si dicho problema continua comuniquese con el administrador</p>
              </div>

              <div class="form-group" v-if="link.id">
                <label for>ID</label>
                <input type="text" class="form-control" disabled v-model="link.id" />
              </div>
              <div class="form-group">
                <label for>Url</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="link.url"
                  placeholder="https://www.example.com"
                  required
                />
              </div>
              <div class="form-group">
                <label for>descripcion</label>
                <textarea class="form-control" rows="5" v-model="link.descripcion"></textarea>
              </div>
              <div class="form-group">
                <label for>Token generado:</label>
                <p>{{ link.token |setUrl }}</p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" :disabled="!link.url">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <input type="text" id="copypaste" style="display:none;">
  </div>
</template>

<script>
import Axios from "axios";

export default {
  props: ["lista"],

  mounted() {
    this.links = this.lista;
  },

  filters: {
    setUrl(value) {
      let url = "http://localhost:8000/v1/";
      if (!value) {
        return url;
      }

      return "http://localhost:8000/v1/" + value;
    }
  },
  data() {
    return {
      statusResponse: 0,
      links: [],
      msg_copy: "copiar",
      link: { id: "", token: "", url: "", descripcion: "" }
    };
  },
  methods: {
    edit(item) {
      this.statusResponse = 0;
      this.link = item;
    },
    remove(item) {
      this.statusResponse = 0;
      this.link = item;
    },
    create() {
      this.statusResponse = 0;
      this.link = { id: "", token: "", url: "", descripcion: "" };
    },

    save() {
      this.statusResponse = 0;
      if (!this.link.id) {
        Axios.post("/links", this.link).then(resp => {
          this.link = res.data;

          this.statusResponse = resp.status;
          if (resp.status == 201) {
            this.links.push(this.resp.data);
          }
        });
      }
    },
    copy(text){
        let aux = document.querySelector("#copypaste");
        aux.style.display="inline-block";
        aux.setAttribute("value","http://localhost:8000/v1/"+text);
        aux.select();
        document.execCommand("copy");
        aux.style.display="none";
    }
  }
};
</script>

<style>
    .icon{
        outline: 1px solid #ccc;
        padding: 2px 5px;
        cursor:pointer;
    }
    .icon:hover{
        background:rgb(139, 139, 139);
        outline: 1px solid #ccc;
        color:#fff;
    }
    .icon:active{
        background:skyblue;

    }
    .btn-xs{
        padding:2.5px 5px;
    }
</style>
