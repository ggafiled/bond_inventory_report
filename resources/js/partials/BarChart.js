import { Bar, mixins } from "vue-chartjs";
import zoomPlugin from "chartjs-plugin-zoom";

Chart.plugins.unregister(zoomPlugin);

export default {
    props: ["chartData", "options"],
    extends: Bar,
    mixins: [mixins.reactiveProp],
    methods: {
        updateChart() {
            this.renderChart(this.chartData, this.options);
        }
    },
    mounted() {
        // Overwriting base render method with actual data.
        this.$parent.$on("updateBarChart", this.updateChart);
        this.addPlugin(zoomPlugin);
        this.renderChart(this.chartData, this.options);
    }
};