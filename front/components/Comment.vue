<script setup lang="ts">
const comment = ref("")
const emit = defineEmits(['comment'])
const handleComment = () => {
  emit('comment', comment.value)
}
</script>

<template>
  <div class="post__comment">
    <NuxtImg src="/img/Avatar-icon.svg"/>
    <div>
      <input v-model="comment" type="text" name="commentary" placeholder="Оставить комментарий">
    </div>
    <button @click="handleComment" class="post__SendComment"><NuxtImg src="/img/Send-icon.svg"/></button>
  </div>
</template>

<style scoped>

</style>