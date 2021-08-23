<template>
    <div :class="{classes}">
        <div class="paper-box">
            <div class="paper-box_head">
                <h3 class="cb-sub-title">{{title}}</h3>
            </div>
            <div class="paper-box_content">
                <span class="text-blue" :style="{'color': color + '!important'}">{{value}}</span>
            </div>
        </div>
    </div>
</template>

<script>


export default {
  name: "StatsCard",

  components: {

  },

  props: {
    title: {
      type: String,
      required: true
    },
    value: {
      type: String,
      required: true
    }, 
    color: {
      type: String,
    },
    classes: {
      type: String,
    }
  },
  watch: {
 
  },
  data() {
    return {
      data: [],
    };
  },
  methods: {

  }
};
</script>

<style lang="scss">
@import "../assets/variables.scss";
@import "../assets/global.scss";
.account-row {
        margin-left: -8px;
        margin-right: -8px;
        @media screen and (max-width: 991px) {
            flex-wrap: wrap;
        }
        .cb-col-4 {
            max-width: 22%;
            flex: 22%;
            padding: 0 8px;
            @media screen and (max-width: 991px) {
                max-width: 33.33%;
                flex: 33.33%;
                margin-top: 15px;
            }
            @media screen and (max-width: 479px) {
                max-width: 50%;
                flex: 50%;
            }
        }
        .cb-col-3 {
            max-width: 28%;
            flex: 28%;
            padding: 0 8px;
            @media screen and (max-width: 991px) {
                max-width: 33.33%;
                flex: 33.33%;
                margin-top: 15px;
            }
            @media screen and (max-width: 479px) {
                max-width: 50%;
                flex: 50%;
            }
        }
        .paper-box {
            height: 100%;
        }
        .paper-box_head {
            border-color: #838384;
            padding-left: 20px;
            padding-right: 20px;
            @media screen and (max-width: 1900px) {
                padding-left: 12px;
                padding-right: 12px;
            }
        }
        .paper-box_content {
            padding: 40px 20px;
            font-size: 38px;
            font-weight: 900;
            @media screen and (max-width: 1900px) {
                padding: 34px 12px;
                font-size: 26px;
            }
            @media screen and (max-width: 767px) {
                padding: 20px 12px;
                font-size: 18px;
            }
        }
        .cb-sub-title {
            font-size: 19px;
            @media screen and (max-width: 1900px) {
                font-size: 16px;
            }
            @media screen and (max-width: 1599px) {
                font-size: 15px;
            }
        }
    }
</style>
