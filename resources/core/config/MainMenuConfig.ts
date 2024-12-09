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
    permissions?: string;
}

const MainMenuConfig: Array<MenuItem> = [
    {
        heading: "Chasier",
        route: "/",
        PhIcon: PhCashRegister,
        permissions: "transactions.index",
    },
    {
        heading: "Dashboard",
        route: "/dashboard",
        PhIcon: PhGauge,
        permissions: "dashboard.index",
    },
    {
        heading: "Report Sales",
        route: "/sales",
        PhIcon: PhCoins,
        permissions: "sales.index",
    },
    {
        heading: "Report Profit",
        route: "/profits",
        PhIcon: PhHandCoins,
        permissions: "profits.index",
    },
    {
        heading: "Products",
        route: "/products",
        PhIcon: PhPackage,
        permissions: "products.index",
    },
    {
        heading: "Categories",
        route: "/categories",
        PhIcon: PhSquaresFour,
        permissions: "categories.index",
    },
    {
        heading: "Outlets",
        route: "/outlets",
        PhIcon: PhStorefront,
        permissions: "outlets.index",
    },
    {
        heading: "Role",
        route: "/roles",
        PhIcon: PhShieldCheck,
        permissions: "roles.index",
    },
    {
        heading: "Employees",
        route: "/employees",
        PhIcon: PhUsers,
        permissions: "employees.index",
    },
];

export default MainMenuConfig;
