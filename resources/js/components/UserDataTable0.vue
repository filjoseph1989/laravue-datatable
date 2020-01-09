<template>
    <div class="row">
       <div class="col-xs-12 form-inline">
           <div class="form-group">
               <label for="filter" class="sr-only">Filter</label>
               <input type="text" class="form-control" v-model="filter" placeholder="Filter" @keydown="$event.stopImmediatePropagation()">
           </div>
       </div>

       <div class="col-xs-12 table-responsive">
           <datatable
               :columns="columns"
               :data="rows"
               :filter="filter"
               :per-page="25">
           </datatable>

           <datatable-pager
               v-model="page"
               type="abbreviated">
           </datatable-pager>
       </div>
   </div>
</template>

<script>
    export default {
        data() {
            return {
                filter: '',
                columns: [
                    {label: 'id', field: 'id', align: 'center', headerClass: 'header-id'},
                    {label: 'Name', field: 'name', align: 'center', class: this.getClasses()},
                    {label: 'Email', field: 'email', align: 'center'},
                    {label: 'Action', align: 'center', interpolate: true, representedAs: function() {
                        return '<a href="#" class="delete">Delete</a> | <a href="#" class="edit">Edit</a>';
                    }}
                ],
                rows: [],
                page: 1,
                per_page: 0,
                from: 0,
                of: 0,
                to: 0,
            }
        },
        methods: {
            getUsers: function() {
                axios.get('/api/users').then(function(response) {
                    this.rows     = response.data.data;
                    this.page     = response.data.current_page;
                    this.per_page = response.data.per_page;
                    this.from     = response.data.from;
                    this.to       = response.data.to;
                    this.of       = response.data.total;

                    console.log(response.data);
                }.bind(this));
            },
            getClasses: function () {
                return 'sample-class';
            }
        },
        created: function() {
            this.getUsers()
        }
    }
</script>
