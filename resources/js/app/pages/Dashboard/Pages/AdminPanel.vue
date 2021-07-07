<template>
  <div>
    <UsersTable></UsersTable>
    <CasesTable></CasesTable>
    <EspecialitiesTable></EspecialitiesTable>
  </div>
</template>

<script>
import UsersTable from "./AdminPanel/UsersTable.vue";
import CasesTable from "./AdminPanel/CasesTable.vue";
import EspecialitiesTable from "./AdminPanel/EspecialitiesTable.vue";
export default {
  components: {
    UsersTable,
    CasesTable,
    EspecialitiesTable
  }
}
</script>

<style>

</style>