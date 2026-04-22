import re

with open('backend/resources/views/welcome.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Tailwind config
tailwind_config_old = """        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }"""
tailwind_config_new = """        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        sand: '#C2A26F',
                        charcoal: '#2D3E4E',
                        sage: '#8DA18E',
                        beige: '#D9D2C5',
                        light: '#F2F2F2',
                    }
                }
            }
        }"""
content = content.replace(tailwind_config_old, tailwind_config_new)

# 2. Update CSS styles
css_replacements = {
    "rgba(12, 20, 69, 0.85)": "rgba(45, 62, 78, 0.95)", # .nav-blur
    "rgba(55, 50, 45, 0.92) 0%, rgba(62, 58, 54, 0.88) 30%, rgba(50, 47, 44, 0.85) 60%, rgba(42, 40, 38, 0.93) 100%": "rgba(217, 210, 197, 0.95) 0%, rgba(217, 210, 197, 0.85) 30%, rgba(242, 242, 242, 0.9) 100%", # .hero-bg::after
    "linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6)": "linear-gradient(135deg, #C2A26F, #8DA18E)", # .gradient-text
    "linear-gradient(135deg, #3b82f6, #8b5cf6)": "#C2A26F", # .btn-primary
    "rgba(59, 130, 246, 0.4)": "rgba(194, 162, 111, 0.4)", # pulseRing, box-shadow
    "linear-gradient(90deg, #3b82f6, #8b5cf6)": "#C2A26F", # .stat-card::after
    "linear-gradient(90deg, transparent, #3b82f6)": "linear-gradient(90deg, transparent, #C2A26F)", # .step-connector::after
    "linear-gradient(180deg, #3b82f6, #8b5cf6)": "#C2A26F", # scrollbar-thumb
    "background: #0a1628;": "background: #D9D2C5;", # scrollbar-track
    "border: 1px solid rgba(255, 255, 255, 0.1)": "border: 1px solid rgba(45, 62, 78, 0.1)", # glass
    "border: 1px solid rgba(255, 255, 255, 0.15)": "border: 1px solid rgba(45, 62, 78, 0.15)", # glass-light
    "background: rgba(255, 255, 255, 0.05)": "background: rgba(255, 255, 255, 0.6)", # glass
    "background: rgba(255, 255, 255, 0.08)": "background: rgba(255, 255, 255, 0.8)", # glass-light
}
for old, new in css_replacements.items():
    content = content.replace(old, new)

# 3. Update HTML classes
class_replacements = {
    'bg-[#0a1628]': 'bg-beige',
    'bg-[#0c1445]': 'bg-charcoal',
    'text-white': 'text-charcoal',
    'text-gray-300': 'text-charcoal/80',
    'text-gray-400': 'text-charcoal/70',
    'text-gray-500': 'text-charcoal/60',
    'hover:text-white': 'hover:text-sand',
    'bg-gradient-to-br from-blue-500 to-purple-600': 'bg-sand text-white',
    'shadow-blue-500/30': 'shadow-sand/30',
    'text-blue-300': 'text-sage',
    'text-blue-200': 'text-sage',
    'border-[#0c1445]': 'border-beige',
    'border-white/5': 'border-charcoal/10',
    'border-white/10': 'border-charcoal/10',
    'hover:bg-white/10': 'hover:bg-charcoal/10',
    'bg-emerald-500/20': 'bg-sage/20',
    'text-emerald-400': 'text-sage',
    'text-emerald-300': 'text-sage',
    'bg-blue-500/20': 'bg-sand/20',
    'text-blue-400': 'text-sand',
    'bg-blue-500/10': 'bg-sand/10',
    'shadow-blue-500/20': 'shadow-sand/20',
    'bg-gradient-to-br from-blue-500/20 to-blue-600/20': 'bg-sand/20',
    'border-blue-500/20': 'border-sand/20',
    'bg-gradient-to-br from-emerald-500/20 to-emerald-600/20': 'bg-sage/20',
    'border-emerald-500/20': 'border-sage/20',
    'bg-gradient-to-br from-purple-500/20 to-purple-600/20': 'bg-charcoal/20',
    'text-purple-400': 'text-charcoal',
    'text-purple-300': 'text-charcoal',
    'border-purple-500/20': 'border-charcoal/20',
    'bg-gradient-to-br from-orange-500/20 to-orange-600/20': 'bg-sand/20',
    'text-orange-400': 'text-sand',
    'border-orange-500/20': 'border-sand/20',
    'bg-gradient-to-br from-pink-500/20 to-pink-600/20': 'bg-sage/20',
    'text-pink-400': 'text-sage',
    'border-pink-500/20': 'border-sage/20',
    'bg-gradient-to-br from-cyan-500/20 to-cyan-600/20': 'bg-charcoal/20',
    'text-cyan-400': 'text-charcoal',
    'border-cyan-500/20': 'border-charcoal/20',
    'bg-gradient-to-br from-blue-500 to-blue-600': 'bg-sand text-white',
    'bg-gradient-to-br from-purple-500 to-purple-600': 'bg-charcoal text-white',
    'shadow-purple-500/30': 'shadow-charcoal/30',
    'bg-gradient-to-br from-pink-500 to-pink-600': 'bg-sage text-white',
    'shadow-pink-500/30': 'shadow-sage/30',
    'bg-emerald-500/10': 'bg-sage/10',
    'bg-purple-500/10': 'bg-charcoal/10',
    'bg-pink-500/10': 'bg-sage/10',
    'bg-blue-500/10 blur-3xl': 'bg-sand/20 blur-3xl',
    'bg-purple-500/10 blur-3xl': 'bg-sage/20 blur-3xl',
    'from-transparent via-blue-900/10 to-transparent': 'from-transparent via-charcoal/5 to-transparent',
    'bg-gradient-to-r from-blue-500/30 via-purple-500/30 to-pink-500/30': 'bg-gradient-to-r from-sand/30 via-charcoal/30 to-sage/30',
    'bg-blue-500': 'bg-sand',
    'bg-purple-600': 'bg-charcoal',
    'bg-cyan-500': 'bg-sage',
    'bg-emerald-400': 'bg-sage',
    'text-red-400': 'text-sand',
    'hover:border-red-500/50': 'hover:border-sand/50',
    'text-yellow-400': 'text-sand',
}

# Apply replacements with word boundaries where appropriate to avoid partial replacements
for old, new in class_replacements.items():
    content = content.replace(old, new)

# Since buttons were 'text-white' but now body is 'text-charcoal', we might have changed button text to charcoal too.
# Let's fix .btn-primary text color in css.
btn_primary_css_old = """        .btn-primary {
            background: #C2A26F;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }"""
btn_primary_css_new = """        .btn-primary {
            background: #C2A26F;
            color: #F2F2F2 !important;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }"""
content = content.replace(btn_primary_css_old, btn_primary_css_new)

# Navbar needs to have light text for charcoal background
content = content.replace('nav id="navbar" class="fixed w-full z-50 transition-all duration-300"', 'nav id="navbar" class="fixed w-full z-50 transition-all duration-300 text-light bg-charcoal"')
# Some texts in navbar might have been changed to text-charcoal by earlier rule
content = content.replace('<h1 class="text-lg font-bold text-charcoal leading-tight">', '<h1 class="text-lg font-bold text-light leading-tight">')
# Actually let's just write to file
with open('backend/resources/views/welcome.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
