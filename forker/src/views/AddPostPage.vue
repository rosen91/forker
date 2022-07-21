<script setup>
import {
  IonContent,
  IonPage,
  IonToolbar,
  IonTitle,
  IonSegment,
  IonSegmentButton,
  IonLabel,
  IonHeader,
  IonImg,
  IonItem,
  IonTextarea,
  IonInput,
  IonIcon,
  IonButton,
} from "@ionic/vue";
import {
  restaurantOutline,
  homeOutline,
  nutritionOutline,
} from "ionicons/icons";

import { Geolocation } from "@capacitor/geolocation";

import { storeToRefs } from "pinia";
import { Swiper, SwiperSlide } from "swiper/vue";
import { usePostsStore } from "../stores/posts";

const { newPost } = storeToRefs(usePostsStore());
const { savePost, searchRestaurant } = usePostsStore();

/* const printCurrentPosition = async () => {
  const coordinates = await Geolocation.getCurrentPosition();

  const searchRes = await searchRestaurant(
    "",
    coordinates.coords.latitude,
    coordinates.coords.longitude
  );
  console.log(searchRes);
};

console.log(await printCurrentPosition()); */
</script>

<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Add post</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <div v-if="newPost">
        <swiper :slides-per-view="1" :space-between="0">
          <swiper-slide v-for="photo in newPost.images" :key="photo.path">
            <ion-img :src="photo.webviewPath"></ion-img>
          </swiper-slide>
        </swiper>
        <ion-toolbar>
          <ion-segment v-model="newPost.type" value="home">
            <ion-segment-button value="home" layout="icon-start">
              <ion-icon size="small" :icon="homeOutline"></ion-icon>
              <ion-label>Home</ion-label>
            </ion-segment-button>
            <ion-segment-button value="restaurant" layout="icon-start">
              <ion-icon size="small" :icon="restaurantOutline"></ion-icon>
              <ion-label>Restaurant</ion-label>
            </ion-segment-button>
            <ion-segment-button value="other" layout="icon-start">
              <ion-icon size="small" :icon="nutritionOutline"></ion-icon>
              <ion-label>Other</ion-label>
            </ion-segment-button>
          </ion-segment>
        </ion-toolbar>
        <ion-item>
          <ion-label position="floating">Post title</ion-label>
          <ion-input v-model="newPost.title"></ion-input>
        </ion-item>
        <ion-item>
          <ion-label position="floating">Caption</ion-label>
          <ion-textarea v-model="newPost.caption"></ion-textarea>
        </ion-item>
        <ion-item>
          <ion-label>Food rating (1-5)</ion-label>
        </ion-item>
        <ion-segment v-model="newPost.rating" value="5">
          <ion-segment-button value="1">
            <ion-label>1</ion-label>
          </ion-segment-button>
          <ion-segment-button value="2">
            <ion-label>2</ion-label>
          </ion-segment-button>
          <ion-segment-button value="3">
            <ion-label>3</ion-label>
          </ion-segment-button>
          <ion-segment-button value="4">
            <ion-label>4</ion-label>
          </ion-segment-button>
          <ion-segment-button value="5">
            <ion-label>5</ion-label>
          </ion-segment-button>
        </ion-segment>
        <ion-button @click="savePost" expand="block">Save Post</ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>