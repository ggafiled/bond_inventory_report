<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row p-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ translate("Preview result") }}</div>
                        </div>
                        <div class="card-body m-0 p-1">
                            <vue-excel-editor ref="grid" filter-row v-model="result">
                                <vue-excel-column field="HAWB" label="HAWB" />
                                <vue-excel-column
                                    field="Location"
                                    label="Location"
                                />
                                <vue-excel-column
                                    field="Arrival"
                                    label="Arrival"
                                />
                                <vue-excel-column field="Days" label="Days" />
                                <vue-excel-column
                                    field="Consignee"
                                    label="Consignee"
                                    width="450px"
                                />
                                <vue-excel-column field="Orig" label="Orig" />
                                <vue-excel-column field="Dest" label="Dest" />
                                <vue-excel-column
                                    field="Weight"
                                    label="Weight"
                                />
                                <vue-excel-column field="Pcs" label="Pcs" />
                                <vue-excel-column
                                    field="Scanned"
                                    label="Scanned"
                                />
                                <vue-excel-column
                                    field="Status"
                                    label="Status"
                                />
                                <vue-excel-column
                                    field="Bond Reason"
                                    label="Bond Reason"
                                    width="150px"
                                />
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
    props: ["result"]
};
</script>

<style></style>
