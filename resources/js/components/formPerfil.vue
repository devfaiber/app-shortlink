<template>
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
                        <h5 class="modal-title" id="exampleModalLabel">
                            Editar Perfil
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <p>Se ha realizo la actualizacion exitosamente</p>
                        </div>
                        <div class="alert alert-danger">
                            <p>No se pudo realiza la actualizacion</p>
                        </div>
                        <div class="form-group">
                            <label for>ID</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="user.id"
                            />
                        </div>
                        <div class="form-group">
                            <label for>Nombre</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="user.name"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label for>Email</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="user.email"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label for>password</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="user.password"
                                placeholder="Establesca la nueva contraseña"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                type="submit"
                                class="btn btn-primary"
                                :disabled="
                                    !user.id || !user.name || !user.email
                                "
                            />
                        </div>
                    </div>
                    {{user.name}}
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props:["id_perfil"],
    mounted(){
        axios.get("/account/view/" + this.id_perfil).then(resp => {
            this.user = resp.data;
        });

    },
    data(){
        return {
            user: {
                id:"",
                name:"",
                email:"",
                password:""
            }
        }
    },

    methods:{
        update(){
            axios.put("/account/edit/"+this.user.id, this.user).then((resp)=>{

                this.$emit("eventEmit",resp.data);
            });
        }
    }
};
</script>

<style></style>
