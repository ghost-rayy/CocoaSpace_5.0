<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link rel="stylesheet" href="/style.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>CocoaSpace</title>
</head>
<body>
    <div class="main">
        <div class="coco-image">
            <div class="image-1"><img src="images/logo.png" alt=""></div>
            <div class="image-2"><img src="images/Waiting-pana 1.png" alt=""></div>
        </div>
        <div class="login-box">
            <form method="POST" action="{{ route('enter.code.submit') }}">
                @csrf
                <div class="logo">
                        <img src="images/logo.jpg" alt="">
                        <h4>Welcome to</h4>
                        <h4> Ghana Cocoa Board </h4><br><br>
                    <div class="login-notice">
                        <h3>Enter Meeting Code</h3>
                        <p>Please enter your e-ticket or meeting code below to proceed to registration.</p>
                    </div><br>
                    
                    <div class="input-box">
                        <input type="text" name="e_ticket" id="e_ticket" class="input-field" placeholder="E-Ticket" required autofocus>
                    </div>
                    <div class="input-submit">
                        <button type="submit" class="submit-btn">Submit</button>
                    </div>
                </div>
            </form>
            <div class="back-home-container">
                <a href="{{ route('homepage') }}" class="back-home-btn"> <span><i class="fa-solid fa-arrow-left"></i></span> Back to Home</a>
            </div>
        </div>
    </div>
    <script>
      document.querySelector('form[action="{{ route('enter.code.submit') }}"]').addEventListener('submit', function(e) {
        Swal.fire({
          title: 'Processing...',
          text: 'Please wait while we verify your code.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
      });

      @if(session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: '{{ session('error') }}',
          confirmButtonColor: '#42ccc5',
          confirmButtonText: 'OK'
        });
      @endif
    </script>
</body>
</html>

<style>
    
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
/* GLOBAL STYLES */
*{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

:root {
    --primary-color: #42ccc5;
    --btn-color: #42ccc5;
    --hover-color: #42a39e;
    --login-color: #fff;
    --login-color: white;
    --white-color: #fff;
    --black-color: #000;
    --input-border-color: #e3e4e6;
    --transition-3s: 3s;
    --shadow-color: rgba(0, 0, 0, 0.1);

}

/* REUSABLE ELEMENTS */
body {
    height: 100vh;
    background-color: var(--primary-color);
    display: flex;
    justify-content: center;
}
.main {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5rem;
    padding-inline: 1rem ;
    padding-top: 3rem;
    /* border: 10px solid brown; */
}

/* INSIDE MAIN */
.coco-image {
    display: flex;
    justify-content: center;
    align-items: center;
    /* border: 1px solid rgb(238, 205, 15); */
    width: 50%;
    height: 690px; 
    padding: 8rem; 
    flex-direction: column;
    margin-right: 7rem;
    opacity: 1;
    transition: display 5.5s ease;
}

.coco-image .image-1 {
    width: 80%;
}
.image-1 img{
    width: 100%;
}
.coco-image .image-2 {
    width: 80%;
}
.image-2 img{
    width: 100%;
}

.login-box {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 38rem;
    height: 690px;
    /* border: 1px solid red; */
    background-color: var(--login-color);
    border-radius: 2rem;
    padding: 10px;
    box-shadow: 0 8px 15px var(--shadow-color);
    /* margin-right: 20rem;
    margin-bottom: 15rem; */
    /* transition: var(--transition-3s); */
}
.logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-bottom: 50px;
    color: #000000;
}
.login-notice {
    text-align: center;
    margin-bottom: 10px;
}
.login-notice h3 {
    color: var(--primary-color);
    font-size: 200%;
    font-weight: 600;
}

.input-box .input-field{
    width: 100%;
    height: 3rem;
    font-size: 100%;
    padding-inline: 30px;
    border-radius: 30px;
    margin-bottom: 15px;
    border: 1px solid #8f8d8d;
    /* box-shadow: 0px 5px 10px 1px rgba(0, 0, 0, 0.1); */
    outline: none;
    transition: 0.3s;
    margin-bottom: 20px;
}
.input-box, .input-submit {
    width: 25rem;
}
::placeholder {
    font-weight: 500;
    color: #222;
    opacity: 0.8;
}
.input-field:focus {
    width: 105%;
}
.submit-btn {
    width: 100%;
    height: 45px;
    border-radius: 30px;
    color: var(--input-border-color);
    background-color: var(--btn-color);
    border: none;
    transition: 0.2s;
    margin-top: 30px;
    color: rgb(255, 255, 255);
    font-weight: 600;
}
.submit-btn:hover {
    background-color: var(--hover-color);
    transform: scale(1.09);
}


@media (min-width: 1443px) {
    
}
@media (max-width: 1300px) {
     .main {
        margin-right: 0;
         gap: 0.8rem;
    }
        .coco-image {
        width: 40%;   /* smaller share */
        margin-right: 2rem;
        opacity: 0.9;
  }
}
@media (max-width: 1176px) {
     .main {
        margin-right: 0;
         gap: 0.5rem;
    }
         .coco-image {
        width: 35%;   /* smaller share */
        margin-right: 1.8rem;
        opacity: 0.9;
  }
  .input-box, .input-submit {
    width: 100%;
}
}
@media (max-width: 1100px) {
    .main {
        margin-right: 0;
         gap: 2rem;
    }

     .coco-image {
    width: 30%;   /* smaller share */
    margin-right: 1.3rem;
      opacity: 0.7;
  }

  .login-box {
    width: 60%;   /* bigger than coco-image */
    
  }
}
@media (max-width: 1000px) {
     .main {
        margin-right: 0;
    }

     .coco-image {
        width: 30%;   /* smaller share */
        opacity: 0.3;
  }

  .login-box {
    width: 70%; /* bigger than coco-image */
    
  }
} 


@media (max-width: 999px) {
  .coco-image {
    display: none;
  }

  .login-box {
    width: 100%; /* optional: make login box full width 
     margin: auto; 
  } 

   .main {
    width: 72%;
    margin: 0 auto; 
  }

  .coco-image {
   display: none;
  }

  .login-box {
    width: 100%;
  }
}
.back-home-container {
    display: flex;
    justify-content: center;
    margin-top: 18px;
}
.back-home-btn {
    display: inline-block;
    padding: 10px 28px;
    background: #42CCC5;
    color: #fff;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(34, 197, 194, 0.08);
    transition: background 0.2s, color 0.2s;
}
.back-home-btn:hover {
    background: #0f766e;
    color: #fff;
}
</style>