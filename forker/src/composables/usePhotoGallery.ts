import { mande } from "mande";
import { useApi } from "@/composables/useApi";
import { ref, onMounted, watch } from "vue";
import { usePostsStore } from "../stores/posts";
import { getData, getAllTags } from "exif-js";

import {
  Camera,
  CameraResultType,
  CameraSource,
  Photo,
} from "@capacitor/camera";

export interface UserPhoto {
  filepath: string;
  webviewPath?: string;
  awsname?: string;
}
interface AwsResponse {
  url: string;
}

export function usePhotoGallery() {
  const postStore = usePostsStore();

  const takePhoto = async () => {
    const { api } = useApi();
    const photo = await Camera.getPhoto({
      resultType: CameraResultType.Uri,
      source: CameraSource.Camera,
      quality: 100,
    });

    console.log(photo.exif);

    const response = await fetch(photo.webPath!);
    const blob = await response.blob();

    const fileName =
      "tmp/" + new Date().getTime() + "." + blob.type.split("/")[1];
    const savedFileImage = {
      filepath: fileName,
      webviewPath: photo.webPath,
    };

    const res: AwsResponse = await api.post("createSignedUrl", {
      name: fileName,
      content_type: blob.type,
    });

    const awsRequest = mande(res.url, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    const awsResponse = fetch(res.url, { method: "PUT", body: blob });

    if (postStore.newPost === null) {
      postStore.newPost = {
        images: [savedFileImage],
        type: "home",
        rating: 5,
      };
    } else {
      postStore.newPost.images?.push(savedFileImage);
    }
  };

  return {
    takePhoto,
  };
}
