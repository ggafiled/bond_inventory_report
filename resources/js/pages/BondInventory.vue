<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                Import ACA Report File
                            </h3>
                            <div class="card-tools p-3" v-if="canShowWorkingOn">
                                <h5 class="text-muted">Working on {{ selectedStatus }}</h5>
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
                                shape="eclipse"
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
                                        @selectedFile="selectedFile"
                                        @vdropzone-removed-file="removeFile"
                                    />
                                </tab-content>
                                <tab-content
                                    title="Configuration"
                                    :before-change="checkStepTwo"
                                >
                                    <Configuration
                                        :statusList="spreadsheet"
                                        :areaList="areasheet"
                                        @selectedStatus="selectedStatusHandler"
                                        @selectedArea="selectedAreaHandler"
                                    />
                                </tab-content>
                                <tab-content title="Review">
                                    <Review ref="grid" :result="results" />
                                </tab-content>
                                <tab-content title="Export/Mail">
                                    <FinalView />
                                </tab-content>
                                <template slot="footer" slot-scope="props">
                                    <div class="wizard-footer-left">
                                        <wizard-button
                                            v-if="props.activeTabIndex > 0"
                                            @click.native="props.prevTab()"
                                            :style="props.fillButtonStyle"
                                            class="text-dark"
                                            >{{
                                                translate("Previous")
                                            }}</wizard-button
                                        >
                                    </div>
                                    <div class="wizard-footer-right">
                                        <wizard-button
                                            v-if="!props.isLastStep"
                                            @click.native="props.nextTab()"
                                            class="wizard-footer-right text-dark"
                                            :style="props.fillButtonStyle"
                                            >{{ translate("Next") }}
                                        </wizard-button>
                                        <wizard-button
                                            v-if="
                                                !props.isLastStep &&
                                                    props.activeTabIndex != 0
                                            "
                                            @click.native="restart"
                                            id="btn-cancel"
                                            class="wizard-footer-right mr-1 bg-danger"
                                            :style="props.fillButtonStyle"
                                            >{{ translate("Cancel") }}
                                        </wizard-button>

                                        <wizard-button
                                            v-show="props.isLastStep"
                                            class="wizard-footer-right finish-button"
                                            :style="props.fillButtonStyle"
                                            @click.native="restart"
                                        >
                                            <span
                                                v-show="onprogress"
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            {{ translate("Done/Clear") }}
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
            spreadsheet: ["RLSE", "BRKR"],
            areasheet: [
                {
                    title: "COY เก่า",
                    subtitle: "A-A-1"
                },
                {
                    title: "COY ใหม่",
                    subtitle: "A-AA-1 "
                },
                {
                    title: "Flyer",
                    subtitle: "F-AA-1"
                },
                {
                    title: "NCY",
                    subtitle: "N-AA-1"
                }
            ],
            file: null,
            selectedStatus: "",
            selectedArea: [],
            results: []
        };
    },
    computed:{
        canShowWorkingOn: function(){
            return this.selectedStatus.length > 0 && this.$refs.wizard.activeTabIndex > 0;
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
            if (
                this.file == null ||
                this.selectedStatus.trim() == "" ||
                this.selectedArea.length == 0
            ) {
                Toast.fire({
                    icon: "error",
                    title: translate("bondinventory.alert_not_import_file_yet")
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
                        this.results = response.data.data.fileInfo.results;
                        // this.file = null;
                        // this.$refs.import.removeFile();
                        this.$refs.import.$emit("vdropzone-queue-complete");
                    });
                setTimeout(() => {
                    LoadingWait.close();
                }, 2000);
            }
            return true;
        },
        selectedFile(file) {
            this.file = file;
        },
        selectedStatusHandler(status) {
            this.selectedStatus = status;
        },
        selectedAreaHandler(area) {
            this.selectedArea = area;
        },
        removeFile() {
            this.$refs.import.$refs.fileImport.removeAllFiles();
            this.file = null;
        },
        restart() {
            this.onprogress = false;
            this.spreadsheet = ["RLSE", "BRKR"];
            (this.areasheet = [
                {
                    title: "COY เก่า",
                    subtitle: "A-A-1"
                },
                {
                    title: "COY ใหม่",
                    subtitle: "A-AA-1 "
                },
                {
                    title: "Flyer",
                    subtitle: "F-AA-1"
                },
                {
                    title: "NCY",
                    subtitle: "N-AA-1"
                }
            ]),
                (this.selectedStatus = "");
            this.selectedArea = [];
            this.results = [];
            this.removeFile();
            this.$refs.wizard.navigateToTab(0);
            this.$refs.wizard.reset();
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
</style>
