const oracledb = require('oracledb');
const dbConfig = { user: 'turismo_real', password: 'admin', connectString: 'localhost/xe'};



async function run() {
    let connection;

    try {
        connection = await oracledb.getConnection(dbConfig);
        sql = `
            BEGIN
                PA_GET_ALLRESERVAS(1, 'dia', :cursor);
            END;`

        const result = await connection.execute(sql, {
            cursor:{
                dir: oracledb.BIND_OUT,
                type: oracledb.CURSOR,
                resultSet: true,
                outFormat: oracledb.OUT_FORMAT_OBJECT
                // outFormat: oracledb.DB_TYPE_JSON
            }
        });

        const resultSet = result.outBinds.cursor;
        const rows = await resultSet.getRows(); // return Array of Array
        // console.log(rows);
        
        // Nested(2) Arrays into Array of Objects
        const arrOfObjects = rows.map(rows => {
            return {
              id: rows[0],
              inicio_reserva: rows[1]
            };
          });

        console.log(arrOfObjects);


        // let row;

        // while ((row = await rs.getRow())) {
        //     if (row.DONE)
        //         console.log(row.DESCRIPTION, "is done");
        //     else
        //         console.log(row.DESCRIPTION, "is NOT done");
        // }



        // await rs.close(); // Always close the ResultSet fetched

    } catch (err) {
        console.error(err);
    } finally {
        if (connection) {
            try {
                await connection.close();
            } catch (err) {
                console.error(err);
            }
        }
    }
};

run();
