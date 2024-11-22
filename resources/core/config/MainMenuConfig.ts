import {
    PhCashRegister,
    PhGauge,
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
        heading: "Employees",
        route: "/employees",
        PhIcon: PhUser,
    },
    {
        heading: "Outlets",
        route: "/outlets",
        PhIcon: PhStorefront,
    },
];

export default MainMenuConfig;
