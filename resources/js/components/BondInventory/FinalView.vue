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
        </div>
    </section>
</template>

<script>
export default {
    title: "Final View -",
    data() {
        return {
            buttons: [
                {
                    title: "Export",
                    icon: "mdi mdi-24px mdi-export-variant",
                    action: () => this.exportTable()
                },
                {
                    title: "Print",
                    icon: "mdi mdi-24px mdi-printer-wireless",
                    action: null
                },
                {
                    title: "Send to Mail",
                    icon: "mdi mdi-24px mdi-email-mark-as-unread",
                    action: null
                }
            ]
        };
    },
    methods: {
        exportTable(){
            this.$emit('export-to-excel');
        }
    }
};
</script>

<style></style>
