/** 
 * LLamamos los módulos comunes que usaremos en todos los casos  
 */
const express = require('express');
const mysql = require('mysql');

const bodyParser = require('body-parser');

const PORT = process.env.PORT || 8080;

const app = express();

app.use(bodyParser.json());

/** 
 * Definimos una conexión a la base de datos 'pacientes'  
 */
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'pacientes'
});

/** Creamos la funcion generarCodigo() que se encargará de generar 
 * un código de ocho caracteres para el paciente, dicho código será 
 * enviado por email sin que el usuario del sistema intervenga 
 */
function generarCodigo() {
    var pass = "";
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for (i = 0; i < 8; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return pass;
};

/** 
 * Función para el envío de email
 * Recibe los parámetros:
 * dest: el destinatario del email.
 * dni: el dni del destinatario.
 * passw: el código generado para el paciente
 * La función recibe los parámetros que se le pasan al hacer el llamado, 
 * llama al módulo nodemailer que será el encargado del envío del email, 
 * suministramos las credenciales de conexión, rellenamos los campos 
 * correspondientes al envío y finalmente se envía.
 */


function enviarEmail(dest, dni, passw) {
    var nodemailer = require('nodemailer');
    var transporter = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: 'viernescare@gmail.com',
            pass: 'v13rn3sc4r3'

        }
    });
    var mailOptions = {
        from: 'ViernesCare',
        to: dest,
        subject: 'Alta en ViernesCare - Sending Email using Node.js',
        html: `<h1>Viernes Care</h1>
        <h2>Bienvenido:</h2>
        <p>Hola, este correo electrónico es generado automáticamente por el sistema de seguimiento de pacientes de COVID, usted ha sido registrado con el dni: ${dni}, y su clave de acceso al sistema es: <b>${passw}</b></p>
        <p>Guarde el presente correo como referencia.</p>
        <p>No responda a esta dirección, ya que esta es una cuenta no monitorizada</p>`
    };
    transporter.sendMail(mailOptions, function (error, info) {
        console.log("senMail returned!");
        if (error) {
            console.log("ERROR!!!!!!", error);
        } else {
            console.log('Email sent: ' + info.response);
        }
    });
};

/** 
 * En este punto definimos los endpoints de nuestra API, esto es, las direcciones 
 * a las cuales deberá accederse con los métodos adecuados, para que se ejecuten 
 * las acciones previstas, las cuales serán descritas a medida que van apareciendo
 */

/**
 * Método: GET
 * Endpoint: localhost:8080/pacientes
 * Devuelve: Archivo json con listado de todos los pacientes.
 */
app.get('/pacientes', (req, res) => {
    const sql = 'SELECT * FROM paciente';
    connection.query(sql, (error, results) => {
        if (error) throw error;
        if (results.length > 0) {
            res.json(results);
        } else {
            res.send('Not results')
        };
    });

});

/**
 * Método: GET
 * Endpoint: localhost:8080/pacientes/{id}
 * Devuelve: Archivo json con datos de paciente según id 
 */

app.get('/pacientes/:id', (req, res) => {
    const { id } = req.params
    const sql = `SELECT * FROM paciente WHERE idPaciente = ${id}`;
    connection.query(sql, (error, result) => {
        if (error) throw error;
        if (result.length > 0) {
            res.json(result);
        } else {
            res.send('Not results')
        };
    });
});

/**
 * Método: GET
 * Endpoint: localhost:8080/notas/{id}
 * Devuelve: Archivo json con las notas de paciente según id 
 */

app.get('/notas/:id', (req, res) => {
    const { id } = req.params
    const sql = `SELECT * FROM notas WHERE idPaciente = ${id}`;
    connection.query(sql, (error, result) => {
        if (error) throw error;
        if (result.length > 0) {
            res.json(result);
        } else {
            res.send('Not results')
        };
    });
});

/**
 * Método: POST
 * Endpoint: localhost:8080/add
 * Recibe: Recibe archivo json en el body del request.
 * Acción: Esta función genera una clave para el paciente, crea el registro
 * dentro de la base de datos y le envía al paciente un email notificándole
 * que ha sido dado de alta y cuál es su contraseña.
 */

