<template>
  <div class="container">
    <h1>Mis Links</h1>

    <button
      @click="create()"
      data-toggle="modal"
      data-target="#exampleModal"
      class="btn btn-md btn-primary float-right"
    >Nuevo link</button>
    <div class="clearfix"></div>
    <hr>
    <div class="row">
        <div class="col-12 d-flex justify-content-center" v-if="links.length <= 0">

            <h4 class="d-inline-block" style="color:gray;"><em>Lo sentimo no posees ningun link guardado</em></h4>

        </div>
      <div class="col-sm-6 col-md-4 col-12 col-lg-3 mt-3" v-for="(item,i) in links" :key="item.id">
        <div>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title align.item-center d-flex justify-content-between">
                {{ item.token }}
                <span
                  class="icon"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="copiar"
                  @click="copy(item.token)"
                >
                  <img src="images/icon-url.svg" />
                </span>
              </h5>
              <h6 class="card-subtitle mb-2 text-muted">{{ item.url}}</h6>
              <p class="card-text">{{ item.descripcion }}</p>
              <a
                href="#"
                class="btn btn btn-sm btn-primary"
                @click.prevent="edit(i,item)"
                data-toggle="modal"
                data-target="#exampleModal"
              >edit</a>
              <a href="#" class="btn btn-danger btn-sm" @click.prevent="remove(i,item)">delete</a>
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
          <form action method="post" @submit.prevent="validateSave()">
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
                <span
                  class="icon"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="copiar"
                  @click="copy(link.token, true)"
                >
                  <img src="images/icon-url.svg" />
                </span>
              </div>
            </div>
            <div class="modal-footer">
                <input type="text" id="copypaste2" style="display:none;" />
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" :disabled="!link.url" >Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <input type="text" id="copypaste" style="display:none;" />
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
      is_delete: false,
      index: null,
      statusResponse: 0,
      links: [],
      msg_copy: "copiar",
      link: { id: "", token: "", url: "", descripcion: "" }
    };
  },
  methods: {
    edit(index, item) {
      this.index = index;
      this.statusResponse = 0;
      this.link = Object.assign({}, item);
    },
    remove(index, item) {
      this.index = index;
      this.statusResponse = 0;
      this.link = item;

      this.$swal({
        title: "Informacion",
        text: "Estas seguro de eliminar este link?",
        icon: "question",
        showCancelButton: true
      }).then(value => {
        if (value.value) {
          this.deleteLink();
        }
      });
    },

    deleteLink() {
      Axios.delete("/links/" + this.link.id).then(resp => {
        console.log(resp);
        if (resp.status == 200) {
          this.$swal({
            icon: "success",
            title: "Listo!",
            text: "Se ha eliminado exitosamente."
          });
          this.links.splice(this.index, 1); // elimina el elemento que se borro
        } else {
          this.$swal({
            icon: "error",
            title: "Error!",
            text: "No se ha podido eliminar el link."
          });
        }
      });
    },
    create() {
      this.statusResponse = 0;
      this.link = { id: "", token: "", url: "", descripcion: "" };
    },

    validateSave(){
        var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
        if(regexp.test(this.link.url)){
            this.save();
        }else{
            this.$swal({
                icon: "warning",
                title: "Aviso!",
                text: "Porfavor rellene el campo de la url con datos validos"
            });
        }
    },
    save() {
      this.statusResponse = 0;
      if (!this.link.id) {
        Axios.post("/links", this.link).then(resp => {
          this.link = resp.data;

          this.statusResponse = resp.status;
          if (resp.status == 201) {
            this.links.push(resp.data);
            this.$swal({
              icon: "success",
              title: "Listo!",
              text: "El link se ha creado exitosamente"
            });
          } else {
            this.$swal({
              icon: "error",
              title: "Error!",
              text:
                "El link se pudo crear en la base de datos, hubo un problema"
            });
          }
        });
      } else {
        Axios.put("/links/" + this.link.id, this.link).then(resp => {
          this.statusResponse = resp.status;
          if (resp.status == 200) {
            this.link = resp.data;
            this.links[this.index] = this.link;
            this.$swal({
              icon: "success",
              title: "Listo!",
              text: "El link se ha actualizado exitosamente"
            });
          } else {
            this.$swal({
              icon: "error",
              title: "Error!",
              text:
                "El link se pudo actualizar en la base de datos, hubo un problema"
            });
          }
        });
      }
    },
    copy(text, modal=false) {
        let aux;
        if(!modal){
            aux = document.querySelector("#copypaste");
        }else{
            aux = document.querySelector("#copypaste2");
        }

      aux.style.display = "inline-block";
      aux.setAttribute("value", "http://localhost:8000/v1/" + text);
      aux.select();
      document.execCommand("copy");
      aux.style.display = "none";
    }
  }
};
</script>

<style>
.icon {
  outline: 1px solid #ccc;
  padding: 2px 5px;
  cursor: pointer;
}
.icon:hover {
  background: rgb(139, 139, 139);
  outline: 1px solid #ccc;
  color: #fff;
}
.icon:active {
  background: skyblue;
}
.btn-xs {
  padding: 2.5px 5px;
}
</style>
