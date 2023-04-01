const axios = require('axios').default;
const dotenv = require('dotenv');

dotenv.config();

const APIurl = "https://user-api-v2.simplybook.me";

const data = { "company": process.env.company, "login": process.env.login, "password": process.env.password };

axios({
    url: `${APIurl}/admin/auth`,
    method: 'post',
    headers: {'Content-type': 'text/html; charset=UTF-8'},
    data: data
}).then(function(response){
    console.log(response.data.token);
})