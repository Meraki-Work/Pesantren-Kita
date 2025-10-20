/** @type {import('tailwindcss').Config} */
export default {
  content: [],
  theme: {
    extend: {
      colors: {
        magicmint: '#A8E6CF',
      }
    },
  },
  plugins: [
  require('@tailwindcss/line-clamp'),
],

}

