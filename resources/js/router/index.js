import Vue from "vue";
import VueRouter from "vue-router";
import index from "../components/index.vue"; //Asi se importan los componentes de vue
import user from "../components/user.vue";

Vue.use(VueRouter);

const routes = [ //definicion de rutas de los componentes 
    {
        path:"/dashboard",  //nombre de la url
        name:"dashboard",   //nombre para las etiquetas
        component:index,    //nombre del componente
    },
    {
        path:"/users",  //nombre de la url
        name:"users",   //nombre para las etiquetas
        component:user,    //nombre del componente
    }
];

const router = new VueRouter({ //configuracion de VueRouter
    mode:"history",
    base:process.env.BASE_URL,
    routes,
});

export default router; //exportacion de la configuracion de VueRouter
