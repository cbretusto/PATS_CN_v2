<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>Document</title> --}}
  @include('shared.css_links.css_links')

</head>
<body>
    <div class="container-fluid">
        {{-- <input type="text" name="" id="clock"> --}}
        <div style="margin-top: 2%;">
            <h2 class="text-center">Invalid Access</h2>

            <p class="text-center">
                Please be sure that you have access or your credentials is equal on CN-PATS.
                <br>
                For concerns/issues, please contact ISS at local numbers 205, 206, or 208.
            </p>
            <hr class="my-4 ">
            
            {{-- <h1 class="display-2 text-center">Invalid</h1>
            
            <hr class="my-4 "> --}}
            

            <p class="text-center">

                {{-- <img src="{{ asset('public/images/images.gif') }}" alt=""> --}}
            </p>
        </div>
      </div>
</body>
@include('shared.js_links.js_links')
<script>
  
</script>
</html>