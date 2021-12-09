<template>
    <div class="card bg-gray-light col-sm-12" style="height: 420px">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xl-9 col-12">
                            <div class="form-group">
                                <div
                                    class="card bg-light"
                                    style="height: 360px"
                                >
                                    <vue-dropzone
                                        ref="fileImport"
                                        id="customdropzone"
                                        :options="dropzoneOptions"
                                        v-on:vdropzone-file-added="selectedFile"
                                        v-on:vdropzone-queue-complete="
                                            completeProgress
                                        "
                                    ></vue-dropzone>
                                    <p class="p-2 m-0 justify-content-center align-items-center">
                                        {{ translate('bondinventory.footer_informed_upload_support') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 d-none d-xl-block">
                            <div class="form-group">
                                <div class="card d-none d-xl-block">
                                    <div
                                        class="card-header text-white"
                                        style="background-color: #A9343B;"
                                    >
                                        <h4>File Import</h4>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            By clicking the image or dropping
                                            the file directly to this left
                                            section will start the import
                                            process for your transactions.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="card d-none d-xl-block">
                                    <div
                                        class="card-header text-white"
                                        style="background-color: #A9343B;"
                                    >
                                        <h4>Simple Data</h4>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Can export the report from ACA.
                                        </p>
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
    data() {
        return {
            dropzoneOptions: {
                url: "/api/import/getInfo",
                autoProcessQueue: false,
                clickable: true,
                thumbnailWidth: 150,
                maxFilesize: 10000000,
                maxFiles: 1,
                duplicateCheck: true,
                addRemoveLinks: true,
                acceptedFiles: ".csv,.txt",
                dictDefaultMessage:
                    "<div class='row g-1'><div class='col-md-4 flex-grow-1 m-0 p-0'><img src='/images/folder.png'/></div><div class='col-md-6'><div class='card-body'><br /><br /><br /><h1>Click or Drop</h1><p class='normal'style='color: Gray'>To upload your files</p></div></div></div>"
            }
        };
    },
    methods: {
        selectedFile(file) {
            this.$emit("selectedFile", file);
        },
        removeFile() {
            this.$refs.fileImport.removeAllFiles();
        },
        completeProgress() {
            document.querySelector("#total-progress").style.opacity = "0.0";
            console.log("completeProgress");
        }
    }
};
</script>

<style lang="scss" scoped>
h1 {
    font-size: 80px;
    font-weight: bold;
}
p.normal {
    font-size: 30px;
    font-weight: bold;
}
p.normal2 {
    font-size: 15px;
}
</style>
