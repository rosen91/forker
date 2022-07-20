import { createRouter, createWebHistory } from "@ionic/vue-router";
import { RouteRecordRaw } from "vue-router";
import TabsPage from "../views/TabsPage.vue";
import LoginPage from "../views/LoginPage.vue";
import HomePage from "../views/HomePage.vue";
import AddPostPage from "../views/AddPostPage.vue";
import { useAuthStore } from "../stores/auth";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    redirect: "/tabs/home",
  },
  {
    path: "/login",
    name: "Login",
    component: LoginPage,
    meta: {
      guestOnly: true,
    },
  },
  {
    path: "/add-post",
    name: "AddPost",
    component: AddPostPage,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/tabs/",
    component: TabsPage,
    meta: {
      requiresAuth: true,
    },
    children: [
      {
        path: "home",
        name: "Home",
        component: () => import("@/views/HomePage.vue"),
      },
      {
        path: "tab2",
        component: () => import("@/views/Tab2Page.vue"),
      },
      {
        path: "tab3",
        component: () => import("@/views/Tab3Page.vue"),
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach(async (to) => {
  const auth = useAuthStore();
  if (!auth.hasCheckedAuth) {
    await auth.setUserAndTokenFromStorage();
    auth.hasCheckedAuth = true;
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (auth.authStatus === "authenticated") {
      return true;
    } else {
      return { name: "Login" };
    }
  } else if (to.matched.some((record) => record.meta.guestOnly)) {
    if (auth.authStatus === "authenticated") {
      return { name: "Home" };
    } else {
      return true;
    }
  } else {
    return true;
  }
});

export default router;
