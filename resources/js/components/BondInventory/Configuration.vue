<template>
    <div class="card bg-gray-light col-sm-12" style="height: 420px">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="row">
                        <div
                            class="card col-sm-12 col-xl-12 overflow-auto"
                            style="height: 420px"
                        >
                            <div class="row sheet-panel">
                                <div class="col-sm-6 m-0 p-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                {{
                                                    translate(
                                                        "bondinventory.header_select_status"
                                                    )
                                                }}
                                            </h3>
                                        </div>
                                        <div class="card-body panel-custom">
                                            <div class="form-group">
                                                <div
                                                    class="d-flex flex-row flex-wrap justify-space-between p-2"
                                                >
                                                    <div
                                                        class="d-flex flex-column mr-4"
                                                        v-for="(item,
                                                        i) in statusList"
                                                        :key="i"
                                                    >
                                                        <label>
                                                            <input
                                                                type="radio"
                                                                :value="item"
                                                                v-model="
                                                                    selectedStatus
                                                                "
                                                            />
                                                            <div class="box">
                                                                <div
                                                                    class="d-flex flex-column justify-content-center align-items-center"
                                                                >
                                                                    <i
                                                                        class="far fa-folder-open fa-3x mb-2"
                                                                    ></i>
                                                                    <span
                                                                        class="d-inline-block text-break p-1"
                                                                        >{{
                                                                            item
                                                                        }}</span
                                                                    >
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 m-0 p-0">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                {{
                                                    translate(
                                                        "bondinventory.header_select_area"
                                                    )
                                                }}
                                            </h3>
                                            <div class="card-tools">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        v-model="selectAll"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        >{{
                                                            translate(
                                                                "bondinventory.button.select_all"
                                                            )
                                                        }}</label
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body panel-custom">
                                            <div class="form-group">
                                                <div
                                                    class="d-flex flex-row flex-wrap justify-space-between p-2"
                                                >
                                                    <div
                                                        class="d-flex flex-column mr-4"
                                                        v-for="(item,
                                                        i) in areaList"
                                                        :key="i"
                                                        v-tooltip.bottom="{
                                                            content:
                                                                item.tooltip,
                                                            offset: 100,
                                                            classes: [
                                                                'tooltip-info'
                                                            ],
                                                            targetClasses: [
                                                                'it-has-a-tooltip'
                                                            ],
                                                            delay: {
                                                                show: 500
                                                            },
                                                            autoHide: true,
                                                            handleResize: true,
                                                            html: true
                                                        }"
                                                    >
                                                        <label>
                                                            <input
                                                                type="checkbox"
                                                                :id="item.title"
                                                                :value="
                                                                    item.title
                                                                "
                                                                v-model="
                                                                    selectedArea
                                                                "
                                                            />
                                                            <div class="box">
                                                                <div
                                                                    class="d-flex flex-column justify-content-center align-items-center"
                                                                >
                                                                    <i
                                                                        class="mdi mdi-48px mdi-warehouse mb-2"
                                                                    ></i>
                                                                    <span
                                                                        class="d-inline-block text-break p-1"
                                                                        >{{
                                                                            item.title
                                                                        }}</span
                                                                    >
                                                                    <span
                                                                        class="d-inline-block text-break p-1"
                                                                        >{{
                                                                            item.subtitle
                                                                        }}</span
                                                                    >
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["forceRender", "statusList", "areaList"],
    components: {},
    data() {
        return {
            selectedStatus: "",
            selectedArea: []
        };
    },
    methods: {
        selectedFile(file) {
            this.$emit("selectedFile", file);
        },
        removeFile() {
            this.$refs.fileImport.removeAllFiles();
        }
    },
    watch: {
        statusList: function(newVal, oldVal) {
            this.selectedStatus = this.statusList[0];
        },
        selectedStatus: function(newVal, oldVal) {
            this.$emit("selectedStatus", newVal);
        },
        areaList: function(newVal, oldVal) {
            this.selectedArea = [newVal[0].title];
        },
        selectedArea: function(newVal, oldVal) {
            this.$emit("selectedArea", newVal);
        }
    },
    computed: {
        selectAll: {
            get: function() {
                return this.areaList
                    ? this.selectedArea.length == this.areaList.length
                    : false;
            },
            set: function(value) {
                var selected = [];
                if (value) {
                    this.areaList.forEach(area => {
                        selected.push(area.title);
                    });
                    this.selectedArea = selected;
                }else{
                    this.selectedArea = [this.areaList[0].title];
                }
            }
        }
    },
    mounted() {
        this.selectedStatus = this.statusList[0];
        this.selectedArea = [this.areaList[0].title];

        this.$emit("selectedStatus", this.selectedStatus);
        this.$emit("selectedArea", this.selectedArea);
    }
};
</script>
<style scoped lang="scss">
.panel-custom {
    input[type="checkbox"],
    input[type="radio"] {
        display: none;
        &:checked {
            + .box {
                background-color: #da5555;
                i {
                    color: white;
                }
                span {
                    color: white;
                    // transform: translateY(70px);
                    &:before {
                        transform: translateY(0px);
                        opacity: 1;
                    }
                }

                small {
                    color: white;
                    // transform: translateY(70px);
                    &:before {
                        transform: translateY(0px);
                        opacity: 1;
                    }
                }
            }
        }
    }
}
.sheet-panel {
    background-color: #f5f5f5;
}
.box {
    width: 150px;
    height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 8px;
    background-color: #eeeeee;
    border: 1px solid #8d90a0;
    transition: all 250ms ease;
    will-change: transition;
    text-align: center;
    cursor: pointer;
    font-weight: 900;
    &:active {
        transform: translateY(10px);
        i,
        span,
        small {
            color: white;
        }
    }
    span,
    small {
        left: 0;
        right: 0;
        transition: all 300ms ease;
        user-select: none;
        color: #aeaeae;
    }
}
</style>
