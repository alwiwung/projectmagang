{{-- File: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warkah Pintar - Solusi Arsip Modern</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .left-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px;
            background: white;
            clip-path: polygon(0 0, 100% 0, 70% 100%, 0% 100%);
            position: relative;
            z-index: 2;
        }

        .right-section {
            flex: 1;
            background: linear-gradient(135deg, #1e5bb8 0%, #0d47a1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: float 3s ease-in-out infinite;
        }

        .logo {
            width: 300px;
            height: 300px;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
        }

        @keyframes float {
            0%, 100% {
                transform: translate(-50%, -50%) translateY(0px);
            }
            50% {
                transform: translate(-50%, -50%) translateY(-20px);
            }
        }

        h1 {
            font-size: 3.5rem;
            color: #1e5bb8;
            font-weight: 800;
            margin-bottom: 10px;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h2 {
            font-size: 2.8rem;
            color: #0d47a1;
            font-weight: 800;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 1.3rem;
            color: #555;
            margin-bottom: 50px;
            line-height: 1.6;
            max-width: 500px;
        }

        .btn-masuk {
            background: linear-gradient(135deg, #1e5bb8 0%, #0d47a1 100%);
            color: white;
            border: none;
            padding: 20px 60px;
            font-size: 1.5rem;
            font-weight: 700;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(30, 91, 184, 0.3);
            text-transform: lowercase;
            letter-spacing: 1px;
            display: inline-block;
            text-decoration: none;
        }

        .btn-masuk:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(30, 91, 184, 0.5);
            background: linear-gradient(135deg, #0d47a1 0%, #1e5bb8 100%);
        }

        .btn-masuk:active {
            transform: translateY(-2px);
        }

        .decorative-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: drift 20s infinite;
        }

        .circle1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .circle2 {
            width: 200px;
            height: 200px;
            bottom: -50px;
            right: 20%;
            animation-delay: 5s;
        }

        .circle3 {
            width: 150px;
            height: 150px;
            top: 30%;
            right: 10%;
            animation-delay: 10s;
        }

        @keyframes drift {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            50% {
                transform: translate(30px, 30px) rotate(180deg);
            }
        }

        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }

            .left-section {
                clip-path: none;
                padding: 60px 40px;
                text-align: center;
                align-items: center;
            }

            .right-section {
                min-height: 400px;
            }

            h1 {
                font-size: 2.5rem;
            }

            h2 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1.1rem;
            }

            .logo {
                width: 200px;
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .left-section {
                padding: 40px 20px;
            }

            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.6rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .btn-masuk {
                padding: 15px 40px;
                font-size: 1.2rem;
            }

            .logo {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>SELAMAT DATANG</h1>
            <h2>DI WARKAH PINTAR</h2>
            <p class="subtitle">Warkah Pintar, Solusi Arsip Modern di Ujung Jari Anda</p>
            
            {{-- Tombol Login --}}
            <a href="#" class="btn-masuk">masuk</a>
        </div>

        <div class="right-section">
            <div class="decorative-circles">
                <div class="circle circle1"></div>
                <div class="circle circle2"></div>
                <div class="circle circle3"></div>
            </div>
            <div class="logo-container">
                {{-- GANTI DENGAN PATH GAMBAR ANDA --}}
                <img src="{{ asset('image/Logo123.png') }}" alt="Logo BPN" class="logo">
            </div>
        </div>
    </div>

    <script>
        // Efek parallax pada logo
        document.addEventListener('mousemove', (e) => {
            const logo = document.querySelector('.logo-container');
            if (logo) {
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                logo.style.transform = `translate(-50%, -50%) translate(${x}px, ${y}px)`;
            }
        });
    </script>
</body>
</html 