<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="home.css">
    <title>Facilities Book</title>
</head>
<body>

<div class="header">
    <h3>Facilities Book</h1>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="profile.php">User Profile</a></li>
        <li><a href="booking.php">Booking Progress</a></li>
        <li><a href="result.php">Booking Result</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

</body>
</html>
<style>
.title {
    text-align: center;
}

#place-content {
    display: none;
}

.header {
    background-color: #333;
    color: #fff;
    padding: 20px;
    display: flex;
    align-items: center;
}

.header h3 {
    margin-right: auto;
}

.header ul {
    list-style-type: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0; /* 添加这行以移除默认的内边距 */
}

.header a {
    text-decoration: none;
    color: #fff;
}

.header a:hover {
    color: #FFA500; 
}


.button-1 {
  appearance: none;
  background-color: #FFFFFF;
  border-width: 0;
  box-sizing: border-box;
  color: #000000;
  cursor: pointer;
  display: inline-block;
  font-family: Clarkson,Helvetica,sans-serif;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0;
  line-height: 1em;
  margin: 0;
  opacity: 1;
  outline: 0;
  padding: 1.5em 2.2em;
  position: relative;
  text-align: center;
  text-decoration: none;
  text-rendering: geometricprecision;
  text-transform: uppercase;
  transition: opacity 300ms cubic-bezier(.694, 0, 0.335, 1),background-color 100ms cubic-bezier(.694, 0, 0.335, 1),color 100ms cubic-bezier(.694, 0, 0.335, 1);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: baseline;
  white-space: nowrap;
}

.button-1:before {
  animation: opacityFallbackOut .5s step-end forwards;
  backface-visibility: hidden;
  background-color: #EBEBEB;
  clip-path: polygon(-1% 0, 0 0, -25% 100%, -1% 100%);
  content: "";
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  transform: translateZ(0);
  transition: clip-path .5s cubic-bezier(.165, 0.84, 0.44, 1), -webkit-clip-path .5s cubic-bezier(.165, 0.84, 0.44, 1);
  width: 100%;
}

.button-1:hover:before {
  animation: opacityFallbackIn 0s step-start forwards;
  clip-path: polygon(0 0, 101% 0, 101% 101%, 0 101%);
}

.button-1:after {
  background-color: #FFFFFF;
}

.button-1 span {
  z-index: 1;
  position: relative;
}

</style>
