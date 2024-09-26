/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/views/site/**/*.php",
    "./app/Helpers/**/*.php",
    "./app/templates/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        'main': 'var(--main-color)',
        'main-button': 'var(--main-color-button)',
        'main-button-hover': 'var(--main-color-button-hover)',
        'main-hover-on-white': 'var(--main-color-hover-on-white)',
        'main-hover': 'var(--main-color-hover)',
        'main-darker': 'var(--main-color-darker)',
        'main-lighter': 'var(--main-color-lighter)',
        'main-lighter2': 'var(--main-color-lighter2)',
        'main-even': 'var(--main-color-even)',
      },
    },
  },
  plugins: [],
}