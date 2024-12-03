import {
    PhCashRegister,
    PhCoins,
    PhGauge,
    PhHandCoins,
    PhPackage,
    PhShieldCheck,
    PhSquaresFour,
    PhStorefront,
    PhUsers,
} from "@phosphor-icons/vue";

export interface MenuItem {
    heading?: string;
    route?: string;
    PhIcon?: any;
}

const MainMenuConfig: Array<MenuItem> = [
    {
        heading: "Chasier",
        route: "/",
        PhIcon: PhCashRegister,
    },
    {
        heading: "Dashboard",
        route: "/dashboard",
        PhIcon: PhGauge,
    },
    {
        heading: "Report Sales",
        route: "/sales",
        PhIcon: PhCoins,
    },
    {
        heading: "Report Profit",
        route: "/profits",
        PhIcon: PhHandCoins,
    },
    {
        heading: "Products",
        route: "/products",
        PhIcon: PhPackage,
    },
    {
        heading: "Categories",
        route: "/categories",
        PhIcon: PhSquaresFour,
    },
    {
        heading: "Outlets",
        route: "/outlets",
        PhIcon: PhStorefront,
    },
    {
        heading: "Role",
        route: "/roles",
        PhIcon: PhShieldCheck,
    },
    {
        heading: "Employees",
        route: "/employees",
        PhIcon: PhUsers,
    },
];

export default MainMenuConfig;
