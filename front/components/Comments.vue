<script setup lang="ts">
import {formatDateTime} from "~/utils/date";

const props = defineProps(['comments'])
</script>

<template>
  <div class="post__comments">
    <div v-for="comment in props.comments" class="post__comments_item">
      <div class="post__comments_item-info">
        <NuxtLink :href="'/users/' + comment.user_id" class="post__comments_item-img">
          <LazyNuxtImg  src="/img/Avatar-icon.svg"/>
        </NuxtLink>
        <p>{{comment.comment}}</p>
      </div>
      <p class="post_time">{{formatDateTime(comment.created_at)}}</p>
    </div>
  </div>
</template>

<style scoped>

</style>