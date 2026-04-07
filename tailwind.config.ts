import type { Config } from 'tailwindcss';

export default {
  content: ['./index.html', './src/**/*.{ts,tsx}'],
  theme: {
    extend: {
      colors: {
        museum: {
          bg: '#f3f5f7',
          ink: '#1f2937',
          soft: '#64748b',
          panel: '#ffffff',
          accent: '#334155'
        }
      },
      boxShadow: {
        museum: '0 10px 30px -18px rgba(15, 23, 42, 0.35)'
      }
    }
  },
  plugins: []
} satisfies Config;
