import { UserPhoto } from "./../composables/usePhotoGallery";
import { defineStore } from "pinia";
import { useAuthStore } from "../stores/auth";

import { mande, OptionsRaw } from "mande";
import { useApi } from "@/composables/useApi";

interface PostResponse {
  posts: Post[];
}

interface Restaurant {
  id: number;
  name: string;
}

interface PostImage {
  id: number;
  url: string;
}

interface User {
  username: string;
}

export interface Post {
  title: string;
  caption: string;
  rating: number;
  restaurant: Restaurant;
  images: PostImage[];
  user: User;
}

type ApiResponse = {
  data: Post[];
};

type NewPostResponse = {
  data: Post;
};

interface NewPost {
  images?: UserPhoto[];
  title?: string;
  caption?: string;
  rating?: number;
  type: "home" | "restaurant" | "other";
  price?: string;
  recipe_url?: string;
  restaurant?: Restaurant;
}

type StoreState = {
  posts: Post[];
  newPost: NewPost | null;
};

interface SearchResultResponse {
  id: number;
  name: string;
}

const convertBlobToBase64 = (blob: Blob) =>
  new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onerror = reject;
    reader.onload = () => {
      resolve(reader.result);
    };
    reader.readAsDataURL(blob);
  });

export const usePostsStore = defineStore("posts", {
  state: (): StoreState => ({
    posts: [],
    newPost: null,
  }),
  actions: {
    async getPosts() {
      const { api } = useApi();
      const res: ApiResponse = await api.get(`/posts`);
      this.posts = res.data;
    },
    async savePost() {
      const { api } = useApi();
      const newPost = this.newPost;

      const res: NewPostResponse = await api.post("/posts", newPost);
      this.posts.push(res.data);
    },
    async searchRestaurant(
      term: string,
      latitude?: string,
      longitude?: string
    ) {
      const { api } = useApi();
      const params = new URLSearchParams();
      params.append("term", term);
      if (latitude) {
        params.append("latitude", latitude);
      }
      if (longitude) {
        params.append("longitude", longitude);
      }

      const res: SearchResultResponse = await api.get(
        `/search?${params.toString()}`
      );
      console.log(res);
    },
  },
});
