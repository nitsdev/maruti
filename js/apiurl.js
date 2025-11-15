const user = localStorage.getItem("user");
let base_url = "";

if (!window.location.host.includes("localhost")) {
    base_url = "";
} else if (user == "dharmendra") {
    base_url = "http://localhost/maruti/";
} else if (user == "nitesh") {
    base_url = "http://localhost/maruti/";
}