
<html>
<style>
body  
{  
    margin: 0;  
    padding: 0;  
    background-color:#6abadeba;  
    font-family: 'Arial';  
}  
.login{  
        width: 382px;  
        overflow: hidden;  
        margin: auto;  
        margin: 20 0 0 450px;  
        padding: 80px;  
        background: #23463f;  
        border-radius: 15px ;  
          
}  
h2{  
    text-align: center;  
    color: #277582;  
    padding: 20px;  
}  
label{  
    color: #08ffd1;  
    font-size: 17px;  
}  
#Uname{  
    width: 300px;  
    height: 30px;  
    border: none;  
    border-radius: 3px;  
    padding-left: 8px;  
}  
#Pass{  
    width: 300px;  
    height: 30px;  
    border: none;  
    border-radius: 3px;  
    padding-left: 8px;  
      
}  
#log{  
    width: 300px;  
    height: 30px;  
    border: none;  
    border-radius: 17px;  
    padding-left: 7px;  
    color: blue;  
  
  
}  
span{  
    color: white;  
    font-size: 17px;  
}  
a{  
    float: right;  
    background-color: grey;  
} 

</style>
	<head>
	<title></title>
	</head>
	<body>
			<form action="/register" method="post">

    <div class="login">
	<label><b>Name     
        </b>    
        </label>    
        <input type="text" name="name" id="Uname" placeholder="John Smith">    
        <br><br>

		<label><b>Email     
        </b>    
        </label>    
        <input type="text" name="email" id="Uname" placeholder="email@example.com">    
        <br><br>

<label><b>User Name     
        </b>    
        </label>    
        <input type="text" name="username" id="Uname" placeholder="Username">    
        <br><br>    
        <label><b>Password     
        </b>    
        </label>    
        <input type="Password" name="password" id="Pass" placeholder="Password">    
        <br><br>    
        <input type="submit" name="log" id="log" value="Register">       
        <br><br>    
	</div>
		</form>
	</body>
</html>