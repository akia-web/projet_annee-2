import { createRouter, createWebHistory } from "vue-router";
import Home from '@/components/home/Home'
import ConnexionApp from '@/components/connexion/ConnexionApp'
import Inscription from '@/components/inscription/Inscription'
import Annonces from '@/components/annonces/Annonces'

const router = createRouter({
    history: createWebHistory(),
    routes:[
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/connexion',
            name: 'connexion',
            component: ConnexionApp,
        },
        {
            path: '/inscription',
            component: Inscription,
        },
        {
            path: '/nouvelle-annonce',
            name: 'nouvelle-annonce',
            component: Annonces,
        },

        // routes dynamique
        // {
        //     name: 'truc', // <- le nom du chemin
        //     path: '/dynamic/:id', //<- le mot dans l'url + le paramètre avec les ":" devant le paramettre
        //     component: Dynamic // le nom du composant appelé
        // }
    ]
})

export default router;