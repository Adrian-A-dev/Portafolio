const {
    BrowserWindow,
    Menu,
    Notification,
    app
} = require('electron');
const pdf = require('html-pdf');


const oracledb = require('oracledb');
const dbConfig = {
    user: 'turismo_real',
    password: 'admin',
    connectString: 'localhost/xe'
};

const path = require('path');
require('electron-reload')(__dirname, {
    electron: path.join(__dirname, '../node_modules', '.bin', 'electron')
});

const bcrypt = require('bcryptjs');

async function getReports(data_form) {

    let sucursal = parseInt(data_form.sucursal);
    // where = ''
    if (sucursal < 1) {
        sucursal = 0;
    }
    connection = await oracledb.getConnection(dbConfig);
    sql = `
    BEGIN
    PA_GET_ALLRESERVAS(${sucursal}, '${data_form.periodo}', :cursor);
    END;`

    const result = await connection.execute(sql, {
        cursor: {
            dir: oracledb.BIND_OUT,
            type: oracledb.CURSOR,
            resultSet: true,
            outFormat: oracledb.OUT_FORMAT_OBJECT
            // outFormat: oracledb.DB_TYPE_JSON
        }
    });

    const resultSet = result.outBinds.cursor;
    const rows = await resultSet.getRows(); // return Array of Array
    if (rows == '' || rows == [] || rows.lenght < 1) {
        new Notification({
            title: "No existe información",
            body: "No existe información para periodo y/o sucursal seleccionada",
        }).show();
        return 'none'
    } else {
        let datos = [];
        rows.forEach(function (valor, indice, array) {
            data = {
                departamento: valor[0],
                total: valor[1],
                sucursal: valor[2],
                periodo: valor[3],
            }
            datos.push(data);
        });

        let titulo = 'REPORTE DE GANANCIAS';
        switch (data_form.periodo) {
            case 'mes':
                titulo = 'REPORTE DE GANANCIAS POR MES';
                break;
            case 'annio':
                titulo = 'REPORTE DE GANANCIAS POR AÑO'
                break;
            case 'dia':
                titulo = 'REPORTE DE GANANCIAS POR DÍA'
                break;
            default:
                titulo = 'REPORTE DE GANANCIAS3';
        }
        let contenido = '';
        datos.forEach(function (v, i, datos) {
            let total = v.total.toString();
            total = total.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            total = "$" + total;
            contenido = contenido + `<tr><td>${v.sucursal}</td><td>${v.departamento}</td><td class="text-center">${total}</td><td class="text-center">${v.periodo}</td></tr>`;
        });

        let content = `
        <div class="row text-center">
            <h1 class="text-center"><b>${titulo}</b></h1>
        </div>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>SUCURSAL</td>
                    <td>DEPARTAMENTO</td>
                    <td class="text-center">TOTAL</td>
                    <td class="text-center">PERIODO</td>
                </tr>
            </thead>
            <tbody>
                ${contenido}
            </tbody>
        </table>
        `;
        let contenido_html = template_pdf(content);
        let nombre = 'reporte_ganacias_' + Math.random() + '.pdf';
        pdf.create(contenido_html).toFile(`pdfs/${nombre}`, function (err, res) {
            if (err) {
                new Notification({
                    title: "Error al generar PDF",
                    body: "Ocurrió un error al generar PDF. Intente Nuevamente",
                }).show();
                console.log(err);
                return 'none'
            } else {
                console.log(res);
                return nombre;
            }
        });
        return nombre;
    }

}

async function getSucursalesDB() {
    connection = await oracledb.getConnection(dbConfig);
    result = await connection.execute(`SELECT id_sucursal, sucursal FROM sucursal WHERE deleted = 'N' order by sucursal`, [], {
        resultSet: true,
        outFormat: oracledb.OUT_FORMAT_OBJECT
    });
    const sucursales = result.resultSet;
    return sucursales

}




let login;

function loginWindow() {
    login = new BrowserWindow({
        minWidth: 500,
        minHeight: 700,
        maxWidth: 500,
        maxHeight: 700,
        width: 500,
        height: 700,
        title: 'Reporte Ganancia por Departamento ',
        webPreferences: {
            nodeIntegration: true,
        }
    });
    login.setMenu(null);
    // const mainMenu = Menu.buildFromTemplate(templateMenu); //MENU PRINCIPAL
    // Menu.setApplicationMenu(mainMenu);
    // login.on('closed', () => {
    //     app.quit();
    // })
    login.loadFile('ui/login.html');
}


