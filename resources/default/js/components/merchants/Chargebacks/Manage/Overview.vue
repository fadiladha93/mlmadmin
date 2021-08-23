<template>
  <div class="chart-wrapper">
    <div class="chart-info">
      <ul class="chart-info_list">
        <li class="chart-info_item">
          <span class="color-identity bg-blue-gradient-v"></span>
          <span class="text">Total Sales</span>
        </li>
        <li class="chart-info_item">
          <span class="color-identity bg-yellow"></span>
          <span class="text">Total Chgbk</span>
        </li>
      </ul>
      <div id="chartContainer"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Overview",

  components: {},

  props: {
    cbData: {
      type: Array,
      required: true
    }
  },
  watch: {
    cbData: function(newVal) {
      this.data = newVal;
      this.populateGraph(this.data);
    }
  },
  data() {
    return {
      data: []
    };
  },
  methods: {
    populateGraph: function(data) {
      let salesDataPoints = [];
      [...Array(12).keys()].forEach((d, idx) => {
        salesDataPoints.push({
          x: new Date(`${new Date().getFullYear()}, ${idx }`),
          y: parseFloat(
            data
              .filter(dt => new Date(dt.cb_date).getMonth() === idx + 1)
              .reduce((prev, next) => {
                return (prev += parseFloat(next.cb_amt));
              }, 0)
              .toFixed(2)
          ),
          indexLabel: d.cb_amt
        });
      });
      let cashbackDataPoints = [];
      [...Array(12).keys()].forEach((d, idx) => {
        cashbackDataPoints.push({
          x: new Date(`${new Date().getFullYear()}, ${idx }`),
          y: 100 * parseFloat(
            data
              .filter(dt => new Date(dt.tran_date).getMonth() === idx + 1)
              .reduce((prev, next) => {
                return parseFloat((prev += next.tran_amt));
              }, 0)
              .toFixed(2)
          ),
          indexLabel: d.tran_amt
        });
      });
      var options = {
        animationEnabled: true,
        backgroundColor: "#f2f6fc",
        theme: "light2",
        axisX: {
          valueFormatString: "MMM",
          
        },
        axisY: {
          gridThickness: 0,
          prefix: "",
          titleFontSize: 0,
          labelFontSize: 0
        },
        toolTip: {
          enabled: true
        },
        legend: {
          cursor: "pointer"
        },
        data: [
          {
            type: "splineArea",
            name: "Profit",
            markerBorderColor: "white",
            markerBorderThickness: 0,
            showInLegend: false,
            yValueFormatString: "$#,##0",
            color: "#0892d0",
            markerColor: "#515974",
            markerSize: 10,
            dataPoints: salesDataPoints
          },
          {
            type: "splineArea",
            name: "Profit",
            markerBorderColor: "white",
            markerBorderThickness: 0,
            showInLegend: false,
            yValueFormatString: "$#,##0",
            color: "#ead401",
            markerColor: "#515974",
            markerSize: 10,
            dataPoints: cashbackDataPoints
          }
        ]
      };
       window.$("#chartContainer").CanvasJSChart(options);
    }
  }
};
</script>

<style lang="scss">
@import "../assets/variables.scss";
.chart-row {
  .paper-box_head {
    padding-left: 30px;
    padding-right: 30px;
    border-color: #e1e7f0;
  }
  .cb-filter-group {
    padding-top: 36px;
    display: flex;
    padding-left: 56px;
    padding-right: 56px;
    @media screen and (max-width: 1599px) {
      padding-left: 30px;
      padding-right: 30px;
    }
    @media screen and (max-width: 991px) {
      padding-left: 20px;
      padding-right: 20px;
    }
    @media screen and (max-width: 767px) {
      flex-wrap: wrap;
    }
    @media screen and (max-width: 479px) {
      padding-top: 15px;
    }
    .cb-btn {
      margin-left: auto;
      @media screen and (max-width: 767px) {
        margin-left: 0;
      }
    }
    .cb-filter {
      margin-right: 35px;
      @media screen and (max-width: 991px) {
        margin-right: 20px;
      }
      @media screen and (max-width: 767px) {
        margin-bottom: 10px;
      }
      &:last-child {
        margin-right: 0;
      }
    }
  }
  .chart-wrapper {
    min-height: 650px;
    padding-left: 30px;
    padding-right: 30px;
    position: relative;
    @media screen and (max-width: 767px) {
      min-height: 330px;
    }
    .chart-info {
      &_list {
        display: block;
        position: absolute;
        left: 88px;
        top: 40px;
        z-index: 2;
        @media screen and (max-width: 1599px) {
          left: 52px;
        }
        @media screen and (max-width: 767px) {
          left: auto;
          right: 9px;
          top: -4px;
        }
      }
      &_item {
        padding: 10px 0;
        display: flex;
        @media screen and (max-width: 767px) {
          padding: 4px 0;
        }
        .text {
          font-size: 20px;
          font-weight: 600;
          color: $primaryText;
          @media screen and (max-width: 767px) {
            font-size: 14px;
          }
        }
        .color-identity {
          display: block;
          height: 28px;
          width: 28px;
          margin-right: 15px;
          @media screen and (max-width: 767px) {
            height: 16px;
            width: 16px;
            margin-right: 10px;
          }
        }
      }
    }
    #chartContainer {
      width: 100%;
      height: 600px;
      @media screen and (max-width: 767px) {
        height: 340px;
      }
    }
  }
}
</style>
