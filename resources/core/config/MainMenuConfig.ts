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
    permissions: 'dashboard.index',
    pages: [
      {
        heading: 'Dashboard',
        route: '/dashboard',
        icon: 'ph:gauge',
      },
    ],
  },
  {
    heading: 'Product',
    permissions: 'products.index',
    pages: [
      {
        heading: 'Categories',
        route: '/categories',
        icon: 'ph:squares-four',
      },
      {
        heading: 'Products',
        route: '/products',
        icon: 'ph:package',
      },
      {
        heading: 'Suppliers',
        route: '/suppliers',
        icon: 'ph:truck',
      },
    ],
  },
  {
    heading: 'Transaction',
    permissions: 'transactions.index',
    pages: [
      {
        heading: 'Transaction',
        route: '/',
        icon: 'ph:cash-register',
      },
      {
        heading: 'Customers',
        route: '/customers',
        icon: 'ph:user-list',
      },
      {
        heading: 'Stock In',
        route: '/stock-in',
        icon: 'ph:box-arrow-down',
      },
      {
        heading: 'Stock Out',
        route: '/stock-out',
        icon: 'ph:box-arrow-up',
      },
      {
        heading: 'Stock Opname',
        route: '/stock-opname',
        icon: 'ph:notebook',
      },
    ],
  },
  {
    heading: 'Discount',
    permissions: 'sales.index',
    pages: [
      {
        heading: 'Product Discount',
        route: '/discount-product',
        icon: 'ph:seal-percent',
      },
      {
        heading: 'Voucher',
        route: '/profits',
        icon: 'ph:seal-percent',
      },
    ],
  },
  {
    heading: 'Report',
    permissions: 'sales.index',
    pages: [
      {
        heading: 'Report Sales',
        route: '/sales',
        icon: 'ph:coins',
      },
      {
        heading: 'Report Profits',
        route: '/profits',
        icon: 'ph:hand-coins',
      },
    ],
  },
  {
    heading: 'Employees',
    permissions: 'employees.index',
    pages: [
      {
        heading: 'Role',
        route: '/roles',
        icon: 'ph:shield-check',
      },
      {
        heading: 'Employees',
        route: '/employees',
        icon: 'ph:users',
      },
    ],
  },
];

export default MainMenuConfig;
