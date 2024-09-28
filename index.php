<!DOCTYPE html>
<html>
<head>
    <title>index</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script>
        function phpPage(){
            window.location.href = "signup.php";
        }
    </script>
</head>

    <style>
        html,body{
            height: 100%; 
        }
        body{
            color: #FFFFFF;
            font-family: "Poppins";
            margin: 0;
            padding: 0;
            background-image: url('./img/index1.jpg');
            background-size: cover; 
            background-position: center;
            background-repeat: no-repeat;
        }
        h2{
           position: absolute;
           padding: 20px 50px;
           color: #FFFFFF;
           font-size: 25px;
           font-weight: 600;
           letter-spacing: -0.1rem;
        }
        h1{
            position: absolute;
            top: 25%; 
            left: 50%; 
            transform: translate(-50% , -50%);
            font-size: 55px;
            font-weight: 600;
            letter-spacing: -0.2rem;
            color: #fff; 
            text-shadow: 0 15px 4px rgba(215, 215, 215, 0.2);
        }
        button{
            position: absolute;
            top: 36%; 
            left: 49%; 
            transform: translate(-50% , -50%);
            color: #fff;
        }
        .btn {
            width: 240px;
            height: 40px;
            background: rgba(255, 255, 255, 0.4);
            color:#ffffff;
            border: none;
            border-radius: 50px 50px 50px 50px;
            cursor: pointer;
            font-size: 30px;
            font-weight: 500;
            margin-top: 60px;
        }
        .navbar {
            position: absolute;
            top: 45px; 
            right: 40px;
        }   

        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar li {
            margin: 0 15px;  /* space btwn links */
        }

        .navbar a {
            text-decoration: none;
            color: #FFFFFF;
            font-weight: bold;
            padding: 10px 15px;
        }

        .navbar a:hover {
            color: grey; 
        }
    </style>
    <body>
        <h2>chaptercraft .</h2>
        <nav class="navbar">
        <ul>
                <li><a href="signup.php">Login / Signup</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div>
            <h1>Unfold New  Worlds .</h1>
            
        </div>
        <button class="btn" onclick="phpPage()">
            explore now &rarr;
            
        </button>
      
       
    </body>
</html>