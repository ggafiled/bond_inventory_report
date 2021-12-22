<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="bi bi-gear mr-1"></i>{{ translate("System Configuration") }}
              </h3>
              <div class="card-tools">
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  @click="newModal"
                >
                  <i class="fa fa-plus-square"></i>
                  {{ translate("user.addnew") }}
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-2">

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
export default {
  title: "System Configuration -",
  data() {
    return {
      form: new Form({
        id: "",
        role: "",
        name: "",
        email: "",
        password: "",
        email_verified_at: "",
        account_status: 1,
      }),
    };
  },
  methods: {
  },
  created() {
    this.$Progress.start();
    LoadingWait.fire();
    // to do
    this.$Progress.finish();
  },
  mounted() {
    setTimeout(() => {
      LoadingWait.close();
    }, 2000);
  },
};
</script>
