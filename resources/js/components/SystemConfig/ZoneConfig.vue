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
                :options="options_for_zone"
                @edit="editModal"
                @delete="deleteItem"
            ></data-tables>
        </div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="addNewZone"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNewZone"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">
                            {{ translate("systemconfig.create.header_zone") }}
                        </h5>
                        <h5 class="modal-title" v-show="editmode">
                            {{ translate("systemconfig.update.header_zone") }}
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
                                <label>Location Name</label>
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
                                <label>Example Location</label>
                                <input
                                    v-model="form.subtitle"
                                    type="text"
                                    name="subtitle"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'subtitle'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="subtitle"
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
                                <label>Zone Format</label>
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
                                <label>Service Type</label>

                                <select
                                    class="custom-select custom-select-sm"
                                    aria-label="Bond"
                                    v-model="form.type"
                                >
                                    <option value="bond"
                                        >Bond Report</option
                                    >
                                    <option value="bfs"
                                        >BFS Report</option
                                    >
                                </select>

                                <has-error
                                    :form="form"
                                    field="type"
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
                                        value="0"
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
            form: new Form({
                id: "",
                title: "",
                subtitle: "",
                tooltip: "",
                format: "",
                type:"bond",
                item_status: 1
            }),
            options_for_zone: {
                ajax: "/api/bondzone",
                columns: [
                    {
                        data: "title",
                        title: "Location Name",
                        className: "text-capitalize"
                    },
                    {
                        data: "subtitle",
                        title: "Sub name"
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
            }
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
            console.log("update data");
            this.onprogress = true;
            console.log(this.form);
            this.form
                .put("/bondzone/" + this.form.id)
                .then(response => {
                    // success
                    $("#addNewZone").modal("hide");
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
        editModal(zone) {
            this.editmode = true;
            this.form.reset();
            $("#addNewZone").modal("show");
            zone.item_status =
                zone.deleted_at == null || zone.deleted_at == "" ? 1 : 2;
            console.log("Editing data");
            console.log(zone);
            this.form.fill(zone);
        },
        newModal() {
            this.editmode = false;
            this.selected = "";
            this.form.reset();
            $("#addNewZone").modal("show");
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
                        .delete("/bondzone/" + item.id)
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
                .post("/bondzone")
                .then(response => {
                    $("#addNewZone").modal("hide");

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
