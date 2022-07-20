<template>
  <ion-app>
    <ion-router-outlet />
    <ion-fab vertical="bottom" horizontal="center" slot="fixed">
      <ion-fab-button @click="takePhoto">
        <ion-icon :icon="camera"></ion-icon>
      </ion-fab-button>
    </ion-fab>
  </ion-app>
</template>

<script lang="ts">
import {
  IonApp,
  IonRouterOutlet,
  IonFab,
  IonFabButton,
  IonIcon,
} from "@ionic/vue";
import { defineComponent, watchEffect } from "vue";
import { storeToRefs } from "pinia";
import { useIonRouter } from "@ionic/vue";

import { usePhotoGallery } from "@/composables/usePhotoGallery";
import { camera } from "ionicons/icons";
import { usePostsStore } from "@/stores/posts";

export default defineComponent({
  name: "App",
  components: {
    IonApp,
    IonRouterOutlet,
    IonFab,
    IonFabButton,
    IonIcon,
  },
  setup() {
    const { takePhoto } = usePhotoGallery();
    const router = useIonRouter();

    const { newPost } = storeToRefs(usePostsStore());

    watchEffect(() => {
      if (newPost.value !== null) {
        router.push({ name: "AddPost" });
      }
    });

    return {
      takePhoto,
      camera,
    };
  },
});
</script>