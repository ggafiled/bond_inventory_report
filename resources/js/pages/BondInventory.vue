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
                                    {{ selectedStatusShow }}
                                    <span v-if="selectedArea.length">
                                        {{
                                            "\\ " + selectedAreaShow.join(", ")
                                        }}
                                    </span>
                                    <span v-if="file">
                                        - ({{
                                             file.name
                                        }})
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
                                    >
                                    </FileImport>
                                </tab-content>
                                <tab-content
                                    title="Configuration"
                                    :before-change="checkStepTwo"
                                >
                                    <Configuration
                                        :forceRender="forceRender"
                                        :statusList="statusList"
                                        :areaList="areaList"
                                        :notUseZoneFilter="notUseZoneFilter"
                                        @selectedStatus="selectedStatusHandler"
                                        @selectedArea="selectedAreaHandler"
                                    >
                                    </Configuration>
                                </tab-content>
                                <tab-content title="Review">
                                    <Review
                                        ref="review"
                                        :forceRender="forceRender"
                                        :result="results"
                                        :columns="columns_forshow"
                                        @table-generated="tableGenerated"
                                    >
                                    </Review>
                                </tab-content>
                                <tab-content title="Export/Mail">
                                    <FinalView
                                        :forceRender="forceRender"
                                        :results="results"
                                        :columns_forexport="columns_forexport"
                                    >
                                    </FinalView>
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
import FileImport from "../components/bondinventory/FileImport.vue";
import Configuration from "../components/bondinventory/Configuration.vue";
import Review from "../components/bondinventory/Review.vue";
import FinalView from "../components/bondinventory/FinalView.vue";
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
            statusList: null,
            areaList: null,
            file: [],
            selectedStatus: null,
            selectedStatusShow: "",
            selectedArea: [],
            selectedAreaShow: "",
            results: [],
            grid: null,
            columns_forexport: [],
            columns_forshow: [],
            notUseZoneFilter: false
        };
    },
    computed: {
        canShowWorkingOn: function() {
            return this.selectedStatus && this.$refs.wizard.activeTabIndex > 0;
        }
    },
    watch: {
        selectedStatus: function(newVal, oldVal) {
            this.selectedStatusShow = this.statusList.filter(item => {
                return item.id == this.selectedStatus;
            })[0].title;
            this.notUseZoneFilter = !Boolean(parseInt(this.statusList.filter(item => {
                return item.id == this.selectedStatus;
            })[0].use_zone_filter));
        },
        selectedArea: function(newVal, oldVal) {
            this.selectedAreaShow = this.selectedArea.map(zone => {
                return this.areaList.filter(item => {
                    return item.id == zone;
                })[0].title;
            });
        }
    },
    methods: {
        async loadStatusList() {
            await axios
                .get("/bondstatus/getInfo")
                .then(({ data }) => (this.statusList = data.data));
        },
        async loadZoneList() {
            await axios
                .get("/bondzone/getInfo")
                .then(({ data }) => (this.areaList = data.data));
        },
        async checkStepOne() {
            console.log(this.file);
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
            } else if (this.selectedStatus == null) {
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
                form.append("type", this.selectedType);
                const headers = { "Content-Type": "multipart/form-data" };
                LoadingWait.fire();
                await axios
                    .post("/import/getInfo", form, { headers })
                    .then(response => {
                        // console.log(response.data.data);
                        this.results = JSON.parse(
                            response.data.data.fileInfo.results
                        );
                        this.columns_forexport = JSON.parse(
                            response.data.data.fileInfo.columns_forexport
                        );
                        this.columns_forshow = JSON.parse(
                            response.data.data.fileInfo.columns_forshow
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
            this.selectedStatus = this.statusList[0].id;
            this.selectedArea = [this.areaList[0].id];
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
    async mounted() {
        await this.loadStatusList();
        await this.loadZoneList();
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
