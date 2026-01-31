/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        // Koshouko Color Palette - Soft Japanese Aesthetic
        primary: {
          50: '#fef9f2',
          100: '#fcf3e5',
          200: '#f9e7cc',
          300: '#f6dbb2',
          400: '#f3cf99',
          500: '#8B5A2B', // Coklat Kayu Utama
          600: '#7a4f25',
          700: '#69451f',
          800: '#583a19',
          900: '#472f13',
        },
        secondary: {
          50: '#fef5f7',
          100: '#fdeaef',
          200: '#fbd5df',
          300: '#f8c0cf',
          400: '#f1c6cf', // Pink Soft
          500: '#E6A3B0', // Pink Sakura
          600: '#d99aa8',
          700: '#cc91a0',
          800: '#bf8898',
          900: '#b27f90',
        },
        accent: {
          50: '#fef5f4',
          100: '#fceaea',
          200: '#f9d5d4',
          300: '#f6c0bf',
          400: '#f3aba9',
          500: '#B63A2B', // Merah Bookmark
          600: '#a43425',
          700: '#922e1f',
          800: '#802819',
          900: '#6e2213',
        },
        'koshouko': {
          'cream': '#E6CFA1',
          'cream-light': '#F3E9D2',
          'wood': '#8B5A2B',
          'brown-dark': '#3B2A1A',
          'black-soft': '#111111',
          'paper': '#F8F6F2',
          'red': '#B63A2B',
          'pink': '#E6A3B0',
          'border': '#CFC7B8',
          'text': '#3B2A1A',
          'text-muted': '#6B4F3F',
        }
      },
      fontFamily: {
        sans: ['Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
        'serif': ['Merriweather', 'Playfair Display', 'serif'],
      },
      boxShadow: {
        'card': '0 1px 3px rgba(59, 42, 26, 0.1), 0 1px 2px rgba(59, 42, 26, 0.06)',
        'md': '0 4px 6px rgba(59, 42, 26, 0.1), 0 2px 4px rgba(59, 42, 26, 0.06)',
        'lg': '0 10px 15px rgba(59, 42, 26, 0.1), 0 4px 6px rgba(59, 42, 26, 0.05)',
      },
      backgroundColor: {
        'main': '#F3E9D2',
        'card': '#FFFFFF',
      }
    },
  },
  plugins: [],
}
