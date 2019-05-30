<template>
  <div class="h-full container">
    {{ routes[0] }}
  </div>
</template>

<script>
export default {
  props: {
    routes: {
      type: Array,
      required: true
    }
  }
};
</script>

