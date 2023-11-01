<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>

<table>
<div class="w3-container-fluid">

  <div class="w3-bar w3-light-grey w3-border">
    <a href="home.php" class="w3-bar-item w3-button w3-mobile">Home</a>
    <a href="about.php" class="w3-bar-item w3-button w3-mobile">About</a>
    <a href="destroy.php" class="w3-bar-item w3-button w3-mobile w3-right">Logout</a>

    <a href="contact.php" class="w3-bar-item w3-button w3-mobile w3-right">Contact Us</a>

    <div class="w3-dropdown-hover w3-mobile">
      <button class="w3-button" onclick="window.location.href='product.php'">Product <i class="fa fa-caret-down"></i></button>
      <div class="w3-dropdown-content w3-bar-block w3-dark-grey">
        <a href="#" class="w3-bar-item w3-button w3-mobile">Link 1</a>
        <a href="#" class="w3-bar-item w3-button w3-mobile">Link 2</a>
        <a href="#" class="w3-bar-item w3-button w3-mobile">Link 3</a>
      </div>
      </div>

      <div class="w3-dropdown-hover w3-mobile">
      <button class="w3-button">Industries <i class="fa fa-caret-down"></i></button>
      <div class="w3-dropdown-content w3-bar-block w3-dark-grey">
        <a href="#" class="w3-bar-item w3-button w3-mobile">Education</a>
        <a href="#" class="w3-bar-item w3-button w3-mobile">Buiness</a>
        <a href="#" class="w3-bar-item w3-button w3-mobile">Manufacturing</a>
      </div>

  </div>
</table>

</body>
</html>
