<template>
    <div>
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Chargeback Import
                        </h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                <chargeback-import @imported="parseCsv($event)" :showSummary="showSummary" @toggle-results="toggleSummary"></chargeback-import>
            </div>  
        </div>
        <div v-if="this.showSummary" class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Chargeback Import Results
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body table-responsive">
                <table class="table table-striped- table-bordered table-hover table-checkable" style="width:100%" id="cb-import-result">
                    <thead>
                        <tr>
                            <th v-for="field in activeFields" :key="field.value" :data-column-name="field.text">{{ field.text }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="result in results" :key="result.id" >
                            <td v-for="header in headers" :key="header.title">{{ result[header.name] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group pb-5">
            </div>
        </div>
    </div>
</template>

<script>

    const   ORDER_NOT_FOUND = 0,
            ORDER_CHARGED_BACK = 1,
            ORDER_REFUNDED = 2,
            ORDER_CAN_BE_CHARGED_BACK = 3,
            INVALID_MERCHANT = 4;

    export default {
        data: () => {
            return {
                ORDER_NOT_FOUND: 0,
                ORDER_CHARGED_BACK: 1,
                ORDER_REFUNDED: 2,
                ORDER_CAN_BE_CHARGED_BACK: 3,
                INVALID_MERCHANT: 4,
                showSummary: false,
                results: [],
                headers: [],
                activeFields: [],
            };
        },
        mounted: function() {
            // ("data.csv", { mode: "no-cors" })
            // .then(response => response.text())
            // .then(text => {
            //     const parsedData = this.csvJSON(text);
            //     this.results = JSON.parse(parsedData);
            //     this.filteredData = JSON.parse(parsedData);
            //     this.headers = this.getColumnNames(this.results);
            // });
        },
        computed: {
        },
        methods: {
            toggleSummary(state){
                if(state != null && typeof(state) == 'boolean') 
                 this.showSummary = state; 
                else 
                    this.showSummary = !this.showSummary
            },
            parseCsv(data) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    console.log("Parsing");
                    
                    const parsedData = this.csvJSON(e.target.result);
                    this.results = JSON.parse(parsedData);
                    this.headers = this.getColumnNames(this.results);
                    this.activeFields = data.activeFields;

                    let dataa = [];
                    this.results.forEach(x => {
                        let row = [];
                        this.headers.forEach(y => {
                            try{
                            row.push(x[y.name])
                            }
                            catch(ex){

                            }
                        })
                        dataa.push(row);
                    }) 
                    console.log(JSON.stringify(dataa))

                    console.log("Done");
                };
                reader.readAsText(data.file);
            },
            csvJSON(csv) {
                let lines = csv.split("\n");
                let result = [];
                let headers = lines[0].split(",");
                // console.log(lines);
                // console.log(result);
                // console.log(headers);
                for (var i = 1; i < lines.length; i++) {
                let obj = {};
                let currentline = lines[i].split(/,(?=(?:[^"]*"[^"]*")*(?![^"]*"))/);

                // console.log(lines.length)
                // console.log(i)
                // console.log(currentline.length)
                for (let j = 0; j < headers.length; j++) {
                    try{
                    obj[headers[j]] = currentline[j].replace(/"/g, "");
                    }
                    catch(err)
                    {
                        
                    }
                }
                result.push(obj);
                }
                return JSON.stringify(result); //JSON
            },
            getColumnNames(data) {
                const keys = Object.keys(data[0]);
                const headers = keys.map(k => {
                const obj = {
                    title: k,
                    name: k,
                    sortField: k
                };
                return obj;
                });

                return headers;
            },
            // eslint-disable-next-line no-unused-vars
            // applyFilter: function(e) {
            //     const startDate = new Date(this.dateRangeValue.start);
            //     const endDate = new Date(this.dateRangeValue.end);
            //     const dataToFilter = this.results;
            //     const result = dataToFilter.filter(d => {
            //     const time = new Date(d.cb_date);
            //     return startDate < time && time < endDate;
            //     });

            //     this.filteredData = [...result];
            // }
            // monthHandler: function() {
            //     this.showMonths = !this.showMonths;
            // },
            // selectMonth: function(m) {
            //     this.selectedMonth = m;
            //     this.showMonths = !this.showMonths;
            // }
        },
    }
</script>

<style lang="scss">
@import "../../../../sass/_variables.scss";
@import "./assets/global.scss";


</style>

