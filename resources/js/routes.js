export default [
    // {
    //     path: "/dashboard",
    //     component: require("./pages/Dashboard.vue").default
    //     // meta: {
    //     //     requiresAuth: true
    //     // }
    // },
    {
        path: "/bondInventory",
        component: require("./pages/bondinventory.vue").default
        // meta: {
        //     requiresAuth: true
        // }
    },
    {
        path: "/systemConfig",
        component: require("./pages/systemconfig.vue").default,
        // meta: {
        //     requiresAuth: true
        // }
    },
    // {
    //     path: "/console-log",
    //     component: require("./pages/consolelog/ConsoleLog.vue").default,
    //     meta: {
    //         requiresAuth: true,
    //         roles: ["superadministrator", "administrator"]
    //     }
    // },
    // {
    //     path: "/profile",
    //     component: require("./pages/Profile.vue").default,
    //     meta: {
    //         requiresAuth: true,
    //         permissions: ["profile-read", "profile-update"]
    //     }
    // },
    // {
    //     path: "/users",
    //     component: require("./pages/Users.vue").default,
    //     meta: {
    //         requiresAuth: true,
    //         roles: ["superadministrator", "administrator"]
    //     }
    // },
    // {
    //     path: "/permission",
    //     component: require("./pages/Permission.vue").default,
    //     meta: {
    //         requiresAuth: true,
    //         roles: ["superadministrator", "administrator"]
    //     }
    // },
    { path: "*", component: require("./pages/NotFound.vue").default }
];
