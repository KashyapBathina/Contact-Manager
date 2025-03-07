const urlBase = 'http://cop4331-sp-group10.darkmooncs.xyz/LAMPAPI';
const extension = 'php';

let userId = 0;
let firstName = "";
let lastName = "";

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;
//	var hash = md5( password );
	
	document.getElementById("loginResult").innerHTML = "";

	let tmp = {Email:login,Password:password};
//	var tmp = {Email:login,Password:hash};
	let jsonPayload = JSON.stringify( tmp );
	
	let url = urlBase + '/Login.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;
		
				if( userId < 1 )
				{		
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
					return;
				}
		
				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

				saveCookie();

				localStorage.setItem('userId', userId); // Store the user ID

	
				window.location.href = "contacts.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
	

}

function doRegistration(){
	let first = document.getElementById("first").value;
        let last = document.getElementById("last").value;
        let email = document.getElementById("email").value;
        let phone = document.getElementById("phone").value;
        let password = document.getElementById("password").value;
        
        // Reset result message
        document.getElementById("registrationResult").innerHTML = "";

	if (password!==confirmation){
                alert("Passwords must match");
                return;
        }
	
	let tmp = {
                first: first,
                last: last,
                email: email,
                phone: phone,
                password: password
        };
        
        let jsonPayload = JSON.stringify(tmp);
        let url = urlBase + '/Registration.' + extension;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

        try
        {
                xhr.onreadystatechange = function()
                {
                        if (this.readyState == 4 && this.status == 200)
                        {
                                let jsonObject = JSON.parse(xhr.responseText);
                                
                                if(jsonObject.success)
                                {
                                        document.getElementById("registrationResult").innerHTML = 
                                                "Registration successful! You can now log in.";
                                        // Optionally redirect to login page after successful registration
                                        setTimeout(function() {
                                                window.location.href = "login.html";
                                        }, 1000); // Redirect after 3 seconds
                                }
                                else
                                {
                                        document.getElementById("registrationResult").innerHTML = 
                                                "Registration failed: " + jsonObject.message;
                                }
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err)
        {
                document.getElementById("registrationResult").innerHTML = err.message;
        }

}

function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "login.html";
	}
	else
	{
//		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	// Clear all cookies
    	document.cookie = "first=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
    	document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
	document.cookie = "lastName=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
	window.location.replace("login.html");
	return false;
}

