const data = { "company": "corporacionkimirina", "login": "aalava@kimirina.org", "password": "Principe406!" };

const APIurl = "https://user-api-v2.simplybook.me";

const options = {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
}

const token = null;
const refreshToken = null;

const conexion = async () => {
    const response = await fetch(`${APIurl}/admin/auth/`, options);
    
    if (!response.ok){
        console.log('reintentando');
        setTimeout(conexion, 10000);
        //conexion();
    }
    else {
        const conexion = await response.json();
        
        this.token = conexion.token;
        this.refreshToken = conexion.refresh_token;
        
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
}

conexion();

const getServicesList = async (requestOptions) => {
    const response = await fetch(`${APIurl}/admin/services/`, requestOptions);
    if (!response.ok){
        //console.log('crear una accion de error');
        //logout(requestOptions);
        console.log(response.status);
    }
    else { 
        const servicios = await response.json();
        //console.log(servicios);

        servicios.data.map((i) => {
            console.log(i.name);         
        })
    logout(this.token);
    }
}

const logout = async (token) => {
    const response = await fetch(`${APIurl}/admin/auth/logout`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'X-Company-Login': 'corporacionkimirina',
            'X-Token': token,  
        },
        body: JSON.stringify({"auth_token": token})
    });
    //const logout = await response.json()
    console.log(response.status);
}