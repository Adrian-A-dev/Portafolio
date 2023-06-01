const oracledb = require('oracledb');
// async function run() {

//     let connection;

//     try {
//         connection = await oracledb.getConnection({
//             user: "turismo_real",
//             password: "admin",
//             connectionString: "localhost/xe"
//         });
//         console.log("Successfully connected to Oracle Database");

//         result = await connection.execute(
//             `SELECT * FROM usuario`,
//             [], {
//                 resultSet: true,
//                 outFormat: oracledb.OUT_FORMAT_OBJECT
//             });
//         const rs = result.resultSet;
       
//         let tr = '';
//         while ((row = await rs.getRow())) {
            
//             tr = tr + `<tr><td>${row.USERNAME}</td></tr>`;
//         }
//         // const template = fs.readFileSync('views/index.html', { encoding: 'utf8' });
//         // content = `<h1>Título en el PDF creado con el paquete html-pdf</h1>
//         //             <p>Generando un PDF con un HTML sencillo</p>
//         //             <table>${tr}</table>`;
    
//         // pdf.create(template).toFile('./html-pdf.pdf', function(err, res) {
//         //     if (err){
//         //         console.log(err);
//         //     } else {
//         //         console.log(res);
//         //     }
//         // });
//         // // const isPasswordValid = await bcrypt.compare('admin', '$2y$10$O6HV7YyZVSlwYKtN9HxqwucE60g5MZ2lUthqrImj5FHbKHuUmv1MO');
//         // // console.log(isPasswordValid);
//         // // Create a table

//         // await connection.execute(`begin
//         //                         execute immediate 'drop table todoitem';
//         //                         exception when others then if sqlcode <> -942 then raise; end if;
//         //                       end;`);

//         // await connection.execute(`create table todoitem (
//         //                         id number generated always as identity,
//         //                         description varchar2(4000),
//         //                         creation_ts timestamp with time zone default current_timestamp,
//         //                         done number(1,0),
//         //                         primary key (id))`);

//         // // Insert some data

//         // const sql = `insert into todoitem (description, done) values(:1, :2)`;

//         // const rows = [
//         //     ["Task 1", 0],
//         //     ["Task 2", 0],
//         //     ["Task 3", 1],
//         //     ["Task 4", 0],
//         //     ["Task 5", 1]
//         // ];

//         // let result = await connection.executeMany(sql, rows);

//         // console.log(result.rowsAffected, "Rows Inserted");

//         // connection.commit();

//         // // Now query the rows back

//         // result = await connection.execute(
//         //     `select description, done from todoitem`,
//         //     [], {
//         //         resultSet: true,
//         //         outFormat: oracledb.OUT_FORMAT_OBJECT
//         //     });

//         // const rs = result.resultSet;
//         // let row;

//         // while ((row = await rs.getRow())) {
//         //     if (row.DONE)
//         //         console.log(row.DESCRIPTION, "is done");
//         //     else
//         //         console.log(row.DESCRIPTION, "is NOT done");
//         // }

//         // await rs.close();

//     } catch (err) {
//         console.error(err);
//     } finally {
//         if (connection) {
//             try {
//                 await connection.close();
//             } catch (err) {
//                 console.error(err);
//             }
//         }
//     }
// }

// run();
