const data = { "company": "corporacionkimirina", "login": "aalava@kimirina.org", "password": "Principe406!" };

const APIurl = "https://user-api-v2.simplybook.me";

const options = {
    method: "POST", // or 'PUT'
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
}

const token = null;

const getAccessToken = async () => { 
    const response = await fetch(`${APIurl}/admin/auth/`, options);
    const conexion = await response.json();

    //console.log(conexion);

    this.token = conexion.token;

    let requestOptions = {
        method: "GET",
        headers: {
            'Content-Type': 'application/json',
            'X-Company-Login': 'corporacionkimirina',
            'X-Token': conexion.token,            
        }
    }

    getServicesList(requestOptions);    

}

getAccessToken();

const getServicesList = async (requestOptions) => {
    const response = await fetch(`${APIurl}/admin/services/`, requestOptions);
    const servicios = await response.json();
    //console.log(servicios);
    
    //var result = Object.keys(servicios).map((key) => [Number(key), servicios[key]]);

    //var result = Object.entries(servicios);
    
    console.log(servicios);

    var result = Object.entries(servicios);

    result.map((i) => {
        console.log(i);
    })
}