const oracledb = require('oracledb');
const bcrypt = require('bcryptjs');
const pdf = require('html-pdf');
const fs = require('fs');
async function run() {

    let connection;

    try {
        connection = await oracledb.getConnection({
            user: "turismo_real",
            password: "admin",
            connectionString: "localhost/xe"
        });
        console.log("Successfully connected to Oracle Database");
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
}

function getConnection(){
    return run();
}

module.exports = { getConnection };