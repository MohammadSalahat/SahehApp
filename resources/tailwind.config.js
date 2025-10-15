/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // 'primary': '#4a6b5a',
        // 'secondary': '#7a9684',
        // 'cream': '#f5f1e8',
        // 'light-cream': '#e8e4d8',
      },
    },
  },
  plugins: [],
}