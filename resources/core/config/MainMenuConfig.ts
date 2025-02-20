import {
    PhBoxArrowDown,
    PhBoxArrowUp,
    PhCashRegister,
    PhCoins,
    PhGauge,
    PhHandCoins,
    PhPackage,
    PhShieldCheck,
    PhShippingContainer,
    PhSquaresFour,
    PhTruck,
    PhUserList,
    PhUsers,
} from "@phosphor-icons/vue";

export interface MenuItem {
    heading?: string;
    route?: string;
    PhIcon?: any;
    permissions?: string;
    pages?: any;
}

const MainMenuConfig: Array<MenuItem> = [
    {
        heading: "",
        permissions: "dashboard.index",
        pages: [
            {
                heading: "Dashboard",
                route: "/dashboard",
                PhIcon: PhGauge,
            },
        ],
    },
    {
        heading: "Product",
        permissions: "products.index",
        pages: [
            {
                heading: "Categories",
                route: "/categories",
                PhIcon: PhSquaresFour,
            },
            {
                heading: "Products",
                route: "/products",
                PhIcon: PhPackage,
            },
            {
                heading: "Suppliers",
                route: "/suppliers",
                PhIcon: PhTruck,
            },
        ],
    },
    {
        heading: "Transaction",
        permissions: "transactions.index",
        pages: [
            {
                heading: "Transaction",
                route: "/",
                PhIcon: PhCashRegister,
            },
            {
                heading: "Customers",
                route: "/customers",
                PhIcon: PhUserList,
            },
            {
                heading: "Stock In",
                route: "/stock-in",
                PhIcon: PhBoxArrowDown,
            },
            {
                heading: "Stock Out",
                route: "/stock-out",
                PhIcon: PhBoxArrowUp,
            },
            {
                heading: "Stock Opname",
                route: "/stock-out",
                PhIcon: PhShippingContainer,
            },
        ],
    },
    {
        heading: "Report",
        permissions: "sales.index",
        pages: [
            {
                heading: "Report Sales",
                route: "/sales",
                PhIcon: PhCoins,
            },
            {
                heading: "Report Profits",
                route: "/profits",
                PhIcon: PhHandCoins,
            },
        ],
    },
    {
        heading: "Employees",
        permissions: "employees.index",
        pages: [
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
        ],
    },
];

export default MainMenuConfig;
