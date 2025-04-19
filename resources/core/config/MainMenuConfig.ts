export interface MenuItem {
  heading?: string;
  route?: string;
  icon?: any;
  permissions?: string;
  pages?: any;
}

const MainMenuConfig: Array<MenuItem> = [
  {
    heading: '',
    pages: [
      {
        heading: 'Dashboard',
        route: '/dashboard',
        icon: 'ph:gauge',
        permissions: 'dashboard.index',
      },
    ],
  },
  {
    heading: 'Product',
    pages: [
      {
        heading: 'Categories',
        route: '/categories',
        icon: 'ph:squares-four',
        permissions: 'categories.index',
      },
      {
        heading: 'Products',
        route: '/products',
        icon: 'ph:package',
        permissions: 'products.index',
      },
      {
        heading: 'Suppliers',
        route: '/suppliers',
        icon: 'ph:truck',
        permissions: 'suppliers.index',
      },
    ],
  },
  {
    heading: 'Transaction',
    pages: [
      {
        heading: 'Transaction',
        route: '/',
        icon: 'ph:cash-register',
        permissions: 'transactions.index',
      },
      {
        heading: 'Customers',
        route: '/customers',
        icon: 'ph:user-list',
        permissions: 'customers.index',
      },
      {
        heading: 'Stock In',
        route: '/stock-in',
        icon: 'ph:box-arrow-down',
        permissions: 'stock_in.index',
      },
      {
        heading: 'Stock Out',
        route: '/stock-out',
        icon: 'ph:box-arrow-up',
        permissions: 'stock_out.index',
      },
      {
        heading: 'Stock Opname',
        route: '/stock-opname',
        icon: 'ph:notebook',
        permissions: 'stock_opname.index',
      },
    ],
  },
  {
    heading: 'Discount',
    pages: [
      {
        heading: 'Discount Transaction',
        route: '/discount-transactions',
        icon: 'ph:seal-percent',
        permissions: 'discounts.index',
      },
    ],
  },
  {
    heading: 'Report',
    pages: [
      {
        heading: 'Report Sales',
        route: '/sales',
        icon: 'ph:coins',
        permissions: 'sales.index',
      },
      {
        heading: 'Report Profits',
        route: '/profits',
        icon: 'ph:hand-coins',
        permissions: 'profits.index',
      },
    ],
  },
  {
    heading: 'Employees',
    pages: [
      {
        heading: 'Role',
        route: '/roles',
        icon: 'ph:shield-check',
        permissions: 'roles.index',
      },
      {
        heading: 'Employees',
        route: '/employees',
        icon: 'ph:users',
        permissions: 'employees.index',
      },
    ],
  },
];

export default MainMenuConfig;
