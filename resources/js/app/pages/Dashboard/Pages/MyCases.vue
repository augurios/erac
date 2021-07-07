<template>
  <div class="md-layout">
    <div class="md-layout-item md-medium-size-100 md-size-100">
      
      <ClientCases v-if="!reRender && user.role != 'mediator'"></ClientCases>
      <MediatorCases v-if="!reRender && user.role === 'mediator'"></MediatorCases>
      <md-progress-spinner v-if="reRender" :md-diameter="100" :md-stroke="10" md-mode="indeterminate"></md-progress-spinner>
      <md-toolbar class="my-toolbar " md-elevation="1">
        <md-button class="md-raised" @click="refresh"><md-icon>cached</md-icon>Refrescar</md-button>
        <md-button class="md-primary" @click="addNew"><md-icon>add</md-icon>Abrir Caso</md-button>
      </md-toolbar>
    </div>
    <NewCase :addNewActive="addNewActive" @close="closeNew" @newCase="newCase"/>
  </div>
</template>

<script>
import ClientCases from "./Cases/ClientCases";
import MediatorCases from "./Cases/MediatorCases";
import NewCase from "./Cases/NewCase"
export default {
  components: {
    ClientCases,
    NewCase,
    MediatorCases
  },
  data() {
    return {
      addNewActive: false,
      reRender: false,
      user
    }
  },
  methods: {
    addNew() {
      this.addNewActive = true;
    },
    closeNew() {
      this.addNewActive = false;
      this.refresh();
      
    },
    newCase() {
      this.addNewActive = false;
      this.refresh();
    },
    refresh() {
      this.reRender = true;
      setTimeout(()=> {
        this.reRender = false;
      }, 10);
    }
  }
}
</script>

<style lang="scss">
  .my-toolbar {
    margin-bottom: 2rem;
    background-color: #ffffff !important;
    .md-button  {
      margin-right: 1rem;
    }
  }
</style>