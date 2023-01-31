<template>
    <div class="border p-8 mb-4">
      
        <h2 class="text-xl font-bold mb-3">Customize your order</h2>
        <label for="">Choose a graphics</label><br>
        <input type="file" class="my-4">
        <br>
        <label  for="">Name of graphics</label>
        <input @change="changePrice" v-model="dprice.name" type="text" class="lvs-input-field">
        <br>
        <label for="">Width(cm)</label>
        <input @change="changePrice" v-model="dprice.width" type="number" class="lvs-input-field">
        <br>
        <label for="">Height(cm)</label>
        <input @change="changePrice" v-model="dprice.height" type="number" class="lvs-input-field">
        <br>
        <label for="">Quantity</label>
        <input @change="changePrice" v-model="dprice.qty" type="number" class="lvs-input-field">
      
        <button type="button" class="lvs-primary-btn mt-4">Calculate</button>
      
        <h3 class="text-lg font-bold mt-6">Total Calculated price: <span>€{{ finalPrice }}</span></h3>
        <p class="text-gray-500">Per meter const: €20</p>
        <br>
    </div>

</template>

<script setup>
import {ref} from 'vue'
const dprice = ref({
  file: '',
  name: '',
  width: 0,
  height: 0,
  qty: 0
})
const finalPrice = ref(0)
function changePrice(){
  let width = (parseInt(dprice.value.width) / 100)
  let height = (parseInt(dprice.value.height) / 100)
  finalPrice.value = parseFloat((width*height*20)*(dprice.value.qty)).toFixed(2)
}
</script>