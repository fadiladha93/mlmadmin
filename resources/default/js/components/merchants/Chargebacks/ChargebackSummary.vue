<template>
  <div id="app" class="ui table-content-row">
    <div class="paper-box">
      <div class="paper-box_head">
        <h3 class="cb-sub-title">Summary for merhcant: {{this.cbData[0].merchant}}</h3>
      </div>
      <!-- <div> 
        <p
          class="text text-red text-center"
        >results in modal = total # CB, Total QV Total CV Total amount Total fees</p>
      </div> -->
        <hr class="m-0"/>
      <div class="paper-box_content">
        <div class="cb-filter-group">

          <!-- <div class="cb-checkbox mr-5">
            <input class="checkbox" type="checkbox" id="checkbox1" />
            <label class="label" for="checkbox1">
              <div class="check-mark"></div>
            </label>
          </div>
           -->
          <div v-if="false" class="show-entries cb-filter m-2">
              show
            <div class="entries-count cb-filter_btn no-border mx-2" @click="entriesHandler">
              <span class="cb-filter_text mr-2">{{perPage}}</span>
              <span class="arrow-icon"></span>
            </div>
              entries
              <ul class="cb-filter_list" v-if="showEntries">
                <li
                v-if="5 <= paginationInfo.total"
                class="cb-filter_item"
                @click="perPage = 5"
              >5</li>
                <li
                v-if="10 <= paginationInfo.total"
                class="cb-filter_item"
                @click="perPage = 10"
              >10</li>
                <li
                v-if="20 <= paginationInfo.total"
                class="cb-filter_item"
                @click="perPage = 20"
              >20</li>
            </ul>
          </div>


          <div class="show-entries">
            <span class="label">show</span>
            <div class="entries-count">
              <input v-model="perPage" type="number" value="10" />
              <div class="entries-btns">
                <button class="up-btn" @click="perPage++"></button>
                <button class="down-btn" @click="perPage--"></button>
              </div>
            </div>
            <span class="label">Entries</span>
          </div>


          <div class="cb-filter" @click="actionHandler">
            <div class="cb-filter_btn bg-gray">
              <span class="cb-filter_text">{{selectedAction}}</span>
              <span class="arrow-icon"></span>
            </div>
            <ul class="cb-filter_list" v-if="showActions">
              <li
                v-for="a in actions"
                :key="a"
                class="cb-filter_item"
                @click="selectAction(a)"
              >{{a}}</li>
            </ul>
          </div>
          <button class="cb-btn bg-blue" @click="submitAction">Submit</button>

          <div class="cb-filter ml-auto"  @click="filterHandler">
            <div class="cb-filter_btn bg-gray">

              <span class="cb-filter_text">{{selectedFilter}}</span>
              <span class="arrow-icon"></span>
            </div>
            <ul class="cb-filter_list" v-if="showFilters">
              <li
                v-for="c in columns"
                :key="c.name"
                class="cb-filter_item"
                :class="('cb-filter_item_active', selectedFilter == c.name)"
                @click="selectFilter(c.name)"
              >By {{c.name}}</li>
            </ul>
          </div>
          <button class="cb-btn bg-blue" @click="exportCSVData">Export CSV</button>
        </div>
        <div class="table-responsive">
          <vuetable
            ref="vuetable"
            :api-mode="false"
            :fields="[
              {name: '__checkbox', titleClass: 'cb-checkbox px-4 center aligned', dataClass: 'cb-checkbox px-4 center aligned'}, 
              ...columns
            ]"
            :per-page="parseInt(perPage)"
            :sort-order="sortOrder"
            :data-manager="dataManager"
            pagination-path="pagination"
            :search="searchQuery"
            @vuetable:pagination-data="onPaginationData"
            @vuetable:checkbox-toggled-all="selectAll"
          ></vuetable>
        </div>
        <div class="table-bottom-wrapper">
          <div class="cb-pagination">
            <vuetable-pagination ref="pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
          </div>
          <div class="table-bottom-right-side">
            <span class="text">Displaying {{paginationInfo.from}} - {{paginationInfo.to}} of {{paginationInfo.total}} records</span>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-wrapper" v-if="showModal">
      <div class="paper-box">
        <div class="paper-box_head">
          <h3 class="cb-sub-title">Calculation Results</h3>
        </div>
        <div class="paper-box_content">
          <div class="model-content">
            <div class="model-row">
              <span class="label">Total CB</span>
              <span class="value">${{formatNumber(totalCB)}}</span>
            </div>
            <div class="model-row">
              <span class="label">Total QV</span>
              <span class="value">${{formatNumber(totalQV)}}</span>
            </div>
            <div class="model-row">
              <span class="label">Total CV</span>
              <span class="value">${{formatNumber(totalCV)}}</span>
            </div>
            <div class="model-row">
              <span class="label">Total Amount</span>
              <span class="value">${{formatNumber(totalAmount)}}</span>
            </div>
            <div class="model-row">
              <span class="label">Total CB Fees</span>
              <span class="value">${{formatNumber(totalCBFees)}}</span>
            </div>
            <div class="btn-group">
              <button class="cb-btn bg-blue" @click="exportCSVData">Export</button>
              <button class="cb-btn bg-blue" @click="toggleModel">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vuetable from "vuetable-2";
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";

