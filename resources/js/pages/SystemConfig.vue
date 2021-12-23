<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-gear mr-1"></i
                                >{{ translate("systemconfig.header") }}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-2">
                            <ul class="nav nav-tabs">
                                <li>
                                    <a
                                        data-toggle="tab"
                                        href="#statusconfig"
                                        class="nav-link active"
                                        >Status Configuration
                                    </a>
                                </li>
                                <li>
                                    <a
                                        data-toggle="tab"
                                        href="#zoneconfig"
                                        class="nav-link"
                                        >Zone Configuration
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="statusconfig" class="tab-pane active">
                                    <section
                                        style="height: 70vh !important; padding: 3; margin: 0;"
                                    >
                                        <StatusConfig>
                                        </StatusConfig>
                                    </section>
                                </div>
                                <div id="zoneconfig" class="tab-pane">
                                    <section
                                        style="height: 65vh !important; padding: 3; margin: 0;"
                                    >
                                        <ZoneConfig>
                                        </ZoneConfig>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import StatusConfig from "../components/systemconfig/StatusConfig.vue";
import ZoneConfig from "../components/systemconfig/ZoneConfig.vue";
export default {
    title: "System Configuration -",
    components: {
        StatusConfig,
        ZoneConfig
    },
    data() {
        return {
            tab: "statusconfig"
        };
    },
    methods: {
        activaTab(tab, vm = this) {
            if ($('.nav-tabs a[href="#' + tab + '"]').length) {
                $('.nav-tabs a[href="#' + tab + '"]').tab("show");
            } else {
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set(
                    "tab",
                    $(".nav-tabs a")
                        .first()
                        .attr("href")
                        .toString()
                        .replace("#", "")
                );
                if (history.pushState) {
                    var newurl =
                        window.location.protocol +
                        "//" +
                        window.location.host +
                        window.location.pathname +
                        "?" +
                        urlParams;
                    window.history.pushState({ path: newurl }, "", newurl);
                }
                $(".nav-tabs a")
                    .first()
                    .tab("show");
            }
        },
        deactivaTab(tab) {
            $(".nav-tabs a").removeClass("active");
        }
    },
    created() {
        this.$Progress.start();
        // LoadingWait.fire();
        // to do
        this.$Progress.finish();
    },
    mounted() {
        // setTimeout(() => {
        //   LoadingWait.close();
        // }, 2000);
        this.deactivaTab();
        this.activaTab(this.$route.query.tab);
        $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
            var target = $(e.target)
                .attr("href")
                .replace("#", ""); // activated tab
            const urlParams = new URLSearchParams();
            urlParams.set("tab", target);
            if (history.pushState) {
                var newurl =
                    window.location.protocol +
                    "//" +
                    window.location.host +
                    window.location.pathname +
                    "?" +
                    urlParams;
                window.history.pushState({ path: newurl }, "", newurl);
            }
            if (target == "gis") {
                vm.map.resize();
            }
        });
    },
    unmounted() {
        this.deactivaTab();
    }
};
</script>
