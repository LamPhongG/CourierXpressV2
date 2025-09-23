<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CourierXpress</title>
    
    <!-- Cyberpunk Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'cyberpunk': ['Orbitron', 'sans-serif'],
                            'cyber-body': ['Exo 2', 'sans-serif'],
                            'cyber-mono': ['Rajdhani', 'monospace'],
                        }
                    }
                }
            }
        </script>
    @endif
    
    <style>
        /* Cyberpunk Global Font Styles */
        * {
            font-family: 'Exo 2', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6, .title, .heading {
            font-family: 'Orbitron', sans-serif !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .cyberpunk-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .cyber-text {
            font-family: 'Rajdhani', monospace;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        button, .btn {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Enhanced Cyberpunk Effects */
        .neon-text {
            text-shadow: 
                0 0 5px currentColor,
                0 0 10px currentColor,
                0 0 15px currentColor,
                0 0 20px #ff6b35;
        }
        
        .cyber-glow {
            box-shadow: 
                0 0 20px rgba(255, 107, 53, 0.3),
                inset 0 0 20px rgba(255, 107, 53, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.header')

    <main class="container mx-auto pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    
</body>
</html> 
