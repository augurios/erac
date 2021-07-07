<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-66">
        <edit-profile-form header-color="primary"> </edit-profile-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-33">
        <user-card button-color="primary" :cardUserImage="user.avatar ? 'storage/'+user.avatar : undefined "> </user-card>
      </div>
    </div>
  </div>
</template>

<script>
import { EditProfileForm, UserCard } from "../../index";
export default {
  data: () => ({
    user
  }),
  components: {
    EditProfileForm,
    UserCard
  }
};
</script>
<style lang="scss">
.text-right {
  display: flex;
}
</style>
