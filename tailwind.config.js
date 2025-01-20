/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./**/*.php",
    "./**/*html",
    "./App/views/**/*.php",
    "./public/index.php",
    "./src/**/*.{html,js}"
  ],
  theme: {
    extend: {
      colors: { 
        customBackgroundcolor: '#ddd7d7',
        customGray: '#c4c4c4',
        customLightGray: '#e1d3d3',
      },
      backgroundColor: { 
        customBackgroundcolor: '#ddd7d7',
        customGray: '#c4c4c4',
        customLightGray: '#e1d3d3',
      },
      fontFamily: {
        rowdies: ['Rowdies', 'cursive'], 
      },
    },
  },
  plugins: [],
};
