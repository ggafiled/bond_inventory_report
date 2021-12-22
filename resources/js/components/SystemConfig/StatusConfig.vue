<template>
    <div>
        <div class="pt-3 px-3">
            <button
                type="button"
                class="btn btn-sm btn-primary mb-3 float-lg-right"
                @click="newModal"
            >
                <i class="fa fa-plus-square"></i>
                {{ translate("systemconfig.addnew") }}
            </button>
            <data-tables
                ref="items"
                :options="options_for_status"
                @edit="editModal"
                @delete="deleteItem"
            ></data-tables>
        </div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="addNewStatus"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNew"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">
                            {{ translate("systemconfig.create.header") }}
                        </h5>
                        <h5 class="modal-title" v-show="editmode">
                            {{ translate("systemconfig.update.header") }}
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

                    <!-- <form @submit.prevent="createUser"> -->

                    <form
                        @submit.prevent="editmode ? updateItem() : createItem()"
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Status Name</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('title')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="title"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea
                                    id="remark"
                                    v-model="form.tooltip"
                                    type="text"
                                    name="tooltip"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('tooltip')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="tooltip"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <label>Status Format</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">/</span>
                                    </div>
                                    <input
                                        v-model="form.format"
                                        type="text"
                                        name="format"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'format'
                                            )
                                        }"
                                    />
                                    <div class="input-group-append">
                                        <span class="input-group-text">/</span>
                                    </div>
                                </div>

                                <has-error
                                    :form="form"
                                    field="format"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <label>Item Status</label>

                                <div
                                    class="form-check form-check-inline cursor-pointer"
                                >
                                    <input
                                        class="form-check-input cursor-pointer"
                                        type="radio"
                                        id="inlineRadio1"
                                        value="1"
                                        v-model="form.item_status"
                                    />
                                    <label
                                        class="form-check-label cursor-pointer"
                                        for="inlineRadio1"
                                        >Enable</label
                                    >
                                </div>
                                <div
                                    class="form-check form-check-inline cursor-pointer"
                                >
                                    <input
                                        class="form-check-input cursor-pointer"
                                        id="inlineRadio2"
                                        type="radio"
                                        value="2"
                                        v-model="form.item_status"
                                    />
                                    <label
                                        class="form-check-label cursor-pointer"
                                        for="inlineRadio2"
                                        >Disable</label
                                    >
                                </div>

                                <has-error
                                    :form="form"
                                    field="item_status"
                                ></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                {{ translate("systemconfig.actions.close") }}
                            </button>
                            <button
                                v-show="editmode"
                                type="submit"
                                class="btn btn-success"
                                :disabled="onprogress"
                            >
                                <span
                                    v-show="onprogress"
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                {{ translate("systemconfig.actions.update") }}
                            </button>
                            <button
                                v-show="!editmode"
                                type="submit"
                                class="btn btn-primary"
                                :disabled="onprogress"
                            >
                                <span
                                    v-show="onprogress"
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                {{ translate("systemconfig.actions.create") }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            editmode: false,
            selected: "",
            onprogress: false,
            options_for_status: {
                ajax: "/api/bondstatus",
                columns: [
                    {
                        data: "title",
                        title: "Status Name",
                        className: "text-capitalize"
                    },
                    {
                        data: "tooltip",
                        title: "Description"
                    },
                    {
                        data: "format",
                        title: "Format Filter"
                    },
                    {
                        data: "deleted_at",
                        title: "Available ?",
                        render: function(data, type, row, meta) {
                            return data !== null
                                ? '<i class="fas fa-times red"></i><span class="invisible">disable</span>'
                                : '<i class="fas fa-check green"></i><span class="invisible">enable</span>';
                        }
                    },
                    {
                        data: "created_at",
                        title: "Careate At",
                        render: function(data, type, row, meta) {
                            return moment(data).format("MMMM Do YYYY");
                        }
                    }
                ]
            },
            form: new Form({
                id: "",
                title: "",
                format: "",
                tooltip: "",
                item_status: 1
            })
        };
    },
    methods: {
        reloadtable() {
            this.$Progress.start();
            this.$refs.items.reloadItems();
            this.$Progress.finish();
        },
        updateItem() {
            this.$Progress.start();
            this.onprogress = true;
            console.log("update data");
            console.log(this.form);
            this.form
                .put("/bondstatus/" + this.form.id)
                .then(response => {
                    // success
                    $("#addNewStatus").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: response.data.message
                    });
                    this.reloadtable();
                    this.onprogress = false;
                    //  Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                    this.onprogress = false;
                });
        },
        editModal(status) {
            this.editmode = true;
            this.form.reset();
            $("#addNewStatus").modal("show");
            status.item_status =
                status.deleted_at == null || status.deleted_at == "" ? 1 : 2;
            console.log("Editing data");
            console.log(status);
            this.form.fill(status);
        },
        newModal() {
            this.editmode = false;
            this.selected = "";
            this.form.reset();
            $("#addNewStatus").modal("show");
        },
        deleteItem(item) {
            this.onprogress = true;
            Swal.fire({
                title: window.translate(
                    "systemconfig.alert.delete_building_title"
                ),
                text:
                    window.translate("systemconfig.alert.delete_building_text") +
                    ` [${item.title.replace(/\b\w/g, l => l.toUpperCase())}]`,
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                cancelButtonText: window.translate(
                    "systemconfig.alert.delete_building_cancel_button_text"
                ),
                confirmButtonText: window.translate(
                    "systemconfig.alert.delete_building_confirm_button_text"
                )
            }).then(result => {
                // Send request to the server
                if (result.value) {
                    this.form
                        .delete("/bondstatus/" + item.id)
                        .then(() => {
                            Swal.fire(
                                window.translate(
                                    "systemconfig.alert.comfirm_delete_title"
                                ),
                                window.translate(
                                    "systemconfig.alert.confirm_delete_message"
                                ),
                                "success"
                            );
                            // Fire.$emit('AfterCreate');
                            this.reloadtable();
                        })
                        .catch(data => {
                            Swal.fire("Failed!", data.message, "warning");
                        });
                }
                this.onprogress = false;
            });
        },
        createItem() {
            if (this.selected == null || this.selected == undefined)
                return false;
            this.onprogress = true;
            console.log(this.form);
            this.form
                .post("/bondstatus")
                .then(response => {
                    $("#addNewStatus").modal("hide");

                    Toast.fire({
                        icon: "success",
                        title: response.data.message
                    });

                    this.reloadtable();
                    this.onprogress = false;
                })
                .catch(() => {
                    Toast.fire({
                        icon: "error",
                        title: "Some error occured! Please try again"
                    });
                    this.onprogress = false;
                });
        }
    }
};
</script>

<style></style>
