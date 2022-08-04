import { createRouter, createWebHistory } from "vue-router";
import Home from "@/components/home/Home";
import ConnexionApp from "@/components/connexion/ConnexionApp";
import Inscription from "@/components/inscription/Inscription";
import Annonces from "@/components/annonces/Annonces";

import Profile from "@/components/profile/profile.vue";
import AnnoncesModif from "@/components/annoncesModif/AnnoncesModif.vue";
import DetailAnnonce from "@/components/detailAnnonce/detailAnnonce.vue";
import Dashboard from "@/components/dashboard/dashboard.vue";
import gestionCategories from "@/components/gestionCategories/GestionCategories.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
    },
    {
      path: "/connexion",
      name: "connexion",
      component: ConnexionApp,
    },
    {
      path: "/informations",
      name: "informations",
      component: Profile,
    },

    {
      path: "/inscription",
      component: Inscription,
    },
    {
      path: "/nouvelle-annonce",
      name: "nouvelle-annonce",
      component: Annonces,
    },

    {
      path: "/dashboard",
      name: "dashboard",
      component: Dashboard,
    },
    {
      path: "/categories",
      name: "categories",
      component: gestionCategories,
    },

    // routes dynamique
    {
      name: "modifAnnonce", // <- le nom du chemin
      path: "/modify-annonce/:id", //<- le mot dans l'url + le paramètre avec les ":" devant le paramettre
      component: AnnoncesModif, // le nom du composant appelé
    },

    // routes dynamique
    {
      name: "getAnnonce", // <- le nom du chemin
      path: "/annonce/:id", //<- le mot dans l'url + le paramètre avec les ":" devant le paramettre
      component: DetailAnnonce, // le nom du composant appelé
    },
  ],
});

export default router;
