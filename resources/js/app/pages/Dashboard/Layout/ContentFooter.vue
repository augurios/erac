<template>
  <footer class="footer">
    <div class="container">
      <nav>
        <ul>
          <li>
            <a href="#">Ayuda</a>
          </li>
          
        </ul>
      </nav>
      <div class="copyright text-center">
        &copy; {{ new Date().getFullYear() }} E-RAC, hecho con <i class="fa fa-heart heart"></i> por <a href="https://a-valerio.com" target="_blank"
          >A-Valerio</a
        >
      </div>
    </div>
  </footer>
</template>
<script>
export default {};
</script>
<style>
  .copyright.text-center a {
    color: #2975BB !important;
  }
</style>
