<template>
  <div class="md-layout">
    <div class="md-layout-item md-small-size-100 md-xsmall-size-100 md-medium-size-50 md-size-50"
            v-for="item in cases"
              :key="item.id"
              :class="{'under-review' : item.status == 'reviewing'}"
               >
     <md-card class="md-primary case-card" md-theme="purple-card" md-with-hover
             @click.native="loadCase(item.id)">
      <md-ripple>
        <md-card-header>
          <div class="md-title">{{ item.opener.lastname }}, {{ item.opener.name }} / <span >{{ item.recipient.lastname }}, {{ item.recipient.name }}</span></div>
          <div class="md-subhead">
            Fecha: <strong>{{item.created_at | moment("DD-MM-YY") }}</strong>, 
            <badge :type="statusBadge(item.status)">Estado: <strong>{{item.status | statusDic}}</strong></badge>
          </div>
        </md-card-header>

        <md-card-actions md-alignment="left">
          <div v-if="item.resolution">
             Resolucion: <strong> {{item.resolution == 'canceled' ? 'Cancelado por Solicitante': 'Terminos aceptados por recibiente'}}</strong> |&nbsp;
          </div>
          <div> Monto: {{item.amount | priceWithCommas}} </div>
        </md-card-actions>
      </md-ripple>
    </md-card>
    </div>
  </div>
</template>

<script>
import { Badge } from '../../../../components'

export default {
  components: {
    Badge
  },
  data() {
    return {
      user,
      cases:[]
    }
  },
  beforeMount() {
    axios.get('cases/' + user.cedula).then((response) => {
          this.cases = response.data.cases;
        }).catch();
  },
  methods: {
    loadCase(id) {
      console.log('case', id);
      this.$router.push('cases/'+id);
    },
    statusBadge(status) {
      const dic = {
        'active':'success',
        'done':'info',
        'closed':'danger',
        'waiting':'warning',
        'reviewing':'info',
      }
      return dic[status];
    }
  }
}
</script>

<style lang="scss" scoped>
.under-review {
  opacity: 0.5;
  pointer-events: none;
}
.md-card {
    display: inline-block;
    vertical-align: top;
    overflow: hidden;
    @media screen and (min-width : 620px) {
        .badge {
          position: absolute;
          top: 1rem;
          right: 1rem;
        }
    }
    
  }
</style>