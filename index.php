<?php 
// INCLUYE CONEXIÓN CON LA BASE DE DATOS
require_once("db.php");

// CONSULTA PARA OBTENER LOS PACIENTES EN LA TABLA (READ)
$sql = "SELECT * FROM patients ORDER BY names ASC";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>PsicoGest</title>

        <link rel="icon" href="assets/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="assets/style.css"> 

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    </head>

    <body>
        <!-- HEADER --> 
        <header class="header">
            <h1 class="app-title">PSIC🧠GEST</h1>
            <h1 class="page-title" id="page-title">FORMULARIO DE REGISTRO DE PACIENTES</h1>
        </header>

        <!-- BOTONES DE NAVEGACIÓN -->
        <div class="nav">
            <button type="button" id="btn-register" class="active">
                REGISTRAR NUEVO PACIENTE
            </button>
            <button type="button" id="btn-patients">
                VER PACIENTES REGISTRADOS
            </button>
        </div>

        <!-- FORMULARIO (CREATE / UPDATE) -->
        <div class="form-container">
            <section class="form-section" id="form-section">
                <h2>DATOS DEL PACIENTE</h2>

                <hr class="divider-h-line">

                <form action="insert.php" method="POST">
                    <!-- input oculto que sirve para el update form -->
                    <input type="hidden" name="uuid" id="uuid">

                    <div class="row two-columns">
                        <div class="field select" id="doctype-field">
                            <label for="doctype">TIPO DE DOCUMENTO</label>
                            
                            <select 
                                name="doctype"
                                id="doctype"
                                required
                            >
                                <option value="" disabled selected hidden>
                                    Selecciona el tipo de documento
                                </option>

                                <option value="cc">Cédula de Ciudadanía</option>
                                <option value="ce">Cédula de Extranjería</option>
                                <option value="ti">Tarjeta de Identidad</option>
                                <option value="pa">Pasaporte</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="docnumber">NÚMERO DE DOCUMENTO</label>
                            <input 
                                type="text" 
                                id="docnumber" 
                                name="docnumber" 
                                minlength="5"
                                placeholder="Ingresa el número de documento" 
                                required
                            >
                        </div>
                    </div>

                    <div class="row two-columns">
                        <div class="field">
                            <label for="names">NOMBRES</label>

                            <input 
                                type="text" 
                                id="names" 
                                name="names" 
                                minlength="3"
                                placeholder="Ingresa el(los) nombre(s)" 
                                required
                            >
                        </div>

                        <div class="field">
                            <label for="surnames">APELLIDOS</label>
                            <input 
                                type="text" 
                                id="surnames" 
                                name="surnames" 
                                minlength="3"
                                placeholder="Ingresa los apellidos" 
                                required
                            >
                        </div>
                    </div>  

                    <div class="row two-columns">
                        <div class="field">
                            <label for="birthdate">FECHA DE NACIMIENTO</label>
                            <input 
                                type="date" 
                                id="birthdate" 
                                name="birthdate" 
                                required
                            >
                        </div>

                        <div class="field">
                            <label for="phone">NÚMERO DE TELÉFONO</label>

                            <input 
                                type="tel" 
                                id="phone" 
                                required
                            >
                            <input 
                                type="hidden" 
                                name="phone" 
                                id="phone-full"
                            >
                        </div>
                    </div> 

                    <div class="row two-columns">
                        <div class="field">
                            <label for="email">CORREO ELECTRÓNICO</label>
                            
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Ingresa el correo electrónico" 
                                required
                            >
                        </div> 

                        <div class="field">
                            <label for="email-confirm">CONFIRMACIÓN DEL CORREO ELECTRÓNICO</label>

                            <input 
                                type="email" 
                                id="email-confirm" 
                                name="email-confirm" 
                                placeholder="Confirma el correo electrónico" required
                            >
                        </div>
                    </div> 

                    <div class="row two-columns">
                        <div class="field">
                            <label for="password">CONTRASEÑA</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                minlength="8"
                                placeholder="Ingresa la contraseña" 
                                required
                            >

                            <button 
                                type="button" 
                                class="toggle-password" 
                                aria-label="Mostrar contraseña"
                            >
                            </button>
                        </div> 

                        <div class="field">
                            <label for="password-confirm">CONFIRMACIÓN DE LA CONTRASEÑA</label>

                            <input 
                                type="password" 
                                id="password-confirm" 
                                name="password-confirm" 
                                placeholder="Confirma la contraseña" 
                                required
                            >

                            <button 
                                type="button" 
                                class="toggle-password" 
                                aria-label="Mostrar contraseña"
                            >
                            </button>
                        </div> 
                    </div> 

                    <hr class="divider-h-line">

                    <label for="location">UBICACIÓN</label>

                    <p>Selecciona la ubicación de residencia, marcando solo una de las siguientes opciones:</p>

                    <div class="location-container">
                        <div class="radio-group">
                            <label>
                                <input 
                                    type="radio" 
                                    name="location" 
                                    value="colombia"
                                    required
                                >
                                EN COLOMBIA
                            </label>

                            <label>
                                <input 
                                    type="radio" 
                                    name="location" 
                                    value="otro">
                                    EN OTRO PAÍS
                            </label>
                        </div>

                        <div id="country-select-wrapper"> 
                            <select 
                                name="country" 
                                id="country"
                            >
                            </select>
                        </div>
                    </div>
 
                    <div class="form-btns"> 
                        <!-- Boton para enviar el formulario --> 
                        <button 
                            type="submit" 
                            class="btn-submit"
                        >
                            REGISTRAR PACIENTE
                        </button>    
                        <!-- Boton para cancelar cuando se actualiza --> 
                        <button 
                            type="button" 
                            class="btn-cancel" 
                            id="btn-cancel" 
                            style="display: none;"
                        >
                            CANCELAR
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <!-- TABLA (READ) -->
        <div class="table-card" id="patients-section">
            <div class="table-container">
                <table class="patients-table">
                    <thead>
                        <tr>
                            <th>UUID</th>
                            <th>Tipo de<br>documento</th>
                            <th>Número de<br>documento</th>
                            <th>Nombre(s)</th>
                            <th>Apellidos</th>
                            <th>Fecha de<br>nacimiento</th>
                            <th>Edad</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Ubicación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <?php
                                $birthDate = new DateTime($row['birthdate']);
                                $today = new DateTime();
                                $age = $today->diff($birthDate)->y;

                                $docTypes = [
                                    "cc" => "Cédula de Ciudadanía",
                                    "ce" => "Cédula de Extranjería",
                                    "ti" => "Tarjeta de Identidad",
                                    "pa" => "Pasaporte"
                                ];
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['uuid']); ?></td>
                                    <td>
                                        <?php 
                                            echo htmlspecialchars(
                                                $docTypes[$row['doc_type']] ?? $row['doc_type']
                                            ); 
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['doc_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['names']); ?></td>
                                    <td><?php echo htmlspecialchars($row['surnames']); ?></td>
                                    <td><?php echo htmlspecialchars($row['birthdate']); ?></td>
                                    <td><?php echo $age; ?> años</td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td>
                                        <button 
                                            type="button" 
                                            class="btn-edit"
                                            onclick='editPatient(
                                                <?php echo json_encode($row["uuid"]); ?>,
                                                <?php echo json_encode($row["doc_type"]); ?>,
                                                <?php echo json_encode($row["doc_number"]); ?>,
                                                <?php echo json_encode($row["names"]); ?>,
                                                <?php echo json_encode($row["surnames"]); ?>,
                                                <?php echo json_encode($row["birthdate"]); ?>,
                                                <?php echo json_encode($row["phone"]); ?>,
                                                <?php echo json_encode($row["email"]); ?>,
                                                <?php echo json_encode($row["location"]); ?>
                                            )'
                                        >
                                            Actualizar
                                        </button>

                                        <button 
                                            type="button" 
                                            class="btn-delete"
                                            onclick='deletePatient(<?php echo json_encode($row["uuid"]); ?>)'
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr> 
                                <td colspan="11">No hay pacientes registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- LIBRERÍAS -->
        <!-- Número de teléfono con indicativo  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script> 
        <!-- Número de teléfono con indicativo: Traducción a español  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/i18n/es.js"></script>
        <!-- Número de teléfono con indicativo: Funciones auxiliares y validaciones -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
        <!-- Selector de países (Choices.js)  -->
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <!-- SCRIPT PRINCIPAL -->
        <script src="assets/js/script.js"></script> 
    </body>
</html>