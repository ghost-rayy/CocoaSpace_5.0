<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f2f2f2, #e0e0e0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .container h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 15px;
        }

        .container p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px;
            }

            .container h1 {
                font-size: 1.5rem;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Session Expired</h1>
        <p>Please logout and try logging in back.<br>Sorry for any inconveniences.</p>
        <a href="{{ route('logout') }}" class="btn"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</body>
</html>
