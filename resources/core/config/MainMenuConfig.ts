import {
    PhCashRegister,
    PhCoins,
    PhGauge,
    PhHandCoins,
    PhPackage,
    PhSquaresFour,
    PhStorefront,
    PhUser,
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
        route: "/profit",
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
        route: "/role",
        PhIcon: PhUser,
    },
    {
        heading: "Employees",
        route: "/employees",
        PhIcon: PhUser,
    },
];

export default MainMenuConfig;