async function validateLogin(data_form) {
    let usuario = (data_form.usuario);
    let password = (data_form.password);
    if (usuario != '' && password != '') {
        connection = await oracledb.getConnection(dbConfig);
        result = await connection.execute(`SELECT * FROM USUARIO WHERE USERNAME = '${usuario}'`, [], {
            resultSet: true,
            outFormat: oracledb.OUT_FORMAT_OBJECT
        });
        result = result.resultSet;
        const rows = await result.getRows();
        if (rows.length > 0) {
            console.log((rows[0].ID_USUARIO));
            const isPasswordValid = await bcrypt.compare(password, rows[0].PASSWORD);
            if (isPasswordValid) {
                if(rows[0].ID_ROL == 1 || rows[0].ID_ROL == 2){
                    createWindow();
                    login.close();
                    return 'ok';
                }else{
                    return '* Usuario no posee los permisos necesarios';
                }
              
            } else {
                return '* Usuario y/o Contraseña Incorrectas';
            }

        } else {
            return '* Usuario No existe';
        }



    } else {
        return '* Usuario y/o Contraseña Incorrectas';
    }


}




let window;

function createWindow() {
    window = new BrowserWindow({
        width: 800,
        height: 600,
        webPreferences: {
            nodeIntegration: true,
        }
        
    });
    window.maximize();
    const mainMenu = Menu.buildFromTemplate(templateMenu); //MENU PRINCIPAL
    Menu.setApplicationMenu(mainMenu);

    window.on('closed', () => {
        app.quit();
    })
    window.loadFile('ui/index.html');
}

let newReporteReservaWindow;

//////////////// RESERVAS WINDOW ////////////////
function reporteReservaWindow() {
    if (newReporteReservaWindow == null) {
        newReporteReservaWindow = new BrowserWindow({
            minWidth: 500,
            minHeight: 700,
            maxWidth: 500,
            maxHeight: 700,
            width: 500,
            height: 700,
            title: 'Reporte Ganancia por Departamento ',
            webPreferences: {
                nodeIntegration: true,
            }
        });
        newReporteReservaWindow.setMenu(null);
        newReporteReservaWindow.loadFile('ui/reservas.html');
    };

    if (newReporteReservaWindow.isMinimized())
        newReporteReservaWindow.restore()
    newReporteReservaWindow.focus()

    newReporteReservaWindow.on('closed', () => {
        newReporteReservaWindow = null;
    })


}


// Menus Principales
const templateMenu = [{
    label: 'Reportería Turismo Real',
    submenu: [{
            label: 'Reporte Ganancia por Departamento',
            // accelerator: 'Ctrl+N', //crea shortcut
            click() {
                reporteReservaWindow();
            }
        },
        {
            label: 'Cerrar Sesión',
            accelerator: process.platform == 'darwin' ? 'command+q' : 'Ctrl+Q', // SI ES MAC O SINO WINDOWS/LINUX
            click() {
                app.quit();
            }

        }
    ]

}];

if (process.platform === 'darwin') {
    templateMenu.unshift({
        label: app.getName()
    });
}

if (process.env.NODE_ENV !== 'production') {
    templateMenu.push({
        label: 'DevTools',
        submenu: [{
                label: 'Show/Hide Dev Tools',
                accelerator: 'Ctrl+D',
                click(item, focusedWindow) {
                    focusedWindow.toggleDevTools();
                }
            },
            {
                role: 'reload'
            }
        ]
    })
}


function template_pdf(contenido) {
    let html = `
    <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- CSS only -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
            <!-- JavaScript Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
            </script>
            <!-- CUSTOM DASHBOARD CONTENT -->
            <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link
                href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                rel="stylesheet">
            <!-- Custom styles for this template -->
            <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
            <!-- Custom styles for this page -->
            <title>Reporteria Reservas</title>

        </head>

        <body>

            <div class="container p-4">
                <div class="card ">
                    <br>
                ${contenido}
                </div>
            </div>
        </body>

        </html>
    
    
    `;

    return html;
}

function cerrar(){
    app.quit();
}

module.exports = {
    createWindow,
    getReports,
    reporteReservaWindow,
    getSucursalesDB,
    loginWindow,
    validateLogin,
    cerrar
}