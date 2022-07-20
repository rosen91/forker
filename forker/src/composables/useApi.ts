import { mande } from "mande";
import { useAuthStore } from "./../stores/auth";
import { OptionsRaw } from "mande";
export function useApi() {
  const auth = useAuthStore();
  const globalOptions: OptionsRaw = {
    headers: {
      Authorization: "Bearer " + auth.token,
    },
  };
  const api = mande(process.env.VUE_APP_API_URL, globalOptions);

  return { api };
}
