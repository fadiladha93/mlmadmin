$( document ).ready(function() {
    var table = $('#dt_country').DataTable({
        ajax: '/settings/dt-countries',
        columns: [
            // {data: 'id'},
            {data: 'country', name: 'Country'},
            {data: 'countrycode', name: 'Country code'},
            {data: 'enable_2fa', name: 'Enable 2FA'},
            {data: 'is_tier3', name: 'Tier 3'},
            {data: 'is_open', name: 'Open'},
            {data: 'action', name: 'Action'},
        ],
        columnDefs: [
            // {
            //     targets: 0,
            //     searchable: false,
            //     orderable: false,
            //     render: function (data, type, full, meta) {
            //         return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
            //     },
            // },
            {
               targets: 1,
               searchable: false,
               orderable: false,
               render: function (data, type, full, meta) {
                  if(data){
                     return '<span class="m-badge m-badge--success m-badge--wide">Yes</span>';
                  }else{
                     return '<span class="m-badge  m-badge--danger m-badge--wide">No</span>';
                  }
               },
            },
            {
               targets: 2,
               searchable: false,
               orderable: false,
               render: function (data, type, full, meta) {
                  if(data){
                     return '<span class="m-badge m-badge--success m-badge--wide">Yes</span>';
                  }else{
                     return '<span class="m-badge  m-badge--danger m-badge--wide">No</span>';
                  }
               }
            },
            {
               targets: 3,
               searchable: false,
               orderable: false,
               render: function (data, type, full, meta) {
                  if(data){
                     return '<span class="m-badge m-badge--success m-badge--wide">Yes</span>';
                  }else{
                     return '<span class="m-badge  m-badge--danger m-badge--wide">No</span>';
                  }
               }
            },
            {
               targets: 4,
               searchable: false,
               orderable: false,
               render: function (data, type, full, meta) {
                  if(data){
                     return '<span class="m-badge m-badge--success m-badge--wide">Yes</span>';
                  }else{
                     return '<span class="m-badge  m-badge--danger m-badge--wide">No</span>';
                  }
               }
            },
        ]

    });

   // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
    // Get all rows with search applied
    var rows = table.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);

    console.log(rows.length);
 });

 // Handle click on checkbox to set state of "Select all" control
 $('#example tbody').on('change', 'input[type="checkbox"]', function(){
    // If checkbox is not checked
    if(!this.checked){
       var el = $('#example-select-all').get(0);
       // If "Select all" control is checked and has 'indeterminate' property
       if(el && el.checked && ('indeterminate' in el)){
          // Set visual state of "Select all" control
          // as 'indeterminate'
          el.indeterminate = true;
       }
    }
 });

   // Handle form submission event
   $('#frm-example').on('submit', function(e){
      var form = this;

      // Iterate over all checkboxes in the table
      table.$('input[type="checkbox"]').each(function(){
         // If checkbox doesn't exist in DOM
         if(!$.contains(document, this)){
            // If checkbox is checked
            if(this.checked){
               // Create a hidden element
               $(form).append(
                  $('<input>')
                     .attr('type', 'hidden')
                     .attr('name', this.name)
                     .val(this.value)
               );
            }
         }
      });
   });

});
  
  