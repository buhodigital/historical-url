<?php
require_once('modelo/conexion.php');

class ModeloSujeto extends Conexion { 
    
    public function agregar($datos){
        //$datos=nombreSolicitante, fechaAvaluo, objetivoAvaluo, domicilio, codigoPostal, metrosCuadradosTerreno, metrosCuadradosConstruccion, anioConstruccion, tipoPropiedad, tipoVivienda, estadoConservacion, generales
        
        $this->query="
        INSERT INTO t_sujeto (snombreSolicita, dtFechaAvaluo, nObjetoAvaluo, sDomicilio, nCP, nMTerreno, nMConstruccion, nAntiguedad, nTipoPropiedad, nTipoVivienda, nEstadoConservacion, nNotaGeneral,dtFechaRegistro,bDisponible,fkUsuario) 
        VALUES ('".$datos[0]."','".$datos[1]."','".$datos[2]."','".$datos[3]."','".$datos[4]."','".$datos[5]."','".$datos[6]."','".$datos[7]."','".$datos[8]."','".$datos[9]."','".$datos[10]."','".$datos[11]."',NOW(),'1','".$datos[12]."')";
        $result=$this->execute_single_query();
        return $result;
    }

    public function listaSujetos($solicitante, $cp, $fi, $ff,$usuario){
        $this->query="SELECT * FROM t_sujeto 
                      WHERE fkUsuario $usuario
                        AND sNombreSolicita LIKE '%$solicitante%'
                        AND nCP LIKE '%$cp%'
                        AND dtFechaAvaluo BETWEEN '$fi' AND '$ff'
                        AND bDisponible = '1'
                        ORDER BY kSujeto DESC  LIMIT 100";
        $result=$this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }   

    public function mostrarSujeto($id){
        $this->query="SELECT * FROM t_sujeto WHERE bDisponible=1 AND kSujeto='".$id."'";
        $result=$this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function modificarSujeto($datos){
        $this->query="UPDATE t_sujeto SET snombreSolicita='".$datos[0]."', dtFechaAvaluo='".$datos[1]."', nObjetoAvaluo='".$datos[2]."', sDomicilio='".$datos[3]."', nCP='".$datos[4]."', nMTerreno='".$datos[5]."', nMConstruccion='".$datos[6]."', nAntiguedad='".$datos[7]."', nTipoPropiedad='".$datos[8]."', nTipoVivienda='".$datos[9]."', nEstadoConservacion='".$datos[10]."', nNotaGeneral='".$datos[11]."' WHERE kSujeto='".$datos[12]."'";
        $result=$this->execute_single_query();
        return $result;
    }   

    public function mostrarBusquedaUrl($url,$cliente,$fi,$ff,$usuario){
        //$datos=> sUrl, sNombreCliente, dtFecha (inicio), dtFecha (fin)
        $this->query="SELECT * FROM t_url 
                      WHERE fkUsuario $usuario
                      AND
                      sShortUrl LIKE '%$url%'
                      AND
                      sNombreCliente LIKE '%$cliente%'
                      AND
                      bDisponible = '1'
                      AND
                      dtFecha BETWEEN '$fi' AND '$ff'
                      AND bDisponible = '1'
                      ORDER BY kUrl DESC  LIMIT 100";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function vincularUrl($url,$sujeto){
        $this->query="INSERT INTO t_enlace_us (fkSujeto, fkUrl,bDisponible) VALUES ('".$sujeto."','".$url."',1)";
        $result=$this->execute_single_query();
        return $sujeto;
    }

    public function enlaces($sujeto){
        $this->query="SELECT * FROM t_enlace_us AS tE
                        INNER JOIN t_url AS tU ON tE.fkUrl = tU.kUrl
                        WHERE tE.fkSujeto='".$sujeto."' AND tE.bDisponible=1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function registrarImagen($datos){
        //t_url_img=>kSujetoImg, fkSujeto, sNameImg, bDisponible
        //$datos=> $kSujeto,$file	
        $this->query="
        INSERT INTO t_sujeto_img (fkSujeto, sNameImg, bDisponible)
        VALUES ('".$datos[0]."','".$datos[1]."','1')
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function fotos($sujeto){
        $this->query="SELECT * FROM t_sujeto_img WHERE fkSujeto='".$sujeto."' AND bDisponible=1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }
    
    public function agregarInformacion($datos){
        $this->query="
        INSERT INTO t_sujeto_info (fkSujeto, sTitulo, sInfo, bDisponible)
        VALUES ('".$datos[0]."','".$datos[1]."','".$datos[2]."','1')
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function mostrarInformacion($kSujeto){
        $this->query="SELECT * FROM t_sujeto_info WHERE fkSujeto='".$kSujeto."' AND bDisponible=1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function guardarHomologacion($sujeto,$datos){
        $this->query="
        INSERT INTO t_opinion_valor (fkSujeto,sOpinionValor,bDisponible) 
        VALUES ('".$sujeto."','".$datos."','1')";
        $result=$this->execute_single_query();
        return $result;
    }

    public function validarOpinion($sujeto){
        //validar si existe por lo menos un registro de opinion de valor
        $this->query="SELECT * FROM t_opinion_valor WHERE fkSujeto='".$sujeto."' AND bDisponible=1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;

    }

    public function datosCaracteristicas($kSujeto){
        $this->query="SELECT * FROM t_opinion_valor WHERE fkSujeto='".$kSujeto."'AND bDisponible=1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function actualizarHomologacion($sujeto,$datos){
		$this->query="
		UPDATE
		t_opinion_valor
		SET
		sOpinionValor='$datos'
		WHERE
		fkSujeto = '$sujeto'
		";
		$result = $this->execute_single_query();
		return $result;
    }

}
?>