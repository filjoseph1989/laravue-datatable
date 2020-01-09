<template>
    <div class="">
        <div class="">
            <input class="" type="text" v-model="search" placeholder="Search here" @input="resetPagination()" style="width: 250px;">

            <div class="option">
                <div class="">
                    <select v-model="length" @change="resetPagination()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                </div>
            </div>
        </div>

        <br>

        <table class="">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column.name" @click="sortBy(column.name)"
                        :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                        style="width: 10%; cursor:pointer;text-align:left;">
                        {{ column.label }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="user in paginated" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.created_at }}</td>
                </tr>
            </tbody>
        </table>

        <br>

        <div>
            <nav class="" v-if="!tableShow.showdata">
                <span class="">{{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}</span>

                <a v-if="pagination.prevPageUrl" class="prev" @click="--pagination.currentPage"> Prev </a>
                <a class="prev disabled" v-else disabled> Prev </a>

                <a v-if="pagination.nextPageUrl" class="prev" @click="++pagination.currentPage"> Next </a>
                <a class="prev disabled" v-else disabled> Next </a>
            </nav>

            <nav class="" v-else>
                <span class="">
                    {{ pagination.from }} - {{ pagination.to }} of {{ filtered.length }}
                    <span v-if="`filteredUsers.length < pagination.total`"></span>
                </span>

                <a v-if="pagination.prevPage" class="next" @click="--pagination.currentPage"> Prev </a>
                <a class="next disabled" v-else disabled> Prev </a>

                <a v-if="pagination.nextPage" class="next" @click="++pagination.currentPage"> Next </a>
                <a class="next disabled"  v-else disabled> Next </a>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        let sortOrders = {};
        let columns = [
            {label: 'ID', name: 'id' },
            {label: 'Name', name: 'name' },
            {label: 'Email', name: 'email'},
            {label: 'Date Added', name: 'created_at'},
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });

        return {
            rows: [],
            columns: columns,
            sortKey: 'created_at',
            sortOrders: sortOrders,
            length: 10,
            search: '',
            tableShow: {
                showdata: true,
            },
            pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                from: '',
                to: ''
            },
        }
    },

    created() {
        this.getUsers();
    },

    methods: {
        getUsers() {
            axios.get('api/users', {params: this.tableShow})
                .then(response => {
                    this.rows             = response.data.data;
                    this.pagination.total = this.rows.length;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },

        paginate(array, length, pageNumber) {
            this.pagination.from     = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
            this.pagination.to       = pageNumber * length > array.length ? array.length : pageNumber * length;
            this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
            this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';

            return array.slice((pageNumber - 1) * length, pageNumber * length);
        },

        resetPagination() {
            this.pagination.currentPage = 1;
            this.pagination.prevPage    = '';
            this.pagination.nextPage    = '';
        },

        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },

        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value)
        },
    },

    computed: {
        paginated() {
            return this.paginate(this.filtered, this.length, this.pagination.currentPage);
        },

        filtered() {
            let rows = this.rows;

            if (this.search) {
                rows = rows.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }

            let sortKey = this.sortKey;
            let order   = this.sortOrders[sortKey] || 1;

            if (sortKey) {
                rows = rows.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'name', sortKey);

                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();

                    if (this.columns[index].type && this.columns[index].type === 'date') {
                        return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                    } else if (this.columns[index].type && this.columns[index].type === 'number') {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }

            return rows;
        }
    }
};
</script>

<style>
    .prev, .next {
        border: 1px solid black;
        padding: 0 5px;
        margin-right: 5px;
        cursor: pointer;
    }
    .disabled {
        cursor: no-drop;
    }
    .option {
        display: inline-block;
    }
</style>