app.post('/add', (req, res) => {
    var passw = generarCodigo();
    const sql = 'INSERT INTO paciente SET ?';
    const pacienteObj = {
        codPaciente: passw,
        dni: req.body.dni,
        email: req.body.email,
        telefono: req.body.telefono,
        idEstado: req.body.idEstado
    }
    var dest = req.body.email;
    var dni = req.body.dni;
    connection.query(sql, pacienteObj, error => {
        if (error) throw error;
        {
            res.send('Paciente Creado');
            enviarEmail(dest, dni, passw)
        };
    })
});

/**
 * Método: POST
 * Endpoint: localhost:8080/addNote/{id}
 * Recibe: Recibe parametro id en url y archivo json en el body del request.
 * Acción: Esta función crea una nota de seguimiento de un paciente
 * identificado por su id
 */

app.post('/addNote/:id', (req, res) => {
    const { id } = req.params;
    const sql = 'INSERT INTO notas SET ?';
    const notaObj = {
        idPaciente: id,
        fechaNota: req.body.fechaNota,
        horaNota: req.body.horaNota,
        nota: req.body.nota        
    }    
    connection.query(sql, notaObj, error => {
        if (error) throw error;
        {
            res.send('Nota Creada');            
        };
    })
});

/**
 * Método: PUT
 * Endpoint: localhost:8080/update/{id}
 * Recibe: Recibe {id} como parámetro en la url y un archivo json en el body 
 * del request.
 * Acción: Esta función actualiza los datos del paciente identificado con el id,
 * para ello recibe un json con los datos del paciente.
 * Nota: todos los datos vendrán informados.
 */

app.put('/update/:id', (req, res) => {
    const { id } = req.params;
    const { dni, email, telefono, idEstado } = req.body;
    const sql = `UPDATE paciente SET dni = '${dni}', email = '${email}', telefono = '${telefono}', idEstado = '${idEstado}' WHERE idPaciente = ${id}`;
    connection.query(sql, error => {
        if (error) throw error;
        res.send('Paciente Actualizado');
    });

});

/**
 * Método: PUT
 * Endpoint: localhost:8080/updateNote/{id}
 * Recibe: Recibe {id} como parámetro en la url y un archivo json en el body 
 * del request.
 * Acción: Esta función actualiza los datos de una nota identificada con el idNota,
 * para ello recibe un json con los datos.
 * Nota: todos los datos vendrán informados.
 */

app.put('/updateNote/:id', (req, res) => {
    const { id } = req.params;
    const { idPaciente, fechaNota, horaNota, nota } = req.body;
    const sql = `UPDATE notas SET idPaciente = '${idPaciente}', fechaNota = '${fechaNota}', horaNota = '${horaNota}', nota = '${nota}' WHERE idNota = ${id}`;
    connection.query(sql, error => {
        if (error) throw error;
        res.send('Nota Actualizada');
    });

});

/**
 * Método: DELETE
 * Endpoint: localhost:8080/delete/{id}
 * Recibe: Recibe {id} como parámetro en la url.
 * Acción: Esta función elimina los datos del paciente identificado con el id.
 */

app.delete('/delete/:id', (req, res) => {
    const { id } = req.params;
    const sql = `DELETE FROM paciente WHERE idPaciente = ${id}`;
    connection.query(sql, error => {
        if (error) throw error;
        res.send('Paciente Eliminado');
    });
});

/**
 * Método: DELETE
 * Endpoint: localhost:8080/deleteNote/{id}
 * Recibe: Recibe {id} como parámetro en la url.
 * Acción: Esta función elimina los datos de la nota identificada con su id.
 */

app.delete('/deleteNote/:id', (req, res) => {
    const { id } = req.params;
    const sql = `DELETE FROM notas WHERE idNota = ${id}`;
    connection.query(sql, error => {
        if (error) throw error;
        res.send('Nota Eliminada');
    });
});

/**
 * Nos muestra mensaje de confirmación de conexión con la base de datos
 * una vez que intentamos correr el servidor.
 */
connection.connect(error => {
    if (error) throw error;
    console.log('Database server is running');
});

/**
 * Nos muestra mensaje de confirmación de funcionamiento del servidor.
 */

app.listen(PORT, () => console.log(`Server running on port ${PORT}`));