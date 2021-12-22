<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold mb-0">
                                <i class="fas fa-dolly-flatbed mr-2"></i>
                                {{ translate("Bond Inventory Report") }}
                            </h3>
                            <div
                                class="card-tools justify-content-center align-items-center p-0 m-0"
                                v-if="canShowWorkingOn"
                            >
                                <div
                                    class="font-weight-bold text-muted justify-content-center align-items-center p-0 m-0 mr-1 my-auto"
                                >
                                    <i
                                        id="icon-working"
                                        class="mdi mdi-18px mdi-image-filter-center-focus-weak mr-1 text-success font-weight-bold blink"
                                    ></i>
                                    {{
                                        translate(
                                            "bondinventory.header_working_on"
                                        )
                                    }}
                                    {{ selectedStatus }}
                                    <span v-if="selectedArea.length">
                                        {{ "\\ " + selectedArea.join(", ") }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0 m-0">
                            <form-wizard
                                ref="wizard"
                                :title="null"
                                :subtitle="null"
                                nextButtonText="Continue"
                                color="#ffca28"
                                shape="tab"
                                stepSize="xs"
                            >
                                <wizard-step
                                    slot-scope="props"
                                    slot="step"
                                    :tab="props.tab"
                                    :transition="props.transition"
                                    :index="props.index"
                                >
                                </wizard-step>
                                <tab-content
                                    title="File Import"
                                    :selected="true"
                                    :before-change="checkStepOne"
                                >
                                    <FileImport
                                        ref="import"
                                        :forceRender="forceRender"
                                        @selectedFile="selectedFile"
                                        @vdropzone-removed-file="removeFile"
                                    />
                                </tab-content>
                                <tab-content
                                    title="Configuration"
                                    :before-change="checkStepTwo"
                                >
                                    <Configuration
                                        :forceRender="forceRender"
                                        :statusList="statusList"
                                        :areaList="areaList"
                                        @selectedStatus="selectedStatusHandler"
                                        @selectedArea="selectedAreaHandler"
                                    />
                                </tab-content>
                                <tab-content title="Review">
                                    <Review
                                        ref="review"
                                        :forceRender="forceRender"
                                        :result="results"
                                        @table-generated="tableGenerated"
                                    />
                                </tab-content>
                                <tab-content title="Export/Mail">
                                    <FinalView
                                        :forceRender="forceRender"
                                        :results="results"
                                        :columns="columns"
                                    />
                                </tab-content>
                                <template slot="footer" slot-scope="props">
                                    <div class="wizard-footer-left">
                                        <wizard-button
                                            v-if="props.activeTabIndex > 0"
                                            @click.native="props.prevTab()"
                                            :style="props.fillButtonStyle"
                                            class="text-dark"
                                            >{{
                                                translate(
                                                    "bondinventory.button.step_previous"
                                                )
                                            }}</wizard-button
                                        >
                                    </div>
                                    <div class="wizard-footer-right">
                                        <wizard-button
                                            v-if="!props.isLastStep"
                                            @click.native="props.nextTab()"
                                            class="wizard-footer-right text-dark"
                                            :style="props.fillButtonStyle"
                                            >{{
                                                translate(
                                                    "bondinventory.button.step_next"
                                                )
                                            }}
                                        </wizard-button>
                                        <wizard-button
                                            v-if="
                                                !props.isLastStep &&
                                                    props.activeTabIndex != 0
                                            "
                                            @click.native="cancel"
                                            id="btn-cancel"
                                            class="wizard-footer-right mr-3 btn-outline-danger"
                                            >{{
                                                translate(
                                                    "bondinventory.button.step_cancel"
                                                )
                                            }}
                                        </wizard-button>

                                        <wizard-button
                                            v-show="props.isLastStep"
                                            class="wizard-footer-right finish-button text-dark"
                                            :style="props.fillButtonStyle"
                                            @click.native="restart"
                                        >
                                            <span
                                                v-show="onprogress"
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            {{
                                                translate(
                                                    "bondinventory.button.step_done"
                                                )
                                            }}
                                        </wizard-button>
                                    </div>
                                </template>
                            </form-wizard>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import FileImport from "../components/BondInventory/FileImport.vue";
import Configuration from "../components/BondInventory/Configuration.vue";
import Review from "../components/BondInventory/Review.vue";
import FinalView from "../components/BondInventory/FinalView.vue";
import anime from "animejs/lib/anime.es.js";

export default {
    title: "Bond Inventory -",
    components: {
        FileImport,
        Configuration,
        Review,
        FinalView
    },
    data() {
        return {
            onprogress: false,
            forceRender: true,
            statusList: ["RLSE", "BRKR"],
            areaList: [
                {
                    title: "COY Old",
                    subtitle: "A-A-1, E-AA-1",
                    tooltip:
                        "COY Zone on the left side.<br/>It has been stored at shield id start with A-A-1 to Z-Z-1. <br/> And E-AA-1 location"
                },
                {
                    title: "COY New",
                    subtitle: "A-AA-1, CLD",
                    tooltip:
                        "COY Zone on the front side.<br/>It has been stored at shield id start with A-AA-1 to Z-ZZ-1 <br/> And Freeze Zone starting with CLD"
                },
                {
                    title: "Flyer",
                    subtitle: "F-AA-1",
                    tooltip:
                        "Area of Flyer Zone has shield id<br/>start with F-A-1 to F-Z-1"
                },
                {
                    title: "NCY",
                    subtitle: "N-AA-1, F-NA-1, OT-CIB",
                    tooltip:
                        "Area of large shipment has been <br/> stored on the first floor."
                }
            ],
            file: null,
            selectedStatus: "",
            selectedArea: [],
            results: [],
            grid: null,
            columns: [
                {
                    label: "HAWB",
                    field: "HAWB"
                },
                {
                    label: "Location",
                    field: "Location"
                },
                {
                    label: "Consignee Name",
                    field: "Consignee"
                },
                {
                    label: "Weight",
                    field: "Weight"
                },
                {
                    label: "Pcs",
                    field: "Pcs"
                },
                {
                    label: "Scanned",
                    field: "Scanned"
                },
                {
                    label: "Status",
                    field: "Status"
                }
            ]
        };
    },
    computed: {
        canShowWorkingOn: function() {
            return (
                this.selectedStatus.length > 0 &&
                this.$refs.wizard.activeTabIndex > 0
            );
        }
    },
    methods: {
        async checkStepOne() {
            if (this.file == null) {
                Toast.fire({
                    icon: "error",
                    title: translate("bondinventory.alert_not_import_file_yet")
                });
                return false;
            }
            return true;
        },
        async checkStepTwo() {
            if (this.file == null) {
                Toast.fire({
                    icon: "error",
                    title: translate("bondinventory.alert_not_import_file_yet")
                });
                return false;
            } else if (this.selectedStatus.trim() == "") {
                Toast.fire({
                    icon: "error",
                    title: translate("initails status can't be set")
                });
                return false;
            } else if (this.selectedArea.length == 0) {
                Toast.fire({
                    icon: "error",
                    title: translate("initails area can't be set")
                });
                return false;
            } else {
                var form = new FormData();
                form.append("file", this.file);
                form.append("bond_status", this.selectedStatus);
                form.append("bond_area", this.selectedArea);
                const headers = { "Content-Type": "multipart/form-data" };
                LoadingWait.fire();
                await axios
                    .post("/import/getInfo", form, { headers })
                    .then(response => {
                        // console.log(response.data.data);
                        this.results = JSON.parse(
                            response.data.data.fileInfo.results
                        );
                        // this.file = null;
                        // this.$refs.import.removeFile();
                        this.$refs.import.$emit("vdropzone-queue-complete");
                    });
                setTimeout(() => {
                    LoadingWait.close();
                }, 700);
            }
            return true;
        },
        selectedFile(file) {
            this.file = file;
        },
        selectedStatusHandler(status) {
            console.log("selectedStatusHandler: " + status);
            this.selectedStatus = status;
        },
        selectedAreaHandler(area) {
            this.selectedArea = area;
        },
        removeFile() {
            this.$refs.import.$refs.fileImport.removeAllFiles();
            this.file = null;
        },
        cancel() {
            Swal.fire({
                title: window.translate(
                    "bondinventory.alert_comfirm_to_cancel"
                ),
                text: window.translate(
                    "bondinventory.alert_subtitle_comfirm_to_cancel"
                ),
                showCancelButton: true,
                confirmButtonColor: "#DA5555",
                cancelButtonColor: "#3085d6",
                cancelButtonText: window.translate(
                    "bondinventory.button.cancel_button_text"
                ),
                confirmButtonText: window.translate(
                    "bondinventory.button.confirm_button_text"
                )
            }).then(result => {
                // Send request to the server
                if (result.value) {
                    this.restart();
                }
            });
        },
        restart() {
            this.onprogress = false;
            this.statusList = this.statusList;
            this.areaList = this.areaList;
            this.selectedStatus = this.statusList[0];
            this.selectedArea = [this.areaList[0].title];
            this.results = [];
            this.removeFile();
            this.$refs.wizard.navigateToTab(0);
            this.$refs.wizard.reset();
            this.forceRender = !this.forceRender;
            window.location.reload();
        },
        tableGenerated(grid) {
            this.grid = grid;
        },
        exportTable() {
            console.log("initial generate file.");
            const format = "xlsx";
            const exportSelectedOnly = false;
            const filename = "result";
            this.grid.exportTable(format, exportSelectedOnly, filename);
            console.log("generated file.");
        }
    },
    mounted() {
        var vm = this;
        window.onbeforeunload = function(e) {
            if (vm.file || vm.file.length > 0) {
                e = e || window.event;
                //old browsers
                if (e) {
                    e.returnValue = "Changes you made may not be saved";
                }
                //safari, chrome(chrome ignores text)
                return "Changes you made may not be saved";
            } else {
                return null;
            }
        };
    }
};
</script>

<style lang="scss">
.dz-progress {
    display: none;
}
#btn-cancel {
    border-color: #c51f1a !important;
}

@keyframes blink {
    50% {
        opacity: 0;
    }
}
@-webkit-keyframes blink {
    50% {
        opacity: 0;
    }
}
.blink {
    animation: blink 1s step-start 0s infinite;
    -webkit-animation: blink 1s step-start 0s infinite;
}
</style>
