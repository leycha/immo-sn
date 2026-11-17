/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./app/Views/**/*.html",
    "./public/**/*.php",
    "./public/assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        // Palette ImmoSn basée sur ta capture
        primary: {
          50:  '#f0fdf4',
          100: '#dcfce7',
          500: '#22c55e',   // vert principal "Rechercher"
          600: '#16a34a',   // vert foncé
          700: '#15803d',
        },
        secondary: {
          500: '#3b82f6',   // bleu "Scanner QR"
          600: '#2563eb',
        },
        dark: '#1F2937',
        light: '#F9FAFB',
        cream: '#F5F1E8',   // fond beige de ta capture
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        card: '0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03)',
      },
    },
  },
  plugins: [],
}