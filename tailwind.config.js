import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Manrope', ...defaultTheme.fontFamily.sans],
        poppins: 'Poppins',
      },
      colors: {
        'primary-bg': '#f3f4f7',
        primary: '#8d78e5',
      },
      screens: {
        short: { raw: '(max-height: 850px)' }, // Breakpoint untuk tinggi
      },
    },
  },
  plugins: [],
};
