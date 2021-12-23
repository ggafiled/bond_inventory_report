<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row p-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{ translate("Preview result") }}
                            </div>
                        </div>
                        <div class="card-body m-0 p-1">
                            <vue-excel-editor
                                ref="grid"
                                id="grid"
                                filter-row
                                v-model="result"
                            >
                                <vue-excel-column v-for="(item, i) in columns" :key="i" :field="item.field" :label="item.label" :width="item.width" />
                                
                            </vue-excel-editor>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: ["forceRender","result", "columns"],
    methods: {
        exportTable() {
            const format = "xlsx";
            const exportSelectedOnly = false;
            const filename = "result";
            this.$refs.grid.exportTable(format, exportSelectedOnly, filename);
        }
    },
    mounted() {
        this.$emit("table-generated", this.$refs.grid);
    }
};
</script>

<style></style>
