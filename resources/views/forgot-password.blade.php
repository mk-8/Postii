<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title> 
      @isset($doctitle)
        {{($doctitle)}} | Postii
        @else
        Postii
     @endisset
    </title>
    <link rel="icon" href= "https://cdn.iconscout.com/icon/premium/png-256-thumb/online-book-3333419-2773654.png?f=webp&w=256" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    @vite(['resources/css/fp.css'])                    
    @vite(['resources/js/app.js'])
  </head>
  <body>
    <header class="header-bar mb-3">
      <div class="container d-flex flex-column flex-md-row align-items-center p-3">
        <h4 class="my-0 mr-md-auto font-weight-normal"><a href="/" class="text-white">Postii</a></h4>
      </div>
    </header>

    @if (session()->has('success'))
        <div class="container container--narrow">
            <div class="alert alert-success text-center">
                {{session('success')}}
            </div>
        </div>
    @endif

   @if (session()->has('failure'))
        <div class="container container--narrow">
            <div class="alert alert-danger text-center">
                {{session('failure')}}
            </div>
        </div>
    @endif

    <div class="container padding-bottom-3x mb-2 mt-5">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="forgot">
          	
          	<h2>Forgot your password?</h2>
          <p>Change your password in three easy steps. This will help you to secure your password!</p>
          <ol class="list-unstyled">
            <li><span class="text-primary text-medium">1. </span>Enter your email address below.</li>
            <li><span class="text-primary text-medium">2. </span>Our system will send you a temporary password and a link.</li>
            <li><span class="text-primary text-medium">3. </span>Reset your password from the link provided.</li>
          </ol>

          </div>	
          
          <form class="card mt-4" method="POST" action = "/forgotPassword">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="email-for-pass">Enter your email address</label>
                <input name = "email" class="form-control" type="email" id="email-for-pass" required=""><small class="form-text text-muted">Enter the email address you used during the registration on Postii. Then we'll email a password to this address.</small>
              </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-success" type="submit">Get New Password</button>
              <button class="btn btn-danger" type="submit"><a href="/" style="color:#fff">Back to Login</a></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>