import { UserPhoto } from "./../composables/usePhotoGallery";
import { defineStore } from "pinia";
import { useAuthStore } from "../stores/auth";

import { mande, OptionsRaw } from "mande";
import { useApi } from "@/composables/useApi";

interface PostResponse {
  posts: Post[];
}

interface Restaurant {
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
}

type StoreState = {
  posts: Post[];
  newPost: NewPost | null;
};

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
  },
});
