<template>
  <div class="md-layout">
    <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25">
       <stats-card header-color="rose">
        <template slot="header">
          <div class="card-icon">
            <md-icon>equalizer</md-icon>
          </div>
          <p class="category">Casos Activos</p>
          <h3 class="title">
            <animated-number :value="cases.length" v-if="cases.length"></animated-number>
          </h3>
        </template>

        <template slot="footer">
          <div class="stats">
            <a href="/app#/cases">Ver Casos</a>
            
          </div>
        </template>
      </stats-card>
    </div>
    
  </div>
</template>

<script>
import AsyncWorldMap from "../../components/WorldMap/AsyncWorldMap.vue";
import {
  StatsCard,
  ChartCard,
  ProductCard,
  AnimatedNumber,
  GlobalSalesCard,
  GlobalSalesTable
} from "../../components";

export default {
  components: {
    StatsCard,
    ChartCard,
    AnimatedNumber,
    ProductCard,
    GlobalSalesCard,
    GlobalSalesTable,
    AsyncWorldMap
  },
  data() {
    return {
      cases: [],
    };
  },
  beforeMount() {
    axios.get('cases/' + user.cedula).then((response) => {
          this.cases = response.data.cases;
        }).catch();
  },
};
</script>
