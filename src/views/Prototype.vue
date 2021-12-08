<template>
  <div>
    Products:
    <select v-model="selected">
      <option
        v-for="product in products"
        v-bind:key="{ id: product.id, text: product.name }"
        >{{ product.name }}</option
      >
    </select>
  </div>
</template>
<script>
export default {
  data() {
    return {
      selected: '',
      products: [
        { id: 1, name: 'A' },
        { id: 2, name: 'B' },
        { id: 3, name: 'C' }
      ]
    }
  }
}
</script>
