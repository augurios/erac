<template>
  <div class="md-layout">
    <div class="md-layout-item">
      <md-card>
        <md-card-header class="md-card-header-icon md-card-header-green">
          <div class="card-icon">
            <md-icon>assignment</md-icon>
          </div>
          <h4 class="title">Especialidades disponibles</h4>
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

            <md-table-row slot="md-table-row" slot-scope="{ item }">
              <md-table-cell md-label="Nombre" md-sort-by="title">{{item.title}}</md-table-cell>
              <md-table-cell md-label="Id" md-sort-by="id">{{item.id}}</md-table-cell>
              <md-table-cell md-label="Fecha" md-sort-by="created_at">{{item.created_at | moment("DD-MM-YY")}}</md-table-cell>
              <md-table-cell md-label="Acciones">
                <md-button
                  class="md-just-icon md-danger md-simple"
                  @click.native="handleDelete(item)"
                >
                  <md-icon>close</md-icon>
                </md-button>
              </md-table-cell>
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
      <md-toolbar class="my-toolbar" md-elevation="1">
        <md-button class="md-primary" @click="newActive = true"><md-icon>add</md-icon>Agregar Especialidad</md-button>
      </md-toolbar>
    </div>
    <modal v-if="newActive" @close="newActive = false" class="edit-modal">
          <template slot="header">
            <h3 class="modal-title">Crear nueva especialidad</h3>
            <md-button class="md-simple md-just-icon md-round modal-default-button" @click="newActive = false">
              <md-icon>clear</md-icon>
            </md-button>
          </template>

          <template slot="body">
            <p>
              <md-field>
                <label>Nombre</label>
                <md-input v-model="newTitle" type="text"></md-input>
              </md-field>
            </p>
          </template>

          <template slot="footer">
            <md-button class="md-success" @click="addNew" :disabled="!newTitle">Agregar nueva especialidad</md-button>
            <md-button class="md-danger md-simple" @click="newActive = false">Cancelar</md-button>
          </template>
    </modal>
  </div>
</template>

<script>
import { Modal } from '../../../../components';
import { Pagination } from "../../../../components";
import Fuse from "fuse.js";
import Swal from "sweetalert2";

export default {
  components: {
    Pagination,
    Modal
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
      footerTable: ["Nombre"],
      searchQuery: "",
      propsToSearch: ["name", "lastname", "cedula", "role"],
      tableData: [],
      searchedData: [],
      fuseSearch: null,
      newActive: false,
      newTitle: ''
    };
  },
  mounted() {
    // Fuse search initialization.
    this.fuseSearch = new Fuse(this.tableData, {
      keys: ["name", "lastname", "cedula", "role"],
      threshold: 0.3
    });
  },
  beforeMount() {
    this.getAllEspecs();
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
    handleDelete(item) {
      Swal.fire({
        title: "Are you sure?",
        text: `You won't be able to revert this!`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "md-button md-success btn-fill",
        cancelButtonClass: "md-button md-danger btn-fill",
        confirmButtonText: "Yes, delete it!",
        buttonsStyling: false
      }).then(result => {
        axios.delete(`especs/${item.id}`).then((response) => {
          if (result.value) {
            this.deleteRow(item);
            Swal.fire({
              title: "Deleted!",
              text: `You deleted Especialidad ID ${item.id}`,
              type: "success",
              confirmButtonClass: "md-button md-success btn-fill",
              buttonsStyling: false
            });
          }
        }).catch();
        
      });
    },
    deleteRow(item) {
      let indexToDelete = this.tableData.findIndex(
        tableRow => tableRow.id === item.id
      );
      if (indexToDelete >= 0) {
        this.tableData.splice(indexToDelete, 1);
      }
    },
    getAllEspecs() {
      axios.get('especs').then((response) => {
          this.tableData = response.data.especialties;
        }).catch();
    },
    addNew() {
      this.newActive = false;
      axios.post('especs',{'title': this.newTitle}).then((response) => {
          this.tableData.push(response.data.especialties);
          this.newTitle = ''
        }).catch();
    }
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
</style>
