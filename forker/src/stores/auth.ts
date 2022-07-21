import { defineStore } from "pinia";
import { Storage } from "@ionic/storage";
import { mande, OptionsRaw } from "mande";

const ionicStorage = new Storage();
await ionicStorage.create();

const globalOptions: OptionsRaw = {};

const auth = mande(process.env.VUE_APP_API_URL, globalOptions);

interface User {
  username: string;
}

interface TokenResponse {
  user: User;
  token: string;
}

type StoreState = {
  user: User | null;
  token: string | null;
  returnUrl: string | null;
  hasCheckedAuth: boolean;
};

// useStore could be anything like useUser, useCart
// the first argument is a unique id of the store across your application
export const useAuthStore = defineStore("auth", {
  state: (): StoreState => ({
    // initialize state from local storage to enable user to stay logged in
    user: null,
    returnUrl: null,
    token: null,
    hasCheckedAuth: false,
  }),
  actions: {
    async setUserAndTokenFromStorage() {
      const user = await ionicStorage.get("user");
      const token = await ionicStorage.get("token");
      if (user) {
        this.user = user;
      }
      if (token) {
        this.token = token;
      }
    },
    async login(email: string, password: string) {
      const deviceName = "Device Name";
      const { token, user }: TokenResponse = await auth.post(`/sanctum/token`, {
        email,
        password,
        device_name: deviceName,
      });

      // store user details and jwt in local storage to keep user logged in between page refreshes
      await ionicStorage.set("user", user);
      await ionicStorage.set("token", token);
      // update pinia state
      this.user = user;
      this.token = token;
    },
    async socialLogin(access_code: string, provider_name: string) {
      const deviceName = "Device Name";
      const { token, user }: TokenResponse = await auth.post(`/social/login`, {
        access_code,
        provider_name,
        device_name: deviceName,
      });

      // store user details and jwt in local storage to keep user logged in between page refreshes
      await ionicStorage.set("user", user);
      await ionicStorage.set("token", token);
      // update pinia state
      this.user = user;
      this.token = token;
    },
    logout() {
      this.user = null;
      this.token = null;
      ionicStorage.remove("user");
      ionicStorage.remove("token");
    },
  },
  getters: {
    authStatus: (state) => (state.user !== null ? "authenticated" : "guest"),
  },
});
