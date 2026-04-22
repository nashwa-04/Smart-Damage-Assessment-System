import re

with open('backend/resources/views/auth/register.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add tailwind config for colors to register page
tailwind_config_addition = """    <script>
        tailwind.config = {
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
        }
    </script>
</head>"""
content = content.replace('</head>', tailwind_config_addition)

# Update hero-gradient
content = content.replace('linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1e1b4b 100%)', 'linear-gradient(135deg, #2D3E4E 0%, #212c38 50%, #171f28 100%)')

# Add new glass-card and input style classes in CSS
glass_card_old = """        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }"""
glass_card_new = """        .glass-card {
            background: rgba(217, 210, 197, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(194, 162, 111, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }"""
content = content.replace(glass_card_old, glass_card_new)

input_style_old = """        .input-style {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .input-style:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(99, 102, 241, 0.5);
        }"""
input_style_new = """        .input-style {
            background: rgba(242, 242, 242, 0.05);
            border: 1px solid rgba(194, 162, 111, 0.2);
            color: #F2F2F2;
        }
        .input-style:focus {
            background: rgba(242, 242, 242, 0.1);
            border-color: #C2A26F;
            box-shadow: 0 0 0 2px rgba(194, 162, 111, 0.3);
        }"""
content = content.replace(input_style_old, input_style_new)

# Tailwind class replacements
replacements = {
    'bg-slate-900/80': 'bg-charcoal/90',
    'border-slate-700/50': 'border-beige/20',
    'bg-gradient-to-br from-indigo-500 to-purple-600': 'bg-sand text-white',
    'hover:border-indigo-500': 'hover:border-sand',
    'bg-gradient-to-r from-indigo-600 to-purple-600': 'bg-sand text-white',
    'hover:from-indigo-700 hover:to-purple-700': 'hover:bg-[#b0905d]',
    'shadow-indigo-500/25': 'shadow-sand/30',
    'text-slate-400': 'text-beige',
    'text-slate-300': 'text-light',
    'border-slate-600': 'border-beige/30',
    'focus:ring-indigo-500/50': 'focus:ring-sand/50',
    'bg-slate-700/30': 'bg-charcoal/40',
    'hover:bg-slate-700/50': 'hover:bg-charcoal/60',
    'bg-indigo-500/20': 'bg-sage/20',
    'hover:bg-indigo-500/30': 'hover:bg-sage/30',
    'border-indigo-500/30': 'border-sage/40',
    'text-indigo-300': 'text-sage',
    'hover:text-indigo-200': 'hover:text-light',
    'text-amber-500': 'text-sand',
    'bg-indigo-500/20 rounded-full blur-3xl': 'bg-sand/20 rounded-full blur-3xl',
    'bg-purple-500/20 rounded-full blur-3xl': 'bg-sage/20 rounded-full blur-3xl',
    'bg-blue-500/10 rounded-full blur-3xl': 'bg-beige/10 rounded-full blur-3xl',
}

for old, new in replacements.items():
    content = content.replace(old, new)

# Write modified content
with open('backend/resources/views/auth/register.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
