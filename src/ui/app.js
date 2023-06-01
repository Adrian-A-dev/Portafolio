const {
    remote
} = require('electron');


const main = remote.require('./main');
const getUsers = async () => {
    const tabla = document.getElementById('dataTable');

    const resultados = await main.users();
    let datos = [];
    resultados.forEach(function (valor, indice, array) {
        data = {
            id: valor[0],
            total: valor[12],
        }
        datos.push(data);
    });
    console.log(datos);
    datos.forEach(function (v, i, datos) {
        const row = tabla.insertRow(-1);
        let cell1 = row.insertCell(0);
        let cell2 = row.insertCell(1);
        cell1.innerHTML = v.id;
        cell2.innerHTML = v.total;
        console.log(v);
    });

}

getUsers();