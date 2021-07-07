<template>
  <div class="md-layout">
    <div class="md-layout-item">
      <md-card>
        <md-card-header class="md-card-header-icon md-card-header-green">
          <div class="card-icon">
            <md-icon>assignment</md-icon>
          </div>
          <h4 class="title">Casos Asignados</h4>
        </md-card-header>
        <md-card-content>
          <md-table
            :value="queriedData"
            :md-sort.sync="currentSort"
            :md-sort-order.sync="currentSortOrder"
            :md-sort-fn="customSort"
            class="paginated-table table-striped table-hover"
          >
            <md-table-toolbar>
              <md-field>
                <label for="pages">Resultados por pagina</label>
                <md-select v-model="pagination.perPage" name="pages">
                  <md-option
                    v-for="item in pagination.perPageOptions"
                    :key="item"
                    :label="item"
                    :value="item"
                  >
                    {{ item }}
                  </md-option>
                </md-select>
              </md-field>

              <md-field>
                <md-input
                  type="search"
                  class="mb-3"
                  clearable
                  style="width: 200px"
                  placeholder="Buscar"
                  v-model="searchQuery"
                >
                </md-input>
              </md-field>
            </md-table-toolbar>

            <md-table-row slot="md-table-row" slot-scope="{ item }" @click.native="handleCase(item.id)">
              <md-table-cell md-label="Estado" md-sort-by="status">{{item.status | statusDic}}</md-table-cell>
              <md-table-cell md-label="Id" md-sort-by="id">{{item.id}}</md-table-cell>
              <md-table-cell md-label="Monto" md-sort-by="amount">{{item.amount | priceWithCommas}}</md-table-cell>
              <md-table-cell md-label="Fecha" md-sort-by="created_at">{{item.created_at | moment("DD-MM-YY")}}</md-table-cell>
              <md-table-cell md-label="Mediador(a)" md-sort-by="mediator">{{ item.mediator.cedula }}</md-table-cell>
              <md-table-cell md-label="Solicitante" md-sort-by="opener">{{ item.opener.cedula }}</md-table-cell>
              <md-table-cell md-label="Solicitado" md-sort-by="recipient">{{ item.recipient.cedula }}</md-table-cell>
              
            </md-table-row>
          </md-table>
          <div class="footer-table md-table">
            <table>
              <tfoot>
                <tr>
                  <th
                    v-for="item in footerTable"
                    :key="item.name"
                    class="md-table-head"
                  >
                    <div class="md-table-head-container md-ripple md-disabled">
                      <div class="md-table-head-label">
                        {{ item }}
                      </div>
                    </div>
                  </th>
                </tr>
              </tfoot>
            </table>
          </div>
        </md-card-content>
        <md-card-actions md-alignment="space-between">
          <div class="">
            <p class="card-category">
              Mostrando {{ from + 1 }} a {{ to }} de {{ total }} Entradas
            </p>
          </div>
          <pagination
            class="pagination-no-border pagination-success"
            v-model="pagination.currentPage"
            :per-page="pagination.perPage"
            :total="total"
          >
          </pagination>
        </md-card-actions>
      </md-card>
    </div>
  </div>
</template>

<script>
import { Pagination } from "../../../../components";
import Fuse from "fuse.js";
import Swal from "sweetalert2";

export default {
  components: {
    Pagination
  },
  computed: {
    /***
     * Returns a page from the searched data or the whole data. Search is performed in the watch section below
     */
    queriedData() {
      let result = this.tableData;
      if (this.searchedData.length > 0) {
        result = this.searchedData;
      }
      return result.slice(this.from, this.to);
    },
    to() {
      let highBound = this.from + this.pagination.perPage;
      if (this.total < highBound) {
        highBound = this.total;
      }
      return highBound;
    },
    from() {
      return this.pagination.perPage * (this.pagination.currentPage - 1);
    },
    total() {
      return this.searchedData.length > 0
        ? this.searchedData.length
        : this.tableData.length;
    }
  },
  data() {
    return {
      currentSort: "name",
      currentSortOrder: "asc",
      pagination: {
        perPage: 5,
        currentPage: 1,
        perPageOptions: [5, 10, 25, 50],
        total: 0
      },
      footerTable: ["Estado", "Id", "Monto", "Fecha", "Mediador(a)", "Solicitante", "Solicitado"],
      searchQuery: "",
      propsToSearch: ["name", "lastname", "cedula", "role"],
      tableData: [],
      searchedData: [],
      fuseSearch: null,
      
    };
  },
  beforeMount() {
    this.getAllCases();
  },
  methods: {
    customSort(value) {
      return value.sort((a, b) => {
        const sortBy = this.currentSort;
        if (this.currentSortOrder === "desc") {
          return a[sortBy].localeCompare(b[sortBy]);
        }
        return b[sortBy].localeCompare(a[sortBy]);
      });
    },
    handleCase(id) {
      this.$router.push('cases/'+id);
    },
    getAllCases() {
      axios.get('cases/' + user.cedula).then((response) => {
          this.tableData = response.data.cases;
        }).catch();
    }
  },
  mounted() {
    // Fuse search initialization.
    this.fuseSearch = new Fuse(this.tableData, {
      keys: ["name", "lastname", "cedula", "role"],
      threshold: 0.3
    });
  },
  watch: {
    /**
     * Searches through the table data by a given query.
     * NOTE: If you have a lot of data, it's recommended to do the search on the Server Side and only display the results here.
     * @param value of the query
     */
    searchQuery(value) {
      let result = this.tableData;
      if (value !== "") {
        result = this.fuseSearch.search(this.searchQuery);
      }
      this.searchedData = result;
    }
  }
};
</script>

<style lang="css" scoped>
.md-card .md-card-actions {
  border: 0;
  margin-left: 20px;
  margin-right: 20px;
}
.md-table-row {
  cursor: pointer;
}
</style>