import _ from "lodash";

export default {
  name: "ChargebackSummary",

  components: {
    Vuetable,
    VuetablePagination
  },

  props: {
    cbData: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    }
  },
  watch: {
    cbData: function(newVal) {
      this.data = newVal;
      this.$refs.vuetable.refresh();
    },
    perPage: function() {
      this.$nextTick(function() {
        this.$refs.vuetable.refresh();
      });
    }

  },
  data() {
    return {
      searchQuery: "",
      vtbleParams: {},
      data: [],
      perPage: 10,
      selectedCategory: "",
      sortOrder: [
        {
          field: "name",
          sortField: "name",
          direction: "asc"
        }
      ],
      actions: ["Calculate Totals", "Mark Disputed", "Reverse volume"],
      showActions: false,
      selectedAction: "Actions",
      showFilters: false,
      showEntries: false,
      selectedFilter: "Select Filter",
      showModal: false,
      paginationInfo:{from: 0, to: 0, total:0},
      selected: []
    };
  },
  methods: {
    markDisputed(){
      let data = this.selected.length? [...this.selected] : [...this.data.map(x => x.id)];
      return axios.post('/chargeback/markDisputed', { importIds: data }).then(res => {
        console.log('markDisputed:', res);
        
      }).catch(err => {
        console.log('markDisputed: erred:', err);

      });
    },
    reverseVolume(){
      let data = this.selected.length? [...this.selected] : [...this.data.map(x => x.id)];
      return axios.post('/chargeback/reverseVolume', { importIds: data }).then(res => {

        console.log('reverseVolume:', res);
      }).catch(err => {

        console.log('reverseVolume: erred:', err);
      });
    },
    exportCSVData(){
      let data = [];
      if(this.selected.length){
        data = [...this.data.filter(x => this.selected.filter(y => x.id == y).length > 0)];
      }
      else
        data = [...this.data];

        this.exportCSVFile(data, `${this.cbData[0].merchant}-data`)
    },
    convertToCSV(objArray) {
        var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
        var str = '';

        for (var i = 0; i < array.length; i++) {
            var line = '';
            for (var index in array[i]) {
                if (line != '') line += ','

                line += array[i][index];
            }

            str += line + '\r\n';
        }

        return str;
    },
    exportCSVFile(items, fileTitle) {

        // Convert Object to JSON
        var jsonObject = JSON.stringify(items);

        var csv = this.convertToCSV(jsonObject);
        
        var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, exportedFilenmae);
        } else {
            var link = document.createElement("a");
            if (link.download !== undefined) { // feature detection
                // Browsers that support HTML5 download attribute
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", exportedFilenmae);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    },
    selectAll(ev){
      console.log(ev);
      
      if(ev)
        this.$refs.vuetable.selectedTo = this.data.map(x => x.id);
      else
        this.$refs.vuetable.selectedTo = []; 

      this.selected = this.$refs.vuetable.selectedTo;
    },
    formatNumber(num){
      let val = '-';
      console.log('num', num );
      
      try{ val = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") } catch(err) {}
      return val;
    },
    entriesHandler: function() {
      this.showEntries = !this.showEntries;
    },
    actionHandler: function() {
      this.showActions = !this.showActions;
    },
    selectAction: function(m) {
      this.selectedAction = m;
      this.showActions = !this.showActions;
    },
    filterHandler: function() {
      this.showFilters = !this.showFilters;
    },
    selectFilter: function(m) {
      this.selectedFilter = m;
      this.sortOrder = [
        {
          field: m,
          sortField: m,
          direction: "asc"
        }];

      this.$refs.vuetable.refresh();
      this.showFilters = !this.showFilters;
    },
    submitAction: function() {
      this.selected = this.$refs.vuetable.selectedTo;
      if(this.selectedAction == "Calculate Totals")
        this.toggleModel();
      else if(this.selectedAction == "Mark Disputed"){
        this.markDisputed();
      }
      else if(this.selectedAction == "Reverse volume"){
        this.reverseVolume();
      }
    },
    toggleModel: function(){
      this.showModal = !this.showModal;
    },
    onPaginationData(paginationData) {
      this.$refs.pagination.setPaginationData(paginationData);
    },
    onChangePage(page) {
      this.$refs.vuetable.changePage(page);
    },
    dataManager(sortOrder, pagination) {
      if (this.data.length < 1) {
        pagination = this.$refs.vuetable.makePagination(0, this.perPage);

        return {
          pagination: pagination,
          data: []
        };
      }
      let local = this.data;

      // sortOrder can be empty, so we have to check for that as well
      if (sortOrder.length > 0) {
        console.log("orderBy:", sortOrder[0].sortField, sortOrder[0].direction);
        local = _.orderBy(
          local,
          sortOrder[0].sortField,
          sortOrder[0].direction
        );
      }

      pagination = this.$refs.vuetable.makePagination(
        local.length,
        this.perPage
      );
      let from = parseInt(pagination.from) - 1;
      let to = parseInt(from) + parseInt(this.perPage);
      let total = local.length;
      this.paginationInfo= {from, to, total }
      return {
        pagination: pagination,
        data: _.slice(local, from, to)
      };
    }
  },
  mounted: function() {
      this.data = this.cbData;
      this.$refs.vuetable.refresh();
      
  },
  computed: {
    totalCB: function(){
      let res = 0;
      if(this.selected.length){
        this.selected.forEach(x => {
          let data = this.data.filter(x1 => x1.id== x)[0];
          res += parseFloat(data.cb_amt);
        })
      }
      else{
        this.data.forEach(x => {
          res += parseFloat(x.cb_amt);
        })
      }
      return res.toFixed(2);
    },
    totalQV: function(){

      let res = 0;
      if(this.selected.length){
        this.selected.forEach(x => {
          let data = this.data.filter(x1 => x1.id== x)[0];
          res += parseFloat(data.order_qv);
        })
      }
      else{
        this.data.forEach(x => {
          res += parseFloat(x.order_qv);
        })
      }
      return res.toFixed(2);
    },
    totalCV: function(){

      let res = 0;
      if(this.selected.length){
        this.selected.forEach(x => {
          let data = this.data.filter(x1 => x1.id== x)[0];
          res += parseFloat(data.order_cv);
        })
      }
      else{
        this.data.forEach(x => {
          res += parseFloat(x.order_cv);
        })
      }
      return res.toFixed(2);
      },
    totalAmount: function(){

      let res = 0;
      if(this.selected.length){
        this.selected.forEach(x => {
          let data = this.data.filter(x1 => x1.id== x)[0];
          res += parseFloat(data.tran_amt);
        })
      }
      else{
        this.data.forEach(x => {
          res += parseFloat(x.tran_amt);
        })
      }
      return res.toFixed(2);
      },
    totalCBFees: function(){
      

      let res = 0;
      if(this.selected.length){
        this.selected.forEach(x => {
          let data = this.data.filter(x1 => x1.id== x)[0];
          res += parseFloat(data.cb_fees);
        })
      }
      else{
        this.data.forEach(x => {
          res += parseFloat(x.cb_fees);
        })
      }
      return parseFloat(res).toFixed(2);
  }
  }
}
</script>

<style lang="scss">
@import "../../../../sass/_variables.scss";
@media screen and (max-width: 1599px){
  .table-content-row .table td, .table-content-row .table th{
    width: auto !important;
    text-align: center;
  }
}
.vuetable-body-wrapper{
      overflow-x: scroll !important;
}
.table-content-row {
  padding-bottom: 15px;
  .paper-box_head {
    padding-left: 30px;
    padding-right: 30px;
    border-color: transparent;
    flex-direction: column;
    .text {
      text-align: center;
      font-size: 14px;
      width: 100%;
      font-weight: 600;
    }
  }
  .cb-sub-title {
    display: block;
    width: 100%;
  }
  .cb-checkbox {
    input[type="checkbox"] {
      height: 18px;
      width: 18px;
      border: 2px solid $primaryText;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      .check-mark {
        opacity: 0;
        transition: all 0.2s ease-in-out;
      }
      // display: none;
      // &:checked ~ .label {
      //   .check-mark {
      //     opacity: 1;
      //   }
      // }
    }
    .check-mark {
      background-image: url("./assets/images/checkmark.png");
      background-size: 10px;
      background-position: center;
      background-repeat: no-repeat;
      display: block;
      width: 100%;
      height: 100%;
    }
    .label {
      height: 18px;
      width: 18px;
      border: 2px solid $primaryText;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      .check-mark {
        opacity: 0;
        transition: all 0.2s ease-in-out;
      }
    }
  }
  .cb-filter-group {
    display: flex;
    align-items: center;
    padding-left: 15px;
    margin-right: 15px;
    margin-top: 15px;
    @media screen and (max-width: 767px) {
      flex-wrap: wrap;
      .cb-filter,
      .show-entries,
      .cb-btn {
        margin-bottom: 10px;
        margin-right: 14px;
      }
    }
    .ml-auto {
      margin-left: auto;
      @media screen and (max-width: 767px) {
        margin-left: 0;
      }
    }
    .cb-btn {
      margin-left: 25px;
      @media screen and (max-width: 767px) {
        margin-left: 0;
      }
    }
    .show-entries {
      margin-right: 35px;
      display: flex;
      margin-left: 25px;
      align-items: center;
      @media screen and (max-width: 991px) {
        margin-right: 15px;
        margin-left: 15px;
      }
      input[type="number"] {
        color: $primaryText;
        border: 0;
        font-size: 18px;
        width: 60%;
        text-align: center;
        background-color: transparent;
        @media screen and (max-width: 991px) {
          font-size: 14px;
        }
        &:focus {
          outline: 0;
          box-shadow: none;
        }
        &::-webkit-inner-spin-button {
          -webkit-appearance: none;
        }
        &::-webkit-outer-spin-button {
          -webkit-appearance: none;
        }
      }
      .entries-count {
        display: flex;
        background-color: $gray;
        max-width: 82px;
        border-radius: 8px;
        height: 58px;
        @media screen and (max-width: 991px) {
          height: 38px;
        }
      }
      .entries-btns {
        display: flex;
        flex-direction: column;
        justify-content: center;
        button {
          border: 0;
          max-width: 11px;
          padding: 0;
          width: 15px;
          height: 15px;
          background-color: transparent;
          img {
            width: 100%;
          }
          &.up-btn {
            background-image: url("./assets/images/up-arrow-icon.png");
            background-repeat: no-repeat;
            background-size: 15px;
            background-position: center;
          }
          &.down-btn {
            background-image: url("./assets/images/down_arrow.png");
            background-repeat: no-repeat;
            background-size: 15px;
            background-position: center;
          }
        }
      }
      .label {
        font-size: 18px;
        font-weight: 500;
        color: $primaryText;
        margin: 0 5px;
        @media screen and (max-width: 991px) {
          font-size: 14px;
        }
      }
    }
  }
  .cb-filter_btn {
    &.bg-gray {
      border-color: $gray;
    }
  }
  .table-responsive {
    overflow-x: auto;
    &::-webkit-scrollbar-track {
      border-radius: 2;
      background-color: #1b2133;
    }

    &::-webkit-scrollbar {
      height: 1px;
      background-color: #ddd;
    }

    &::-webkit-scrollbar-thumb {
      border-radius: 2px;
      background-color: #1b2133;
    }
  }
  .table {
    border-spacing: 0;
    width: 100%;
    margin-top: 20px;
    min-width: 1400px;
    thead {
      background-color: #b5ddf1;
    }
    td,
    th {
      padding: 1vw 0;
      font-size: 0.6vw;
      color: $primaryText;
      font-weight: 500;
      @media screen and (max-width: 1599px) {
        font-size: 13px;
        padding: 20px 0;
      }
      &:first-child {
        padding-left: 15px;
      }
      &.conditional {
        font-weight: 600;
        font-size: 14px;
      }
    }
    tbody {
      tr {
        &:nth-child(odd) {
          background-color: #e4ebf7;
        }
      }
    }
  }
  .cb-pagination {
    display: flex;
  }
}
.cb-pagination {
  .pagination {
    display: inline-flex;
  }
  .item {
    margin: 0 4px;
    height: 40px;
    width: 40px;
    border-radius: 12px;
    overflow: hidden;
    font-size: 18px;
    color: $primaryText;
    display: flex;
    justify-content: center;
    align-items: center;
    @media screen and (max-width: 1599px) {
      height: 32px;
      width: 32px;
      border-radius: 8px;
    }
    @media screen and (max-width: 1279px) {
      height: 26px;
      width: 26px;
      img {
        width: 14px;
      }
    }
    @media screen and (max-width: 479px) {
      margin: 0 2px;
      height: 23px;
      width: 23px;
    }

    @media screen and (max-width: 1599px) {
      font-size: 16px;
    }
    @media screen and (max-width: 1279px) {
      font-size: 14px;
    }
    &.active {
      background-color: $lightBlue;
      color: #fff;
    }
    cursor: pointer;
    &.btn-nav {
      background-color: $gray;
      display: flex;
      justify-content: center;
      align-content: center;
    }
    .double {
      background-repeat: no-repeat;
      display: block;
      width: 17px;
      height: 17px;
      margin-bottom: 3px;
      &.left {
        background-image: url("./assets/images/pagination-arrow-iconLeft1.png");
      }
      &.right {
        background-image: url("./assets/images/pagination-arrow-iconRight1.png");
      }
    }
    .chevron {
      background-repeat: no-repeat;
      display: block;
      width: 17px;
      height: 17px;
      margin-bottom: 3px;
      &.left {
        background-image: url("./assets/images/pagination-arrow-iconLeft2.png");
      }
      &.right {
        background-image: url("./assets/images/pagination-arrow-iconRight2.png");
      }
    }
  }
}
.table-bottom-wrapper {
  display: flex;
  justify-content: space-between;
  padding-top: 40px;
  padding-bottom: 60px;
  padding-left: 15px;
  padding-right: 15px;
  max-width: 1300px;
  margin: 0 auto;
  flex-wrap: wrap;
  @media screen and (max-width: 767px) {
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  .table-bottom-right-side {
    display: flex;
    align-items: center;
    @media screen and (max-width: 767px) {
      margin-top: 10px;
    }
    .text {
      color: #515974;
      font-size: 16px;
      @media screen and (max-width: 767px) {
        font-size: 13px;
      }
    }
  }
  .cb-filter_btn {
    background-color: #e4ebf7;
    height: 42px;
    margin-right: 20px;
    .cb-filter_text {
      font-size: 18px;
    }
    @media screen and (max-width: 767px) {
      height: 32px;
      font-size: 16px;
    }
  }
}
.modal-wrapper {
  width: 100%;
  height: 100%;
  position: fixed;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  background-color: rgba(0, 0, 0, 0.3);
  z-index: 9999;
  .paper-box {
    max-width: 600px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    @media screen and (max-width: 639px) {
      left: 20px;
      right: 20px;
      transform: translate(0, -50%);
      width: calc(100% - 40px);
    }
    &_head {
      border-color: lightblue;
      padding-left: 20px;
      padding-right: 20px;
    }
    &_content {
      padding: 40px;
      .model-content {
        max-width: 400px;
        margin: 0 auto;
      }
    }
    .model-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      span {
        font-size: 28px;
        font-weight: bold;
        color: $primaryText;
        padding: 8px 0;
        @media screen and (max-width: 767px) {
          font-size: 20px;
        }
      }
    }
    .btn-group {
      display: flex;
      justify-content: center;
      margin-top: 40px;
      .cb-btn {
        height: 50px;
        margin: 0 10px;
        @media screen and (max-width: 767px) {
          height: 36px;
        }
      }
    }
  }
}
</style>
