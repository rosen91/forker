<script setup>
import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
} from "@ionic/vue";

import { storeToRefs } from "pinia";
import { usePostsStore } from "../stores/posts";
import PostCard from "../components/PostCard.vue";

const { posts } = storeToRefs(usePostsStore());

const { getPosts } = usePostsStore();

getPosts();
</script>

<template>
  <ion-page>
    <div v-for="post in posts" :key="post.id">
      <PostCard :post="post" />
    </div>
  </ion-page>
</template>