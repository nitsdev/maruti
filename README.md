# if developer working in localhost for this project then you need to follow the below steps

1. create user.js inside js folder that exist in root and paste below code 
   localStorage.setItem("user", "YOUR_NAME");

2. In apiurl.js file add below code if not exist
   else if (user == "YOUR_NAME") {
    base_url = "YOUR PROJECT URL THAT RUNNING IN LOCAL";  //example- http://test.com
  }

3. Make sure belore using app you hit application home page - like http://test.com
# maruti
