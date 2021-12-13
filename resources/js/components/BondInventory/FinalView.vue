<template>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{ translate("Select your action") }}
                            </div>
                        </div>
                        <div class="card-body">
                            <v-btn class="mb-3" block x-large>
                                <vue-excel-xlsx
                                    :data="results"
                                    :columns="columns"
                                    :filename="fileName"
                                    sheetname="Results"
                                    class="w-100"
                                >
                                    <i
                                        class="mdi mdi-24px mdi-export-variant"
                                    ></i>
                                    Export
                                </vue-excel-xlsx>
                            </v-btn>
                            <v-btn
                                class="mb-3"
                                block
                                x-large
                                v-for="(item, i) in buttons"
                                :key="i"
                                @click="item.action"
                                ><i :class="item.icon"></i>
                                {{ item.title }}
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-none">
                <div id="exportToPaper">
                    <div class="container">
                        <table
                            id="tableExport"
                            class="table table-sm table-responsive font-weight-bold w-auto text-center"
                        >
                            <tr>
                                <th class="mw-100">NO</th>
                                <th v-for="(item, i) in columns" :key="i">
                                    {{ item.label }}
                                </th>
                            </tr>
                            <tr v-for="(item, i) in results" :key="i">
                                <td class="mw-100">{{ i + 1 }}</td>
                                <td>{{ item.HAWB }}</td>
                                <td>{{ item.Location }}</td>
                                <td>{{ item.Weight }}</td>
                                <td class="mw-100">{{ item.Pcs }}</td>
                                <td class="mw-100">{{ item.Scanned }}</td>
                                <td class="mw-100">{{ item.Status }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import moment from "moment";
export default {
    title: "Final View -",
    props: ["results", "columns"],
    data() {
        return {
            data() {
                return {
                    fileName: ""
                };
            },
            buttons: [
                {
                    title: "Print",
                    icon: "mdi mdi-24px mdi-printer-wireless",
                    action: async () => await this.printTopaper()
                }
                // {
                //     title: "Send to Mail",
                //     icon: "mdi mdi-24px mdi-email-mark-as-unread",
                //     action: async () => await this.exportAndSendToMail()
                // }
            ]
        };
    },
    methods: {
        setFileName() {
            this.fileName = "Results-" + moment();
        },
        exportAndSendToMail() {},
        async printTopaper() {
            await this.$htmlToPaper("exportToPaper", {
                name: "_blank",
                specs: ["fullscreen=yes", "titlebar=yes", "scrollbars=yes"],
                styles: ["/css/app.css"],
                autoClose: true,
                windowTitle: "Print."
            });
        }
    },
    mounted() {
        this.setFileName();
    }
};
</script>
