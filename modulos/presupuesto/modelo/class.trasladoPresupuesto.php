<?php
/*****************************************************************************
 * @Clase para el modelo de Traslado de Presupuesto
 * @versión: 1.0
 * @fecha creación: 31/08/2016
 * @autor: Hector Damian Lema
 ******************************************************************************
 * @fecha modificacion
 * @autor
 * @descripcion
 ******************************************************************************/

class TrasladoPresupuesto
{

    private $idtraslado_presupuestario;
    private $numero_solicitud;
    private $fecha_solicitud;
    private $numero_resolucion;
    private $fecha_resolucion;
    private $concepto;
    private $anio;

    //
    //Metodo para registrar los datos basicos del Traslado Presupuestario
    //
    public function ingresarDatosBasicos()
    {

        try {
            if (!empty($_POST['numero_solicitud']) and !empty($_POST['fecha_solicitud']) and !empty($_POST['concepto'])) {
                $db                      = new Conexion();
                $this->numero_solicitud  = $db->real_escape_string($_POST['numero_solicitud']);
                $this->fecha_solicitud   = date('Y-m-d', strtotime($_POST['fecha_solicitud']));
                $this->numero_resolucion = $db->real_escape_string($_POST['numero_resolucion']);
                $this->anio              = $_SESSION["anio_fiscal"];

                if (empty($_POST['fecha_resolucion']) or $_POST['fecha_resolucion'] != '') {
                    $this->fecha_resolucion = date('Y-m-d', strtotime($_POST['fecha_resolucion']));
                } else {
                    $this->fecha_resolucion = '0000-00-00';
                }

                $this->concepto = $db->real_escape_string($_POST['concepto']);

                $sql = $db->query("SELECT * FROM traslados_presupuestarios WHERE nro_solicitud = '$this->numero_solicitud'");
                if ($db->rows($sql) == 0) {

                    $sql_insertar = $db->query("INSERT INTO traslados_presupuestarios (nro_solicitud,
																						fecha_solicitud,
																						nro_resolucion,
																						fecha_resolucion,
																						justificacion,
																						estado,
																						status,
																						anio)
												VALUES ('$this->numero_solicitud',
														'$this->fecha_solicitud',
														'$this->numero_resolucion',
														'$this->fecha_resolucion',
														'$this->concepto',
														'elaboracion',
														'a',
														'$this->anio')");
                    echo '1|.|' . $db->insert_id;
                } else {
                    throw new Exception(2);
                }
                $db->liberar($sql);
                $db->close();
            } else {
                throw new Exception(3);
            }
        } catch (Exception $registroCatch) {
            echo $registroCatch->getMessage();
        }
    }

    //
    // Metodo para modificar los datos de la cabecera del Traslado Presupuestario
    //
    public function actualizarDatosBasicos()
    {

        try {
            if (!empty($_POST['idtraslado_presupuestario']) and !empty($_POST['fecha_solicitud'])
                and !empty($_POST['concepto'])) {
                $db                              = new Conexion();
                $this->idtraslado_presupuestario = $_POST['idtraslado_presupuestario'];
                $this->fecha_solicitud           = date('Y-m-d', strtotime($_POST['fecha_solicitud']));
                $this->numero_resolucion         = $db->real_escape_string($_POST['numero_resolucion']);

                if (empty($_POST['fecha_resolucion']) or $_POST['fecha_resolucion'] != '') {
                    $this->fecha_resolucion = date('Y-m-d', strtotime($_POST['fecha_resolucion']));
                } else {
                    $this->fecha_resolucion = '0000-00-00';
                }

                $this->concepto = $db->real_escape_string($_POST['concepto']);

                $sql = $db->query("UPDATE traslados_presupuestarios
									SET fecha_solicitud  = '$this->fecha_solicitud',
										nro_resolucion   = '$this->numero_resolucion',
										fecha_resolucion = '$this->fecha_resolucion',
										justificacion    = '$this->concepto',
										total_debito = (SELECT sum(monto_debitar)
                										FROM partidas_cedentes_traslado
                										WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario'),
                						total_credito = (SELECT sum(monto_acreditar)
                										FROM partidas_receptoras_traslado
                										WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario')
						WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario'");

                if ($db->errno == 0) {
                    echo '1';
                } else {
                    echo '2|.|' . $db->errno;
                }
                $db->close();
            } else {
                throw new Exception(3);
            }
        } catch (Exception $registroCatch) {
            echo $registroCatch->getMessage();
        }
    }

    //
    //Metodo para mostrar actualizar y mostrar los totales
    //
    public function actualizarTotales()
    {
        $db                              = new Conexion();
        $this->idtraslado_presupuestario = $_POST['idtraslado_presupuestario'];
        $sql                             = $db->query("UPDATE traslados_presupuestarios
									SET
										total_debito = (SELECT sum(monto_debitar)
                										FROM partidas_cedentes_traslado
                										WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario'),
                						total_credito = (SELECT sum(monto_acreditar)
                										FROM partidas_receptoras_traslado
                										WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario')
						WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario'");

        $sql = $db->query("SELECT * FROM traslados_presupuestarios
									WHERE idtraslados_presupuestarios = '$this->idtraslado_presupuestario'");
        $registro = $db->recorrer($sql);
        echo $registro["total_debito"] . "|.|" .
            $registro["total_credito"];
    }
}
