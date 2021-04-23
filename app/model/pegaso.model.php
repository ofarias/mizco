<?php

require_once 'app/model/database.php';

/* Clase para hacer uso de database */

class pegaso extends database {
    /* Comprueba datos de login */

    function AccesoLogin($user, $pass) {
        $u = strtolower($user);
        $this->query = "SELECT MAX(ID) as numero FROM SEMANAS";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $actual = date('W');
        $anio = date('Y');
        $this->query = "SELECT ID, USER_LOGIN, USER_PASS, USER_ROL, LETRA, LETRA2, LETRA3, LETRA4, LETRA5, LETRA6, NUMERO_LETRAS, NOMBRE, CC, CR
						FROM PG_USERS
						WHERE USER_LOGIN = '$u' and USER_PASS = '$pass'";
        $log = $this->QueryObtieneDatos();
        if (count($log) > 0) {
            $_SESSION['user'] = $log;
            return $_SESSION['user'];
        } else {
            return 0;
        }
    }

///// Lista los pedidos PENDIENTES.
    function LPedidos() {
        $l = $_SESSION['user']->LETRA;
        $l2 = $_SESSION['user']->LETRA2;
        $l3 = $_SESSION['user']->LETRA3;
        $l4 = $_SESSION['user']->LETRA4;
        $l5 = $_SESSION['user']->LETRA5;
        $l6 = $_SESSION['user']->LETRA6;
        $n = $_SESSION['user']->NUMERO_LETRAS;
        if ($n == 1) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where status_ventas = 'Pe' and (letra_v = '$l') group by cotiza";
        } elseif ($n == 2) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where  status_ventas = 'Pe' and (letra_v = '$l' or letra_v = '$l2')
                          group by cotiza";
        } elseif ($n == 3) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where  status_ventas = 'Pe' and (letra_v = '$l' or letra_v = '$l2' or letra_v = '$l3)'
                          group by cotiza";
        } elseif ($n == 5) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where status_ventas = 'Pe' AND (letra_v = '$l' or letra_v = '$l2' or letra_v = '$l3' or letra_v = '$l4' or letra_v = '$l5')
                          group by cotiza";
        } elseif ($n == 6) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where status_ventas = 'Pe' AND (letra_v = '$l' or letra_v = '$l2' or letra_v = '$l3' or letra_v = '$l4' or letra_v = '$l5' or letra_v = '$l6')
                          group by cotiza";
        } elseif ($n == 99) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where status_ventas = 'Pe'
                          group by cotiza";
        }
        //status_ventas is null or (status_ventas ='') and '
        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
        }
        return 0;
    }

    /// LISTA TODOS LOS PEDIDOS.

    function LPedidosTodos() {

        $l = $_SESSION['user']->LETRA;
        $l2 = $_SESSION['user']->LETRA2;
        $l3 = $_SESSION['user']->LETRA3;
        $l4 = $_SESSION['user']->LETRA4;
        $l5 = $_SESSION['user']->LETRA5;
        $n = $_SESSION['user']->NUMERO_LETRAS;

        if ($n == 1) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where letra_v = '$l'
                          group by cotiza";
            echo $l . " " . $n;
        } elseif ($n == 2) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where letra_v = '$l' or letra_v = '$l2'
                          group by cotiza";
            echo $l;
        } elseif ($n == 3) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where letra_v = '$l' or letra_v = '$l2' or letra_v = '$l3'
                          group by cotiza";
            echo $l;
        } elseif ($n == 5) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where letra_v = '$l' or letra_v = '$l2' or letra_v = '$l3' or letra_v = '$l4' or letra_v = '$l5'
                          group by cotiza";
        } elseif ($n == 6) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          where letra_v = '$l' or letra_v = '$l2' or letra_v = '$l3' or letra_v = '$l4' or letra_v = '$l5' or letra_v = '$l6'
                          group by cotiza";
        } elseif ($n == 99) {
            $this->query = " SELECT cotiza, max(nom_cli) as cliente,max (urgente) as urgente, max(fechasol) as fecha, sum (cant_orig) as piezas, sum(ordenado) as Ordenado, count (prod) as productos, MAX(current_timestamp) as HOY, MAX(datediff(day, fechasol, current_date )) as Dias, MAX(FACTURA) as factura, max (importe) as importe
                          from preoc01 a
                          group by cotiza";
        }
        //status_ventas is null or (status_ventas ='') and '
        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
        }
        return 0;
    }

    /* consulta para mostrar los componentes dados de alta */

    function MuestraComp() {
        $this->query = "SELECT a.ID, a.SEG_NOMBRE, a.SEG_DURACION, a.SEG_TIPO, a.USUARIO, a.FECHAR_CREACION, a.FECHA_MODIFICACION, a.STATUS
						FROM PG_SEGCOMP a
						WHERE status = 'alta' ORDER BY a.SEG_NOMBRE ASC";
        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
        }

        return 0;
    }

    //// Inicia el Detalle del Pedido.

    function CabeceraPedidoDoc($doc) {

        $this->query = "SELECT a.CVE_DOC, b.nombre, a.fechaelab, a.can_tot, a.importe, current_timestamp as Hoy, datediff(day,a.fecha_doc, current_date ) as Dias
						from factp01 a left join clie01 b on a.cve_clpv = b.clave where cve_doc = '$doc'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function LoginA($user, $pass) {
        session_cache_limiter('private_no_expire');
        $data = new pegaso;
        $rs = $data->AccesoLogin($user, $pass);
        //var_dump($rs);
        if (count($rs) > 0) {
            $r = $data->CompruebaRol($user);
            //var_dump($r);
            switch ($r->USER_ROL) {
                case 'administrador':
                    $this->MenuAdmin();
                    break;
                case 'administracion':
                    $this->MenuAd();
                    break;
                case 'usuario':
                    $this->MenuUsuario();
                    break;
                case 'ventas':
                    $this->MenuVentas();
                    break;
                case 'compras':
                    $this->MenuCompras();
                    break;
                case 'recepcion':
                    $this->MenuRecep();
                    break;
                default:
                    $e = "Error en acceso 1, favor de revisar usuario y/o contraseña";
                    header('Location: index.php?action=login&e=' . urlencode($e));
                    exit;
                    break;
            }
            /* if($r->USER_ROL == 'administrador'){ /*Cambio el fetch_assoc cambia la forma en acceder al dato
              $this->MenuAdmin();
              }elseif($r->USER_ROL == 'administracion'){
              $this->MenuAd();
              }elseif($r->USER_ROL == 'usuario'){
              $this->MenuUsuario();
              }else{


              } */
        } else {
            $e = "Error en acceso 2, favor de revisar usuario y/o contraseña";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function DetallePedidoDoc($doc) {

        $this->query = "SELECT a.id, a.cotiza as pedido, a.nom_cli, a.urgente, a.fact_ant, a.fechasol, a.prod, a.nomprod,  a.cant_orig , a.status, b.cve_doc as Orden_de_Compra, b.CANT as Cant_Solicitada, b.status, a.rest as Falta_Solicitar, c.cve_doc as Recepcion, c.cant as Cant_Recibida, a.REC_faltante, c.status , d.cve_doc as factura, d.fechaelab as fecha_fac, d.importe, e.cve_doc as remision, e.fechaelab as Fecha_rem, f.tp_tes, f.ruta, f.unidad
                from preoc01 a
                left join par_compo01 b on a.id = b.id_preoc  and b.status <> 'C'
                left join compo01 f on b.cve_doc = f.cve_doc
                left join par_compr01 c on b.cve_doc = c.doc_ant and a.id = c.id_preoc and c.status <> 'C'
                left join factf01 d on  a.cotiza = d.doc_ant and d.status <> 'C'
                left join factr01 e on a.cotiza = e.doc_ant and e.status <> 'C'
                where cotiza = '$doc'";
        $result = $this->QueryObtieneDatosN();

        $result = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /// INICIA la Orden de compra, Cabecera y Detalles.
    function CabeceraDoc($doc) {
        $this->query = "select a.CVE_DOC, b.nombre, a.fechaelab, a.can_tot, a.importe, current_timestamp as Hoy, datediff(day,a.fecha_doc, current_date ) as Dias
						from compo01 a left join PROV01 b on a.cve_clpv = b.clave where cve_doc =  '$doc'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /// Detalles del documento

    function DetalleDoc($doc) {
        $this->query = "SELECT a.cve_art, b.descr, a.num_par, a.cant, a.pxr, a.cost, a.uni_venta, a.num_alm, a.tot_partida, a.doc_recep, a.doc_recep_status, a.fecha_doc_recep
						from par_compo01 a
						left join inve01 b on a.cve_art = b.cve_art
						where cve_doc = '$doc'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /// Asigna la Unidad o Asigna Ruta.

    function ARuta() {
        $this->query = "SELECT iif(a.docs is null, 'No', a.docs) as DOCS, a.cve_doc, b.nombre, a.fecha_pago, a.pago_tes, a.tp_tes, a.pago_entregado, c.camplib2 , a.unidad, a.estado, a.fechaelab, (datediff(day, a.fechaelab, current_date )) as Dias, a.urgencia, b.codigo, b.estado as estadoprov, a.vueltas
					    from compo01 a
						left join prov01 b on a.cve_clpv = b.clave
						left join compo_clib01 c on a.cve_doc = c.clave_doc
						where a.status <> 'C' AND status_log = 'Nuevo' and tp_tes is not Null ";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function InsertaNUnidad($numero, $marca, $modelo, $placas, $operador, $tipo, $tipo2, $coordinador) {
        //$idu = $this->obtieneidu();
        //$iduf = $idu + 1;
        $this->query = "INSERT INTO unidades (NUMERO, MARCA, MODELO, PLACAS, OPERADOR, TIPO, TIPO2, COORDINADOR)
                                VALUES ('$numero', '$marca', '$modelo', '$placas', '$operador','$tipo', '$tipo2', '$coordinador')";
        $rs = $this->EjecutaQuerySimple();
        //echo $rs;
        //unset($iduf);
        return $rs;
    }

    function altaunidades1($numero, $marca, $modelo, $placas, $operador, $tipo) {
        $idu = $this->obtieneidu();
        $iduf = $idu + 1;
        $this->query = "INSERT INTO unidades (IDU, NUMERO, MARCA, MODELO, PLACAS, OPERADO, TIPO) 					VALUES ($idu, '$numero', '$marca', '$modelo', '$placas', '$operador','$tipo')";
        $rs = $this->EjecutaQuerySimple();
        //echo $rs;
        unset($iduf);
        return $rs;
    }

    function EliminaUnidad($idu) {
        $this->query = "DELETE FROM unidades WHERE IDU = $idu";
        $rs = $this->EjecutaQuerySimple();
        //echo $rs;
        unset($iduf);
        return $rs;
    }

    function obtieneidu() {
        $this->query = "SELECT count(*) FROM unidades";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            return $tsArray->COUNT;
        }
    }

    function GuardaPagoCorrecto($cuentaban, $docu, $tipop, $monto, $nomprov, $cveclpv, $fechadoc) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $usuario = $_SESSION['user']->NOMBRE;
        if (substr($docu, 0, 1) == 'O') {
            $this->query = "UPDATE compo01
					  SET TP_TES = '$tipop', PAGO_TES = $monto, FECHA_PAGO = '$HOY', STATUS_PAGO = 'PP'
					  WHERE Trim(CVE_DOC) = trim('$docu')";
        } elseif (substr($docu, 0, 1 != 'O')) {
            $this->query = "UPDATE SOLICITUD_PAGO
						SET TP_TES_FINAL='$tipop' , MONTO_FINAL=$monto, BANCO_FINAL = '$cuentaban', fecha_reg_pago_final = current_date, fecha_pago = current_timestamp, status = 'Pagado', usuario_pago = '$usuario'
						where idsol =$docu";
            $rs = $this->EjecutaQuerySimple();
            $this->query = "SELECT MAX(FOLIO) as FOLIO FROM SOLICITUD_PAGO WHERE TP_TES_FINAL ='$tipop'";
            $res = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($res);
            $folioNuevo = $row->FOLIO + 1;
            $this->query = "UPDATE SOLICITUD_PAGO SET FOLIO = $folioNuevo where idsol = $docu";
            $result = $this->EjecutaQuerySimple();
            return $rs;
        } else {
            $this->query = "UPDATE compR01
					  SET TP_TES = '$tipop', PAGO_TES = $monto, FECHA_PAGO = '$HOY', STATUS_PAGO = 'PP'
					  WHERE Trim(CVE_DOC) = trim('$docu')";
        }
        $rs = $this->EjecutaQuerySimple();
        $rs += $this->ActPagoParOC($docu, $tipop, $monto, $nomprov, $cveclpv, $fechadoc, $cuentaban);
        $rs += $this->GuardaCuentaBan($docu, $cuentaban);
        return $rs;
    }

    function GuardaCuentaBan($docu, $cuentaban) {
        $this->query = "INSERT INTO
							pg_pagoBanco
							(documento, cuenta, Banco)
						VALUES
							('$docu', '$cuentaban',TRIM(SUBSTRING('$cuentaban' from 1 for 8)))";
        $rs = $this->EjecutaQuerySimple();
    }

    /// Insertar Pagos a tablas P_CHEQUES
    function ActPagoParOC($docu, $tipop, $monto, $nomprov, $cveclpv, $fechadoc, $cuentaban) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $iva = $monto - ($monto / 1.16);
        $usuario = $_SESSION['user']->NOMBRE;

        if ($tipop == 'ch') {
            $query = "INSERT INTO P_CHEQUES (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, CHEQUE, USUARIO_PAGO, BANCO) VALUES (";
            $query .= " '" . $tipop . "',";
            $query .= " '" . $fechadoc . "',";
            $query .= " '" . $monto . "',";
            $query .= " '" . $nomprov . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docu . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpv . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0',";
            $query .= " '$usuario',";
            $query .= " '$cuentaban'";
            $query .= ")";
            //echo $query;
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        } elseif ($tipop == 'tr') {

            $query = "INSERT INTO P_TRANS (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, TRANS, USUARIO_PAGO, BANCO) VALUES (";
            $query .= " '" . $tipop . "',";
            $query .= " '" . $fechadoc . "',";
            $query .= " '" . $monto . "',";
            $query .= " '" . $nomprov . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docu . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpv . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0',";
            $query .= " '$usuario',";
            $query .= " '$cuentaban'";
            $query .= ")";
            //echo $query;
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        } elseif ($tipop == 'cr') {
            $query = "INSERT INTO P_CREDITO (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, CREDITO) VALUES (";
            $query .= " '" . $tipop . "',";
            $query .= " '" . $fechadoc . "',";
            $query .= " '" . $monto . "',";
            $query .= " '" . $nomprov . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docu . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpv . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0'";
            $query .= ")";
            //echo $query;
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        } elseif ($tipop == 'e') {
            $query = "INSERT INTO P_EFECTIVO (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, EFECTIVO, USUARIO_PAGO, BANCO) VALUES (";
            $query .= " '" . $tipop . "',";
            $query .= " '" . $fechadoc . "',";
            $query .= " '" . $monto . "',";
            $query .= " '" . $nomprov . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docu . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpv . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0',";
            $query .= " '$usuario',";
            $query .= " '$cuentaban'";
            $query .= ")";
            //Secho $query;
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        }
    }

    function TraeUnidades() {
        $this->query = "SELECT numero
						FROM unidades";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function detallePago($documento) {

        if (substr($documento, 0, 1) != 'O') {
            $this->query = " SELECT s.idsol as cve_doc, p.nombre, s.monto as importe, s.fechaelab, s.fecha as fecha_doc, 'Contrarecibos' as Recepcion, 'NA' as enlazado, s.tipo as TipoPagoR, 'NA' as FER, 'NA' as TE, usuario as Confirmado, s.tipo as PagoTesoreria, 'NA' as pago_tes
								from SOLICITUD_PAGO s
								left join prov01 p on s.proveedor = p.clave
								where idsol = $documento";
        } else {
            $this->query = "	SELECT a.cve_doc, b.nombre, a.importe, a.fechaelab, a.fecha_doc, doc_sig as Recepcion, a.enlazado, c.camplib1 as TipoPagoR, c.camplib3 as FER,c.camplib2 as TE, c.camplib4 as Confirmado, a.tp_tes as PagoTesoreria, a.pago_tes, pago_entregado, c.camplib6, a.cve_clpv, a.URGENTE, datediff(day, a.fechaelab, current_date ) as Dias
						from compo01 a
						left join Prov01 b on a.cve_clpv = b.clave
						LEFT JOIN compo_clib01 c on a.cve_doc = c.clave_doc
						where cve_doc = '$documento'";
        }

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function CompruebaRol($user) {
        $this->query = "SELECT USER_ROL FROM PG_USERS WHERE USER_LOGIN = '$user'"; /* Falta Tabla */
        $log = $this->QueryObtieneDatos();
        if (count($log) > 0) {
            return $log;
        } else {
            return 0;
        }
    }

    function ObtieneReg() {
        $this->query = "SELECT COUNT(*)
  						FROM PG_USERS";

        $r = $this->QueryObtieneDatos();

        return $r;
    }

    function ObtieneRegSC() {
        $this->query = "SELECT COUNT(*)
  						FROM PG_SEGCOMP";

        $r = $this->QueryObtieneDatos();

        return $r;
    }

    function AsignadosComp() {
        $this->query = "SELECT CVE_DOC, CVE_CLPV, IMPORTE, ID_SEG, STATUS_FACT
						FROM FACTF01
						WHERE STATUS_FACT = 'a'";
        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
        }

        return 0;
    }

    function NuevoUser($usuario, $contra, $email, $rol, $id, $letra) {
        $fecha = date('m-d-Y'); /* Fechas en firebird siempre comienzan con MM/DD/AAAA */
        $u = strtolower($usuario);
        $e = strtolower($email);
        //echo $fecha;
        $this->query = "INSERT INTO PG_USERS VALUES ($id, '$u', '$contra', '$e', '$fecha', 'alta', '$rol', '$letra')";
        $rs = $this->EjecutaQuerySimple();
        //echo $rs;
        return $rs;
    }

    /* ###################################Cambios de OFA################################### */

    // INSERTA LAS PARTIDAS DE LA ORDEN DE COMPRA
    function NuevaPartidaOrdenCompra($Doc, $IdPreoco, $Rest, $Prod, $Cantidad, $Costo, $unimed, $facconv, $cveuser) {
        //echo $partida ="<br/>".$CVE_DOC."<br/>".$TOTAL."<br/>".$TIME."<br/>HOY=".$HOY."<br/>".$IdPreoco."<br/>".$Consecutivo."<br/>".$Doc."<br/>".$Prod."<br/>".$Costo."<br/>".$unimed."<br/>".$facconv."<br/>".$Cantidad."<br/>".$Rest."<br/>".$consecutivo2;
        $Costoa = $Costo / 1.16;
        $nuevoRest = $Rest - $Cantidad;
        if ($nuevoRest <= 0) {
            $status = 'B';
        } else {
            $status = 'N';
        }
        $totalPartida = $Cantidad * $Costo;
        if ($Rest == NULL || $Rest == '') {
            $Rest = 0;
        }

        $a = " UPDATE PREOC01 set status='" . $status . "', rest='" . $nuevoRest . "'  WHERE ID= $IdPreoco ";
        //echo "Actualiza Pre Orden de compra: $a";

        $this->query = $a;
        $rs = $this->EjecutaQuerySimple();

        $consultPartMAX = " SELECT COUNT(NUM_PAR) as FOLIO FROM PAR_COMPO01 WHERE CVE_DOC_REAL='" . $Doc . "'";
        // echo "sql: $consultPartMAX";
        $this->query = $consultPartMAX;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        //echo "row: ".$row->FOLIO;
        $COD = $row->FOLIO;
        $COD = $COD + 1;
        $CVEDOCCOMPUESTO = $Doc . '000' . $COD;

        $partida = " INSERT INTO PAR_COMPO01 (CVE_DOC, NUM_PAR, CVE_ART,CANT, PXR, PREC, COST, IMPU1,IMPU2, IMPU3, IMPU4, IMP1APLA,";
        $partida .= " IMP2APLA, IMP3APLA, IMP4APLA,TOTIMP1, TOTIMP2, TOTIMP3, TOTIMP4,DESCU, ACT_INV, TIP_CAM, UNI_VENTA,TIPO_ELEM, TIPO_PROD, CVE_OBS, E_LTPD,";
        $partida .= " REG_SERIE, FACTCONV, COST_DEV, NUM_ALM,MINDIRECTO, NUM_MOV, TOT_PARTIDA,CVE_DOC_REAL, ID_PREOC, USUARIO_PHP) VALUES (";
        $partida .= " '" . $Doc . "',";
        $partida .= " " . $COD . ", ";
        $partida .= " '" . $Prod . "',";
        $partida .= " " . $Cantidad . " ,";
        $partida .= " " . $Cantidad . " ,";
        $partida .= " 99, ";
        $partida .= " " . $Costoa . ", ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 16, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 'N', ";
        $partida .= " 1, ";
        $partida .= " '" . $unimed . "', ";
        $partida .= " 'N', ";
        $partida .= " 'P', ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " 0, ";
        $partida .= " " . $facconv . ", ";
        $partida .= " NULL, ";
        $partida .= " 95, ";
        $partida .= " NULL,";
        $partida .= " NULL, ";
        $partida .= " '" . $totalPartida . "',";
        $partida .= " '" . $Doc . "',";
        $partida .= " '" . $IdPreoco . "',";
        $partida .= " '" . $cveuser . "')";

        $this->query = $partida;
        $s = $this->EjecutaQuerySimple();

        $consultPart = " SELECT SUM(TOT_PARTIDA) TOT FROM PAR_COMPO01 WHERE CVE_DOC_REAL='" . $Doc . "' ";
        $this->query = $consultPart;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $SUMATOTALPARTIDAS = $row->TOT;

        $urgente = "SELECT URGENTE from PREOC01 where id = '" . $IdPreoco . "'";
        $this->query = $urgente;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $urgentes = $row->URGENTE;

        if ($urgentes == 'U') {
            $esurgente = "UPDATE COMPO01 SET URGENTE = 'U' WHERE CVE_DOC ='" . $Doc . "'";
            $this->query = $esurgente;
            $result = $this->EjecutaQuerySimple();
        }
    }

    //INSERTA ORDEN DE COMPRA
    //function NuevoOrdComp($PROVEEDOR,$CVE_DOC,$TOTAL,$TIME,$HOY,$IdPreoco,$Consecutivo,$Doc,$Prod,$Costo,$unimed,$facconv,$Cantidad,$Rest){
    function NuevoOrdComp($PROVEEDOR, $CVE_DOC, $TOTAL, $TIME, $HOY, $Doc) {

        $Control = $PROVEEDOR . 'A';

        $consultFOLIO = " SELECT MAX(folio) FOLIO  FROM COMPO01";
        $this->query = $consultFOLIO;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $FOLIO = $row->FOLIO;
        $FOL = $FOLIO + 1;
        //echo "folio=".$FOL;
        //echo "CVE_DOC=".$CVE_DOC;
        $cveuser = $_SESSION['user']->USER_LOGIN;
        $nombre = "SELECT NOMBRE FROM PG_USERS WHERE User_login = '$cveuser'";
        $this->query = $nombre;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $NOM_USUARIO = $row->NOMBRE;

        $PEDID = substr($CVE_DOC, 0, 2);
        //echo "PEDIDO=".$PEDID;

        $NUEVACVEDOC = $PEDID . $FOL;
        $HOYY = date("Y-m-d");
        //list($day,$mon,$year) = explode('-',$HOYY);
        //$oldDate =date('d-m-Y',mktime(0,0,0,$mon,$day+30,$year));
        //$arr = explode('-', $oldDate);
        //$newDate30 = $arr[2].'-'.$arr[1].'-'.$arr[0];
        $PROVV = trim($PROVEEDOR);
        $pp = str_pad($PROVV, 10, " ", STR_PAD_LEFT);

        $cabecera = " INSERT INTO COMPO01 (";
        $cabecera .= "	 TIP_DOC, CVE_DOC, CVE_CLPV, STATUS,";
        $cabecera .= "	 SU_REFER, FECHA_DOC, FECHA_REC, FECHA_PAG,";
        $cabecera .= "	 FECHA_CANCELA, CAN_TOT,";
        $cabecera .= "	 IMP_TOT1, IMP_TOT2, IMP_TOT3, IMP_TOT4,";
        $cabecera .= "	 DES_TOT, DES_FIN, TOT_IND,";
        $cabecera .= "	 OBS_COND, CVE_OBS, NUM_ALMA, ACT_CXP, ACT_COI,";
        $cabecera .= "	 NUM_MONED, TIPCAMB, ENLAZADO, TIP_DOC_E, NUM_PAGOS,";
        $cabecera .= "	 FECHAELAB, SERIE, FOLIO, CTLPOL, ESCFD, CONTADO, BLOQ,";
        $cabecera .= "	 DES_FIN_PORC, DES_TOT_PORC, IMPORTE, TIP_DOC_ANT,";
        $cabecera .= "	 DOC_ANT, TIP_DOC_SIG, DOC_SIG, FORMAENVIO,CONTROL, STATUS_LOG, REALIZA)";
        $cabecera .= "	VALUES ( ";
        $cabecera .= "	'o', ";
        $cabecera .= "	'" . $NUEVACVEDOC . "', ";
        $cabecera .= "	'" . $pp . "',";
        $cabecera .= "	'O', ";
        $cabecera .= "	'', ";
        $cabecera .= "	'" . $HOYY . "', ";
        $cabecera .= "	'" . $HOYY . "', ";
        $cabecera .= "	'" . $HOYY . "', "; //mas 30 dias $nuevafecha = strtotime ( '+1 day' , strtotime ($fechaFFase ) ) ;
        $cabecera .= "	NULL, ";
        //$cabecera  .="	".$SUMATOTALPARTIDAS.", "; //cantidad total suma
        $cabecera .= "	'22', "; //cantidad total suma
        $cabecera .= "	0, ";
        $cabecera .= "	0, ";
        $cabecera .= "	0, ";
        $cabecera .= "	2, "; //totaliva
        $cabecera .= "	0, ";
        $cabecera .= "	0, ";
        $cabecera .= "	0, ";
        $cabecera .= "	'', ";
        $cabecera .= "	0, ";
        $cabecera .= "	95, ";
        $cabecera .= "	'S', ";
        $cabecera .= "	'N', ";
        $cabecera .= "	1, ";
        $cabecera .= "	1, ";
        $cabecera .= "	'O',";
        $cabecera .= "	'O', ";
        $cabecera .= "	NULL,";
        $cabecera .= "	'" . $HOY . "', ";
        $cabecera .= "	'OZ', "; //rest
        //$cabecera  .="	'".$Consecutivo."', ";
        $cabecera .= "	'" . $FOL . "', ";
        $cabecera .= "	0, ";
        $cabecera .= "	'N', ";
        $cabecera .= "	'N', ";
        $cabecera .= "	'N', ";
        $cabecera .= "	0, ";
        $cabecera .= "	0, ";
        $cabecera .= "	0, "; //suma total
        $cabecera .= "	'', ";
        $cabecera .= "	'', ";
        $cabecera .= "	NULL, ";
        $cabecera .= "	NULL, ";
        $cabecera .= "	NULL, ";
        $cabecera .= "	'" . $Control . "',";
        $cabecera .= "  'Nuevo',";
        $cabecera .= "	'" . $NOM_USUARIO . "'";
        $cabecera .= "	) ";
        //echo $cabecera;
        $this->query = $cabecera;
        $rs = $this->EjecutaQuerySimple();

        $paga = " INSERT INTO PAGA_M01 (";
        $paga .= " CVE_PROV, REFER, NUM_CARGO, NUM_CPTO, CVE_FOLIO, CVE_OBS, NO_FACTURA,";
        $paga .= " DOCTO, IMPORTE, FECHA_APLI, FECHA_VENC, AFEC_COI, NUM_MONED, TCAMBIO,";
        $paga .= " IMPMON_EXT, FECHAELAB, CTLPOL, TIPO_MOV, CVE_BITA, SIGNO, CVE_AUT, ";
        $paga .= " USUARIO, ENTREGADA, FECHA_ENTREGA, REF_SIST, STATUS)";
        $paga .= " VALUES (";
        $paga .= " '" . $PROVEEDOR . "',";
        $paga .= " '" . $NUEVACVEDOC . "',";
        $paga .= " 1,24,'',0,";
        $paga .= " '" . $NUEVACVEDOC . "',";
        $paga .= " '" . $NUEVACVEDOC . "',";
        $paga .= " " . $TOTAL . ",";
        $paga .= " '" . $HOYY . "',";
        $paga .= " '" . $HOYY . "', ";
        $paga .= " '', 1, 1, ";
        $paga .= " " . $TOTAL . ",";
        $paga .= " '" . $HOYY . "',";
        $paga .= " 0, 'C', 0, 1, 0, 0, '',";
        $paga .= " '" . $HOYY . "',";
        $paga .= " '', 'A')";
        $this->query = $paga;
        $s = $this->EjecutaQuerySimple();

        //$a=" UPDATE PREOC01 set status=Ω".$status."', rest='".$nuevoRest."'  WHERE ID= $IdPreoco ";
        //$this->query = $a;
        //$rs = $this->EjecutaQuerySimple();

        return $NUEVACVEDOC;
    }

    // actualiza totales en orden de compra
    function actualizaTotalPaga($proveedor, $documento, $importe) {

        $query = "UPDATE PAGA_M01 SET IMPORTE = $importe WHERE DOCTO = '$documento' AND CVE_PROV = '$proveedor';";
        //echo "query: $query";
        $this->query = $query;
        $result = $this->EjecutaQuerySimple();
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    // actualiza totales en orden de compra
    function actualizaTotalOrdenCompra($documento, $cantidad, $impuesto, $importe) {

        $query = "UPDATE COMPO01 SET CAN_TOT = $cantidad, IMP_TOT4 = $impuesto, IMPORTE = $importe WHERE CVE_DOC = '$documento'";
        //echo "query: $query";
        $this->query = $query;
        $result = $this->EjecutaQuerySimple();
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    //ORDEN DE COMPRA
    function ConsultaOrdenComp($id) {
        $hoy = date("d.m.y");
        switch ($id) {

            case '1':
                // OFA $this->query ="SELECT cve_doc, status, documento AS cotizacion, cve_art, cant, b.camplib3 AS COSTO, b.camplib2 AS Proveedor, b.camplib1 AS CODIGO, b.camplib3 * a.cant AS Total FROM par_factp01 a LEFT JOIN par_factp_clib01 b ON a.cve_doc = b.clave_doc AND a.num_par = b.num_part  WHERE a.status='E' ";
                $this->query = "select id, fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,date '$hoy') as DIAS
								from preoc01 WHERE status='N' and rest > 0 and rec_faltante > 0 ORDER BY  nom_prov  ASC ";
                break;
            case '2':
                $this->query = "select id, fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti,cant_orig,costo, prove, nom_prov, total,rest,docorigen, urgente, um, b.camplib8 as categoria
							from preoc01 a left join inve_clib01 b on b.cve_prod = a.prod
							where status='N' and rest > 0 and rec_faltante > 0 and (b.camplib8 = 'JARCIERIA Y SEGURIDAD INDUSTRIAL' or b.camplib8 = 'ACEROS Y PERFILES') ORDER BY  nom_prov  ASC";
                break;
            case '3':
                $this->query = "select id,fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti, cant_orig,costo, prove, nom_prov, total,rest,docorigen, urgente, um, b.camplib8 as categoria
							from preoc01 a left join inve_clib01 b on b.cve_prod = a.prod
							where status='N' and rest > 0 and rec_faltante > 0 and (b.camplib8 = 'PLOMERIA' or b.camplib8 = 'FERRETERIA' or camplib8 = 'HERRAMIENTA MANUAL') ORDER BY  nom_prov  ASC";
                break;
            case '4':
                $this->query = "select id, fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti, cant_orig,costo, prove, nom_prov, total,rest,docorigen, urgente, um, b.camplib8 as categoria
							from preoc01 a left join inve_clib01 b on b.cve_prod = a.prod
							where status='N' and rest > 0 and rec_faltante > 0 and (b.camplib8 = 'ADHESIVOS' or b.camplib8 = 'CERRAJERIA Y HERRAJES' or b.camplib8 = 'MEDICION') ORDER BY  nom_prov  ASC";
                break;
            case '5':
                $this->query = "select id, fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti, cant_orig,costo, prove, nom_prov, total,rest,docorigen, urgente, um, b.camplib8 as categoria
							from preoc01 a left join inve_clib01 b on b.cve_prod = a.prod
							where status='N' and rest > 0 and rec_faltante > 0 and (b.camplib8 = 'CONSTRUCCION Y PINTURAS' or b.camplib8 = 'FIJACION Y SOPORTE') ORDER BY  nom_prov  ASC";
                break;
            case '6':
                $this->query = "select id, fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti, cant_orig,costo, prove, nom_prov, total,rest,docorigen, urgente, um, b.camplib8 as categoria
							from preoc01 a left join inve_clib01 b on b.cve_prod = a.prod
							where status='N' and rest > 0 and rec_faltante > 0 and (b.camplib8 = 'HERRAMIENTA ELECTRICA' or b.camplib8 = 'ACCESORIOS Y CONSTRUCCION DE HERRAM' or b.camplib8 = 'ELECTRICO') ORDER BY  nom_prov  ASC";
                break;
            case '7':
                $this->query = "select id, fechasol, fecha_auto, cotiza, par, status, prod, nomprod, canti, cant_orig,costo, prove, nom_prov, total,rest,docorigen, urgente, um, b.camplib8 as categoria
							from preoc01 a left join inve_clib01 b on b.cve_prod = a.prod
							where status='N' and rest > 0 and rec_faltante > 0 and urgente = 'U' ORDER BY  nom_prov  ASC";
                break;
        }


        $result = $this->QueryObtieneDatosN();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
        }

        return 0;
    }

    // Listar partidas no recibidad

    function ListaPartidasNoRecibidas() {
        //$this->query = "SELECT a.ID_PREOC, a.CVE_DOC, a.PXR, a.CVE_ART, b.REC_faltante from par_compo01 a left join preoc01 b on a.id_preoc = b.id where Doc_Recep is not null and pxr > 0";
        $this->query = "SELECT a.ID_PREOC, a.cve_doc, a.cve_art, d.camplib7, a.cant, a.pxr, c.nombre, a.fecha_doc_recep, a.doc_recep, a.DOC_RECEP_STATUS, a.vuelta
			from par_compo01 a
			left join compo01 b on a.cve_doc = b.cve_doc and b.status <> 'C'
			left join prov01 c on b.cve_clpv = c.clave
			left join inve_clib01 d on a.cve_art = d.cve_prod
			left join preoc01 e on a.id_preoc = e.id
			where Doc_Recep is not null and pxr > 0 and doc_recep_status <>'C' and (b.status_log2 = 'F' or b.status_log2= 'Suministros') and b.fechaelab >= '01.06.2016'";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    //ACTUALIZA actualizaPreOc
    function actualizaPreOc($provcostid, $provedor, $costo, $total, $nombreprovedor, $cantidad, $rest) {

        $query = " UPDATE PREOC01 SET";
        $query .= " prove=$provedor,";
        $query .= " costo=$costo,";
        $query .= " total=$total,";
        $query .= " canti=$cantidad,";
        $query .= " nom_prov='" . $nombreprovedor . "'";
        $query .= " WHERE id=$provcostid ";
        $this->query = $query;
        $result = $this->EjecutaQuerySimple();
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    //SABER SI EL PROVEEDOR EXISTE
    function valorPreOcProvedor($provedor) {
        $query = " SELECT NOMBRE FROM PROV01 WHERE";
        $query .= " CLAVE=$provedor ";
        $query .= " AND STATUS='A' ";
        $this->query = $query;
        $result = $this->EjecutaQuerySimple();
        while ($row = ibase_fetch_object($result)) {
            $nom = $row->NOMBRE;
            return $nom;
        }
    }

    //SABER SI EL UNI_MED DEL ARTICULO EXISTE
    function valorArticulo($Prod) {
        //unset($$_SESSION["unimed"]);
        $query = " SELECT uni_med,fac_conv FROM INVE01 WHERE";
        $query .= " cve_art='" . $Prod . "' ";
        $query .= " AND STATUS='A' ";

        $this->query = $query;
        $result = $this->EjecutaQuerySimple();
        while ($row = ibase_fetch_object($result)) {
            $nom = $row->UNI_MED;
            $fac = $row->FAC_CONV;
            return $nom . '|' . $fac;
        }
    }

    /* ############################Cambios de OFA#################################### */

    //// Consulta donde muesta la informacion de los pedidos, desde la pagina de Buscar Pedidos, primer parte es la cabecera
    function ConsultaPreoc($pre) {
        $l = $_SESSION['user']->LETRA;
        $u = $_SESSION['user']->USER_LOGIN;
        $n = $_SESSION['user']->NUMERO_LETRAS;

        if ($n < 99) {
            $this->query = "SELECT a.*, FACTURAS as factura, b.fechaelab as fecha_fac, c.cve_doc as remision, c.fechaelab as fecha_rem
						FROM preoc01 a
						left join factf01 b on a.cotiza = b.doc_ant
						left join factp01 c on a.cotiza = c.doc_ant
						WHERE cotiza = '" . strtoupper($pre) . "'";
        } elseif ($n == 99) {
            $this->query = "SELECT a.*, Facturas as factura, b.fechaelab as fecha_fac, c.cve_doc as remision, c.fechaelab as fecha_rem
						FROM preoc01 a
						left join factf01 b on a.cotiza = b.doc_ant
						left join factp01 c on a.cotiza = c.doc_ant
						WHERE cotiza = '" . strtoupper($pre) . "'";
        }
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /// Segunda parte de Buscar Pedidos Detalle del pedido.
    function ConsultaMov($mov) {
        $l = $_SESSION['user']->LETRA;
        $u = $_SESSION['user']->USER_LOGIN;
        $n = $_SESSION['user']->NUMERO_LETRAS;
        if ($n == 1) {
            $this->query = "SELECT a.id, a.cotiza as pedido, a.nom_cli, a.urgente, a.fact_ant, a.fechasol, a.prod, a.nomprod,  a.cant_orig , a.status,
						b.cve_doc as Orden_de_Compra, b.CANT as CANT_SOLICITADA, b.status, a.rest as Falta_Solicitar, c.cve_doc as Recepcion,
						c.cant as Cant_Recibida, a.REC_faltante, c.status , d.cve_doc, d.fechaelab, d.importe, e.fechaelab as fecha_oc, f.fechaelab as fecha_r,
						e.tp_tes, e.ruta, e.unidad, e.fecha_secuencia, e.status_log, e.cant_rec
            	from preoc01 a
            	left join par_compo01 b on a.id = b.id_preoc  and b.status <> 'C'
            	left join compo01 e on b.cve_doc = e.cve_doc
            	left join par_compr01 c on b.cve_doc = c.doc_ant and b.id_preoc = c.id_preoc and c.status <> 'C'
            	left join compr01 f on c.cve_doc = f.cve_doc
            	left join factf01 d on  a.cotiza = d.doc_ant and d.status <> 'C'
				WHERE COTIZA = ('" . strtoupper($mov) . "')";
            //var_dump($this->query);
        } elseif ($n > 1) {
            $this->query = "SELECT a.id, a.cotiza as pedido, a.nom_cli, a.urgente, a.fact_ant, a.fechasol, a.prod, a.nomprod,  a.cant_orig , a.status,
						b.cve_doc as Orden_de_Compra, b.CANT as CANT_SOLICITADA, b.status, a.rest as Falta_Solicitar, c.cve_doc as Recepcion,
						c.cant as Cant_Recibida, a.REC_faltante, c.status , d.cve_doc, d.fechaelab, d.importe, e.fechaelab as fecha_oc, f.fechaelab as fecha_r,
						e.tp_tes, e.ruta, e.unidad, e.fecha_secuencia, e.status_log, e.cant_rec
            	from preoc01 a
            	left join par_compo01 b on a.id = b.id_preoc  and b.status <> 'C'
            	left join compo01 e on b.cve_doc = e.cve_doc
            	left join par_compr01 c on b.cve_doc = c.doc_ant and b.id_preoc = c.id_preoc and c.status <> 'C'
            	left join compr01 f on c.cve_doc = f.cve_doc
            	left join factf01 d on  a.cotiza = d.doc_ant and d.status <> 'C'
				WHERE COTIZA ='" . strtoupper($mov) . "'";
        }
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function InsertaCompo($nombre, $duracion, $tipo, $usuario) {
        //$usuario = $_SESSION['user']; antes de insertar tomar el ultimo valor de id sumarle 1 e insertar
        $user = $_SESSION['user']["USER_LOGIN"];
        //print_r($user);
        $fecha = date('d-m-Y');
        $rs = $this->ObtieneRegSC();
        $id = (int) $rs["COUNT"] + 1;
        $this->query = "INSERT INTO PG_SEGCOMP VALUES ($id, '$nombre', '$duracion', '$tipo', '$user', '$fecha', '$fecha', 'alta')";
        $result = $this->EjecutaQuerySimple();
        //print_r($result);
        return $result;
    }

    function ConsultaUsur() {
        $this->query = "SELECT a.ID, a.USER_LOGIN, a.USER_EMAIL, a.USER_STATUS, a.USER_ROL
						FROM PG_USERS a";

        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
            unset($data);
        }

        return 0;
    }

    function ConsultaUsurEmail($email) {
        $this->query = "SELECT a.ID, a.USER_LOGIN, a.USER_EMAIL, a.USER_STATUS, a.USER_ROL
							FROM PG_USERS a WHERE a.USER_EMAIL = '$email'";

        //unset($data);
        $result = $this->QueryObtieneDatos();
        //var_dump($result);
        //if($this->NumRows($result) > 0){
        //while ( $tsArray = $result) {
        //echo "dentro del while";
        $data[] = $result;
        //}
        //var_dump($data);
        return $data;
        //unset($data1);
        //}

        return 0;
    }

    function ActualizaStatusSegdoc($compo) {
        $this->query = "UPDATE PG_SEGDOC
						SET ESTATUS = 1
						WHERE ID = $compo";
        $result = $this->EjecutaQuerySimple();
        if (count($result) > 0) {
            $d = $this->ConsultaUsur();
            return $d;
        } else {
            return 0;
        }
    }

    function ActualizaFactf($factura, $compo) {
        $this->query = "UPDATE FACTF01
						SET STATUS_FACT = 'a', ID_SEG = '$compo'
						WHERE CVE_DOC = '$factura'";
        $result = $this->EjecutaQuerySimple();
        if (count($result) > 0) {
            $this->ActualizaStatusSegdoc($compo);
            return $result;
        } else {
            return 0;
        }
    }

    function ActualizaUsr($mail, $usuario, $contrasena, $email, $rol, $estatus) {
        $this->query = "UPDATE PG_USERS
						SET USER_LOGIN = '$usuario', USER_PASS = '$contrasena', USER_EMAIL = '$email', USER_ROL = '$rol', USER_STATUS = '$estatus'
						WHERE USER_EMAIL = '$mail'"; /* actualizamos datos y retornamos ConsultaUsur() */

        $result = $this->EjecutaQuerySimple();
        //var_dump($result);
        if (count($result) > 0) {
            $d = $this->ConsultaUsur();
            return $d;
        } else {
            return 0;
        }
    }

    function ObtieneRegIC() {
        $this->query = "SELECT COUNT(*)
  						FROM PG_SEGDOC";

        $r = $this->QueryObtieneDatos();

        return $r;
    }

    /* metodo para mostrar facturas sin asignar */

    function insertaDocumentoXML($documento, $archivo, $archivoPDF, $emisorRFC, $emisorNombre, $receptorRFC, $receptorNombre, $fecha, $uuid, $importe) {
        $this->query = "INSERT INTO COMP_XML (RFC_E, NOMBRE_E, RFC_R, NOMBRE_R, FECHA_TIM, UUIDC, IMPORTE, ARCHIVO, PDF, OC)
                        VALUES ('$emisorRFC', '$emisorNombre', '$receptorRFC', '$receptorNombre', '$fecha', '$uuid', $importe, '$archivo', '$archivoPDF', '$documento')";
        $result = $this->EjecutaQuerySimple();


        $result += $this->actualizaComprobado($documento, $importe);
        return $result > 0;
    }

    function actualizaComprobado($documento, $total) {
        $porComprobar = 0;
        $this->query = "SELECT POR_COMPROBAR, COMPROBADO FROM compo01 WHERE CVE_DOC = '$documento'";
        $result = $this->EjecutaQuerySimple();
        while ($row = ibase_fetch_object($result)) {
            $porComprobar = $row->POR_COMPROBAR;
            $comprobado = $row->COMPROBADO;
        }
        if ($porComprobar > $total) {
            $Comprobado = $comprobado + $total;
            $por_comprobar = $porComprobar - $total;
            $this->query = "UPDATE compo01 SET status_compra = 'Co', comprobado = $Comprobado, por_comprobar = $por_comprobar WHERE cve_doc = '$documento'";
        } elseif ($porComprobar == $total) {
            $comprobado = $porComprobar + $total;
            $this->query = "UPDATE compo01 SET status_compra = 'CC', comprobado = $comprobado, por_comprobar = $por_comprobar WHERE cve_doc = '$documento'";
        } else {
            return false;
        }
        echo $por_comprobar;
        $updated = $this->EjecutaQuerySimple();
        $vuelta = count($updated);
        return $vuelta > 0;
    }

    function validaEmisor($documento, $emisorRFC) {
        $this->query = "SELECT a.cve_doc, b.nombre, b.rfc FROM compo01 a LEFT JOIN prov01 b on a.cve_clpv = b.clave WHERE cve_doc = '$documento' AND b.rfc = '$emisorRFC'";
        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            return true;
        }
        return false;
    }

    function verOrdenes() {
        $this->query = "SELECT a.cve_doc, a.enlazado, a.folio, a.status, a.fechaelab, datediff(day, a.fechaelab, current_date ) as Dias, can_tot, cve_clpv, Nombre, a.TP_TES, a.fecha_pago
						from compo01 a
						left join Prov01 b on a.cve_clpv = b.clave
						where a.enlazado <> 'T' and a.status <>'C'";
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function OC($doco) {
        $this->query = "SELECT a.cve_doc, a.enlazado, a.folio, a.status, a.fechaelab, datediff(day, a.fechaelab, current_date ) as Dias, can_tot, cve_clpv, Nombre, c.CAMPLIB2
						from
						(compo01 a
						LEFT JOIN compo_clib01 c
						ON a.cve_doc = c.clave_doc)
						left join Prov01 b on a.cve_clpv = b.clave
						where a.cve_doc = '$doco'";
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function OCL($doco) {
        $this->query = "SELECT a.cve_doc, a.enlazado, a.folio, a.status, a.fechaelab, datediff(day, a.fechaelab, current_date ) as Dias, can_tot, cve_clpv, Nombre, b.calle, b.numext, b.numint, b.colonia, b.codigo, b.municipio, b.telefono, c.camplib4, c.camplib2,  a.realiza, c.camplib3, d.str_obs
						from compo01 a
						left join Prov01 b on a.cve_clpv = b.clave
						left join COMPO_CLIB01 c on a.cve_doc = c.clave_doc
						left join  obs_docc01 d on a.cve_obs = d.cve_obs
						where cve_doc = '$doco'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function detalleOC($doco) {
        $this->query = "SELECT a.id_preoc, a.cve_doc, a.num_par, a.cve_art, b.descr,  a.cant, a.pxr, a.TOT_PARTIDA, a.status, a.recep, a.fecha_doc_recep, c.cotiza
						from par_compo01 a
						left join inve01 b on a.cve_art = b.cve_art
						left join preoc01 c on a.id_preoc = c.id
						where cve_doc = '$doco'";
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function idPreoc($idd) {
        $this->query = "SELECT id, COTIZA, prod, STATUS, cant_orig, ordenado, rest, recepcion, REC_faltante,status_ventas
    					FROM preoc01
    					where id = $idd";
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function idCompo($idd) {
        $this->query = "SELECT id_preoc, cve_doc, cve_art, cant, pxr, tot_partida, num_par
    					FROM par_compo01
    					where id_preoc = $idd";
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function idCompr($idd) {
        $this->query = "SELECT a.id_preoc, a.cve_art, a.cant, a.CVE_DOC, a.TOT_PARTIDA, a.NUM_PAR, b.DESCR, a.status, a.fecha_doc
    					FROM par_compr01 a
    					left join inve01 b on a.cve_art = b.cve_art
    					where id_preoc = $idd";
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    ///// BOTON  VER / IMPRIMIR COMPROBANTES.

    function verPagos() {
        $this->query = "SELECT a.cve_doc, a.cve_clpv, b.nombre, a.importe, a.fechaelab, a.fecha_doc, doc_sig as Recepcion, a.enlazado,c.camplib1 as TipoPagoR, c.camplib3 as FER, c.camplib2 as TE, c.camplib4 as Confirmado, a.tp_tes as PagoTesoreria, a.pago_tes, pago_entregado, c.camplib6
					from compo01 a
					left join Prov01 b on a.cve_clpv = b.clave
					left join compo_clib01 c on a.cve_doc = c.clave_doc
					where a.status <> 'C' and TP_TES <> ''";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verEfectivos() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_EFECTIVO a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'N'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verCheques() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_CHEQUES a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'N'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verTrans() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_TRANS a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'N'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verCreditos() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_CREDITO a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'N'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verPXL() {
        $this->query = "SELECT a.*, b.*, c.*
					  FROM factp01 a
					  LEFT JOIN clie01 b on a.cve_clpv = b.clave
					  left join factp_clib01 c on a.cve_doc = c.clave_doc
					  where status2 is null and a.status <> 'C' ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function liberaPedido($pedido) {
        $this->query = "UPDATE Preoc01 set Status = 'N', fecha_auto = current_timestamp where cotiza = '$pedido'";
        $result = $this->EjecutaQuerySimple();
        $result += $this->actPedido($pedido);
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function actPedido($pedido) {
        $this->query = "UPDATE factp01 set STATUS2 = 'L' where cve_doc = '$pedido'";
        $result = $this->EjecutaQuerySimple();
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function GuardaPagoCorrectoOLD($docuOLD, $tipopOLD, $montoOLD, $nomprovOLD, $cveclpvOLD) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $this->query = "UPDATE compo01
					  SET TP_TES = '$tipopOLD', PAGO_TES = $montoOLD, FECHA_PAGO = '$HOY', STATUS_PAGO = 'PP'
					  WHERE CVE_DOC = '$docuOLD'";
        $rs = $this->EjecutaQuerySimple();
        $rs += $this->ActPagoParOCOLD($docuOLD, $tipopOLD, $montoOLD, $nomprovOLD, $cveclpvOLD);
        echo $rs;
        return $rs;
    }

    function Pagos_OLD() {
        $this->query = "	SELECT a.cve_doc, b.nombre, a.importe, a.fechaelab, a.fecha_doc, doc_sig as Recepcion, a.enlazado, c.camplib1 as TipoPagoR, c.camplib3 as FER,c.camplib2 as TE, c.camplib4 as Confirmado, a.tp_tes as PagoTesoreria, a.pago_tes, pago_entregado, c.camplib6, a.cve_clpv from compo01 a
						left join Prov01 b on a.cve_clpv = b.clave
						LEFT JOIN compo_clib01 c on a.cve_doc = c.clave_doc
						where a.fechaelab < '02/22/2016' and a.status <> 'C' and  TP_TES is null and (STATUS_PAGO = 'Ch' or STATUS_PAGO = 'CH')";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    //// Insertar Pagos OLD

    function ActPagoParOCOLD($docuOLD, $tipopOLD, $montoOLD, $nomprovOLD, $cveclpvOLD) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $iva = $montoOLD - ($montoOLD / 1.16);

        if ($tipopOLD == 'ch') {
            $query = "INSERT INTO P_CHEQUES (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, CHEQUE) VALUES (";
            $query .= " '" . $tipopOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $montoOLD . "',";
            $query .= " '" . $nomprovOLD . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docuOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpvOLD . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0'";
            $query .= ")";
            //echo $query;
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        } elseif ($tipopOLD == 'tr') {

            $query = "INSERT INTO P_TRANS (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, TRANS) VALUES (";
            $query .= " '" . $tipopOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $montoOLD . "',";
            $query .= " '" . $nomprovOLD . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docuOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpvOLD . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0'";
            $query .= ")";
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        } elseif ($tipopOLD == 'cr') {
            $query = "INSERT INTO P_CREDITO (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, CREDITO) VALUES (";
            $query .= " '" . $tipopOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $montoOLD . "',";
            $query .= " '" . $nomprovOLD . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docuOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpvOLD . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0'";
            $query .= ")";
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        } elseif ($tipopOLD == 'e') {
            $query = "INSERT INTO P_EFECTIVO (TIPO, FECHA, MONTO, BENEFICIARIO, IVA, DOCUMENTO, FECHAELAB, CVE_PROV,STATUS,FECHA_DOC, FECHA_APLI, EFECTIVO) VALUES (";
            $query .= " '" . $tipopOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $montoOLD . "',";
            $query .= " '" . $nomprovOLD . "',";
            $query .= " '" . $iva . "',";
            $query .= " '" . $docuOLD . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $cveclpvOLD . "',";
            $query .= " 'N',";
            $query .= " '" . $HOY . "',";
            $query .= " '" . $HOY . "',";
            $query .= " '0'";
            $query .= ")";
            $this->query = $query;
            $rs = $this->EjecutaQuerySimple();
        }
    }

    /* obtiene datos para imprimir */

    function ObtieneDatosTrans($id) {
        $this->query = "SELECT a.*, b.CLABE, b.BANCO
					  FROM p_trans a
					  left join Prov01 b on a.cve_prov = b.clave
					  WHERE id = $id";
        $res = $this->QueryObtieneDatos();
        if (count($res) > 0) {
            return $res;
        }
        return 0;
    }

    function ObtieneDatosOc($oc) {
        $this->query = "SELECT a.*, b.CLABE, b.BANCO
					  FROM p_trans a
					  left join Prov01 b on a.cve_prov = b.clave
					  WHERE id = $id";
        $res = $this->QueryObtieneDatos();
        if (count($res) > 0) {
            return $res;
        }
        return 0;
    }

    function ActStatusImpresoTrans($id) {
        $this->query = "UPDATE P_TRANS
					   set STATUS = 'I'
					   where id = '$id'";
        $res = $this->EjecutaQuerySimple();
    }

    function ActRuta($id, $doc) {
        $this->query = "UPDATE COMPO01
					  set RUTA = 'N'
					  where cve_doc = '$doc'";
        $res = $this->EjecutaQuerySimple();
    }

    function ObtieneDatosEfectivo($id) {
        $this->query = "SELECT *
					  FROM P_EFECTIVO
					  WHERE id = $id";
        $res = $this->QueryObtieneDatos();
        if (count($res) > 0) {
            return $res;
        }
        return 0;
    }

    function ActStatusImpresoEfectivo($id) {
        $this->query = "UPDATE P_efectivo
					   set STATUS = 'I'
					   where id = '$id'";
        $res = $this->EjecutaQuerySimple();
    }

    function ObtieneDatosCheque($id) {
        $this->query = "SELECT *
					  FROM P_Cheques
					  WHERE id = $id";
        $res = $this->QueryObtieneDatos();
        if (count($res) > 0) {
            return $res;
        }
        return 0;
    }

    function ActStatusImpresoCheque($id) {
        $this->query = "UPDATE P_Cheques
					   set STATUS = 'I'
					   where id = '$id'";
        $res = $this->EjecutaQuerySimple();
    }

    function ObtieneDatosCredito($id) {
        $this->query = "SELECT *
					  FROM P_CREDITO
					  WHERE id = $id";
        $res = $this->QueryObtieneDatos();
        if (count($res) > 0) {
            return $res;
        }
        return 0;
    }

    function ActStatusImpresoCredito($id) {
        $this->query = "UPDATE P_Credito
					   set STATUS = 'I'
					   where id = '$id'";
        $res = $this->EjecutaQuerySimple();
    }

    function verEfectivosImp() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_EFECTIVO a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'I'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verChequesImp() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_CHEQUES a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'I'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verTransImp() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_TRANS a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'I'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verCreditosImp() {
        $this->query = "SELECT a.*, b.CON_CREDITO, b.diascred, b.clabe, b.BENEFICIARIO as Benef, b.telefono FROM P_CREDITO a
					  left join Prov01 b on a.cve_prov = b.clave
					  where a.status = 'I'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function ActualizaRuta($docu, $unidad) {
        #echo "Este es el valor de unidad: ".$unidad;
        $TIME = time();
        $HOY = date("Y-m-d");
        $date = DateTime::createFromFormat('Y-m-d', $HOY);
        $formatdate = $date->format('m-d-Y');
        $idunidad = "SELECT IDU FROM UNIDADES WHERE NUMERO = '$unidad'";
        $this->query = $idunidad;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $idunidad = $row->IDU;

        $this->query = "UPDATE compo01
					  SET UNIDAD = '$unidad', RUTA = 'A', idu= '$idunidad', STATUS_LOG = 'secuencia'
					  WHERE CVE_DOC = '$docu'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function ActualizaRutaEdoMex($docu, $unidad) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $this->query = "UPDATE compo01
					  SET UNIDAD = '$unidad', RUTA = 'A'
					  WHERE CVE_DOC = '$docu'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function verUnidades() {
        $this->query = "SELECT * FROM UNIDADES";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verUnidad($unidad) {

        $uni = "SELECT NUMERO FROM UNIDADES WHERE IDU = '$unidad'";
        $this->query = $uni;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $uni = $row->NUMERO;

        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where UNIDAD = '$uni'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verUnidadesRutas($unidad) {
        $uni = "SELECT * FROM UNIDADES WHERE idu = '$unidad'";
        $this->query = $uni;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $uni = $row->NUMERO;

        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where UNIDAD = '$uni' and secuencia is null
     				  order by b.clave asc";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verUnidadesRuta() {
        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where secuencia is null and unidad <> ''
     				  order by b.clave asc";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verUnidadesRutas2($unidad) {

        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where UNIDAD = '$unidad'  and secuencia is null
     				  order by b.clave asc";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verUnidadesRuta3() {

        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where unidad <> '' and secuencia is null
     				  order by b.clave asc";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function asignaSecu($docu, $secu, $unidad, $fechai, $fechaf) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $this->query = "UPDATE COMPO01
					  SET SECUENCIA = '$secu', fecha_secuencia = '$HOY', fecha_log_i = '$fechai', fecha_log_f = '$fechaf'
					  where cve_doc = '$docu'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function ConsultaUnidad($unidad) {
        $this->query = "SELECT a.IDU, a.NUMERO, a.MARCA, a.MODELO, a.PLACAS, a.OPERADOR, a.TIPO, a.TIPO2, a.COORDINADOR FROM UNIDADES a WHERE a.IDU = '$unidad'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function ActualizaNUnidad($numero, $marca, $modelo, $placas, $operador, $tipo, $tipo2, $coordinador, $idu) {
        $this->query = "UPDATE UNIDADES
					  SET NUMERO = '$numero', MARCA = '$marca', MODELO = '$modelo', PLACAS = '$placas', OPERADOR = '$operador', TIPO = '$tipo', TIPO2 = '$tipo2',
					  COORDINADOR = $coordinador
					  where IDU = '$idu'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function verRutasxUnidad($id) {
        $uni = "SELECT * FROM UNIDADES WHERE idu = '$id'";
        $this->query = $uni;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $uni = $row->NUMERO;

        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where UNIDAD = '$uni' and secuencia is null
     				  order by b.clave asc";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function AdmonRutasxUnidad($idr) {
        $uni = "SELECT * FROM UNIDADES WHERE idu = '$idr'";
        $this->query = $uni;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $uni = $row->NUMERO;

        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV,
					  b.codigo, a.secuencia, (current_date) as HOY, a.IDU
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where UNIDAD = '$uni' and a.status_log = 'admon' AND a.STATUS != 'C'
     				  order by a.secuencia asc ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }  // and a.status_log = 'Nuevo'
        return $data;
    }

    function AdmonRutasxUnidad2($doc, $secuencia, $uni, $tipo) {
        $this->query = "SELECT  a.*, b.NOMBRE, (datediff(day, a.fechaelab, current_date )) as Dias, b.codigo, b.estado as ESTADOPROV, b.codigo, a.secuencia, (current_date) as HOY
     				  from compo01 a
     				  left join PROV01 b on a.cve_clpv = b.clave
     				  where UNIDAD = '$uni' and secuencia is not null and a.status_log = 'admon'
     				  order by a.secuencia asc ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function AdmonRutasxUnidadEntrega($idr) {
        $a = "SELECT a.*, c.NOMBRE, c.estado, c.codigo, b.fechaelab, d.fechaelab as fechfact, d.cve_doc as FACTURA, (datediff(day, a.FECHA_CREACION,current_date)) as DIAS, iif(a.factura is null or a.factura ='', a.remision, a.factura) as documento
			FROM CAJAS a
			LEFT JOIN FACTP01 b ON a.CVE_FACT = b.cve_doc
			LEFT JOIN CLIE01 c ON c.clave = b.cve_clpv
			LEFT JOIN FACTF01 d ON b.doc_sig = d.cve_doc
			WHERE idu = $idr and a.status_log = 'admon'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AdmonRutasxUnidadEntregaForaneo($idr) {  // para las entregas foraneas
        $a = "SELECT a.*, c.NOMBRE, c.estado, c.codigo, b.fechaelab, d.fechaelab as fechfact, d.cve_doc as FACTURA, (datediff(day, a.FECHA_CREACION,current_date)) as DIAS, cast(cl.CAMPLIB7 as char(255)) as destino_predeterminado
			FROM CAJAS a
			LEFT JOIN FACTP01 b ON a.CVE_FACT = b.cve_doc
			LEFT JOIN CLIE01 c ON c.clave = b.cve_clpv
			LEFT JOIN FACTF01 d ON b.doc_sig = d.cve_doc
			LEFT JOIN CLIE_CLIB01 cl on cl.cve_clie = c.clave
			WHERE idu = $idr and a.status_log = 'admon'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AdmonRutasxUnidadEntrega2($idr) {
        $a = "SELECT a.*, c.NOMBRE, c.estado, c.codigo, b.fechaelab, d.fechaelab as fechfact, d.cve_doc as FACTURA, (datediff(day, a.FECHA_CREACION,current_date)) as DIAS, iif(a.factura is null or a.factura ='', a.remision, a.factura) as documento
			FROM CAJAS a
			LEFT JOIN FACTP01 b ON a.CVE_FACT = b.cve_doc
			LEFT JOIN CLIE01 c ON c.clave = b.cve_clpv
			LEFT JOIN FACTF01 d ON b.doc_sig = d.cve_doc
			WHERE unidad = '$idr' and a.status_log = 'admon'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AsignaSec($unidad) {
        $uni = "SELECT * FROM UNIDADES WHERE idu = '$unidad'";
        $this->query = $uni;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $uni = $row->NUMERO;
        $this->query = "SELECT  b.NOMBRE, count (a.cve_doc) as cve_doc, MAX(current_date ) as Fecha, MAX (b.codigo) as codigo,
                                      MAX (b.estado) as ESTADOPROV, MAX (b.codigo) as codigo, MAX(unidad) as unidad,
                                      MAX (datediff(day, a.fechaelab, current_date )) as Dias, max(a.cve_clpv) as prov, a.IDU
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where idu = '$unidad' and secuencia is null and status_log = 'secuencia'
                       group by b.nombre, a.idu";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function AsignaSec2($prove, $secuencia, $uni, $fecha) {
        $this->query = "SELECT  b.NOMBRE, count (a.cve_doc) as cve_doc, MAX(current_date ) as Fecha, MAX (b.codigo) as codigo, MAX (b.estado) as ESTADOPROV, MAX (b.codigo) as codigo, MAX(unidad) as unidad, MAX (datediff(day, a.fechaelab, current_date )) as Dias, max(a.cve_clpv) as prov, max(idu) as IDU
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where a.UNIDAD = '$uni' and secuencia is null AND status_log = 'secuencia'
                        group by b.nombre ";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function SecUni($prove, $secuencia, $uni, $fecha) {
        $date = DateTime::createFromFormat('Y-m-d', $fecha);
        $formatdate = $date->format('m-d-Y');
        $fecha = date('Y-m-j');
        $sec = "UPDATE compo01
				        set secuencia ='$secuencia', status_log = 'admon', fecha_secuencia = current_date
				        where cve_clpv = '$prove' and unidad = '$uni' and secuencia is null and status_log = 'secuencia'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function ObiteneDataSecRO($prove, $uni) {
        $sec = "SELECT CVE_DOC FROM COMPO01 where cve_clpv = '$prove' and unidad = '$uni' and secuencia is null and status_log = 'secuencia' ";
        $this->query = $sec;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function SecRo($cvedoc, $secuencia) {
        foreach ($cvedoc AS $clave) {
            $this->query = "UPDATE REGISTRO_OPERADORES SET SECUENCIA = '$secuencia', RESULTADO ='admon' WHERE DOCUMENTO = '$clave->CVE_DOC' AND (SECUENCIA IS NULL OR (SECUENCIA IS NOT NULL AND MOTIVO IS NOT NULL)) ";
            $resultado = $this->EjecutaQuerySimple();
        }
        return $resultado;
    }

    /* ////22-03-2016
      $date=DateTime::createFromFormat('Y-m-d',$fecha);
      $formatdate=$date->format('m-d-Y');
      $fecha=date('Y-m-j');
      $nuevafecha=strtotime('-1 day', strtotime($fecha));
      $nuevafecha=date ('m-j-Y', $nuevafecha);
      $nuevafecha2=strtotime('-2 day', strtotime($fecha));
      $nuevafecha2=date ('m-j-Y', $nuevafecha2);
      $nuevafecha3=strtotime('-3 day', strtotime($fecha));
      $nuevafecha3=date ('m-j-Y', $nuevafecha3);
      $sec ="UPDATE compo01
      set secuencia ='$secuencia'
      where cve_clpv = '$prove' and unidad = '$uni' and (fecha_secuencia = '$nuevafecha' or fecha_secuencia = '$formatdate' or fecha_secuencia = '$nuevafecha2' or fecha_secuencia = '$nuevafecha3') and secuencia is null";
      $this->query = $sec;
      $rs = $this->EjecutaQuerySimple();
      return $rs;
      }
     */
    /* 	$date=DateTime::createFromFormat('Y-m-d',$fecha);
      $formatdate=$date->format('m-d-Y');
      $sec ="UPDATE compo01
      set secuencia ='$secuencia'
      where cve_clpv = '$prove' and unidad = '$uni' and fecha_secuencia = '$formatdate'";
      $this->query = $sec;
      $rs = $this->EjecutaQuerySimple();
      //echo $sec;
      //echo "Unidad: ".$uni." Proveedor: ".$prove." Secuencia ".$secuencia." fecha: ".$formatdate;

      return $rs;
      } */

    // function DefineRuta($doc, $secuencia, $uni, $horai, $horaf, $tipo){
    // 160316 cfa -> se omiten los campos de horai y horaf
    function DefineRuta($doc, $secuencia, $uni, $tipo) {
        $tabla = 'compo01';
        $docu = 'CVE_DOC';
        $entrega = strpos($doc, 'P');
        if ($entrega !== false) {
            $tabla = 'CAJAS';
            $docu = 'CVE_FACT';
        }
        $sec = "UPDATE $tabla
				set status_log = '$tipo', MOTIVO = NULL
					where $docu = '$doc'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();

        if ($tabla == 'CAJAS') {
            $b = "UPDATE CAJAS SET ADUANA = NULL, docs = 'Si' WHERE 	$docu = '$doc'";
            $this->query = $b;
            $result = $this->EjecutaQuerySimple();
        }
        return $rs;
    }

    function defineRutaForaneo($doc, $guia, $fletera, $cpdestino, $destino, $fechaestimada) {
        $this->query = "UPDATE cajas SET status_log = 'Envio', guia_fletera = '$guia', fletera = '$fletera', fecha_guia = current_timestamp, fecha_entrega = '$fechaestimada', destino = '$destino', cp_destino = $cpdestino WHERE cve_fact = '$doc'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function guardaGuiaForaneo($ped, $target_file_cc) { //guarda la ruta de la guia foranea en la bd
        $this->query = "UPDATE cajas SET f_guia_fletera = '$target_file_cc' WHERE cve_fact = '$ped'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function DefineResultadoFinRO($doc, $tipo) {
        $this->query = "UPDATE REGISTRO_OPERADORES SET RESULTADO = '$tipo' WHERE DOCUMENTO = '$doc'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function VerFallidos($idf) {
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad, (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago, secuencia, (current_date) as HOY, idu
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where a.idu = '$idf' and status_log = 'Fallido' and Motivo is null";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function VerOCFallidas() {
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad, (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago, secuencia, (current_date) as HOY, idu, motivo
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where (status_log = 'Fallido') and motivo is not null";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function FinalizaRuta($idf, $secuencia, $uni, $motivo, $doc) {
        //$date=DateTime::createFromFormat('Y-m-d',$fecha);
        //$formatdate=$date->format('m-d-Y');
        $sec = "UPDATE compo01
				set Motivo ='$motivo'
				where cve_doc = '$doc'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        //echo $sec;
        //echo "Unidad: ".$uni." Documento: ".$doc." Hora inicial: ".$horai." Hora Final: ".$horaf;

        return $rs;
    }

    function FinalizaReEnRuta($idf, $motivo, $doc) {     //FINALIZA RE ENRUTA
        $sec = "UPDATE compo01
				set Motivo ='$motivo', STATUS_LOG = 'Nuevo',  UNIDAD = NULL,SECUENCIA = NULL,
				FECHA_SECUENCIA = NULL, IDU = NULL, RUTA = 'N', HORAI = NULL, HORAF = NULL
				where cve_doc = '$doc'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function VerRutaDia() {

        //$TIME = time();
        $HOY = date("Y-m-d");
        $date = DateTime::createFromFormat("Y-m-d", $HOY);
        $formatdate = $date->format("m-d-Y");
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad, (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago, secuencia, (current_date) as HOY, idu, motivo
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where fecha_secuencia >= '$formatdate'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function VerRutaXDia($idr) {

        $HOY = date("Y-m-d");
        $date = DateTime::createFromFormat("Y-m-d", $HOY);
        $formatdate = $date->format("m-d-Y");
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad,
                              (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago,
                              secuencia, (current_date) as HOY, idu, motivo, LEFT(c.camplib3,10) AS CITA, a.urgente
                       from (compo01 a
                       LEFT JOIN COMPO_CLIB01 c
                       ON  a.cve_doc = c.clave_doc)
                       left join PROV01 b on a.cve_clpv = b.clave
                       where fecha_secuencia >= '$formatdate' and idu = '$idr'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function asignaHoraInicio($documento) {
        $pedido = strrpos($documento, 'P');
        $tabla = 'COMPO01';
        $campo = 'CVE_DOC';
        if ($pedido !== false) {
            $tabla = 'CAJAS';
            $campo = 'CVE_FACT';
        }
        $ahora = date("H:i:s");
        $sec = "UPDATE $tabla SET HORAI = '$ahora' WHERE $campo = '$documento'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function asignaHoraFin($documento) {
        $pedido = strrpos($documento, 'P');
        $tabla = 'COMPO01';
        $campo = 'CVE_DOC';
        if ($pedido !== false) {
            $tabla = 'CAJAS';
            $campo = 'CVE_FACT';
        }
        $ahora = date("H:i:s");
        $sec = "UPDATE $tabla SET HORAF = '$ahora' WHERE $campo = '$documento'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function asignaHoraInicioRO($documento) {
        $ahora = date("H:i:s");
        $sec = "UPDATE REGISTRO_OPERADORES SET HORAINI = '$ahora' WHERE DOCUMENTO = '$documento'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function asignaHoraFinRO($documento) {
        $ahora = date("H:i:s");
        $sec = "UPDATE REGISTRO_OPERADORES SET HORAFIN = '$ahora' WHERE DOCUMENTO = '$documento'";
        $this->query = $sec;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function VerTotales($idf) {
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad, (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago, secuencia, (current_date) as HOY, idu
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where a.idu = '$idf' and status_log = 'Total'";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function VerPnoEnrutar($idf) {
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad, (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago, secuencia, (current_date) as HOY, idu
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where a.idu = '$idf' and status_log = 'PNR' and MOTIVO is null";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function VerReEnrutar($idf) {
        $this->query = "SELECT  b.NOMBRE, a.cve_doc, (current_date ) as Fecha, (b.codigo) as codigo, (b.estado) as ESTADOPROV, (unidad) as unidad, (datediff(day, a.fechaelab, current_date )) as Dias, (a.cve_clpv) as prov, status_log, fechaelab, pago_tes, fecha_pago, secuencia, (current_date) as HOY, idu
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where  a.idu = '$idf' AND (status_log = 'Parcial' OR status_log = 'Tiempo') and a.MOTIVO is null";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function CreaSubMenu() {
        $this->query = "SELECT * FROM unidades
						ORDER BY IDU ASC";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    // Ver Todos los registros de logistica que Tengan un status asignado.
    function Logistica() {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $logistica = "SELECT a.*, b.*,p.NOMBRE , (datediff(day, a.fechaelab, current_date )) as Dias, '$HOY' AS HOY
		FROM
        (COMPO01 a LEFT JOIN prov01 p ON a.cve_clpv = p.clave)
        left join UNIDADES b on a.idu = b.idu
        where status_log is not null and a.fechaelab >= '04/01/2016' and a.status != 'C' and a.ENLAZADO != 'T'";
        $this->query = $logistica;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return$data;
    }

    /*
      function Logistica(){
      $logistica="SELECT a.*, b.*  FROM COMPO01 a
      left join UNIDADES b on a.idu = b.idu
      where status_log is not null";
      $this->query=$logistica;
      $result=$this->QueryObtieneDatosN();
      while ($tsArray=ibase_fetch_object($result)){
      $data[] =$tsArray;
      }
      return$data;
      } */

    /// Modificar los Status de Logistica.

    function LinUniOC($doc, $tipo, $uni, $tipoA) {  //22-03-2016 ICA
        $Statusi = $tipoA;
        $Statusn = $tipo;

        if ($Statusi != 'Nuevo') {
            if ($Statusn == 'Total') {
                $rs = "UPDATE compo01
				set status_log = 'Total',
				hist_logistica = CASE
								WHEN hist_logistica IS NULL THEN '$Statusn' || '/'
								WHEN hist_logistica IS NOT NULL THEN hist_logistica || '$Statusn' || '/'
								ELSE hist_logistica
								END
			where cve_doc='$doc'";
            } elseif ($Statusn == 'Parcial') {
                $rs = "UPDATE compo01
				set status_log = 'Parcial',
				motivo = null,
				hist_logistica = CASE
								WHEN hist_logistica IS NULL THEN '$Statusn' || '/'
								WHEN hist_logistica IS NOT NULL THEN hist_logistica || '$Statusn' || '/'
								ELSE hist_logistica
								END
			where cve_doc='$doc'";
            } elseif ($Statusn == 'PNR') {
                $rs = "UPDATE compo01
				set status_log = 'PNR',
				motivo = null,
				hist_logistica = CASE
								WHEN hist_logistica IS NULL THEN '$Statusn' || '/'
								WHEN hist_logistica IS NOT NULL THEN hist_logistica || '$Statusn' || '/'
								ELSE hist_logistica
								END
			 where cve_doc='$doc'";
            } elseif ($Statusn == 'Tiempo') {
                $rs = "UPDATE compo01
			set status_log='Tiempo',
			motivo = null,
			hist_logistica = CASE
								WHEN hist_logistica IS NULL THEN '$Statusn' || '/'
								WHEN hist_logistica IS NOT NULL THEN hist_logistica || '$Statusn' || '/'
								ELSE hist_logistica
								END
			where cve_doc='$doc'";
            } elseif ($Statusn == 'Fallido') {
                $rs = "UPDATE compo01
				set status_log='Fallido',
				motivo = null,
				hist_logistica = CASE
								WHEN hist_logistica IS NULL THEN '$Statusn' || '/'
								WHEN hist_logistica IS NOT NULL THEN hist_logistica || '$Statusn' || '/'
								ELSE hist_logistica
								END
				where cve_doc='$doc'";
            } elseif ($Statusn == 'AsignaU' || $Statusn == 'Nuevo') {
                $rs = "UPDATE compo01
				set motivo = null,
				STATUS_LOG = 'Nuevo',
				UNIDAD = NULL,
				SECUENCIA = NULL,
				FECHA_SECUENCIA = NULL,
				IDU = NULL,
				RUTA = 'N',
				HORAI = NULL,
				HORAF = NULL,
				DOC_SIG = NULL,
				hist_logistica = CASE
								WHEN hist_logistica IS NULL THEN '$Statusn' || '/'
								WHEN hist_logistica IS NOT NULL THEN hist_logistica || '$Statusn' || '/'
								ELSE hist_logistica
								end
					where cve_doc='$doc'";
            }
            $this->query = $rs;
            $result = $this->EjecutaQuerySimple();
        }
    }

    /*
      $urgente = "SELECT URGENTE from PREOC01 where id = '".$IdPreoco."'";
      $this->query = $urgente;
      $result = $this->EjecutaQuerySimple();
      $row = ibase_fetch_object($result);
      $urgentes = $row->URGENTE;

      if ($urgentes == 'U'){
      $esurgente = "UPDATE COMPO01 SET URGENTE = 'U' WHERE CVE_DOC ='".$Doc."'";
      $this->query = $esurgente;
      $result = $this->EjecutaQuerySimple();
     */

    function TraeProveedores($prov) {
        $this->query = "SELECT CLAVE, NOMBRE FROM prov01
    					WHERE nombre CONTAINING '$prov'";
        $result = $this->QueryDevuelveAutocomplete();
        return $result;
    }

    function TraeProductos($prod) {
        $this->query = "SELECT CVE_ART, DESCR FROM inve01
    					WHERE DESCR CONTAINING '$prod'";
        $result = $this->QueryDevuelveAutocompleteP();
        return $result;
    }

    function verRecepciones() {
        $this->query = "SELECT a.*, b.NOMBRE, c.OPERADOR, iif(d.cve_doc is null, a.doc_sig, d.cve_doc) as Recepcion
   				  from compo01 a
   				  left join prov01 b on a.cve_clpv = b.clave
   				  left join unidades c on a.unidad = c.numero
   				  left join compr01 d on a.doc_sig = d.cve_doc
   				  where (a.status_rec is null or a.status_rec = 'par') and (Status_log = 'Total' or Status_log = 'Parcial' or Status_log = 'PNR' or Status_log = 'Fallido') and a.fechaelab >= '04/01/2016' AND a.STATUS != 'C' and (a.status_log2 is null or a.status_log2 = 'R' or a.status_log2  like '%Nuevo/')";
        //// modificcion el 22 de Julio "and (a.status_log2 is null or a.status_log2 = 'R' or a.status_log2 ='Nuevo/' )""
        //echo $this->query;

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /* function verRecepciones(){

      // VALIDACION INICIAL = 0, VALIDACION INCOMPLETA = 1 VALIDACION COMPLETA = 2

      $this->query="SELECT r.*, p.nombre

      from compr01 r

      left join prov01 p on p.clave = r.cve_clpv

      where cve_doc in (SELECT CVE_DOC FROM DOCTOSIGC01  GROUP BY CVE_DOC having max(tip_doc) = 'r' and (min(VALIDACION) = 0 OR min(VALIDACION) = 1) and min(ubica) = 0)";

      $rs=$this->QueryObtieneDatosN();

      while($tsArray=ibase_fetch_object($rs)){

      $data[]=$tsArray;

      }

      return $data;

      } */

    function RECEP($doc) {
        $test = "SELECT a.*, b.CAMPLIB2, b.camplib4, c.codigo, c.municipio, c.telefono, c.nombre, d.fechaelab as fechaoc, d.cve_doc as OC
					  FROM COMPR01 a
					  left join compo_clib01 b on a.doc_ant = b.CLAVE_doc
					  left join prov01 c on a.cve_clpv = c.clave
					  left join  compo01 d on a.doc_ant = d.cve_doc
					  where trim(a.cve_doc) = trim('$doc')";
        $this->query = $test;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function detalleRECEP($doc) {
        $test2 = "SELECT a.*, b.descr, c.cotiza
					  FROM PAR_COMPR01 a
					  left join inve01 b on a.cve_art = b.cve_art
					  left join preoc01 c on a.id_preoc = c.id
					  where trim(cve_doc) = trim('$doc')";

        $this->query = $test2;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function CotizacionSinCompra() {
        //$hoy = date("d.m.y");
        $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS
					from preoc01 WHERE status='N' and rest > 0 and rec_faltante > 0 AND fechasol > '29.02.2016' ORDER BY  nom_prov  ASC ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function OCSinPago() {
        $hoy = date("d.m.y");
        $this->query = "	SELECT a.cve_doc, b.nombre, a.importe, a.fechaelab, a.fecha_doc, doc_sig as Recepcion, a.enlazado, c.camplib1 as TipoPagoR, c.camplib3 as FER,c.camplib2 as TE, c.camplib4 as Confirmado, a.tp_tes as PagoTesoreria, a.pago_tes, pago_entregado, c.camplib6, a.cve_clpv, a.URGENTE, datediff(day, a.fechaelab, current_date ) as Dias
						from compo01 a
						left join Prov01 b on a.cve_clpv = b.clave
						LEFT JOIN compo_clib01 c on a.cve_doc = c.clave_doc
						where a.status <> 'C' and  TP_TES is null and fechaelab > '03/14/2016' order by a.fechaelab asc";
        /* "SELECT cve_doc, datediff(day, fecha_doc, date '$hoy') AS dias
          FROM compo01
          WHERE status_pago = 'PP' AND fecha_doc > '15.03.2016'"; */
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function OCSinRuta() {
        //$hoy = date("d.m.y");
        $this->query = "SELECT a.cve_doc, b.nombre, a.fecha_pago, a.pago_tes, a.tp_tes, a.pago_entregado, c.camplib2 , a.unidad, a.estado, a.fechaelab, (datediff(day, a.fechaelab, current_date )) as Dias, a.urgencia, b.codigo, b.estado as estadoprov
					    from compo01 a
						left join prov01 b on a.cve_clpv = b.clave
						left join compo_clib01 c on a.cve_doc = c.clave_doc
						where a.ruta = 'N' and a.doc_sig is null";
        /* "SELECT a.cve_doc, b.nombre, b.estado, b.codigo, a.fecha_doc, datediff(day, a.fecha_doc, date '$hoy') AS dias,
          a.pago_tes, a.fecha_pago
          FROM
          compo01 a
          INNER JOIN prov01 b
          ON a.cve_clpv = b.clave
          WHERE a.status_log = 'Nuevo' AND a.fecha_doc > '15.03.2016'"; */
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function OCSinRecepcion() {
        $hoy = date("d.m.y");
        $this->query = "SELECT a.cve_doc, b.nombre, b.estado, b.codigo, a.fecha_doc, datediff(day, a.fecha_doc, date '$hoy') AS dias,
							  a.UNIDAD, a.RUTA, c.OPERADOR
							  FROM
							  	(compo01 a
							  	LEFT JOIN UNIDADES c
								ON a.idu = c.idu)
							  	LEFT JOIN prov01 b
							  	ON a.cve_clpv = b.clave
							  	WHERE DOC_SIG IS NULL AND fecha_doc > '15.03.2016'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ValidarRecepcion($docr) {

        $this->query = "SELECT a.*, a.cve_doc as RECEPCION, a.doc_ant as OC, b.nombre, c.unidad, d.operador, c.status_log
					  FROM COMPR01 a
					  left join prov01 b on a.cve_clpv = b.clave
					  left join compo01 c on a.cve_doc = c.doc_sig
					  left join unidades d on c.unidad = d.numero
					  where a.cve_doc = '$docr'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function PartidasRecep($docr, $doco) {
        $tipo = substr($docr, 0, 1);
        if ($tipo == 'F') {
            $a = "SELECT a.*, b.descr, a.cant as Cant_oc, b.uni_alt, a.pxr as PXR_OC, d.cve_doc as DOCO, c.cant as CANT
					, a.id_preoc as ID_PREOC, e.fechaelab as fecha_doco, b.cve_art
				      from par_compo01 a
				      left join Inve01 b on a.cve_art = b.cve_art
				      left join compr01 d on a.cve_doc = d.doc_ant
				      left join par_compr01 c on c.id_preoc = a.id_preoc AND c.cve_doc = d.cve_doc
				      left join compo01 e on a.cve_doc = e.cve_doc
				      where a.cve_doc = '$doco' and a.status != 'c'and (a.status_rec is null or a.status_rec = 'f' or a.status_rec = 'p' or a.status_rec = 'par')";
        } else {
            $a = "SELECT poc.num_par,pr.tot_partida, pr.cant as Cant_oc, i.uni_alt, pr.pxr as PXR_OC, r.cve_doc as DOCO, pr.cant as CANT,
			 			  r.fechaelab, pr.id_preoc as ID_PREOC, r.fechaelab as fecha_doco, i.cve_art, i.descr, oc.cve_doc, pr.pxr, pr.cost
						  FROM par_compr01 pr
						  left join inve01 i on pr.cve_art = i.cve_art
						  left join compr01 r on r.cve_doc = pr.cve_doc
						  left join compo01 oc on r.doc_ant = oc.cve_doc
						  left join par_compo01 poc on poc.cve_doc = r.doc_ant and poc.id_Preoc = pr.id_Preoc
						  where trim(pr.cve_doc) = trim('$docr') and (poc.status_log2 is null or poc.status_log2 = 'R')";
        }
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function PartidasNoRecep($docr, $doco) {
        $c = "SELECT a.*, b.descr, b.uni_alt, e.cant_rec as L, e.cost_rec as J, d.doc_sig, iif(a.saldo is null, 0, a.saldo) as SALDO
				      from par_compo01 a
				      left join Inve01 b on a.cve_art = b.cve_art
				      left join compo01 d on a.cve_doc = d.cve_doc
				      left join compr01 c on d.cve_doc = c.doc_ant
				      left join par_compr01 e on c.cve_doc = e.cve_doc and a.id_preoc = e.id_preoc
				      where a.cve_doc = '$doco' and (a.status_rec is not null )";

        $this->query = $c;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function ActCantParRecep($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {
        $actcant = "UPDATE par_compr01 set cant = $cantn where cve_doc = '$docr'  and id_preoc = '$idpreoc'";
        $this->query = $actcant;
        $result = $this->EjecutaQuerySimple();
    }

//// Revisarar para que sirve esta funcion y de donde viene.

    function ActPXR($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {
        $pxr = "SELECT pxr from par_compo01 where cve_doc = '$doco' and id_preoc = idpreoc";
        $this->query = $pxr;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $pxroc = $row->$PXR;

        if ($cantn > $cantorig) {
            $cantfinal = $cantn - $cantorig;
            $pxrfinal = $pxroc - $cantfinal;
            $actpxr = "UPDATE par_comoo01 set pxr = '$pxrfinal' where cve_doc = '$doco' and id_preoc = $idPreoc";
        } else {
            $cantfinal = $cantorig - $cantn;
            $pxrfinal = $pxroc + $cantfinal;
            $actpxr = "UPDATE par_compo01 set pxr = $pxrfinal where cve_doc = '$doco' and id_preoc = $idpreoc";
        }
        $this->query = $actpxr;
        $result = $this->EjecutaQuerySimple();
    }

    function VerNoSuministrableC() {
        $hoy = date("d.m.y");
        $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti, cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,date '$hoy') as DIAS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NULL ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function VerNoSuministrableCMotivo() {
        $hoy = date("d.m.y");
        $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,date '$hoy') as DIAS, obs
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL ORDER BY  nom_prov  ASC ";
        $result = $this->EjecutaQuerySimple();
        if ($this->NumRows($result) > 0) {
            while ($tsArray = $this->FetchAs($result))
                $data[] = $tsArray;

            return $data;
        }

        return $result;
    }

    function MotivoNoSuministrable($id, $motivo) {
        $this->query = "UPDATE PREOC01 SET OBS = '$motivo' WHERE id = '$id'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function VerNoSuministrableV() {
        $numeroletras = $_SESSION['user']->NUMERO_LETRAS;
        $letra1 = $_SESSION['user']->LETRA;
        $letra2 = $_SESSION['user']->LETRA2;
        $letra3 = $_SESSION['user']->LETRA3;
        $letra4 = $_SESSION['user']->LETRA4;
        $letra5 = $_SESSION['user']->LETRA5;
        switch ($numeroletras) {

            case 1:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL AND LETRA_V IN ('$letra1') ORDER BY  nom_prov  ASC ";
                break;

            case 2:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL
								AND LETRA_V IN ('$letra1','$letra2') ORDER BY  nom_prov  ASC ";
                break;

            case 3:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL
								AND LETRA_V IN ('$letra1','$letra2','$letra3') ORDER BY  nom_prov  ASC ";
                break;

            case 4:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL
								AND LETRA_V IN ('$letra1','$letra2','$letra3','$letra4') ORDER BY  nom_prov  ASC ";
                break;

            case 5:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL
								AND LETRA_V IN ('$letra1','$letra2','$letra3','$letra4','$letra5') ORDER BY  nom_prov  ASC ";
                break;

            case 6:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL
								AND LETRA_V IN ('$letra1','$letra2','$letra3','$letra4','$letra5','$letra6') ORDER BY  nom_prov  ASC ";
                break;

            case 99:
                $this->query = "select id, fechasol, cotiza, par, status, prod, nomprod, canti,cant_orig, costo, prove, nom_prov, total,rest,docorigen, urgente, um, datediff(day,fechasol,current_date) as DIAS, OBS
								from preoc01 WHERE status='S' and rest > 0 and rec_faltante > 0 AND OBS IS NOT NULL ORDER BY  nom_prov  ASC ";
                break;

            default:
                berak;
        }
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function StatusNoSuministrableV($id, $status) {
        $this->query = "UPDATE PREOC01 SET status = '$status' WHERE id = '$id'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    ///// Cuando se actuliza el Costo pero la cantidad es la misma.

    function ActRecCosto($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {
        $subtot = "SELECT sum(tot_partida) as TOTAL, sum(totimp4) as TOTALIVA from par_compr01 where trim(cve_doc) = trim('" . $docr . "')";
        //echo $subtot;
        $this->query = $subtot;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_row($result);
        //print $row[0];
        //print $row[1];
        $t = $row[0];
        $TotalIVA = $row[1];
        $SubTotal = $t - $TotalIVA;
        return $result;
    }

    function ActStatusParRec($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {
        $actpar = "UPDATE par_compr01 set status_rec = 'CCo' where cve_doc = '$docr' and Id_Preoc = '$idpreoc'";
        $this->query = $actpar;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function ActCostoPar($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {

        $consulta = "UPDATE PAR_COMPR01 SET cost = ('$coston'*'$cantn'), TOTIMP4 = (('$coston'*'$cantn') * 0.16), TOT_PARTIDA = (('$coston'*'$cantn') * 1.16)

					WHERE ID_PREOC = '$IDPREOC'";

        $this->query = $consulta;

        $result = $this->EjecutaQuerySimple();
    }

//// Cuando Atualiza Cantidad y Costo

    function ActRecCCx($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {
        $subtot = "SELECT sum(tot_part) as TOTAL , sum(tot_imp4) as TOTALIVA from par_compr01 where cve_doc = '" . $docr . "'";
        $this->query = $subtot;
        $result->$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $Total = $row->$TOTAL;
        $TotalIVA = $row->$TOTALIVA;
        $SubTotal = $TOTALIVA - $TOTAL;

        /* $ActDoc= "UPDATE compr01 set CAN_TOT = $SubTotal, IMPORTE = $Total, IMP_TOT4 = $TotalIVA where CVE_DOC = '$docr'";
          $this->query=$ActDoc;
          $result=$this->EjecutaQuerySimple();
          return $result; */
    }

/// actuaiza es estatus de recepcion.


    function ActRecepOk($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc, $idordencompra, $par, $fechadoco, $descripcion, $cveart, $fval, $desc1, $desc2, $desc3) {
        $usuario = $_SESSION['user']->NOMBRE;

        $a = "INSERT INTO VALIDA_RECEPCION (DOCUMENTO, ID_PREOC, PRODUCTO, DESCRIPCION, PARTIDA, FECHA_DOC,FECHA_VAL, CANT_ORIGINAL, CANT_VALIDADA, CANT_ACUMULADA, COSTO_ORIGINAL, COSTO_VALIDADO, SERIE, APLICADO, IMPRESO, USUARIO, FOLIO_VAL, DESC1 ,DESC2, DESC3)
			VALUES ('$doco',$idpreoc,'$cveart','$descripcion',$par,'$fechadoco', current_timestamp, '$cantorig', '$cantn', 0, $costoorig, $coston, 'RV', 'No', 'No', '$usuario', $fval, $desc1 , $desc2, $desc3)";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        //echo $a;
        //break;
        $b = "SELECT SUM(CANT_VALIDADA) AS ACUMULADO, MAX(ID) AS MID FROM VALIDA_RECEPCION WHERE id_preoc = $idpreoc group by id_preoc";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $acumulado = $row->ACUMULADO;
        $id = $row->MID;
        //echo $id;
        //break;
        $c = "UPDATE VALIDA_RECEPCION SET CANT_ACUMULADA = $acumulado where id = $id";
        $this->query = $c;
        $result = $this->EjecutaQuerySimple();

        $d = "UPDATE PREOC01 SET RECEPCION = iif(recepcion = 0, $acumulado, recepcion + $cantn), rec_faltante = cant_orig - iif(recepcion = 0, $acumulado, recepcion + $cantn) where id = $idpreoc";

        $this->query = $d;
        $result = $this->EjecutaQuerySimple();

        $e = "UPDATE VALIDA_RECEPCION SET APLICADO = 'Si' WHERE id = $id";
        $this->query = $e;
        $result = $this->EjecutaQuerySimple();

        $f = "SELECT DOCUMENTO, SUM(CANT_VALIDADA) as cantval, MAX(PARTIDA) AS PARTIDA, MAX(ID_PREOC) AS ID_PREOC  FROM VALIDA_RECEPCION  WHERE documento = '$doco' and PARTIDA = $par and id_preoc= $idpreoc GROUP BY DOCUMENTO";
        $this->query = $f;

        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $oc = $row->DOCUMENTO;
        $cantval = $row->CANTVAL;
        $partida = $row->PARTIDA;
        $idpreoc = $row->ID_PREOC;

        $g = "UPDATE par_compo01 set pxr = cant - $cantval, status_rec = iif((cant - $cantval)= 0, 'ok', 'par' ), doc_recep = iif(doc_recep is null, 'f', doc_recep), doc_recep_status = iif(doc_recep_status is null, 'f', doc_recep_status), cant_rec = iif(cant_rec is null, $cantn, cant_rec + $cantn),status_log2 = iif((cant - $cantval) = 0, 'T', 'Suministros'), cost_rec = $coston, saldo =tot_partida - ($coston * $cantn) where cve_doc = '$oc' and num_par = $partida and id_preoc = $idpreoc";
        //echo $g;
        //break;
        $this->query = $g;
        $result = $this->EjecutaQuerySimple();


        $partidas = "SELECT count(num_par) as PARTIDAS from par_compo01 where cve_doc = '$doco' and status_rec = 'ok'";
        $this->query = $partidas;
        $rspoc = $this->EjecutaQuerySimple();
        $row = ibase_fetch_row($rspoc);
        $paroc = $row[0];
        //echo $partidas;
        //echo "Este es el total de partidad comprobadas: ".$paroc;

        $part = "SELECT count(num_par) as PARTOT FROM PAR_COMPO01 WHERE CVE_DOC = '$doco'";
        $this->query = $part;
        $rspocr = $this->EjecutaQuerySimple();
        $row2 = ibase_fetch_object($rspocr);
        $partot = $row2->PARTOT;
        //echo " Total de las partidas del Documento: ".$partot;

        if ($partot == $paroc) {
            $actrecep_status = "UPDATE compo01 set status_rec = 'OK' where cve_doc = '$doco'";
        } else {
            $actrecep_status = "UPDATE compo01 set status_rec = 'par', status_log2 = 'Suministros' where cve_doc = '$doco'";
        }
        $this->query = $actrecep_status;
        $result = $this->EjecutaQuerySimple();
        //	echo $actrecep_status;
        ///// hasta aqui es codigo nuevo.
        ///Se define si se visualiza en el area de imprimir comprbante.
        $partidasrec = "SELECT count(num_par) as PARTIDAS from par_compr01 where trim(cve_doc) = trim('$docr') and status_rec is not null";
        $this->query = $partidasrec;
        $rsprec = $this->EjecutaQuerySimple();
        $row5 = ibase_fetch_row($rsprec);
        $parrec = $row5[0];
        //echo $partidasrec;
        //echo "Este es el total de partidad comprobadas: ".$parrec;
        $partrec = "SELECT max(num_par) as PARTOT FROM PAR_COMPR01 WHERE Trim(CVE_DOC) = Trim('$docr')";
        $this->query = $partrec;
        $rsprecr = $this->EjecutaQuerySimple();
        $row6 = ibase_fetch_object($rsprecr);
        $partotrec = $row6->PARTOT;
        //echo " Total de las partidas del Documento: ".$partotrec;

        if ($partotrec == $parrec) {
            $actrecep_status_rec = "UPDATE compr01 set status_rec = 'ok' where trim(cve_doc) = trim('$docr')";
            $this->query = $actrecep_status_rec;
            $result = $this->EjecutaQuerySimple();
            //	echo $actrecep_status_rec;
        }

        /*
          //// Inicia la actualizacion del Pedido para validar si se puede preparar.

          $b="SELECT max(par) as PAR from preoc01 where cotiza = '$doc' group by cotiza";
          $this->query=$b;
          $result=$this->QueryObtieneDatosN();
          $row = ibase_fetch_object($result);
          $partidas=$row->PAR;

          $c="SELECT iif(count(id)= 0, 0,count(id)) as PARPEND from preoc01 where emp_status = 'pendiente' and cotiza = '$doc' group by cotiza";
          $this->query= $c;
          $result = $this->QueryObtieneDatosN();
          $row = ibase_fetch_object($result);
          @$parpen = $row->PARPEND;


          $d="SELECT iif(count(id) is null, 0, count(id)) as PARPAR from preoc01 where emp_status = 'parcial' and cotiza = '$doc' group by cotiza";
          $this->query= $d;
          $result = $this->QueryObtieneDatosN();
          $row = ibase_fetch_object($result);
          @$parpar = $row->PARPAR;

          if ($parpen == $partidas){
          $update= "UPDATE FACTP01 SET EMP_STATUS='pendiente' where cve_doc = '$doc'";
          }elseif ($partidas == $parcom){
          $update= "UPDATE FACTP01 SET EMP_STATUS='completo' where cve_doc = '$doc'";
          }elseif($partidas == $parpar){
          $update= "UPDATE FACTP01 SET EMP_STATUS='parcial' where cve_doc = '$doc'";
          }else{
          $update= "UPDATE FACTP01 SET EMP_STATUS='eparcial' where cve_doc = '$doc'";
          }
          $this->query=$update;
          $result = $this->EjecutaQuerySimple();

         */
        return $result;
    }

    function verRecepcion() {
        $this->query = "SELECT a.*, b.NOMBRE, c.OPERADOR, d.cve_doc as Recepcion
    				  from compo01 a
    				  left join prov01 b on a.cve_clpv = b.clave
    				  left join unidades c on a.unidad = c.numero
    				  left join compr01 d on a.doc_sig = d.cve_doc
    				  where (Status_log = 'Total' or Status_log = 'Parcial' or Status_log = 'PNR') and a.status_rec is null ";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function StatusNoSuministrable($id) {
        $this->query = "UPDATE PREOC01
						SET
							status = 'S',
							MOTIVOS_NOSUMINISTRABLE = IIF(MOTIVOS_NOSUMINISTRABLE IS NULL, OBS, MOTIVOS_NOSUMINISTRABLE || '/' || OBS),
							OBS = NULL
						 WHERE id = '$id'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function verRecepV() {
        $recepv = "SELECT a.*, b.nombre, c.cve_doc as OC, d.numero, c.unidad, d.operador
    	         from compr01 a
    	         left join prov01 b on a.cve_clpv = b.clave
    	         left join compo01 c on a.doc_ant = c.cve_doc
    	         left join unidades d on c.unidad = d.numero
    	         where a.status_rec is not null";
        $this->query = $recepv;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ConsultaPagadas() {
        /* $this->query ="SELECT recepciones.CVE_DOC AS recepcion,ordenes.CVE_DOC AS orden, proveedor.nombre AS proveedor,
          recepciones.importe,recepciones.folio,ordenes.STATUS_PAGO
          FROM
          (compr01 recepciones
          INNER JOIN prov01 proveedor
          ON proveedor.clave = recepciones.cve_clpv)
          INNER JOIN compo01 ordenes
          ON recepciones.doc_ant = ordenes.cve_doc
          WHERE ordenes.status_pago = 'PP'";
         */
        $this->query = "SELECT recepciones.CVE_DOC AS recepcion,ordenes.CVE_DOC AS orden, proveedor.nombre AS proveedor,
    					recepciones.importe,recepciones.folio,ordenes.STATUS_PAGO
    					FROM
        					(compr01 recepciones
       						 INNER JOIN prov01 proveedor
       						 ON proveedor.clave = recepciones.cve_clpv)
     					   	INNER JOIN compo01 ordenes
        					ON recepciones.doc_ant = ordenes.cve_doc
    						WHERE recepciones.status_rec is not null";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ActRecepNo($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc) {

        $dif = $cantorig;
        //echo "Esta es la diferencia : ".$dif;
        /// Obtener los pendientes por recibir actuales:
        $pxra = "SELECT pxr from par_compo01 where cve_doc = '$doco' and id_preoc = '$idpreoc'";
        $this->query = $pxra;
        $respxr = $this->EjecutaQuerySimple();
        $row4 = ibase_fetch_object($respxr);
        $pxrnow = $row4->PXR;
        $npxr = $pxrnow + $dif;
        //echo "Obtener el pendiente por recibir actual: ".$pxra;
        //echo "Esta es la diferecia : ".$dif;
        //echo "Este es el nuevo pendiente por recibir : ".$npxr;
        $actdif = "UPDATE par_compo01 set pxr = $npxr where trim(cve_doc) = trim('$doco') and id_preoc = '$idpreoc'";
        $this->query = $actdif;
        $result = $this->EjecutaQuerySimple();
        //echo "Cosnulta para actualizar el PXR : ".$actdif;
        //// Actualiza los estatus de recepcion de partidas.
        $recepo = "UPDATE par_compo01 set status_rec = 'ko' where cve_doc = '$doco' and id_preoc = $idpreoc";
        $recepr = "UPDATE par_compr01 set status_rec = 'ko' where cve_doc = '$docr' and id_preoc = $idpreoc";
        $this->query = $recepo;
        $result = $this->EjecutaQuerySimple();
        $this->query = $recepr;
        $result = $this->EjecutaQuerySimple();
        //// Actualiza las cantidades recibidas y los costos recibidos.
        $costtp = $cantn * $coston;
        $actparr = "UPDATE par_compr01 set cant_rec = 0, cost_rec = 0 where cve_doc = '$docr' and id_preoc = $idpreoc";
        $this->query = $actparr;
        $result = $this->EjecutaQuerySimple();
        /// ACTUALIZA NUEVOS TOTALES
        /// Obtenemos el costo todal del documento a la fecha.
        $costotot = "SELECT SUM(iif(cost_rec is null, 0, cost_rec)) FROM PAR_COMPR01 WHERE TRIM(CVE_DOC) = TRIM('$docr') and status_rec is not null";
        $this->query = $costotot;
        $rct = $this->EjecutaQuerySimple();
        $row3 = ibase_fetch_row($rct);
        $nct = $row3[0];
        //echo "Este es el nuevo costo: ".$nct;
        $actcostdoc = "UPDATE compr01 set Cost_rec = $nct where TRIM(cve_doc) = Trim('$docr')";
        $this->query = $actcostdoc;
        $result = $this->EjecutaQuerySimple();
        //echo "Actualiza Totales : ".$actcostdoc;
        ////Se define si el documento de Orde de compra se vuelve a mostrar para su validacion de partidas restantes.
        $partidas = "SELECT count(num_par) as PARTIDAS from par_compo01 where cve_doc = '$doco' and status_rec is not null";
        $this->query = $partidas;
        $rspoc = $this->EjecutaQuerySimple();
        $row = ibase_fetch_row($rspoc);
        $paroc = $row[0];
        //echo $partidas;
        //echo "Este es el total de partidad comprobadas: ".$paroc;
        $part = "SELECT max(num_par) as PARTOT FROM PAR_COMPO01 WHERE CVE_DOC = '$doco'";
        $this->query = $part;
        $rspocr = $this->EjecutaQuerySimple();
        $row2 = ibase_fetch_object($rspocr);
        $partot = $row2->PARTOT;
        //echo " Total de las partidas del Documento: ".$partot;
        if ($partot == $paroc) {
            $actrecep_status = "UPDATE compo01 set status_rec = 'OK' where cve_doc = '$doco'";
            $this->query = $actrecep_status;
            $result = $this->EjecutaQuerySimple();
            //	echo $actrecep_status;
        }

        ///Se define si se visualiza en el area de imprimir comprbante.

        $partidasrec = "SELECT count(num_par) as PARTIDAS from par_compr01 where trim(cve_doc) = trim('$docr') and status_rec is not null";
        $this->query = $partidasrec;
        $rsprec = $this->EjecutaQuerySimple();
        $row5 = ibase_fetch_row($rsprec);
        $parrec = $row5[0];
        //echo $partidasrec;
        //echo "Este es el total de partidad comprobadas: ".$parrec;

        $partrec = "SELECT max(num_par) as PARTOT FROM PAR_COMPR01 WHERE Trim(CVE_DOC) = Trim('$docr')";
        $this->query = $partrec;
        $rsprecr = $this->EjecutaQuerySimple();
        $row6 = ibase_fetch_object($rsprecr);
        $partotrec = $row6->PARTOT;
        //echo " Total de las partidas del Documento: ".$partotrec;

        if ($partotrec == $parrec) {
            $actrecep_status_rec = "UPDATE compr01 set status_rec = 'ok' where trim(cve_doc) = trim('$docr')";
            $this->query = $actrecep_status_rec;
            $result = $this->EjecutaQuerySimple();
            //	echo $actrecep_status_rec;
        }
    }

    /*     * *
     * cfa: 210316
     * consulta todas las cotizaciones registradas en la aplicación. Esta consulta es preparada para mostrar el grid
     * en la pantalla de p.cotizacion.php
     * * */

    function consultarCotizaciones($cerradas = false) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "SELECT CDFOLIO as folio, CVE_CLIENTE as cliente, NOMBRE, RFC, IDPEDIDO, INSTATUS as estatus, EXTRACT(DAY FROM DTFECREG) || '/' || EXTRACT(MONTH FROM DTFECREG) || '/' || EXTRACT(YEAR FROM DTFECREG) AS FECHA
            FROM FTC_COTIZACION A INNER JOIN CLIE01 B
              ON TRIM(A.CVE_CLIENTE) = TRIM(B.CLAVE)
            WHERE CDUSUARI = '$usuario'";
        $cerradas ? $this->query .= " AND upper(INSTATUS) <> upper('PENDIENTE') " : $this->query .= " AND upper(INSTATUS) = upper('PENDIENTE') ";
        $this->query .= " ORDER BY CDFOLIO";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function cabeceraCotizacion($folio) {
        $this->query = "SELECT CDFOLIO, CVE_CLIENTE, NOMBRE, RFC, INSTATUS, DSIDEDOC, IDPEDIDO, EXTRACT(DAY FROM DTFECREG) || '/' || EXTRACT(MONTH FROM DTFECREG) || '/' || EXTRACT(YEAR FROM DTFECREG) AS FECHA,
                                DSPLANTA, DSENTREG, DBIMPSUB, DBIMPIMP, DBIMPTOT
                          FROM FTC_COTIZACION A INNER JOIN CLIE01 B
                            ON TRIM(A.CVE_CLIENTE) = TRIM(B.CLAVE)
                        WHERE CDFOLIO = '$folio'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function detalleCotizacion($folio) {
        $this->query = "SELECT CDFOLIO, A.CVE_ART, DESCR, FLCANTID, DBIMPCOS, DBIMPPRE, DBIMPDES
                          FROM FTC_COTIZACION_DETALLE A
                        INNER JOIN INVE01 B
                          ON A.CVE_ART = B.CVE_ART
                        WHERE CDFOLIO = '$folio'";
        $result = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function listaArticulos($cliente, $articulo, $descripcion) {
        $data = array();
        if ($articulo != '') {
            $this->query = "SELECT A.CVE_ART, DESCRIPCION, COSTO, PRECIO
                              FROM FTC_Articulos A INNER JOIN PRECIO_X_PROD01 B
                                ON A.CVE_ART = B.CVE_ART
                                AND A.CVE_ART = '" . $articulo . "'
                                AND CVE_PRECIO = (SELECT LISTA_PREC FROM CLIE01 WHERE TRIM(CLAVE) = '" . $cliente . "')";
        } elseif ($descripcion != '') {
            $this->query = "SELECT A.CVE_ART, DESCRIPCION, COSTO, PRECIO
                              FROM FTC_Articulos A INNER JOIN PRECIO_X_PROD01 B
                                ON A.CVE_ART = B.CVE_ART
                                AND upper(DESCRIPCION) LIKE upper('%" . $descripcion . "%')
                                AND CVE_PRECIO = (SELECT LISTA_PREC FROM CLIE01 WHERE TRIM(CLAVE) = '" . $cliente . "')";
        } else {
            return $data;
        }

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function actualizaTotales($folio) {
        $this->query = "SELECT FLCANTID, DBIMPPRE, DBIMPDES FROM FTC_COTIZACION_DETALLE WHERE CDFOLIO = $folio";
        $result = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        $subtotal = 0;
        $impuesto = 0;
        $descuento = 0;
        $total = 0;
        if (count($data) > 0) {
            foreach ($data as $row) {
                $cantidad = $row->FLCANTID;
                $precio = $row->DBIMPPRE;
                $descuentoPartida = $row->DBIMPDES;
                $subtotalPartida = round($cantidad * $precio, 2) - round($cantidad * $descuentoPartida, 2);
                //echo "Subtotal Partida: $subtotalPartida <br />";
                $subtotal += $subtotalPartida;
                $descuento += $descuentoPartida;
                //echo "Subtotal: $subtotal <br />";
            }
            $descuento = round($descuento, 2);
            $impuesto = round(($subtotal * 0.16), 2);
            //echo "Impuesto: $impuesto <br />";
            $total = round(($subtotal + $impuesto), 2);
            //echo "Total: $total <br />";
        }
        $this->query = "UPDATE FTC_COTIZACION SET DBIMPSUB = $subtotal, DBIMPIMP = $impuesto, DBIMPTOT = $total, DBIMPDES = $descuento "
                . " WHERE CDFOLIO = $folio";

        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function insertaCotizacion($cliente, $identificadorDocumento) {
        $folio = 1;
        $this->query = "SELECT MAX(cdfolio)+1 folio FROM FTC_COTIZACION";
        $result = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        if (count($data) > 0) {
            foreach ($data as $row) {
                $folio = $row->FOLIO;
            }
        }
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "INSERT INTO FTC_COTIZACION (CDFOLIO, CVE_CLIENTE, DSIDEDOC, DTFECREG, INSTATUS, DBIMPSUB, DBIMPIMP, DBIMPTOT, DSPLANTA, DSENTREG, CDUSUARI) "
                . "VALUES ($folio, TRIM('$cliente'), '$identificadorDocumento', CAST('Now' as date),'PENDIENTE',0,0,0,(SELECT COALESCE(CAMPLIB7, '') FROM CLIE_CLIB01 WHERE TRIM(CVE_CLIE) = TRIM('$cliente')),(SELECT COALESCE(CAMPLIB8, '') FROM CLIE_CLIB01 WHERE TRIM(CVE_CLIE) = TRIM('$cliente')),'$usuario')";

        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function avanzaCotizacion($folio) {
        $this->generaDocumentoCotizacion($folio);

        $this->query = "UPDATE FTC_COTIZACION SET INSTATUS = 'CERRADA' WHERE CDFOLIO = $folio";
        //echo "<br />query: ".$this->query;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function generaDocumentoCotizacion($folio) {
        $this->query = "SELECT CDFOLIO, CVE_CLIENTE, DSIDEDOC, IDPEDIDO, DBIMPSUB, DBIMPIMP, DBIMPTOT, DBIMPDES FROM FTC_COTIZACION WHERE CDFOLIO = $folio";
        $result = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        $existeFolio = false;
        if (count($data) > 0) {
            $existeFolio = true;
            foreach ($data as $row) {
                $folio = $row->CDFOLIO;
                $cliente = $row->CVE_CLIENTE;
                $letra = $row->DSIDEDOC;
                $pedido = $row->IDPEDIDO;
                $subtotal = $row->DBIMPSUB;
                $impuesto = $row->DBIMPIMP;
                $total = $row->DBIMPTOT;
                $descuento = $row->DBIMPDES;
            }
        }
        $serie = 'C' . substr($letra, 1);
        //echo "serie: $serie";
        if (!$existeFolio) {
            return NULL;
        } else {
            $usuario = $_SESSION['user']->USER_LOGIN;
            $consecutivo = $this->obtieneConsecutivoClaveDocumento($serie);
            $cve_doc = $letra . $consecutivo;
        }

        $insert = "INSERT INTO FACTC01 ";
        $insert .= "(TIP_DOC, CVE_DOC, CVE_CLPV, STATUS, CVE_PEDI, FECHA_DOC, FECHA_ENT, CAN_TOT, IMP_TOT1, IMP_TOT2, IMP_TOT3, IMP_TOT4, DES_TOT, DES_FIN, IMPORTE, CVE_OBS, NUM_ALMA, ACT_COI, NUM_MONED, TIPCAMB, ENLAZADO, TIP_DOC_E, NUM_PAGOS, FECHAELAB, SERIE, FOLIO, CTLPOL, ESCFD, CONTADO, BLOQ, DES_FIN_PORC, DES_TOT_PORC, TIP_DOC_ANT, DOC_ANT, TIP_DOC_SIG, DOC_SIG, FORMAENVIO, REALIZA)";
        $insert .= "VALUES";
        $insert .= "('C' ,'$cve_doc', (SELECT CLAVE FROM CLIE01 WHERE TRIM(CLAVE) = TRIM('$cliente')), 'O' , '$pedido' , CAST('Now' as date), CAST('Now' as date), $subtotal, 0, 0, 0 , $impuesto, $descuento, 0, $total, 0, 9, 'N', 1, 1, 'O', 'O',NULL, CAST('Now' as date),'$serie', $consecutivo, 0, 'N', 'N', 'N', 0 , 0, '', '',  '', '', '', '$usuario')";
        //echo "insert: ".$insert;
        $this->query = $insert;
        $rs = $this->EjecutaQuerySimple();

        $this->query = "SELECT CVE_ART, DBIMPCOS, FLCANTID, DBIMPPRE, DBIMPDES FROM FTC_COTIZACION_DETALLE WHERE CDFOLIO = $folio";
        $result = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        if (count($data) > 0) {
            foreach ($data as $row) {
                $cve_art = $row->CVE_ART;
                $costo = $row->DBIMPCOS;
                $cantidad = $row->FLCANTID;
                $precio = $row->DBIMPPRE;
                $descuentoPartida = $row->DBIMPDES;
                $subtotalPartida = round($cantidad * $precio, 2) - round($cantidad * $descuentoPartida, 2);
                //echo "Subtotal Partida: $subtotalPartida <br />";
                $subtotal += $subtotalPartida;
                $descuento += $descuentoPartida;
                //echo "Subtotal: $subtotal <br />";
                $actualiza = "INSERT INTO PAR_FACTC01
                (CVE_DOC, NUM_PAR, CVE_ART,CANT, PREC, COST, IMPU1,IMPU2, IMPU3, IMPU4, IMP1APLA, IMP2APLA, IMP3APLA, IMP4APLA,TOTIMP1, TOTIMP2,TOTIMP3,TOTIMP4,DESC1,ACT_INV, TIP_CAM, UNI_VENTA,TIPO_ELEM, TIPO_PROD, CVE_OBS, E_LTPD, NUM_ALM, NUM_MOV, TOT_PARTIDA, USUARIO_PHP)
                VALUES
                ('$cve_doc',(SELECT COALESCE(MAX(NUM_PAR), 0) FROM PAR_FACTC01) + 1,'$cve_art',$cantidad,$precio,$costo,0,0,0,16,0,0,0,0,0,0,0,$impuesto,$descuento,'N',1,(SELECT UNI_MED FROM INVE01 WHERE CVE_ART = '$cve_art'),'P','P',0,0,9,NULL,($subtotalPartida),'$usuario')";
                //echo "<br />UPDATE: ".$actualiza;
                $this->query = $actualiza;
                $rs = $this->EjecutaQuerySimple();
            }
        }
        return $rs;
    }

    function obtieneConsecutivoClaveDocumento($letra) {
        $this->query = "SELECT COALESCE(MAX(FOLIO), 1)+1 FOLIO FROM FACTC01 WHERE TIP_DOC = 'C' AND SERIE = '$letra'";
        $result = $this->QueryObtieneDatosN();
        //echo "query: ".$this->query;
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        $consecutivo = 1;
        if (count($data) > 0) {
            foreach ($data as $row) {
                $consecutivo = $row->FOLIO;
            }
        }
        //echo "consecutivo : $consecutivo";
        return $consecutivo;
    }

    function actualizaPedidoCotizacion($folio, $pedido) {
        $this->query = "UPDATE FTC_COTIZACION SET IDPEDIDO = '$pedido' WHERE CDFOLIO = $folio";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function cancelaCotizacion($folio) {
        $this->query = "UPDATE FTC_COTIZACION SET INSTATUS = 'CANCELADA' WHERE CDFOLIO = $folio";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function quitarCotizacionPartida($folio, $partida) {
        $this->query = "DELETE FROM FTC_COTIZACION_DETALLE WHERE CDFOLIO = $folio AND CVE_ART = '$partida'";
        $rs = $this->EjecutaQuerySimple();
        $this->actualizaTotales($folio);
        return $rs;
    }

    function actualizaCotizacion($folio, $partida, $articulo, $precio, $descuento, $cantidad) {
        if ($partida != '') {
            $this->query = "UPDATE FTC_COTIZACION_DETALLE SET "
                    . " CVE_ART = '$articulo', FLCANTID = $cantidad, DBIMPCOS = (SELECT MAX(costo) FROM PRVPROD01 A WHERE A.CVE_ART = '$articulo' GROUP BY cve_art), DBIMPPRE = $precio, DBIMPDES = $descuento "
                    . " WHERE CDFOLIO = '$folio' AND CVE_ART = '$partida'";
        } else {
            $this->query = "INSERT INTO FTC_COTIZACION_DETALLE "
                    . "(CDFOLIO,CVE_ART,FLCANTID,DBIMPPRE,DBIMPCOS,DBIMPDES)"
                    . "VALUES ('$folio','$articulo',$cantidad,$precio,(SELECT MAX(costo) FROM PRVPROD01 A WHERE A.CVE_ART = '$articulo' GROUP BY cve_art), $descuento)";
        }
        $rs = $this->EjecutaQuerySimple();
        $this->actualizaTotales($folio);
        return $rs;
    }

    function moverClienteCotizacion($folio, $cliente) {
        $this->query = "UPDATE FTC_COTIZACION SET CVE_CLIENTE = TRIM('$cliente') WHERE CDFOLIO = $folio";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function autocompletaArticulo($descripcion) {
        $this->query = "SELECT DESC FROM INVE01 WHERE DESC LIKE '$descripcion%'";
        $result = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray->descripcion;
        }
        $json = json_encode($data);
        return $json;
    }

    function listadoClientes($clave, $cliente) {
        $data = array();
        $usuario = $_SESSION['user']->USER_LOGIN;
        $select_letras = ", (SELECT COALESCE(LETRA, '') || ',' || COALESCE(LETRA2, '') || ',' || COALESCE(LETRA3, '') || ',' || COALESCE(LETRA4, '') || ',' || COALESCE(LETRA5, '') LETRAS ";
        $select_letras .= " FROM PG_USERS ";
        $select_letras .= " WHERE USER_LOGIN = '$usuario') letras";
        if ($clave != '') {
            $this->query = "SELECT TRIM(CLAVE) CLAVE, STATUS, NOMBRE, RFC " . $select_letras . " FROM CLIE01 WHERE STATUS <> 'S' AND TRIM(CLAVE) = '$clave'";
        } elseif ($cliente != '') {
            $this->query = "SELECT TRIM(CLAVE) CLAVE, STATUS, NOMBRE, RFC " . $select_letras . " FROM CLIE01 WHERE upper(NOMBRE) LIKE upper('%$cliente%') AND STATUS <> 'S'";
        } else {
            return $data;
        }
        $result = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function listadoLetras() {
        $usuario = $_SESSION['user'];
        $this->query = "SELECT COALESCE(LETRA, '') || ',' || COALESCE(LETRA2, '') || ',' || COALESCE(LETRA3, '') || ',' || COALESCE(LETRA4, '') || ',' || COALESCE(LETRA5, '') LETRAS";
        $this->query .= " FROM PG_USERS ";
        $this->query .= " WHERE USER_LOGIN = '$usuario'";
        $data = array();
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        $letras = "";
        if (count($data) > 0) {
            foreach ($data as $row) {
                $letras = $row->LETRAS;
            }
        }
        $myArray = explode(',', $letras);
        print_r($myArray);
        return $myArray;
    }

////// FINALIZA COTIZACION CFA-
///// Modulo de productos almacen 10.
    function VerCat10($alm) {
        $prod = "SELECT * from PRODUCTOS WHERE ACTIVO = 'S'";
        $this->query = $prod;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /*
      function EditProd($id){
      $this->query="SELECT * from PRODUCTOS where id =$id";
      $result=$this->QueryObtieneDatosN();
      while ($tsArray=ibase_fetch_object($result)){
      $data[]=$tsArray;
      }
      return $data;
      }
     */

    //// CAMBIOS OFA 25 04 2016
    function ARutaEntrega() {
        $entrega = "SELECT iif(a.docs is null, 'No', a.docs) as DOCS, a.*, c.nombre, c.estado, c.codigo, b.fechaelab, (datediff(day, a.fecha_creacion, current_date)) as DIAS, a.Factura as Factura, e.cve_doc as remisiondoc
		          from CAJAS a
		          LEFT JOIN FACTP01 d ON a.cve_fact = d.cve_doc
		          LEFT JOIN FACTF01 b ON a.factura = b.cve_doc
		          LEFT JOIN CLIE01 c ON d.cve_clpv = c.clave
		          LEFT JOIN FACTR01 e on a.remision = e.cve_doc
		          where a.ruta = 'N' AND a.STATUS = 'cerrado' and fecha_creacion >='08.08.2016' and (factura is not null or remision is not null)"; // Material completo.   and (b.fechaelab >= '06/30/2016' or d.fechaelab >= '06/30/2016')
        $this->query = $entrega;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function FacturaSinMaterial() {
        $SinMaterial = "SELECT a.*, (datediff(day, fechaelab, current_date )) as Dias, b.*
					  FROM FACTF01 a
					  left join CLIE01 b on a.cve_clpv = b.clave
				      WHERE STATUS_MAT IS NULL";
        $this->query = $SinMaterial;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function FacturasSinMat($docf) {
        $doc = $docf;
        $p = 'P';
        $pedido = strpos($doc, $p);
        if ($pedido !== false) {  /// hace la consulta del Pedido,
            $SinMaterial = "SELECT a.*, (datediff(day, fechaelab, current_date )) as Dias, b.*
					  FROM FACTP01 a
					  left join CLIE01 b on a.cve_clpv = b.clave
				      WHERE cve_doc = '$docf'";
            $this->query = $SinMaterial;
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($result)) {
                $data[] = $tsArray;
            }
        } else { // hace la consulta de la factura.
            $SinMaterial = "SELECT a.*, (datediff(day, fechaelab, current_date )) as Dias, b.*
					  FROM FACTF01 a
					  left join CLIE01 b on a.cve_clpv = b.clave
				      WHERE cve_doc = '$docf'";
            $this->query = $SinMaterial;
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($result)) {
                $data[] = $tsArray;
            }
        }
        return $data;
    }

    function ParFactMaterial($docf) {
        $doc = $docf;
        $p = 'P';
        $pedido = strpos($doc, $p);

        if ($pedido !== false) {
            $parmat = "SELECT a.*, b.*, c.uni_med, d.recepcion, d.rec_faltante
		         FROM PAR_FACTP01 a
		         left join inve_clib01 b on a.cve_art =  b.cve_prod
		         left join inve01 c on a.cve_art = c.cve_art
		         left join preoc01 d on a.id_preoc = d.id
		         WHERE (STATUS_MAT <> 'OK' OR STATUS_MAT IS NULL) AND CVE_DOC = '$docf'";

            $this->query = $parmat;
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($result)) {
                $data[] = $tsArray;
            }
        } else {
            $parmat = "SELECT a.*, b.*, c.uni_med, d.recepcion, d.rec_faltante
		         FROM PAR_FACTF01 a
		         left join inve_clib01 b on a.cve_art =  b.cve_prod
		         left join inve01 c on a.cve_art = c.cve_art
		         left join preoc01 d on a.id_preoc = d.id
		         WHERE (STATUS_MAT <> 'OK' OR STATUS_MAT IS NULL) AND CVE_DOC = '$docf'";
            $this->query = $parmat;
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($result)) {
                $data[] = $tsArray;
            }
        }
        return $data;
    }

    function EditProd($id) {
        $this->query = "SELECT a.*, b.NOMBRE AS proveedor from PRODUCTOS a LEFT JOIN Prov01 b ON a.PROV1 = b.CLAVE where a.id =$id";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AltaProductos($clave, $descripcion, $marca1, $categoria, $desc1, $desc2, $desc3, $desc4, $desc5, $iva, $costo_total, $clave_prov, $codigo_prov1, $costo_prov1, $prov2, $codigo_prov2, $costo_prov2, $unidadcompra, $factorcompra, $unidadventa, $factorventa) {
        $this->query = "EXECUTE PROCEDURE sp_producto_nuevo
                        ('$clave','$descripcion','$marca1','$categoria','$desc1','$desc2','$desc3','$desc4','$desc5','$iva','$costo_total','" . rtrim($clave_prov) . "','$codigo_prov1','$costo_prov1','$prov2','$codigo_prov2','$costo_prov2','$unidadcompra','$factorcompra','$unidadventa','$factorventa')";

        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function ActualizaProductos($id, $clave, $descripcion, $marca1, $categoria, $desc1, $desc2, $desc3, $desc4, $desc5, $iva, $costo_total, $clave_prov, $codigo_prov1, $costo_prov1, $prov2, $codigo_prov2, $costo_prov2, $unidadcompra, $factorcompra, $unidadventa, $factorventa, $activo) {
        $this->query = " EXECUTE PROCEDURE sp_modifica_producto
                                             ('$id','$clave','$descripcion','$marca1','$categoria',$desc1,$desc2,$desc3,$desc4,$desc5,$iva,$costo_total,'" . rtrim($clave_prov) . "','$codigo_prov1',$costo_prov1,'$prov2','$codigo_prov2',$costo_prov2,'$unidadcompra',$factorcompra,'$unidadventa',$factorventa,'$activo')";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function DatosPreorden($id) {
        $this->query = "SELECT a.ID, a.PROD, a.COTIZA, a.PAR, a.NOMPROD, a.CANTI, a.COSTO,a.PROVE, a.NOM_PROV, a.OBS, a.MOTIVOS_NOSUMINISTRABLE, b.CAMPLIB1 AS MARCA, c.PREC AS PRECIO
						FROM (PREOC01 a
						LEFT JOIN par_factp01 c
						ON c.CVE_DOC = a.COTIZA AND c.NUM_PAR = a.PAR)
						LEFT JOIN INVE_CLIB01 b
						ON a.PROD = b.CVE_PROD
						WHERE ID = '$id'";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /* 	function UpdateParFactP($cotizacion,$partida){
      $this->query = "UPDATE PAR_FACTP01 SET PXS = 0, STATUS_PRE = 'M' WHERE  CVE_DOC = '$cotizacion' AND NUM_PAR = '$partida'";
      $resultado = $this->EjecutaQuerySimple();
      return $resultado;
      } */

    function UpdatePreoc($idPreorden, $motivo, $costo, $claveproveedor, $nombreproveedor) {
        $prove = rtrim($claveproveedor);
        $nomprov = trim($nombreproveedor);
        $this->query = "UPDATE PREOC01
						SET STATUS = 'N',
							OBS = 'Ventas Modifica: ' || '$motivo',
							COSTO = '$costo',
							TOTAL = (CANTI * $costo),
							PROVE = '$prove',
							NOM_PROV = '$nomprov'
						 WHERE  ID = '$idPreorden'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function RegistroOperadores($docu, $unidad) {
        $this->query = "SELECT a.FECHA_DOC, a.URGENCIA, b.CAMPLIB3, a.TIP_DOC, b.CAMPLIB1, b.CAMPLIB5
						FROM compo01 a LEFT JOIN COMPO_CLIB01 b
						ON a.CVE_DOC = b.CLAVE_DOC
						WHERE a.CVE_DOC = '$docu'";

        $result = $this->QueryObtieneDatosN();
        $compo;
        while ($tsArray = ibase_fetch_row($result)) {
            $compo = $tsArray;
        }

        $this->query = "SELECT OPERADOR FROM UNIDADES WHERE NUMERO = '$unidad'";
        $resultado = $this->QueryObtieneDatosN();
        $uni;
        while ($TsArray = ibase_fetch_row($resultado)) {
            $uni = $TsArray;
        }
        //var_dump($compo); echo "\n";
        $this->query = "
			INSERT INTO REGISTRO_OPERADORES(OPERADOR,FECHAASIG,UNIDAD,DOCUMENTO,FECHADOC,URGENCIA,CITA,TIPO,FORMAPAGO,FOLIO_FP,RESULTADO)
			VALUES('$uni[0]',CURRENT_DATE,'$unidad','$docu',LEFT('$compo[0]',10),'$compo[1]',LEFT('$compo[2]',10),'$compo[3]','$compo[4]','$compo[5]','secuencia')";

        $rs = $this->EjecutaQuerySimple();
        //var_dump($this->query);
        return $rs;
    }

    function ActRecepRO($idordencompra, $congruencia) {
        $this->query = "UPDATE REGISTRO_OPERADORES SET ESTVSREAL = '$congruencia' WHERE DOCUMENTO = '$idordencompra'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function ActEmpaque($docf, $par, $canto, $idpreoc, $cantn, $empaque, $art, $desc, $idcaja) {
        $doc = $docf;
        $p = 'P';
        $pedido = strpos($docf, $p);
        $tabla = 'PAR_FACTF01';
        if ($pedido !== false) {
            $tabla = 'PAR_FACTP01';
        }
        $cantval = "SELECT CANT_VAL, CANT FROM $tabla WHERE CVE_DOC = '$docf' and NUM_PAR = $par";
        $this->query = $cantval;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $cantact = $row->CANT_VAL;
        $cantot = $cantact + $cantn;
        $cantidad = $row->CANT;
        //echo "Estas es el resultado de la cantidad actual y la nueva cantidad: ".$cantot;
        if ($canto == $cantot) {
            $act = "UPDATE $tabla SET CANT_VAL = iif(cant_val is null, $cantn, cant_val + $cantn), pxs= (pxs + $cantn), NUM_EMPAQUE = $empaque, STATUS_MAT = 'OK' where cve_doc = '$docf' and num_par = $par";
            $act2 = "UPDATE FACTP01 SET ENLAZADO ='O' WHERE CVE_DOC = '$docf'";
        } elseif ($canto > $cantidad) {
            $act = "UPDATE $tabla SET CANT_VAL = iif(cant_val is null, $cantn, cant_val + $cantn), pxs=($cantn), NUM_EMPAQUE = $empaque, STATUS_MAT = 'OK', cant_error=($canto - $cantidad) where cve_doc = '$docf' and num_par = $par";
            $act2 = "UPDATE FACTP01 SET ENLAZADO ='O' WHERE CVE_DOC = '$docf'";
        } elseif ($canto <> $cantot) {
            $act = "UPDATE $tabla SET CANT_VAL = iif(cant_val is null, $cantn, cant_val + $cantn), pxs=(pxs + $cantn), NUM_EMPAQUE = $empaque, STATUS_MAT = 'PAR' where cve_doc = '$docf' and num_par = $par";
            $act2 = "UPDATE FACTP01 SET ENLAZADO ='O' WHERE CVE_DOC = '$docf'";
        }
        $this->query = $act;
        $result = $this->EjecutaQuerySimple();
        $this->query = $act2;
        $rs = $this->EjecutaQuerySimple();

        $this->query = "SELECT PXS, CANT FROM PAR_FACTP01 WHERE cve_doc = '$docf' and num_par = $par";
        $rs = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $pxs = $row->PXS;
        $cantidad = $row->CANT;

        if ($pxs > $cantidad) {
            $this->query = "SELECT REMISIONADO, FACTURADO FROM PREOC01 WHERE COTIZA='$docf' and par = $par";
            $rs = $this->EjecutaQuerySimple();
            $row = ibase_fetch_object($rs);
            $rem = $row->REMISIONADO;
            $fac = $row->FACTURADO;

            if ($rem == 0 and $fac == 0) {
                $this->query = "UPDATE PAR_FACTP01 SET PXS=$cantidad where CVE_DOC ='$docf' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($rem != 0 and $fac == 0) {
                $this->query = "UPDATE PAR_FACTP01 SET PXS=($cantidad - $rem) where cve_doc = '$docf' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($rem == 0 and $fac != 0) {
                $this->query = "UPDATE PAR_FACTP01 SET PXS=($cantidad - $fac) where cve_doc = '$docf' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($fac > $rem) {
                $this->query = "UPDATE PAR_FACTP01 SET PXS=($cantidad - $rem) where cve_doc = '$docf' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($fac < $ren) {
                $this->query = "UPDATE PAR_FACTP01 SET PXS=($cantidad - $rem) where cve_doc = '$docf' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($fac == $rem) {
                $this->query = "UPDATE PAR_FACTP01 SET PXS=($cantidad - $rem) where cve_doc = 'docf' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            }
        }


        $result += $this->ActEmpacado($docf, $par, $canto, $idpreoc, $cantn, $empaque, $art, $desc, $idcaja);
        return $result;
    }

    function ActEmpacado($docf, $par, $canto, $idpreoc, $cantn, $empaque, $art, $desc, $idcaja) {
        $a = "UPDATE preoc01 set empacado = empacado + $cantn where id = $idpreoc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function InsPaquete($docf, $par, $canto, $idpreoc, $cantn, $empaque, $art, $desc, $idcaja, $tipopaq) {        //23062016
        //$valpaq="SELECT iif(max(FECHA_PAQUETE) is null, current_date, max(FECHA_PAQUETE)) as Fechaact FROM PAQUETES WHERE DOCUMENTO = '$docf'";
        $valpaq = "SELECT iif(count(DOCUMENTO) IS NULL, 0, COUNT(DOCUMENTO)) AS DOCU FROM PAQUETES WHERE DOCUMENTO ='$docf'";
        //echo $valpaq;
        $this->query = $valpaq;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $doc = $row->DOCU;
        ///echo "Valor de DOCUMENTO: ".$doc;

        if ($doc == 0) {
            //// inserta cuando el documento es nuevo.
            $p = 1;
            $emp = "INSERT INTO PAQUETES (DOCUMENTO, ARTICULO, PARTIDA, DESCRIPCION, CANTIDAD, FECHA_PAQUETE, EMPAQUE,  STATUS_LOG, ID_PREOC, TIPO_EMPAQUE, BASE_TIPO, CONSECUTIVO_TIPO, PAQUETE, FECHA_EMPAQUE, IDCAJA)
		      VALUES ('$docf', '$art', $par, '$desc', $cantn, current_date, $empaque, 'nuevo', $idpreoc, '$tipopaq', 0, 0, $p, current_timestamp, $idcaja)";
            //7echo $emp;
            $this->query = $emp;
            $result = $this->EjecutaQuerySimple();
            return $result;
        } else {
            /// inserta cuando no es nuevo y la fecha es diferente a la original.
            $f = "SELECT MAX(FECHA_PAQUETE) as fechau, MAX(PAQUETE) AS PAQUETE FROM PAQUETES WHERE DOCUMENTO = '$docf'";
            $this->query = $f;
            $result = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($result);
            $fech = $row->FECHAU;
            $pa = $row->PAQUETE;

            if ($fech != date("Y-m-d")) {
                $p = $pa + 1;
                $emp = "INSERT INTO PAQUETES (DOCUMENTO, ARTICULO, PARTIDA, DESCRIPCION, CANTIDAD, FECHA_PAQUETE, EMPAQUE,  STATUS_LOG, ID_PREOC, TIPO_EMPAQUE, BASE_TIPO, CONSECUTIVO_TIPO, PAQUETE, FECHA_EMPAQUE, IDCAJA)
                                                    VALUES ('$docf', '$art', $par, '$desc', $cantn, current_date, $empaque, 'nuevo', $idpreoc, '$tipopaq', 0, 0, $p, current_timestamp, $idcaja)";
                $this->query = $emp;
                $result = $this->EjecutaQuerySimple();
                return $result;
                //echo $emp;
            } else {
                $t = "SELECT MAX(PAQUETE) as PAQ FROM PAQUETES WHERE DOCUMENTO = '$docf' AND FECHA_PAQUETE = current_date";
                $this->query = $t;
                $result = $this->QueryObtieneDatosN();
                $row = ibase_fetch_object($result);
                $pa = $row->PAQ;
                //echo $pa;
                $emp = "INSERT INTO PAQUETES (DOCUMENTO, ARTICULO, PARTIDA, DESCRIPCION, CANTIDAD, FECHA_PAQUETE, EMPAQUE,  STATUS_LOG, ID_PREOC, TIPO_EMPAQUE, BASE_TIPO, CONSECUTIVO_TIPO, PAQUETE, FECHA_EMPAQUE, IDCAJA)
                                                    VALUES ('$docf', '$art', $par, '$desc', $cantn, current_date, $empaque, 'nuevo', $idpreoc, '$tipopaq', 0, 0, $pa, current_timestamp, $idcaja)";
                $this->query = $emp;
                $result = $this->EjecutaQuerySimple();
                return $result;
            }
        }
    }

//// Tiene que actualizar el dumento para que ya no se muestre en la pantalla para actualizar empaque
    function ActEmpaqueDoc($docf, $par, $canto, $idpreoc, $cantn, $empaque) {
        $pedido = strpos($docf, 'P');
        $tabla = 'PAR_FACTP01';
        $tabla2 = 'FACTF01';
        if ($pedido !== false) {
            $tabla = 'PAR_FACTP01';
            $tabla2 = 'FACTP01';
        }
        $part = "SELECT MAX (NUM_PAR) as PARTIDAS FROM $tabla WHERE CVE_DOC = '$docf'";
        $this->query = $part;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $partmax = $row->PARTIDAS;

        $partok = "SELECT COUNT(cve_doc) as PARTOK FROM $tabla WHERE CVE_DOC = '$docf' and STATUS_MAT = 'OK'";
        $this->query = $partok;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $partidasok = $row->PARTOK;

        if ($partidasok == $partmax) {
            $actdoc = "UPDATE $tabla2 SET STATUS_MAT = 'COM' WHERE CVE_DOC = '$docf'";
            $this->query = $actdoc;
            $result = $this->EjecutaQuerySimple();
            return $result;
        }
    }

    function verPaquetes() {
        $paq = "SELECT DOCUMENTO, MAX(EMPAQUE) AS PAQUETE, MAX(FECHA_PAQUETE) AS FECHA FROM PAQUETES WHERE STATUS_LOG='nuevo' group by DOCUMENTO";
        $this->query = $paq;
        $result = $this->QueryObtieneDatosN();
        //echo $paq;
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verPaquetesEmb($docf) {     //23062016
        //$paq="SELECT * from PAQUETES WHERE embalado is null and DOCUMENTO = '$docf' ";
        $this->query = "SELECT  TIPO_ENVIO,DOCUMENTO, IDCAJA, FECHA_PAQUETE, EMPAQUE, TIPO_EMPAQUE
                                from PAQUETES WHERE embalado is null and DOCUMENTO = '$docf'
                                group by  TIPO_ENVIO,DOCUMENTO, IDCAJA, FECHA_PAQUETE, EMPAQUE, TIPO_EMPAQUE ";
        $result = $this->QueryObtieneDatosN();
        //echo $paq;
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verDetallePaq($docf) {      //23062016
        $this->query = "SELECT ID_PREOC, TIPO_ENVIO,DOCUMENTO, IDCAJA, FECHA_PAQUETE, EMPAQUE,ARTICULO,DESCRIPCION, CANTIDAD
                                        from PAQUETES WHERE embalado is null and DOCUMENTO = '$docf'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCajasAbiertas() {
        $a = "SELECT a.id, max(CVE_FACT) as CVE_FACT, max(c.nombre) as NOMBRE, max(empaque) as PAQUETE, max(fecha_creacion) as FECHA_CREACION, max (b.doc_sig) as FACTURA, max(e.fechaelab) as FECHA_FACT
			FROM CAJAS a
			LEFT JOIN FACTP01 b on a.cve_fact = b.cve_doc
			LEFT JOIN CLIE01 c on b.cve_clpv = c.clave
			LEFT JOIN PAQUETES d on a.id = d.idcaja and d.embalado is null
			LEFT JOIN FACTF01 e on b.doc_sig = e.doc_ant or a.cve_fact = e.doc_ant
			WHERE a.STATUS = 'abierto' and (a.embalaje != 'TOTAL' or a.embalaje is null) group by a.id";
        $this->query = $a;

        $result = $this->QueryObtieneDatosN();
        //echo $paq;
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AsignaEmbalaje($docf, $paquete1, $paquete2, $tipo, $peso, $alto, $largo, $ancho, $pesovol, $idc, $idemp) {       //23062016
        $a = "UPDATE PAQUETES SET PAQUETE1=$paquete1, PAQUETE2 = $paquete2, PESO= $peso, LARGO=$largo, ANCHO=$ancho, ALTO=$alto, PESO_VOLUMETRICO=$pesovol, embalado = 'S', fecha_embalaje = current_timestamp where documento = '$docf' and idcaja = $idc and tipo_empaque = '$tipo' AND EMPAQUE = $idemp ";
        echo $a;
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        $c = "SELECT DOCUMENTO, COUNT(PARTIDA) PARTOT, SUM(PESO) AS PESO FROM PAQUETES WHERE documento = '$docf' AND EMBALADO = 'S' GROUP BY DOCUMENTO";
        $this->query = $c;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $parf = $row->PARTOT;
        $peso = $row->PESO;
        $d = "SELECT COUNT(PARTIDA) PARTOTP FROM PAQUETES WHERE documento = '$docf'";
        $this->query = $d;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $parr = $row->PARTOTP;

        if ($parr == $parf) {
            $e = "UPDATE cajas set EMBALAJE = 'TOTAL', peso = $peso where cve_fact='$docf' AND ID = $idc";
            #echo 'Actualiza Caja:'.$e;
            $this->query = $e;
            $result = $this->EjecutaQuerySimple();
            return $result;
        }

        return $result;
    }

    function embalados($docf) {
        $b = "SELECT * FROM PAQUETES WHERE DOCUMENTO = '$docf' and EMBALADO='S'";
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function DataCaja($caja) {
        $c = "SELECT * FROM Cajas WHERE ID = '$caja'";
        $this->query = $c;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function embaladosTotales($docf, $caja) {
        $d = "SELECT documento, count(partida) as partidas, sum(cantidad) as cantidades, sum (peso) as PESO
		 from paquetes
		 where documento = '$doc' and idcaja = $caja group by documento";
        echo $d;
        $this->query = $d;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function CajasXFactura($docf) {
        $this->query = "SELECT * FROM Cajas WHERE CVE_FACT = '$docf'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DataFactCaja($docf) {
        //var_dump($docf);

        /* $c="SELECT CVE_DOC FROM FACTP01 WHERE CVE_DOC = '$docf'";
          echo $c;
          $this->query=$c;
          $result=$this->QueryObtieneDatosN();
          $row=ibase_fetch_object($result);
          $CVE_DOC = $ROW->CVE_DOC;
          return $CVE_DOC;
         */
        $doc = $docf;
        $p = 'P';
        $pedido = strpos($doc, $p);
        if ($pedido !== false) {
            $a = "SELECT CVE_DOC FROM FACTP01 WHERE CVE_DOC = '$docf' GROUP BY CVE_DOC";
            $this->query = $a;
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($result)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT CVE_DOC FROM FACTF01 WHERE CVE_DOC = '$docf' GROUP BY CVE_DOC";
            $resultado = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($resultado)) {
                $data[] = $tsArray;
            }
        }
        return @$data;
    }

    function NuevaCaja($facturanuevacaja) {

        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "SELECT MAX(ENVIO) as envio , MAX(REV_DOSPASOS) as rev_dospasos, MAX(CLIEN) AS CLIEN FROM preoc01 where cotiza = '$facturanuevacaja'";
        //echo $a;
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $envio = $row->ENVIO;
        $revdp = $row->REV_DOSPASOS;
        $cliente = $row->CLIEN;

        $b = "SELECT CARTERA_REVISION, CARTERA_COBRANZA, dias_revision, DIAS_PAGO FROM CARTERA WHERE TRIM(IDCLIENTE) = TRIM($cliente)";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $cr = $row->CARTERA_REVISION;
        $cc = $row->CARTERA_COBRANZA;
        $dr = $row->DIAS_REVISION;
        $dp = $row->DIAS_PAGO;

        $c = "SELECT iif(max(CVE_DOC) is null, '',max(CVE_DOC)) AS FACTURA FROM FACTF01 WHERE DOC_ANT = '$facturanuevacaja' and idc is null ";
        $this->query = $c;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $factura = $row->FACTURA;
        $d = "SELECT iif(max(CVE_DOC) is null, '',max(CVE_DOC)) AS REMISION FROM FACTR01 WHERE DOC_ANT = '$facturanuevacaja' and idc is null";
        $this->query = $d;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $remision = $row->REMISION;

        $vcaja = "SELECT COUNT(STATUS) as STATUS
					FROM CAJAS
    				WHERE (STATUS = 'abierto' or ( STATUS='cerrado' and  FACTURA= '' and REMISION= ''))
    				AND CVE_FACT = '$facturanuevacaja'";
        $this->query = $vcaja;
        $rs = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $val = $row->STATUS;
        if ($val == 0) {
            $this->query = "INSERT INTO CAJAS (FECHA_CREACION,STATUS,CVE_FACT, FACTURA, REMISION, envio, rev_dospasos, usuario_caja, cr, dias_revision, cc, dias_pago, IMP_COMP_REENRUTAR)
					  VALUES(current_timestamp,'abierto','$facturanuevacaja', '$factura','$remision', '$envio', '$revdp', '$usuario','$cr', '$dr', '$cc','$dp', 'Nu')";
            $resultado = $this->EjecutaQuerySimple();
        }

        return $resultado;
    }

    function ValidaCajasAbiertas($facturanuevacaja) {
        $this->query = "SELECT COUNT(STATUS) FROM CAJAS
    				WHERE (STATUS = 'abierto' or ( STATUS='cerrado' and  FACTURA= '' and REMISION= ''))
    				AND CVE_FACT='$facturanuevacaja'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_row($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ConsultaRO($buscar) {
        //var_dump($buscar);
        $this->query = "SELECT a.*, c.nombre AS PROVEEDOR
						FROM REGISTRO_OPERADORES a
						LEFT JOIN (COMPO01 b
						LEFT JOIN PROV01 c ON b.cve_clpv = c.CLAVE)
						ON a.documento = b.CVE_DOC
						WHERE
						 a.DOCUMENTO = '$buscar' OR a.OPERADOR CONTAINING '$buscar' OR a.UNIDAD = '$buscar' ";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($data);
        return @$data;
    }

    function CabeceraConsultaRO($buscar) {
        $this->query = "SELECT FIRST 1 ID, OPERADOR, UNIDAD FROM REGISTRO_OPERADORES WHERE DOCUMENTO = '$buscar' OR OPERADOR CONTAINING '$buscar' OR UNIDAD = '$buscar'
						GROUP BY ID,OPERADOR,UNIDAD ORDER BY ID ASC";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_row($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($data);
        return @$data;
    }

    function RutasDelDia() {
        $this->query = "SELECT a.cve_doc, b.nombre, a.fecha_pago, a.pago_tes, a.tp_tes, a.pago_entregado, c.camplib2 , a.unidad, a.estado, a.fechaelab, (datediff(day, a.fechaelab, current_date )) as Dias, a.urgencia, b.codigo, b.estado as estadoprov, a.unidad
					    from compo01 a
						left join prov01 b on a.cve_clpv = b.clave
						left join compo_clib01 c on a.cve_doc = c.clave_doc
						where a.ruta = 'A' AND idu IS NOT NULL AND STATUS_LOG = 'secuencia'
						AND  FECHA_SECUENCIA >= dateadd(-1 day to current_date)";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function RutasDelDiaEntrega() {
        $a = "SELECT a.*, c.nombre, c.estado, c.codigo, b.fechaelab, b.cita, b.importe, (datediff(day,b.fechaelab,CURRENT_DATE)) as Dias
		    FROM CAJAS a
			LEFT JOIN FACTF01 b on a.cve_fact = b.cve_doc
			LEFT JOIN CLIE01 c on b.cve_clpv = c.clave
			where UNIDAD is not null  ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DataRecepcionesAC() {
        $this->query = "SELECT a.*, b.NOMBRE AS PROVEEDOR, c.OPERADOR, c.NUMERO AS UNIDAD, d.FECHA_SECUENCIA, d.STATUS_LOG
    				  from compr01 a
    				  left join prov01 b on a.cve_clpv = b.clave
    				  left join compo01 d on a.doc_ant = d.cve_doc
    				  left join unidades c on d.unidad = c.numero
    				  where a.status <> 'C' AND  (a.status_rec <> 'ok' OR a.status_rec is null)";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DataRecepcionAC($recepcion) {
        $this->query = "SELECT a.CVE_DOC,a.DOC_ANT,a.FECHAELAB, a.ENLAZADO, b.NOMBRE AS PROVEEDOR, c.OPERADOR, c.NUMERO AS UNIDAD, d.FECHA_SECUENCIA, d.STATUS_LOG
    				  from compr01 a
    				  left join prov01 b on a.cve_clpv = b.clave
    				  left join compo01 d on a.doc_ant = d.cve_doc
    				  left join unidades c on d.unidad = c.numero
    				  where a.CVE_DOC = '$recepcion'
    				  GROUP BY a.CVE_DOC,a.DOC_ANT,a.FECHAELAB, a.ENLAZADO, b.NOMBRE, c.OPERADOR, c.NUMERO, d.FECHA_SECUENCIA, d.STATUS_LOG";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function PartidasRecepcionAC($recepcion) {
        $this->query = "SELECT a.NUM_PAR, b.DESCR, a.CANT, a.COST, a.TOTIMP4 , a.TOT_PARTIDA
    				  FROM PAR_COMPR01 a
    				  LEFT JOIN  inve01 b
    				  ON b.CVE_ART = a.CVE_ART
    				  WHERE CVE_DOC = '$recepcion'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function OrdenSinRecepcion() {
        $this->query = "SELECT a.cve_doc, b.nombre, a.fecha_pago, a.pago_tes, a.tp_tes, a.pago_entregado, c.camplib2 , a.unidad, a.estado, a.fechaelab, (datediff(day, a.fechaelab, current_date )) as Dias, a.urgencia, b.codigo, b.estado as estadoprov, a.unidad
					    from compo01 a
						left join prov01 b on a.cve_clpv = b.clave
						left join compo_clib01 c on a.cve_doc = c.clave_doc
						where STATUS_LOG != 'Nuevo' AND DOC_SIG IS NULL";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function Cajas() {
        $a = "SELECT a.*, c.NOMBRE, d.CAMPLIB7, c.CODIGO, b.FECHAELAB, b.importe, b.cita, (datediff(day, b.fechaelab, current_date)) as DIAS, b.DOC_SIG
		    FROM CAJAS a
		    left join factP01 b on b.cve_doc = a.cve_fact
		    left join clie01 c on b.cve_clpv = c.clave
		    left join CLIE_CLIB01 d on d.cve_clie = c.clave
		    WHERE a.STATUS='abierto'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function CerrarCaja($idcaja, $docf) {
        $a = "UPDATE CAJAS SET STATUS = 'cerrado', ruta = 'N', Docs = 'No' where id = $idcaja and CVE_FACT = '$docf'";
        $this->query = $a;

        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    /* QUITAR FUNCION YA QUE NO TIENE CASO SOLO BORRARLOS  PENDIENTE

      function cierraPar($idcaja, $docf){
      $b="UPDATE PAQUETES SET IDCAJA=NULL, PESO= NULL, LARGO=NULL, ANCHO=NULL, ALTO=NULL WHERE IDCAJA=$idcaja and DOCUMENTO='$docf' AND EMBALADO IS NULL";
      $this->query=$b;
      $result=$this->EjecutaQuerySimple();
      return $result;
      }
     */

    function RutaEntregaSecuencia($idcaja, $docf, $estado, $unidad) {
        #echo "Este es el valor de unidad: ".$unidad;
        $TIME = time();
        $HOY = date("Y-m-d");
        $date = DateTime::createFromFormat('Y-m-d', $HOY);
        $formatdate = $date->format('m-d-Y');
        $idunidad = "SELECT IDU FROM UNIDADES WHERE NUMERO = '$unidad'";
        $this->query = $idunidad;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $idunidad = $row->IDU;

        $this->query = "UPDATE cajas
					  SET UNIDAD = '$unidad', RUTA = 'A', fecha_secuencia = current_timestamp, idu= '$idunidad', STATUS_LOG = 'secuencia', STATUS_MER = '', DOCS = 'No'
					  WHERE CVE_FACT = '$docf'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function AsignaSecEntrega($unidad) {
        $a = "SELECT a.*, c.nombre, c.estado, c.codigo, b.fechaelab, (datediff(day,b.fechaelab,current_date)) as dias,
			b.cve_doc as FACTURA, iif(a.factura is null or a.factura = '', r.importe, b.importe) as importe
		    FROM CAJAS a
		    LEFT JOIN FACTP01 d ON a.cve_fact = d.cve_doc
		    LEFT JOIN FACTF01 b on d.cve_doc = b.doc_ant
		    left join factr01 r on a.remision = r.cve_doc
		    LEFT JOIN CLIE01 c on d.cve_clpv = c.clave
		    where idu = '$unidad' and a.secuencia is null and a.status_log = 'secuencia' ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AsignaSecEntrega2($prove, $secuencia, $uni, $fecha, $idu) {
        $a = "SELECT a.*, c.nombre, c.estado, c.codigo, b.fechaelab, (datediff(day,b.fechaelab,current_date)) as dias,
			b.cve_doc as FACTURA, iif(a.factura is null or a.factura = '', r.importe, b.importe) as importe
		    FROM CAJAS a
		    LEFT JOIN FACTP01 d ON a.cve_fact = d.cve_doc
		    LEFT JOIN FACTF01 b on d.cve_doc = b.doc_ant
		    left join factr01 r on a.remision = r.cve_doc
		    LEFT JOIN CLIE01 c on d.cve_clpv = c.clave
		    where idu = $idu";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AsignaSecuenciaEntrega($idu, $clie, $unidad, $secuencia, $docf, $idcaja) {
        $a = "UPDATE CAJAS
		    SET SECUENCIA = $secuencia, status_log = 'admon', fecha_secuencia = current_timestamp, STATUS_MER =''
		    where id = $idcaja";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function AsignaSec3($idu) {
        $this->query = "SELECT  b.NOMBRE, count (a.cve_doc) as cve_doc, MAX(current_date ) as Fecha, MAX (b.codigo) as codigo, MAX (b.estado) as ESTADOPROV, MAX (b.codigo) as codigo, MAX(unidad) as unidad, MAX (datediff(day, a.fechaelab, current_date )) as Dias, max(a.cve_clpv) as prov, max(idu) as IDU
                       from compo01 a
                       left join PROV01 b on a.cve_clpv = b.clave
                       where a.idu = $idu and secuencia is null
                        group by b.nombre ";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

//################# Ordenes de compra a avanzar #################
    function DataOrdenesAA() {
        $this->query = "SELECT a.CVE_DOC, b.NOMBRE AS PROVEEDOR, a.FECHAELAB, a.IMPORTE, a.STATUS, datediff(day FROM FECHAELAB TO current_date) AS DIAS
						FROM COMPO01 a INNER JOIN PROV01 b
						ON a.CVE_CLPV = b.CLAVE
						WHERE DOC_SIG IS NULL AND STATUS_LOG != 'Falso' AND a.STATUS != 'C'
						ORDER BY DIAS";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DataOrdenAA($idorden) {
        $this->query = "SELECT a.CVE_DOC, b.NOMBRE AS PROVEEDOR, a.FECHAELAB, a.IMPORTE, a.STATUS, datediff(day FROM FECHAELAB TO current_date) AS DIAS
						FROM COMPO01 a INNER JOIN PROV01 b
						ON a.CVE_CLPV = b.CLAVE
						WHERE a.CVE_DOC = '$idorden' ";

        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function PartidasOrdenAA($idorden) {
        $this->query = "SELECT a.CVE_DOC,a.NUM_PAR, a.ID_PREOC, a.CVE_ART, b.DESCR, a.CANT, a.PXR, a.TOT_PARTIDA, a.FECHA_DOC_RECEP
						FROM PAR_COMPO01 a INNER JOIN INVE01 b ON a.CVE_ART = b.CVE_ART
						WHERE a.CVE_DOC = '$idorden' AND PXR > 0  AND FOLIO_FALSO IS NULL";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ObtienFolioFalso() {
        $this->query = "SELECT COUNT(FOLIO_FALSO) FROM COMPO01 WHERE FOLIO_FALSO IS NOT NULL ";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_row($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AvanzaCompo($idorden, $folio) {
        $this->query = "UPDATE COMPO01 SET STATUS_LOG = 'Falso', FOLIO_FALSO = 'F-'||'$folio'   WHERE CVE_DOC = '$idorden'";
        $resultado = $this->EjecutaQuerySimple();

        return $resultado;
    }

    function ObtienFolioFalsoPar() {
        $this->query = "SELECT COUNT(FOLIO_FALSO) FROM PAR_COMPO01 WHERE FOLIO_FALSO IS NOT NULL ";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_row($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function AvanzaParCompo($idorden, $partida, $folio) {
        $this->query = "UPDATE PAR_COMPO01 SET PXR = 0.00, FOLIO_FALSO = 'F-'||'$folio' WHERE CVE_DOC = '$idorden' AND NUM_PAR = '$partida' ";
        $resultado = $this->EjecutaQuerySimple();
        //var_dump($partida);
        return $resultado;
    }

    function ValidarPartidas($idorden) {
        $this->query = "SELECT COUNT(NUM_PAR) FROM PAR_COMPO01 WHERE CVE_DOC = '$idorden' AND FOLIO_FALSO IS NULL";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_row($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

//################# Finaliza orden de compra a avanzar #################
//// Produntos por RFC

    function prodxrfc($rfc) {
        $a = "SELECT a.clave
				from clie01 a
				where RFC = '$rfc'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data2[] = "'" . $tsArray->CLAVE . "'";
            $claves = implode(",", $data2);
        }

        $b = "SELECT A.PROD, max(ID) as ID, max(COTIZA) AS COTIZA, MAX(NOMPROD) AS NOMPROD, SUM(CANTI) AS CANTI, AVG(b.prec) AS PREC, SUM(CANT_ORIG) AS CANT_ORIG, MAX(NOM_CLI) AS NOM_CLI, MAX(CLIEN) AS CLIEN, MAX(FECHASOL) AS FECHASOL, AVG(PREC) AS PREC
				FROM PREOC01 a
				left join par_factp01 b on a.cotiza = b.cve_doc and a.par = b.num_par
				WHERE CLIEN in ($claves) group by PROD";
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DatosUnidad($unidad) {
        $this->query = "SELECT numero, marca, modelo, placas, operador, coordinador FROM unidades WHERE idu = $unidad";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_row($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function recibirDoc($doc) {

        $pedido = strrpos($doc, 'P');
        $tabla = 'COMPO01';
        $campo = 'cve_doc';

        if ($pedido !== false) {
            $tabla = 'CAJAS';
            $campo = 'CVE_FACT';
        }
        $a = "UPDATE $tabla set docs = 'S' where $campo = '$doc'";
        //echo $a;
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function AsignaSecDetalle($unidad) {
        $this->query = "SELECT a.CVE_DOC, a.FECHA_DOC,a.CVE_CLPV, datediff(day, a.fechaelab, current_date) AS DIAS
                            from compo01 a
                            left join PROV01 b on a.cve_clpv = b.clave
                            where IDU = '$unidad' and secuencia is null";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function RutaUnidadRec($idr) {
        $HOY = date("Y-m-d");
        $today = getdate();
        if ($today['wday'] == 1) {
            $a = "SELECT iif(a.doc_sig is null,'No', a.doc_sig) as DOC_SIG,
                         a.*,
                         b.nombre,
                         b.ESTADO,
                         b.codigo,
                         datediff(day,a.fechaelab, current_date) as DIAS,
                         current_date as HOY,
                         a.cierre_uni as cierre
                         FROM COMPO01 a
                         LEFT JOIN PROV01 b on a.cve_clpv = b.clave
                         WHERE IDU = $idr AND (FECHA_SECUENCIA between DATEADD(DAY,-4,current_date)
                         and cast('TOMORROW' AS DATE))
                         and
                         (cierre_uni is null or cierre_uni != 'impreso') and (status_log != 'admon' and Status_log != 'Nuevo' and status_log != 'secuencia')";
            ///echo $a;
        } else {
            $a = "SELECT iif(a.doc_sig is null,'No', a.doc_sig) as DOC_SIG, a.*, b.nombre, b.ESTADO, b.codigo, datediff(day,a.fechaelab, current_date) as DIAS, current_date as HOY, a.cierre_uni as cierre
        		FROM COMPO01 a
        		LEFT JOIN PROV01 b on a.cve_clpv = b.clave
        		WHERE IDU = $idr AND (FECHA_SECUENCIA between CAST('YESTERDAY'AS date)  and cast('TODAY' AS DATE)) and (cierre_uni is null or cierre_uni != 'impreso') and (status_log != 'admon' and Status_log != 'Nuevo' and status_log != 'secuencia')";
            //echo $a; (fecha_rev between CAST('TODAY' AS DATE) AND CAST('TOMORROW' AS DATE)
        }

        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function RutaUnidadEnt($idr) {
        $HOY = date("Y-m-d");
        $today = getdate();
        if ($today['wday'] == 1) {
            $b = "SELECT a.*, d.nombre, d.estado, d.codigo, b.fechaelab, c.fechaelab as fechfact, c.cve_doc as factura, datediff(day, c.fechaelab, current_date) as Dias, a.Docs, a.idu, a.val_aduana as aduana, iif(a.factura is null or a.factura = '', a.remision, a.factura) as documento
        		FROM CAJAS a
        		left join factp01 b on a.cve_fact = b.cve_doc
        		left join factf01 c on b.cve_doc = c.doc_ant
        		left join Clie01 d on d.clave = b.cve_clpv
        		WHERE IDU = $idr and (cierre_uni is null or cierre_uni != 'impreso')
        		AND FECHA_SECUENCIA >= DATEADD(DAY, -4 , current_date)
        		AND (a.STATUS_LOG = 'Entregado' or a.status_log = 'NC' or a.status_log = 'Reenviar' or a.status_log = 'Recibido')";
        } else {
            $b = "SELECT a.*, d.nombre, d.estado, d.codigo, b.fechaelab, c.fechaelab as fechfact, c.cve_doc as factura, datediff(day, c.fechaelab, current_date) as Dias, a.Docs, a.idu, a.val_aduana as aduana, iif(a.factura is null or a.factura = '', a.remision, a.factura) as documento
        		FROM CAJAS a
        		left join factp01 b on a.cve_fact = b.cve_doc
        		left join factf01 c on b.cve_doc = c.doc_ant
        		left join Clie01 d on d.clave = b.cve_clpv
        		WHERE IDU = $idr and (cierre_uni is null or cierre_uni != 'impreso') AND FECHA_SECUENCIA >= cast('YESTERDAY' as date) AND (a.STATUS_LOG = 'Entregado' or a.status_log = 'NC' or a.status_log = 'Reenviar' or a.status_log = 'Recibido')";
        }
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function RegresaDocs($doc, $idr, $docs) {

        $pedido = strrpos($doc, 'P');
        $tabla = 'COMPO01';
        $campo = 'CVE_DOC';
        if ($pedido !== false) {
            $tabla = 'CAJAS';
            $campo = 'CVE_FACT';
        }

        //echo $docs;
        if ($docs == 'No') {
            $b = "UPDATE $tabla set docs = 'Si' where $campo = '$doc'";
            //	echo $b;
            $this->query = $b;
            $result = $this->EjecutaQuerySimple();
            return $result;
        }

        $b = "UPDATE $tabla set docs = 'N' where $campo = '$doc'";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function CerrarOC($doc, $idr, $tipo, $idc) {
        $pedido = strrpos($doc, 'P');
        $tabla = 'COMPO01';
        $campo = 'CVE_DOC';

        if ($pedido !== false) {
            $tabla = 'CAJAS';
            $campo = 'ID';
        };

        if ($tipo == 'Parcial') {
            $valor = 'Parcial';
        } elseif ($tipo == 'Tiempo') {
            $valor = 'Tiempo';
        } else {
            $valor = 'ok';
        };

        if ($campo == 'ID') {
            $c = "UPDATE $tabla set cierre_uni = '$valor' where $campo=$idc";
        } else {
            $c = "UPDATE $tabla set cierre_uni = '$valor' where $campo='$doc'";
        }

        //echo "Valor de la consulta: ".$c;
        $this->query = $c;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function RutaUnidadRecGen() {
        $a = "SELECT a.*, b.nombre, CURRENT_DATE as HOY, datediff(day,a.fechaelab, current_date) as DIAS
        		FROM COMPO01 a
        		LEFT JOIN prov01 b on a.cve_clpv = b.clave
        		WHERE (FECHA_SECUENCIA between CAST('TODAY'AS date)  and cast('YESTERDAY' AS DATE))
        		AND CIERRE_TOT IS NULL";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {

            $data[] = $tsArray;
        }
        return $data;
    }

    function CerrarGen() {
        $b = "SELECT fecha_secuencia, count(a.cve_doc) as Documentos, count (a.cierre_uni) as DOCS
                FROM COMPO01 a
                LEFT JOIN prov01 b on a.cve_clpv = b.clave
                WHERE FECHA_SECUENCIA = current_date AND CIERRE_TOT IS NULL
                group by fecha_secuencia";
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $Docs = $row->DOCUMENTOS;
        $Cerrados = $row->DOCS;
        if ($Docs == $Cerrados) {
            $var = true;
        } else {
            $var = false;
        }
        return $var;
    }

    function CerrarRutasRecoleccion($documentos) {
        $arrayLength = count($documentos);
        for ($contador = 0; $contador < $arrayLength; $contador++) {
            if ((substr($documentos[$contador][0], 0, 1)) == 'P') {
                $tabla = 'CAJAS';
                $campo = 'CVE_FACT';
            } else {
                $tabla = 'COMPO01';
                $campo = 'CVE_DOC';
            }

            $doc = $documentos[$contador][0];
            $vueltas = (int) $documentos[$contador][2];
            // var_dump($vueltas);
            if ($vueltas >= 5) {
                $this->query = "UPDATE $tabla SET STATUS_LOG = 'Fallido' WHERE $campo = '$doc'";
            } else {
                switch ($documentos[$contador][1]) {
                    case 'Total':
                        $this->query = "UPDATE $tabla SET CIERRE_TOT = 'OK', VUELTAS = VUELTAS + 1 WHERE $campo = '$doc' ";
                        break;
                    case 'Parcial':
                        $this->query = "UPDATE $tabla SET STATUS_LOG = 'Parcial', RUTA = 'N', SECUENCIA = NULL, UNIDAD = NULL, idu = NULL, VUELTAS = VUELTAS + 1, CIERRE_TOT = 'R' WHERE $campo = '$doc'";
                        break;
                    case 'Tiempo':
                        $this->query = "UPDATE $tabla SET STATUS_LOG = 'Nuevo', RUTA = 'N', SECUENCIA = NULL, UNIDAD = NULL, idu = NULL, VUELTAS = VUELTAS + 1 WHERE $campo = '$doc'";
                        break;
                    case 'PNR':
                        $this->query = "UPDATE $tabla SET CIERRE_TOT = 'OK', VUELTAS = VUELTAS + 1 WHERE $campo = '$doc' ";
                        break;
                    case 'Fallido':
                        $this->query = "UPDATE $tabla SET CIERRE_TOT = 'OK', VUELTAS = VUELTAS + 1 WHERE $campo = '$doc' ";
                        break;
                    case 'Reenvio':
                        $this->query = "UPDATE $tabla SET CIERRE_TOT = 'OK', VUELTAS = VUELTAS + 1 WHERE $campo = '$doc' ";
                        break;
                    case 'Tiempo2':
                        $this->query = "UPDATE $tabla SET STATUS_LOG = 'FalloProv', RUTA = 'N', SECUENCIA = NULL, UNIDAD = NULL, idu = NULL, VUELTAS = VUELTAS + 1, CIERRE_TOT = 'R' WHERE $campo = '$doc'";
                        break;
                    default:
                        break;
                }
            }
            $resultado = $this->EjecutaQuerySimple();
        }
        return $resultado;
    }

    function VentasVsCobrado($fechaini, $fechafin, $vend) {
        if (!empty($vend))
            $filtrovend = "and v.nombre LIKE '($vend%'";
        else
            $filtrovend = " ";

        $this->query = "SELECT
                                cm.CVE_CLIE AS CLIENTE,
                                cm.REFER AS REFERENCIA,
                                iif(fd.CVE_DOC IS NULL, '',fd.CVE_DOC) AS NC_ASOCIADA,
                                cm.FECHAELAB AS FECHA_ELABORACION,
                                cd.FECHA_APLI AS FECHA_APLICACION,
                                cm.IMPORTE AS IMPORTE_VENDIDO,
                                SUM(iif(cd.IMPORTE IS null, 0, cd.IMPORTE)) AS IMPORTE_COBRADO,
                                iif(fd.IMPORTE IS NULL, 0, fd.IMPORTE) AS IMPORTE_NC,
                                round(cm.IMPORTE,2) - round((iif(fd.IMPORTE IS NULL, 0, fd.IMPORTE)),2) AS VENTA_REAL,
                                round(cm.IMPORTE,2) - round(SUM(iif(cd.IMPORTE IS null, 0, cd.IMPORTE)),2) AS SALDO,
                                iif(v.nombre IS NULL, '', v.nombre) AS VENDEDOR,
                                (cm.IMPORTE - (iif(fd.IMPORTE IS NULL, 0, fd.IMPORTE))) - (cm.IMPORTE - SUM(iif(cd.IMPORTE IS null, 0, cd.IMPORTE))) AS VENTA_SALDO,
                                (((cm.IMPORTE - (iif(fd.IMPORTE IS NULL, 0, fd.IMPORTE))) - (cm.IMPORTE - SUM(iif(cd.IMPORTE IS null, 0, cd.IMPORTE)))) / 1.16) * 0.01 AS COMISION


                                FROM
                                    ((cuen_m01 cm
                                    LEFT JOIN factd01 as fd
                                    ON fd.doc_ant = cm.refer)
                                    LEFT JOIN vend01 v
                                    ON v.CVE_VEND = cm.strcvevend)
                                    INNER JOIN cuen_det01 cd
                                    ON cm.refer = cd.refer

								WHERE
								cm.tipo_mov = 'C'

                                GROUP BY
                                cm.CVE_CLIE,
                                cm.REFER ,
                                fd.CVE_DOC,
                                cm.FECHAELAB,
                                cd.FECHA_APLI,
                                cm.IMPORTE,
                                fd.IMPORTE,
                                cm.IMPORTE,
                                v.nombre
								HAVING
								cd.FECHA_APLI BETWEEN '$fechaini' AND '$fechafin'
								$filtrovend
								ORDER BY REFERENCIA";
        @$resultado = $this->QueryObtieneDatosN();
        while (@$tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($this->query);
        return @$data;
    }

    function VerCatGastos() {
        $this->query = "SELECT * FROM CAT_GASTOS WHERE ACTIVO = 'S' ORDER BY ID ASC";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardarNuevaCuenta($concepto, $descripcion, $iva, $cc, $cuenta, $gasto, $presupuesto, $retieneiva, $retieneisr, $retieneflete) {
        $viva = (!empty($retieneiva) ? $retieneiva : 0);
        $visr = (!empty($retieneisr) ? $retieneisr : 0);
        $vflete = (!empty($retieneflete) ? $retieneflete : 0);
        $this->query = "INSERT INTO CAT_GASTOS (CONCEPTO,DESCRIPCION,CAUSA_IVA,CENTRO_COSTOS,CUENTA_CONTABLE,GASTO,PRESUPUESTO,IVA,ISR,FLETE)
                           VALUES ('$concepto', '$descripcion', '$iva', '$cc', '$cuenta', '$gasto', $presupuesto, $viva, $visr, $vflete)";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function editCuentaGasto($id) {
        $this->query = "SELECT * FROM CAT_GASTOS WHERE ID = $id";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardarCambiosCuenta($concepto, $descripcion, $iva, $cc, $cuenta, $gasto, $presupuesto, $id, $retieneiva, $retieneisr, $retieneflete, $activo, $cveprov) {
        if (empty($presupuesto)) {
            $presupuesto = 0;
        }

        if (empty($cveprov)) {
            $cveprov = 'No';
            $Nombre = 'No';
        } else {
            $a = "SELECT NOMBRE FROM PROV01 WHERE CLAVE = '$cveprov'";
            $this->query = $a;
            $result = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($result);
            $Nombre = $row->NOMBRE;
        }

        $this->query = "UPDATE CAT_GASTOS SET CONCEPTO = '$concepto',DESCRIPCION = '$descripcion',CAUSA_IVA = '$iva',
                           CENTRO_COSTOS = '$cc',CUENTA_CONTABLE = '$cuenta',GASTO = '$gasto',PRESUPUESTO = iif($presupuesto=0,presupuesto,$presupuesto), IVA = $retieneiva, ISR = $retieneisr, FLETE = $retieneflete, ACTIVO = '$activo', cve_prov = iif('$cveprov' = 'No',cve_prov, '$cveprov'), proveedor=iif('$Nombre'= 'No', proveedor,'$Nombre')
                           WHERE ID = $id";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    /* editado por GDELEON 3/Ago/2016 */

    function delCuentaGasto($id) {
        /* no eliminar solo cambiar ACTIVO N */
        //$this->query = "DELETE FROM CAT_GASTOS WHERE ID = $id";
        $this->query = "UPDATE CAT_GASTOS
            				SET ACTIVO = 'N'
            				WHERE ID = $id";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    /* Modificado por GDELEON 3/Ago/2016 */

    function traeProveedoresGastos() {
        $this->query = "SELECT p.CLAVE, p.NOMBRE
                            FROM PROV01 p
                            LEFT JOIN PROV_CLIB01 pcl
                            	ON p.CLAVE = pcl.CVE_PROV
                            WHERE (UPPER(pcl.CAMPLIB2) starting with UPPER('G'))";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function LiberarPartidasNoRecibidas($id_preoc, $pxr, $doco) {

        $this->query = "SELECT * FROM PREOC01 WHERE ID = $id_preoc";
        $rs = $this->QueryObtieneDatosN();
        echo $this->query;
        $row = ibase_fetch_object($rs);
        $co = $row->CANT_ORIG;
        $cs = $row->CANTI;
        //echo $co;
        //echo $pxr;

        if ($cs > $co) {
            $this->query = "SELECT iif(sum(cant) is null, 0, sum(cant)) as cantpedida FROM PAR_COMPO01 WHERE ID_PREOC = $id_preoc and status = 'E'";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $cp = $row->CANTPEDIDA;
            //echo 'Esta es la cantidad solicitada: '.$cp;
            //echo $this->query;
            $this->query = "UPDATE preoc01 set canti= ($co-$cp), rest=($co-$cp), ordenado=$cp where id=$id_preoc";
            $rs = $this->EjecutaQuerySimple();
            //echo $this->query;
            $this->query = "UPDATE par_compo01 set pxr = 0, status = 'D' where id_preoc = $id_preoc and cve_doc = '$doco'";
            $rs = $this->EjecutaQuerySimple();
            //echo $this->query;
            throw new Exception("Se esta tratando de solicitar mas de lo debido, se reporta a Direccion");
            return $rs;
        }
        echo $co;
        echo $pxr;
        if ($pxr <= $co) {

            $query = "UPDATE PAR_COMPO01 SET";
            $query .= " pxr= (pxr - $pxr) ,";
            $query .= " status = 'L'";
            $query .= " WHERE id_preoc = $id_preoc and cve_doc = '$doco'";
            $this->query = $query;
            $result = $this->EjecutaQuerySimple();

            $query = "UPDATE PREOC01 SET";
            $query .= " rest= (rest + $pxr),";
            $query .= " canti= (rest + $pxr),";
            $query .= " ordenado= ordenado - $pxr,";
            $query .= " status='N'";
            $query .= " WHERE id=$id_preoc";
            $this->query = $query;
            $result = $this->EjecutaQuerySimple();
            //$result+= $this->ActPendientesPorRecibir($id_preoc, $pxr, $doco);
            $result += $this->libSaldo($id_preoc, $pxr, $doco);
        }

        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function libSaldo($id_preoc, $pxr, $doco) {
        $b = "SELECT a.cost, b.cve_clpv
			from par_compo01 a
			left join compo01 b on b.cve_doc = a.cve_doc
			where a.cve_doc = '$doco' and a.id_preoc = $id_preoc";
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $costo = $row->COST;
        $prov = $row->CVE_CLPV;
        //echo $costo;
        //echo $prov;

        $a = "UPDATE PROV01 SET SALDO_LIBERADO = SALDO_LIBERADO + ($costo * $pxr) where clave = $prov";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function ReEnrutar($id_preoc, $pxr, $doco) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $getData = "SELECT * FROM COMPO01 WHERE CVE_DOC = '$doco'";
        $this->query = $getData;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $fecha = $row->FECHAELAB;
        $unidad = $row->UNIDAD;
        $s_log = $row->STATUS_LOG;
        $idu = $row->IDU;
        $vuelta = $row->VUELTAS;
        $secuencia = $row->SECUENCIA;
        $fechas = $row->FECHA_SECUENCIA;
        $s_log2 = $row->STATUS_LOG2;

        $b = "INSERT INTO LOG_REENRUTAR (DOCUMENTO, FECHA_DOC, FECHA, USUARIO, UNIDAD, STATUS_LOG, IDU, VUELTAS, SECUENCIA, FECHA_SECUENCIA, STATUS_LOG2)
			VALUES ('$doco', '$fecha', current_timestamp, '$usuario', '$unidad', '$s_log', $idu, $vuelta, $secuencia, '$fechas', '$s_log2')";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();


        $a = "UPDATE compo01 set unidad = null, ruta = 'N', status_log ='Nuevo', idu = null, vueltas = vueltas + 1, secuencia = null, fecha_secuencia = null, status_log2 = 'R' where cve_doc = '$doco'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        $result += $this->VueltaPartida($id_preoc, $pxr, $doco);
        return $result;
    }

    function VueltaPartida($id_preoc, $pxr, $doco) {
        /* $b="UPDATE PAR_COMPO01 SET vuelta = vuelta + 1, status_log2 = 'R', pxr = pxr + $pxr where id_preoc = $id_preoc and cve_doc = '$doco'"; */
        $b = "UPDATE PAR_COMPO01 SET vuelta = vuelta + 1, status_log2 = 'R' where id_preoc = $id_preoc and cve_doc = '$doco'";

        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function traeConceptoGastos() {
        $this->query = "SELECT ID, CONCEPTO, PRESUPUESTO FROM CAT_GASTOS WHERE ACTIVO = 'S'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /* editado por GDELEON 3/Ago/2016 */
    /* function delCuentaGasto($id){
      /*no eliminar solo cambiar ACTIVO N*
      //$this->query = "DELETE FROM CAT_GASTOS WHERE ID = $id";
      $this->query = "UPDATE CAT_GASTOS
      SET ACTIVO = 'N'
      WHERE ID = $id";
      $resultado = $this->EjecutaQuerySimple();
      return $resultado;
      } */

    function traeImpuestoGasto($concepto) {
        $this->query = "SELECT CAUSA_IVA,IVA,ISR,FLETE FROM CAT_GASTOS WHERE ID = $concepto";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeClasificacionGastos() {
        $this->query = "SELECT * FROM CLA_GASTOS WHERE ACTIVO = 'S'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function dataClasificacion($id) {
        $this->query = "SELECT * FROM CLA_GASTOS WHERE ID = $id";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_assoc($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardaCambiosCG($id, $clasif, $descripcion, $activo) {
        $this->query = "UPDATE CLA_GASTOS SET CLASIFICACION = '$clasif', DESCRIPCION = '$descripcion', ACTIVO = '$activo' WHERE ID = $id";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function guardaNuevaClaGasto($clasif, $descripcion) {
        $this->query = "INSERT INTO CLA_GASTOS (CLASIFICACION, DESCRIPCION) VALUES ('$clasif','$descripcion')";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function verEntregas() {
        $a = "SELECT a.*,  e.nombre, c.CVE_DOC as Remision, d.cve_doc as FACTURA, c.fechaelab as FECHAREM, d.FECHAELAB as FECHAFAC
			FROM CAJAS a
			left join FACTP01 b ON a.cve_fact = b.cve_doc
			left join FACTR01 c on a.cve_fact = c.doc_ant
			left join FACTF01 d on a.cve_fact = d.doc_ant
			left join clie01 e on b.cve_clpv = e.clave
			WHERE a.STATUS_LOG = 'Entregado' and CONTRARECIBO IS NULL";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verNoEntregas() {   //21
        $a = "SELECT a.*,  e.nombre, c.CVE_DOC as Remisiondoc, d.cve_doc as FACTURADOC, c.fechaelab as FECHAREM, d.FECHAELAB as FECHAFAC
			FROM CAJAS a
			left join FACTP01 b ON a.cve_fact = b.cve_doc
			left join FACTR01 c on a.cve_fact = c.doc_ant
			left join FACTF01 d on a.cve_fact = d.doc_ant
			left join clie01 e on b.cve_clpv = e.clave
			WHERE a.STATUS_LOG = 'NC' or a.status_log = 'Reenviar' or a.status_log = 'recibido' ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function insContra($cr, $idc, $docf) {
        $a = "UPDATE CAJAS SET CONTRARECIBO = '$cr'
			where CVE_FACT = '$docf' and id = $idc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function verFacturasCompF($docf, $docp, $idcaja) {        //20062016
        $this->query = "SELECT   a.CVE_DOC AS FACTURA, a.FECHAELAB AS FECHA_FACTURA, b.nombre AS CLIENTE,
                                    p.cve_fact as pedido, p.unidad AS UNIDAD, p.idu,
                                    p.status_log, p.docs, p.CIERRE_TOT, p.motivo,
                                    p.id AS CAJA, a.impreso, a.status_log as Resultado
                           FROM FACTF01 a
                           INNER JOIN CLIE01 b ON a.cve_clpv = b.clave
                           INNER JOIN cajas p on a.doc_ant = p.cve_fact
                           WHERE  a.CVE_DOC = '$docf' AND p.CVE_FACT = '$docp' AND p.id =  $idcaja";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function insertaRevFact($docf, $docp, $idcaja, $tipo) {
        $this->query = "EXECUTE PROCEDURE insert_revfact('$docf','$docp',$idcaja,'$tipo')";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        // var_dump($data);
        return $data;
    }

    function actualizaStatusCaja($idcaja) {      //21
        $this->query = "UPDATE CAJAS SET status_log = 'Recibido' WHERE id = $idcaja";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    //fin 20062016

    function recDocFact($docf, $docp, $idcaja) {
        $b = "UPDATE CAJAS SET DOCS = 'No' where id = $idcaja";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function recDocFactNC($docf, $docp, $idcaja) {
        $b = "UPDATE CAJAS SET DOCS = 'No' where id = $idcaja";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function imprimeReciboFact($docf, $docp, $idcaja) {
        $a = "SELECT *
			FROM FACTF01
			WHERE CVE_DOC='$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        $result += $this->infoPedido();
        $result += $this->infolog();
        $result += $this->infoCom();
        $result += $this->infoPreOC();
        return $result;
    }

    function verembalaje($id, $docf) {
        $a = "SELECT * FROM PAQUETES WHERE IDCAJA = $id and documento = '$docf' and devuelto != cantidad ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function recibeCaja($id, $docf, $idc) {    //21
        $a = "UPDATE CAJAS set status_mer = 'recibido' where id = $idc and cve_fact = '$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        $result += $this->InsertaFolio($id, $docf, $idc);
        return $result;
    }

    function InsertaFolio($id, $docf, $idc) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $z = "SELECT max(f.cve_doc) as FACT, max(f.fechaelab) as FECHAFACT, max(p.cve_doc) as remi, max(p.fechaelab) as fecharemi
			FROM factp01 a
			left join factf01 f on f.doc_ant= '$docf'
			left join factr01 p on p.doc_ant= '$docf'
 			WHERE a.cve_doc = '$docf'
 			group by a.cve_doc";
        $this->query = $z;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $factura = $row->FACT;
        $remision = $row->REMI;
        $ffact = $row->FECHAFACT;
        $fremi = $row->FECHAREMI;

        if (is_null($ffact)) {
            $ffact = '01/01/2016';
        } elseif (is_null($fremi)) {
            $fremi = '01/01/2016';
        }

        $a = "INSERT INTO RECMCIA (IDCAJA, IDU, PEDIDO, FACTURA, REMISION, FECHA_RECEP, USUARIO, SERIE, FECHA_FACT, FECHA_PEDI, RECIBIDO)
			VALUES ($idc, $idc ,'$docf', '$factura', '$remision', current_timestamp ,'$usuario','R','$ffact','$fremi', 'No')";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();

        $b = "SELECT ID as FOLIO FROM RECMCIA WHERE IDCAJA = $idc and RECIBIDO = 'No'";
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $folio = $row->FOLIO;

        $c = "UPDATE CAJAS SET FOLIO_RECMCIA = $folio, aduana = null WHERE ID = $idc";
        $this->query = $c;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function statusImpresoCaja($id) { //21
        $this->query = "EXECUTE PROCEDURE SP_Status_Bodega($id)";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    //14062016

    function traeDocumentosxCliente() {
        $this->query = "SELECT * FROM CATALOGO_DOCUMENTOS";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardaNuevoDocC($nombre, $descripcion) {
        $this->query = "EXECUTE PROCEDURE SP_DOCUMENTOC_NUEVO('$nombre', '$descripcion')";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function traeDocumentoC($id) {
        $this->query = "SELECT * FROM CATALOGO_DOCUMENTOS WHERE ID = $id";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardaCambiosDocC($activo, $nombre, $descripcion, $id) {
        $this->query = "EXECUTE PROCEDURE SP_MODIFICA_DOCUMENTOC('$activo','$nombre','$descripcion',$id)";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function traeClientesParaDocs() {
        $this->query = "SELECT * FROM CATALOGO_CLIENTES_DOCS";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeDocumentosCliente($clave) {
        $this->query = "SELECT * FROM SP_DOCUMENTOS_CLIENTE('$clave')";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($data);
        return $data;
    }

    function NuevoDocCliente($cliente, $requerido, $copias, $documento) {
        $this->query = "EXECUTE PROCEDURE SP_ASIGNA_DOCUMENTOC('$cliente',$documento,'$requerido',$copias)";
        $resultado = $this->EjecutaQuerySimple();
        //var_dump($cliente);
        //var_dump($this->query);
        return $resultado;
    }

    function FolioRecMcia($id, $docf, $docr, $fact) {
        $this->query = "SELECT idu, max(id) as id, max(usuario) as usuario, max(fecha_recep) as fecha_recep, max(factura) as factura, max(remision) as remision
        					FROM RECMCIA
        					WHERE idcaja=$id group by idu";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verNoEntregasImpresas() {       //22062016
        $a = "SELECT a.*,  e.nombre, c.CVE_DOC as Remision, d.cve_doc as FACTURA, c.fechaelab as FECHAREM, d.FECHAELAB as FECHAFAC
			FROM CAJAS a
			left join FACTP01 b ON a.cve_fact = b.cve_doc
			left join FACTR01 c on a.cve_fact = c.doc_ant
			left join FACTF01 d on a.cve_fact = d.doc_ant
			left join clie01 e on b.cve_clpv = e.clave
			WHERE a.STATUS_LOG = 'Recibido' AND  CONTRARECIBO IS NULL";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function recibirCajaNC($id, $docf, $idc, $idpreoc, $cantr) {

        #$a="UPDATE CAJAS SET STATUS='NC', STATUS_LOG='BodegaNC' WHERE ID=$idc and cve_fact='$docf'";
        #$b="UPDATE FACTF01 set STATUS_LOG='BodegaNC' where cve_doc='$docf'";
        $c = "UPDATE preoc01 set devuelto = iif(devuelto is null, $cantr, devuelto + $cantr) where id =$idpreoc";
        $d = "UPDATE PAQUETES SET Devuelto = (devuelto + $cantr) where id = $id";
        $this->query = $d;
        $result = $this->EjecutaQuerySimple();
        #echo $a;
        #echo $b;
        #$this->query = $a;
        #$result=$this->EjecutaQuerySimple();
        #$this->query=$b;
        #$result=$this->EjecutaQuerySimple();
        $this->query = $c;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function devueltoNC($id, $docf) {
        $a = "SELECT * FROM PAQUETES WHERE idcaja = $id and devuelto > 0";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardaContraRecibo($contrarecibo, $idcaja) {         //22062016
        $this->query = "EXECUTE PROCEDURE sp_nuevo_contrarecibo ('$contrarecibo',$idcaja)";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function avanzaCobranza($docf, $docp, $idcaja, $tipo, $nstatus) {      //21
        $usuario = $_SESSION['user']->USER_LOGIN;
        switch (trim($nstatus)) {
            case 'Reenviar': /// la inicializa para que aparezca en Asignar unidad y cuebta + 1 en vueltas.
                $status = 'Reenviar';
                $getData = "SELECT id, vueltas, status_log, unidad, idu, iif(cierre_uni is null, 'Sin Cierre', cierre_uni) as cierreu, iif(cierre_tot is null, 'Sin Cierre', cierre_tot) as cierret, iif(fletera is null, 'Sin Fletera',fletera) as fletera, iif(guia_fletera is null, 'Sin guia', guia_fletera) as guia, FECHA_SECUENCIA, iif(usuario_log is null, 'ninguno', usuario_log) as usuario_log  from CAJAS where id= $idcaja";
                $this->query = $getData;
                $result = $this->EjecutaQuerySimple();
                $row = ibase_fetch_object($result);
                $vueltas = $row->VUELTAS;
                $sl = $row->STATUS_LOG;
                $unidad = $row->UNIDAD;
                $idu = $row->IDU;
                $cierreu = $row->CIERREU;
                $cierret = $row->CIERRET;
                $fletera = $row->FLETERA;
                $guia = $row->GUIA;
                $fecha_sec = $row->FECHA_SECUENCIA;
                $usu_log = $row->USUARIO_LOG;
                $h = "INSERT INTO HISTORIA_CAJA (IDCAJA, FECHA_MOV, H_STATUS, H_VUELTAS, H_STATUS_LOG, H_UNIDAD, H_IDU, H_CIERRE_UNI, H_CIERRE_TOT, H_FLETERA, H_GUIA, H_FECHA_SECUENCIA, H_USUARIO_LOG, H_USUARIO_ADUANA)
            			values ($idcaja, current_timestamp, '$tipo', $vueltas , '$sl', '$unidad', $idu, '$cierreu', '$cierret', '$fletera', '$guia', '$fecha_sec', '$usu_log', '$usuario')";
                //echo $h;
                $this->query = $h;
                $result = $this->EjecutaQuerySimple();

                $a = "UPDATE CAJAS SET
            			aduana = null,
            			ruta = 'N',
            			status='cerrado',
            			vueltas = vueltas + 1,
            			logistica = iif(logistica is null, '$tipo',(logistica||'-'||'$tipo')),
            			unidad = null,
            			fecha_secuencia = null,
            			idu = null,
            			DOCS = 'No',
            			cierre_uni = null,
            			cierre_tot = null,
            			fletera = null,
            			guia_fletera = null,
            			status_log = 'nuevo',
            			secuencia = null,
            			fecha_aduana = current_timestamp,
            			usuario_aduana ='$usuario',
            			reenvio = 'Si',
            			IMP_COMP_REENRUTAR = 'No',
            			status_mer = null
            			where id = $idcaja";
                //echo $a;
                $this->query = $a;
                $result = $this->EjecutaQuerySimple();

                $b = "UPDATE RECMCIA SET RECIBIDO = 'Si', fecha_recibo = current_date, usuario_recibo = '$usuario' where idcaja = $idcaja and recibido = 'No'";
                $this->query = $b;
                $result = $this->EjecutaQuerySimple();

                break;
            case 'Facturar':////La manda a la pantalla de Factuar Remisiones.
                $status = 'Facturar';
                $a = "UPDATE CAJAS SET aduana = '$nstatus' , fecha_aduana = current_timestamp, usuario_aduana ='$usuario'  where id = $idcaja";
                break;
            case 'NC': //// La manda a la pantalla de Realizar Nota de Credito.
                $status = 'NC';
                $a = "UPDATE CAJAS SET aduana ='$nstatus', fecha_aduana = current_timestamp, usuario_aduana ='$usuario'  where id = $idcaja";
                break;
            case 'Deslinde': /// La manda a la pantalla para que revisen el documento,
                $status = 'Deslinde';
                $a = "UPDATE CAJAS SET ADUANA = '$nstatus', fecha_aduana = current_timestamp, usuario_aduana ='$usuario'  where id = $idcaja";
            /* case 'Revision':
              $status='Revision';
              $a="UPDATE CAJAS SET ADUANA = '$nstatus', fecha_aduana = current_timestamp, usuario_aduana ='$usuario'  where id = $idcaja"; */
            case 'Acuse':
                $status = 'Acuse';
                $a = "UPDATE CAJAS SET ADUANA = '$nstatus', fecha_aduana = current_timestamp, usuario_aduana ='$usuario'  where id = $idcaja";
                break;
            case 'Revision':
                $status = 'Revision';
                $a = "UPDATE CAJAS SET ADUANA = '$nstatus', status_log='Recibido', fecha_aduana = current_timestamp, usuario_aduana ='$usuario'  where id = $idcaja";
                break;
            case 'Revision2p':
                $status = 'Revision2p ';
                $a = "UPDATE CAJAS SET ADUANA = '$nstatus', status_log='Revision', fecha_aduana = current_timestamp, usuario_aduana ='$usuario'
            		 where id = $idcaja";
                break;
            default:
                $status = 'Errorparam';
                break;
        }

        ////$a="UPDATE FACTF01 SET STATUS_LOG = '$status' where cve_doc = '$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();

        //$b="UPDATE CAJAS SET status = 'Recibido' where id = $idcaja and cve_fact = '$docp'";
        //$this->query=$b;
        //        $result=$this->EjecutaQuerySimple();
        //$result=$this->EjecutaQuerySimple();
        return $result;
    }

    function PendientesGenNC() {     //2306-
        $this->query = "SELECT f.cve_doc AS FACTURA, f.doc_ant AS PEDIDO FROM factf01 f INNER JOIN CAJAS c on f.doc_ant = c.cve_fact
                            WHERE f.status_log = 'GenerarNC' AND c.status_log = 'Recibido' AND doc_sig IS NULL";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function PendientesGenRee() {     //2306-
        $this->query = "SELECT f.cve_doc AS FACTURA, f.doc_ant AS PEDIDO,c.ID AS CAJA FROM factf01 f
            				INNER JOIN CAJAS c on f.doc_ant = c.cve_fact
                            WHERE f.status_log = 'Reenviar' AND c.status_log = 'Recibido'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function reenviaCaja($factura, $caja) {
        $this->query = "UPDATE cajas SET ruta = 'N', STATUS = 'cerrado',fecha_cierre = null, completa = null, idu = null, secuencia = null, horai = null, horaf = null, cierre_uni = null, cierre_tot = null, caja = null, contrarecibo = null, motivo = null WHERE id = $caja ";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function datosCobranzaC($idCliente) {    //28062016
        $this->query = "SELECT ca.*, c.cve_maestro as clave_m, m.nombre as nombre_maestro, c.nombre
            				FROM cartera ca
            				left join clie01 c on trim(c.clave) = trim(ca.idCliente)
            				left join maestros m on c.cve_maestro= m.clave
            				WHERE trim(ca.idcliente) = trim('$idCliente')";
        //echo $this->query;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    //28062016
    function salvarDatosCobranza($cliente, $carteraCob, $carteraRev, $diasRevision, $diasPago, $dosPasos, $plazo, $addenda, $portal, $usuario, $contrasena, $observaciones, $envio, $cp, $maps, $tipo, $ln, $pc) {
        $revision = implode(",", $diasRevision);
        $pago = implode(",", $diasPago);
        $this->query = "EXECUTE PROCEDURE sp_inserta_datacobranza('$cliente','$carteraCob','$carteraRev','$revision','$pago','$dosPasos',$plazo,'$addenda','$portal','$usuario','$contrasena','$observaciones','$envio',$cp,'$maps','$tipo',$ln,'$pc')";
        $result = $this->EjecutaQuerySimple();

        $this->query = "UPDATE CLIE01 SET CVE_MAESTRO = '$tipo' where trim(clave) = trim('$cliente')";
        $rs = $this->EjecutaQuerySimple();

        return $result;
    }

    //28062016
    function salvarCambiosCobranza($cliente, $carteraCob, $carteraRev, $diasRevision, $diasPago, $dosPasos, $plazo, $addenda, $portal, $usuario, $contrasena, $observaciones, $envio, $cp, $maps, $tipo, $ln, $pc) {
        $revision = implode(",", $diasRevision);
        $pago = implode(",", $diasPago);
        $this->query = "EXECUTE PROCEDURE sp_actualiza_datacobranza('$cliente','$carteraCob','$carteraRev','$revision','$pago','$dosPasos',$plazo,'$addenda','$portal','$usuario','$contrasena','$observaciones','$envio',$cp,'$maps','$tipo',$ln,'$pc')";
        $result = $this->EjecutaQuerySimple();
        //echo $this->query;
        //break;
        $this->query = "SELECT IIF(CVE_MAESTRO IS NULL, '0', CVE_MAESTRO) as Valida FROM CLIE01 WHERE TRIM(CLAVE) = TRIM('$cliente')";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $valida = $row->VALIDA;
        echo $this->query;
        echo 'valor de cliente: ' . $cliente;

        $this->query = "UPDATE CLIE01 SET CVE_MAESTRO = '$tipo' where trim(clave) = trim('$cliente')";
        $rs = $this->EjecutaQuerySimple();
        echo $this->query;
        $this->query = "UPDATE FACTF01 SET CVE_MAESTRO = '$tipo' where trim(cve_clpv) = trim('$cliente')";
        $rs = $this->EjecutaQuerySimple();
        echo $this->query;
        $this->query = "SELECT iif(SUM(SALDOFINAL) is null, 0, sum(saldofinal)) AS S15 FROM FACTF01 WHERE TRIM(CVE_CLPV) = TRIM('$cliente') and extract(year from fechaelab) = 2015";
        $rs = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $s15 = $row->S15;
        echo $this->query;
        $this->query = "SELECT iif(SUM(SALDOFINAL) is null, 0, sum(saldofinal)) as S16 from factf01 where trim(cve_clpv) = Trim('$cliente') and extract(year from fechaelab) = 2016";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $s16 = $row->S16;

        $this->query = "SELECT iif(SUM(SALDOFINAL) is null, 0, sum(saldofinal)) as S17 from factf01 where trim(cve_clpv) = Trim('$cliente') and extract(year from fechaelab) = 2017";
        $rs = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $s17 = $row->S17;

        $this->query = "UPDATE MAESTROS SET SALDO_2015 = (saldo_2015 + $s15), SALDO_2016 = (saldo_2016 + $s16), SALDO_2017= (saldo_2017 + $s17) where clave = '$tipo'";
        $rs = $this->EjecutaQuerySimple();

        if ($valida != '0') {

            $this->query = "UPDATE MAESTROS SET SALDO_2015 = (saldo_2015 - $s15), SALDO_2016 = (saldo_2016 - $s16), SALDO_2017= (saldo_2017 - $s17) where clave = '$valida'";
            $rs = $this->EjecutaQuerySimple();
        }

        return $result;
    }

    function verCierreDiaEntregas() {         //27062016
        $this->query = "SELECT a.*,  e.nombre, c.CVE_DOC as Remision, d.cve_doc as FACTURA, c.fechaelab as FECHAREM, d.FECHAELAB as FECHAFAC
                	FROM CAJAS a
			left join FACTP01 b ON a.cve_fact = b.cve_doc
			left join FACTR01 c on a.cve_fact = c.doc_ant
			left join FACTF01 d on a.cve_fact = d.doc_ant
			left join clie01 e on b.cve_clpv = e.clave
					WHERE (a.STATUS_LOG = 'NC' or a.status_log = 'Reenviar' or a.status_log = 'recibido') AND a.cierre_tot is null ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function insertCierreDiaEntregas() {      //27062016
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "EXECUTE PROCEDURE SP_CIERRE_ENT('$usuario')";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function insCierreRutaRecoleccion() {    //27062016
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "EXECUTE PROCEDURE SP_CIERRE_REC('$usuario')";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function imprimeCierre($idu) {
        $a = "SELECT * FROM COMPO01 WHERE IDU = $idu and fecha_secuencia = current_date";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function imprimeCierreCab($idu) {
        $a = "SELECT max(UNIDAD) as unidad, max(fecha_secuencia) as fecha_secuencia FROM COMPO01 WHERE IDU = $idu and fecha_secuencia = current_date";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function actCierreUni($idu) {
        $a = "UPDATE COMPO01 SET CIERRE_UNI ='impreso' where idu = $idu and fecha_secuencia = current_date";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function habilitaImpresionCierre($idr) {
        $c = "SELECT COUNT(CVE_DOC) as documentos FROM COMPO01 WHERE idu = $idr and fecha_secuencia = CURRENT_DATE ";
        $this->query = $c;
        ////echo $c;

        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $TotalOC = $row->DOCUMENTOS;

        $d = "SELECT COUNT(CVE_DOC) AS docc from compo01 where idu=$idr and fecha_secuencia = current_date and (cierre_uni is not null)";
        ///echo $d;
        $this->query = $d;
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $TotalOCC = $row->DOCC;

        $cierre = 'No';
        if ($TotalOC == $TotalOCC) {
            $cierre = 'Si';
        }

        return $cierre;
    }

    function traeCarteras() {        //2806
        $this->query = "SELECT * FROM CARTERAS_REVISION";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCartera($cr) {       //02082016
        $this->query = "SELECT id,clave,nombre,cve_fact,status_log,remision,importe_remision,FACTURA,importe_factura,fechaelab,-- inicia consulta
                    cr,dias,contrarecibo_cr,sol_deslinde,FECHAFAC,FECHAREM
                    FROM(
                    SELECT a.id,cl.clave,cl.nombre,a.cve_fact,a.status_log, -- Consulta que trae las cajas
                    a.remision,r.importe as importe_remision,
                    a.FACTURA,f.importe as importe_factura,
                    p.fechaelab,
                    a.cr,datediff(day,a.fecha_secuencia,current_date) AS dias,a.contrarecibo_cr,a.sol_deslinde,f.fecha_doc as FECHAFAC,r.fecha_doc as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.statuscr_cr = 'N'
                    UNION
                    SELECT 'Total Cliente' as id,cl.clave,cl.nombre,'Total Cliente' as cve_fact, null as status_log, -- consulta que totaliza por cliente
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,null as FECHAFAC,null as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.statuscr_cr = 'N'
                    GROUP BY cl.clave,cl.nombre
                    UNION
                    SELECT 'Total General' as id,'Total General' as clave,null as nombre,'Total General' as cve_fact, null as status_log, -- consulta de total general
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,null as FECHAFAC,null as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.statuscr_cr = 'N'
                    )ORDER BY clave,nombre,id  -- Fin de la consulta";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function sinCartera() {       //2906
        $this->query = "SELECT * FROM SP_MOSTRAR_SINCARTERA_REVISION";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function salvarContraRecibo($contraRecibo, $caja) {     //02082016
        $this->query = "UPDATE CAJAS SET contrarecibo_cr = '$contraRecibo' WHERE ID = $caja";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function salvarStatusECR($caja) {     //02082016
        $this->query = "UPDATE CAJAS SET statuscr_cr = 'E' WHERE id = $caja";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function traeDataContraRecibo($caja) {       //02082016
        $this->query = "  SELECT a.id,a.cve_fact,a.status_log,c.nombre, r.CVE_DOC as Remision, f.cve_doc as FACTURA,
                            r.fechaelab as FECHAREM, f.FECHAELAB as FECHAFAC, a.cr,
                            datediff(day,a.fecha_secuencia,current_date) AS dias,a.contrarecibo_cr
                            FROM (CAJAS a
                            INNER join (FACTP01 p
                            INNER JOIN clie01 c on c.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc)
                            left join FACTR01 r on a.remision = r.cve_doc
                            left join FACTF01 f on a.factura = f.cve_doc
                            WHERE a.STATUS_LOG = 'Recibido'  AND a.ID = $caja";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCarteraDia($cr) {       //02082016 Modificación cartera revisión
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }

        $this->query = "SELECT id,clave,nombre,cve_fact,status_log,remision,importe_remision,FACTURA,importe_factura,fechaelab,-- inicia consulta
                    cr,dias,contrarecibo_cr,sol_deslinde,FECHAFAC,FECHAREM
                    FROM(
                    SELECT a.id,cl.clave,cl.nombre,a.cve_fact,a.status_log, -- Consulta que trae las cajas
                    a.remision,r.importe as importe_remision,
                    a.FACTURA,f.importe as importe_factura,
                    p.fechaelab,
                    a.cr,datediff(day,a.fecha_secuencia,current_date) AS dias,a.contrarecibo_cr,a.sol_deslinde,f.fecha_doc as FECHAFAC,r.fecha_doc as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.dias_revision CONTAINING '$dia' AND a.statuscr_cr = 'N'
                    UNION
                    SELECT 'Total Cliente' as id,cl.clave,cl.nombre,'Total Cliente' as cve_fact, null as status_log, -- consulta que totaliza por cliente
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,NULL as FECHAFAC,NULL as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.dias_revision CONTAINING '$dia' AND a.statuscr_cr = 'N'
                    GROUP BY cl.clave,cl.nombre
                    UNION
                    SELECT 'Total General' as id,'Total General' as clave,null as nombre,'Total General' as cve_fact, null as status_log, -- consulta de total general
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,NULL as FECHAFAC,NULL as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.dias_revision CONTAINING '$dia' AND a.statuscr_cr = 'N'
                    )ORDER BY clave,nombre,id  -- Fin de la consulta";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCarteraDia10($cr) {       //3006
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "SELECT id,clave,nombre,cve_fact,status_log,remision,importe_remision,FACTURA,importe_factura,fechaelab,-- inicia consulta
                    cr,dias,contrarecibo_cr,sol_deslinde,FECHAFAC,FECHAREM
                    FROM(
                    SELECT a.id,cl.clave,cl.nombre,a.cve_fact,a.status_log, -- Consulta que trae las cajas
                    a.remision,r.importe as importe_remision,
                    a.FACTURA,f.importe as importe_factura,
                    p.fechaelab,
                    a.cr,datediff(day,a.fecha_secuencia,current_date) AS dias,a.contrarecibo_cr,a.sol_deslinde,f.fecha_doc as FECHAFAC,r.fecha_doc as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.dias_revision CONTAINING '$dia' AND a.statuscr_cr = 'N' AND datediff(day,a.fecha_secuencia,current_date) > 10
                    UNION
                    SELECT 'Total Cliente' as id,cl.clave,cl.nombre,'Total Cliente' as cve_fact, null as status_log, -- consulta que totaliza por cliente
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,NULL as FECHAFAC,NULL as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.dias_revision CONTAINING '$dia' AND a.statuscr_cr = 'N' AND datediff(day,a.fecha_secuencia,current_date) > 10
                    GROUP BY cl.clave,cl.nombre
                    UNION
                    SELECT 'Total General' as id,'Total General' as clave,null as nombre,'Total General' as cve_fact, null as status_log, -- consulta de total general
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,NULL as FECHAFAC,NULL as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.dias_revision CONTAINING '$dia' AND a.statuscr_cr = 'N' AND datediff(day,a.fecha_secuencia,current_date) > 10
                    )ORDER BY clave,nombre,id  -- Fin de la consulta";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCartera10($cr) {       //3006
        $this->query = "SELECT id,clave,nombre,cve_fact,status_log,remision,importe_remision,FACTURA,importe_factura,fechaelab,-- inicia consulta
                    cr,dias,contrarecibo_cr,sol_deslinde,FECHAFAC,FECHAREM
                    FROM(
                    SELECT a.id,cl.clave,cl.nombre,a.cve_fact,a.status_log, -- Consulta que trae las cajas
                    a.remision,r.importe as importe_remision,
                    a.FACTURA,f.importe as importe_factura,
                    p.fechaelab,
                    a.cr,datediff(day,a.fecha_secuencia,current_date) AS dias,a.contrarecibo_cr,a.sol_deslinde,f.fecha_doc as FECHAFAC,r.fecha_doc as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.statuscr_cr = 'N' AND datediff(day,a.fecha_secuencia,current_date) > 10
                    UNION
                    SELECT 'Total Cliente' as id,cl.clave,cl.nombre,'Total Cliente' as cve_fact, null as status_log, -- consulta que totaliza por cliente
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,null as FECHAFAC,null as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.statuscr_cr = 'N' AND datediff(day,a.fecha_secuencia,current_date) > 10
                    GROUP BY cl.clave,cl.nombre
                    UNION
                    SELECT 'Total General' as id,'Total General' as clave,null as nombre,'Total General' as cve_fact, null as status_log, -- consulta de total general
                    null as remision,sum(r.importe) as importe_remision,
                    null as FACTURA, sum(f.importe) as importe_factura,
                    null as fechaelab,null as cr,null AS dias,null as contrarecibo_cr,null as sol_deslinde,null as FECHAFAC,null as FECHAREM
                    FROM ((CAJAS a
                    LEFT JOIN factr01 r on a.remision = r.cve_doc)
                    LEFT JOIN factf01 f on a.factura = f.cve_doc)
                    INNER JOIN (FACTP01 p
                    INNER JOIN clie01 cl ON cl.clave = p.cve_clpv) ON a.cve_fact = p.cve_doc
                    WHERE a.STATUS_LOG = 'Recibido' AND a.cr = '$cr' AND a.statuscr_cr = 'N' AND datediff(day,a.fecha_secuencia,current_date)  > 10
                    )ORDER BY clave,nombre,id  -- Fin de la consulta";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function sinCartera10() {       //3006
        $this->query = "SELECT * FROM SP_MOSTRAR_SINCARTERA_REV10";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function RechazarPedido($docp, $motivo) {
        $a = "UPDATE PREOC01 SET STATUS = 'RE', MOTIVO_RECHAZO = '$motivo', fecha_rechazo = current_timestamp WHERE COTIZA = '$docp'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        $b = "UPDATE FACTP01 SET STATUS2 = 'RE' WHERE CVE_DOC = '$docp'";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function traeDataContraReciboSCR($factura, $remision) {       //3006
        if (!empty($factura))
            $documento = $factura;
        else
            $documento = $remision;
        $this->query = "SELECT * FROM SP_DATOSDELCONTRARECIBO_SINCR('$documento')";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCarteraCierreDia($cr) {     //07072016
        /* switch (date('w')){
          case '0':
          $dia = 'D';
          break;
          case '1':
          $dia = 'L';
          break;
          case '2':
          $dia = 'MA';
          break;
          case '3':
          $dia = 'MI';
          break;
          case '4':
          $dia = 'J';
          break;
          case '5':
          $dia = 'V';
          break;
          case '6':
          $dia = 'S';
          break;
          default:
          break;
          } */
        $this->query = "SELECT a.id, a.aduana , a.cve_fact,a.status_log, a.Remision,
    			c.importe as ImpRem,  a.FACTURA, d.importe as ImpFac,
    			c.fechaelab as FECHAREM, d.FECHAELAB as FECHAFAC, a.cr,
    			datediff(day,a.fecha_secuencia,current_date) AS dias,
    			a.CONTRARECIBO_CR , cl.nombre as CLIENTE, a.cr as CARTERA_REV
    			FROM CAJAS a
    			left join FACTR01 c on c.cve_doc = a.remision
    			left join FACTF01 d on d.cve_doc = a.factura
    			left join factp01 p on p.cve_doc = a.cve_fact
    			left join clie01 cl on p.cve_clpv = cl.clave
    			WHERE a.aduana = 'Cobranza'
    			and (a.CR = '$cr')
    			and a.contrarecibo_cr is not null
    			and a.imp_cierre = 0";
        //echo $this->query;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verCarteraCierreDiaSinCR($cr) {     //07072016
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $a = "SELECT a.*, c.nombre as cliente, f.importe as ImpFac, r.importe as ImpRem, f.fechaelab as fechafac, r.fechaelab as fecharem, datediff(day, fecha_aduana, current_date) as dias, a.cr as CARTERA_REV, a.CONTRARECIBO_CR, m.motivo as mot
            	FROM CAJAS a
            	left join factp01 p on a.cve_fact = p.cve_doc
            	left join clie01 c on c.clave = p.cve_clpv
            	left join factf01 f on f.cve_doc = a.factura
            	left join factr01 r on r.cve_doc = a.remision
            	left join motivos_nocr m on (m.cve_doc = a.factura or m.cve_doc = a.remision) AND m.fecha = current_date
            	WHERE a.aduana='Revision'
            	and a.dias_revision CONTAINING('$dia')
            	and a.cr = '$cr'
            	and a.imp_cierre = 0";
        #$this->query="SELECT * FROM SP_CRCIERRESINCR('$dia','$cr')";
        //echo $this->query;
        $this->query = $a;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function salvarMotivoSinContraR($motivo, $factura, $remision) {        //06072016
        $documento = (!empty($factura)) ? $factura : $remision;
        $this->query = "INSERT INTO motivos_nocr (cve_doc,motivo) VALUES ('$documento','$motivo')";
        $resultado = $this->EjecutaQuerySimple();

        $this->query = "UPDATE CAJAS SET MOTIVO = (cast('now' as date)||' - '||'$motivo'), fecha_ultimo_motivo = (cast('Now' as date )) where factura = '$factura'";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function GenerarCierreCR($cr) {     //07072016
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "EXECUTE PROCEDURE SP_GENERARCIERRE_CR('$dia','$cr')";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function verCatCobranza($cc) {  //07072016
        $this->query = "SELECT * FROM SP_VERCOBRANZA('$cc')";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCatCobranzaDia($cc) {  //07072016
        $this->query = "SELECT * FROM SP_VERCOBRANZA_DIA('$cc')";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCatCobranza10d() {  //06072016
        $this->query = "SELECT * FROM SP_VERCOBRANZA_10D";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeCarterasCobranza() {        //07072016
        $this->query = "SELECT * FROM CARTERAS_COBRANZA";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function acuse_revision() {
        $a = "SELECT a.cve_fact, id as caja, c.nombre as Cliente, f.cve_doc as FACTURA, f.importe as IMPFAC, f.fechaelab as FECHAFAC, r.cve_doc as remision, r.importe as IMPREM, r.fechaelab as FECHAREM, a.status_log, cr.CARTERA_REVISION as CARTERA_REV, datediff(day, a.fecha_secuencia, current_date ) as Dias  FROM CAJAS a
        	left join factp01 p on a.cve_fact = p.cve_doc
        	left join factr01 r on p.cve_doc = r.doc_ant
        	left join factf01 f on p.cve_doc = f.doc_ant
        	left join clie01 c on p.cve_clpv = c.clave
        	left join cartera cr on c.clave = cr.idcliente
        	WHERE a.status_log = 'Entregado' and a.envio = 'foraneo' and cr.CARTERA_REVISION ='CR1' and (guia_fletera is null or fletera is null)";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function info_foraneo($caja, $doccaja, $guia, $fletera) {
        $a = "UPDATE cajas set guia_fletera = '$guia', fletera ='$fletera' where id = $caja and CVE_FACT = '$doccaja'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();

        return $result;
    }

    function VerRemisiones() {       //20072016
        $a = "SELECT a.*, r.fechaelab, c.nombre, iif(a.remision is null, r.cve_doc, a.remision) as REM,comp.archivo as archivo,r.doc_sig,x.archivo as xmlfile, fecha_aduana as fecha, datediff(day, fecha_aduana, current_date) as DIAS
                FROM CAJAS a
                left join factr01 r on a.cve_fact = r.doc_ant and r.tip_doc_sig = 'F'
                left join factp01 p on p.cve_doc = a.cve_fact
                left join clie01 c on p.cve_clpv = c.clave
                left join comprobantes_caja comp on comp.idcaja = a.id
                left join xmldocven x on x.cve_doc = r.doc_sig
                WHERE ADUANA = 'Facturar'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function asociarFactura($caja, $docp, $factura) {    //03082016
        $a = "UPDATE CAJAS set FACTURA = '$factura' ,ADUANA = 'Facturado', STATUS_LOG = 'Facturado' where id = $caja";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

////////////// Se modifica el status_log = Facturado para que el documento regrese a Aduana y desde aduana
    ///////////// se mande a revisión o revisión dos pasos el formulario de aduana manda a llamar a otro metodo
    ///////////// que actualiza aduana a Revision2p y Revision y status_log a recibido

    function verNCFactura() { //20072016
        $a = "SELECT a.*, f.fechaelab, c.nombre, f.cve_doc as docfactura,comp.archivo as archivo, f.doc_sig,x.archivo as xmlfile
     			from cajas a
     			left join factp01 p on p.cve_doc = a.cve_fact
     			left join factf01 f on f.cve_doc = a.factura
     			left join clie01 c on p.cve_clpv = c.clave
                left join comprobantes_caja comp on comp.idcaja = a.id
                left join xmldocven x on x.cve_doc = f.doc_sig
     			where aduana = 'NC'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function asociarNC($caja, $docp, $nc) {      //03082016
        $a = "UPDATE CAJAS set NC = '$nc', ADUANA = 'Devuelto', STATUS_LOG = 'Devuelto' where id = $caja";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();

        return $result;
    }

////////////// Se modifica el status_log = Devuelto para que el documento regrese a Aduana y desde aduana
    ///////////// se mande a revisión o revisión dos pasos el formulario de aduana manda a llamar a otro metodo
    ///////////// que actualiza aduana a Revision2p y Revision y status_log a recibido

    function VerFacturasDeslinde() { //20072016
        $a = "SELECT a.*, f.fechaelab, c.nombre, f.cve_doc as docfactura,md.motivo AS motivodes,comp.archivo as archivo
                 from cajas a
                 left join factp01 p on p.cve_doc = a.cve_fact
                 left join factf01 f on a.cve_fact = f.doc_ant
                 left join clie01 c on p.cve_clpv = c.clave
                 left join motivos_deslinde md on md.idcaja = a.id and md.fecha = current_date
                 left join comprobantes_caja comp on comp.idcaja = a.id
                 where aduana = 'Deslinde' ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function AvanzaDeslinde($caja, $pedido, $motivo) {
        //$this->query="UPDATE cajas SET aduana = null WHERE id = '$caja'";
        //$resultado = $this->EjecutaQuerySimple();
        $this->query = "INSERT INTO motivos_deslinde(idcaja,pedido,motivo,fecha) VALUES($caja,'$pedido','$motivo',current_timestamp)";
        $resultado = $this->EjecutaQuerySimple();
        var_dump($this->query);
        return $resultado;
    }

    function VerFacturasAcuse() { //20072016
        $a = "SELECT a.*, f.fechaelab, c.nombre, f.cve_doc as docfactura ,comp.archivo as archivo
     			from cajas a
     			left join factp01 p on p.cve_doc = a.cve_fact
     			left join factf01 f on a.cve_fact = f.doc_ant
     			left join clie01 c on p.cve_clpv = c.clave
                        left join comprobantes_caja comp on comp.idcaja = a.id
     			where aduana = 'Acuse'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function GuardaAcuse($caja, $pedido, $guia, $fletera) {
        $this->query = "UPDATE cajas SET guia_fletera = '$guia',fletera = '$fletera' WHERE id = '$caja'";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function CierreNcFactura() {
        $this->query = "EXECUTE BLOCK
			     			AS
			     				declare variable caja int;
			     			BEGIN
				     			FOR SELECT a.id
				     			from cajas a
				     			left join factp01 p on p.cve_doc = a.cve_fact
				     			left join factf01 f on a.cve_fact = f.doc_ant
				     			left join clie01 c on p.cve_clpv = c.clave
				     			where aduana = 'NC' INTO :caja DO
				     			begin
				     				UPDATE cajas SET aduana = 'CierreNC' WHERE id = :caja;
				     			end
			     			END";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function CierreFacturaDeslinde() {
        $this->query = "EXECUTE BLOCK
			     			AS
			     				declare variable caja int;
			     			BEGIN
				     			FOR SELECT a.id
				                 from cajas a
				                 left join factp01 p on p.cve_doc = a.cve_fact
				                 left join factf01 f on a.cve_fact = f.doc_ant
				                 left join clie01 c on p.cve_clpv = c.clave
				                 left join motivos_deslinde md on md.idcaja = a.id and md.fecha = current_date
				                 where aduana = 'Deslinde' INTO :caja DO
				     			begin
				     				UPDATE cajas SET aduana = null WHERE id = :caja;
				     			end
			     			END";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function CierreFacturaAcuse() {
        $this->query = "EXECUTE BLOCK
			     			AS
			     				declare variable caja int;
			     			BEGIN
				     			FOR SELECT a.id
					     			from cajas a
					     			left join factp01 p on p.cve_doc = a.cve_fact
					     			left join factf01 f on a.cve_fact = f.doc_ant
					     			left join clie01 c on p.cve_clpv = c.clave
					     			where aduana = 'Acuse' INTO :caja DO
				     			begin
				     				UPDATE cajas SET aduana = NULL WHERE id = :caja;
				     			end
			     			END";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function CierrePendienteFacturar() {
        $this->query = "EXECUTE BLOCK
			     			AS
			     				declare variable caja int;
			     			BEGIN
				     			FOR SELECT a.id
					        		FROM CAJAS a
					        		left join factr01 r on a.cve_fact = r.doc_ant and r.doc_sig is null
					        		left join factp01 p on p.cve_doc = a.cve_fact
					        		left join clie01 c on p.cve_clpv = c.clave
					        		WHERE ADUANA = 'Facturar' INTO :caja DO
				     			begin
				     				UPDATE cajas SET aduana = null WHERE id = :caja;
				     			end
			     			END";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function traeSaldosMaestro() {     //12072016
        $this->query = "SELECT * FROM saldosmaestro";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function actualizaSaldoVencido() {       //19072016
        $this->query = " EXECUTE PROCEDURE SP_ACTUALIZAR_SALDOS_VENCIDOS";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function saldoMaestro($cartera) {
        /* $this->query="SELECT substring(c.cve_maestro from 1 for 3) as Maestro,
          max(c.identificador_maestro) as Nom_Maestro,
          SUM(f.saldofinal) as saldo2016,
          sum(limcred) as creditoxmaestro,
          count(cve_maestro) sucursales,
          (select max(cartera_cobranza)||' / '||max(dias_pago)from cartera where idcliente in (select clave from clie01 c1 where c1.cve_maestro= c.cve_maestro)) as cartera_cobranza,
          (select sum(saldofinal) from factf01 where extract(year from fechaelab) = 2015 and (deuda2015 =1 or deuda2015 is null )and cve_clpv in(select clave from clie01 cl where  c.cve_maestro= cl.cve_maestro)) as S15,
          (select sum(saldofinal) from factf01 where extract(year from fechaelab) = 2017 and cve_clpv in(select clave from clie01 cl where  c.cve_maestro= cl.cve_maestro)) as S17,
          (select sum(monto) from Acreedores where cliente in (select clave from clie01 cl where  c.cve_maestro = cl.cve_maestro)) as acreedor
          from factf01 f
          left join clie01 c on c.clave = f.cve_clpv
          where  f.status <> 'C'
          and extract (year from f.fechaelab)= 2016 and c.status ='A'
          group by c.cve_maestro";

         */
        /* 			$this->query="SELECT clave FROM MAESTROS where clave != '' and  clave is not null";
          $rs=$this->QueryObtieneDatosN();
          while($tsArray = ibase_fetch_object($rs)){
          $data[]=$tsArray;
          }

          foreach ($data as $key) {
          $cm =$key->CLAVE;
          $this->query ="SELECT iif(sum(saldofinal) is null, 0, sum(saldofinal)) AS S15 FROM FACTF01 WHERE deuda2015 =1 AND CVE_MAESTRO = '$cm'";
          $rs=$this->EjecutaQuerySimple();
          $row=ibase_fetch_object($rs);
          $s15 =$row->S15;

          $this->query ="SELECT iif(sum(saldofinal) is null, 0, sum(saldofinal)) AS S16 FROM FACTF01 WHERE extract(year from fechaelab) = 2016 AND CVE_MAESTRO = '$cm'";
          $rs=$this->EjecutaQuerySimple();
          $row=ibase_fetch_object($rs);
          //echo $this->query;
          $s16 =$row->S16;
          $this->query ="SELECT iif(sum(saldofinal) is null, 0, sum(saldofinal)) AS S17 FROM FACTF01 WHERE extract(year from fechaelab) = 2017 AND CVE_MAESTRO = '$cm'";
          $rs=$this->EjecutaQuerySimple();
          $row=ibase_fetch_object($rs);
          $s17 =$row->S17;

          $this->query="UPDATE MAESTROS SET SALDO_2015 = $s15, SALDO_2016 = $s16, SALDO_2017 =$s17  where clave = '$cm'";
          $rs=$this->EjecutaQuerySimple();
          }

         */
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }

        if ($cartera == '99') {
            $this->query = "SELECT clave as maestro, nombre as NOM_MAESTRO, sucursales, saldo_2015 as S15 , saldo_2016 as saldo2016, saldo_2017 as S17, acreedor, CARTERA as cartera_cobranza, CC_DP, limite_global as CREDITOXMAESTRO
				FROM MAESTROS
				where clave <> ''
				and clave is not null
				and (saldo_2015 > 0 or saldo_2016 > 0 or saldo_2017 > 0)
				and not CC_DP containing ('$dia')";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data1[] = $tsArray;
            }
        } else {
            $this->query = "SELECT clave as maestro, nombre as NOM_MAESTRO, sucursales, saldo_2015 as S15 , saldo_2016 as saldo2016, saldo_2017 as S17, acreedor, CARTERA as cartera_cobranza, CC_DP, limite_global as CREDITOXMAESTRO
				FROM MAESTROS
				where clave <> ''
				and clave is not null
				and (saldo_2015 > 0 or saldo_2016 > 0 or saldo_2017 > 0)
				and cartera  = '$cartera' ";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data1[] = $tsArray;
            }
        }
        //echo $this->query;
        return $data1;
    }

    function saldoMaestrodia($cartera) {
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }

        if ($cartera == '99') {
            $this->query = "SELECT clave as maestro, nombre as NOM_MAESTRO, sucursales, saldo_2015 as S15 , saldo_2016 as saldo2016, saldo_2017 as S17, acreedor, CARTERA as cartera_cobranza, CC_DP, limite_global as CREDITOXMAESTRO
				FROM MAESTROS
				where clave <> ''
				and clave is not null
				and (saldo_2015 > 0 or saldo_2016 > 0 or saldo_2017 > 0)
				and CC_DP containing ('$dia')";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data1[] = $tsArray;
            }
        } else {
            $this->query = "SELECT clave as maestro, nombre as NOM_MAESTRO, sucursales, saldo_2015 as S15 , saldo_2016 as saldo2016, saldo_2017 as S17, acreedor, CARTERA as cartera_cobranza, CC_DP, limite_global as CREDITOXMAESTRO
				FROM MAESTROS
				where clave <> ''
				and clave is not null
				and (saldo_2015 > 0 or saldo_2016 > 0 or saldo_2017 > 0)
				and cartera  = '$cartera'
				and CC_DP containing ('$dia')";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data1[] = $tsArray;
            }
        }
        //echo $this->query;
        return @$data1;
    }

    function saldoAcumulado() {
        $this->query = "SELECT
        						SUM(SALDOFINAL) AS SA15,
        						(SELECT SUM(SALDOFINAL) FROM FACTF01 WHERE EXTRACT(YEAR FROM FECHAELAB) = 2016) AS SA16,
        						(SELECT SUM(SALDOFINAL) FROM FACTF01 WHERE EXTRACT(YEAR FROM FECHAELAB) = 2017) AS SA17,
        						 (select sum(monto) from acreedores where status != 99) as SAC
        						 FROM FACTF01 WHERE EXTRACT(YEAR FROM FECHAELAB)= 2015";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        //var_dump($data);
        return $data;
    }

    function saldoIndividual($cve_maestro) {
        $this->query = "SELECT sum(saldofinal) as s166, (max(c.nombre)||'( '||trim(max(c.clave))||' )') as nombre, c.clave as clave,
							max(c.identificador_maestro) as idm, max(c.clave) as cc, max(c.diascred) as plazo, max(c.limcred) as linea_cred,
							(select sum(saldofinal) from factf01 fa where trim(fa.cve_clpv) = trim(c.clave ) and extract(year from fa.fechaelab) = 2015 and (deuda2015 = 1 or deuda2015 is null)) as s15,
							(select sum(saldofinal) from factf01 fe where c.clave = fe.cve_clpv and extract(year from fe.fechaelab) = 2017) as s17,
							(select sum (monto) from acreedores ac where c.clave = ac.cliente ) as acreedor,
							(select sum(saldofinal) from factf01 fe where c.clave = fe.cve_clpv and extract(year from fe.fechaelab) = 2016) as s16
							FROM FACTF01 f left join clie01 c on c.clave= f.cve_clpv
							WHERE c.cve_maestro = '$cve_maestro'
							and f.status <> 'C' group by c.clave";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeSaldosCliente($rfc) {     //19072016
        $this->query = "SELECT clave,nombre,rfc,ca.linea_cred,ca.plazo, saldo,saldo_vencido,saldo_corriente
                            FROM clie01 c
                            INNER JOIN cartera ca on ca.idcliente = c.clave
                            WHERE rfc = '$rfc'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeSaldosDoc($cliente, $historico) {     //12072016
        if ($historico == 'Si') {
            $this->query = "SELECT  f.cve_clpv,
            				f.cve_doc,
            				f.fechaelab,
            				f.fecha_cr,
            				f.fecha_vencimiento,
            				'Guia' as guia,
            				f.importe,
                            datediff(day, current_timestamp, f.fecha_vencimiento) as dias ,
                            f.contrarecibo_cr,
                            f.cve_pedi as pedido,
                            f.saldofinal,
                            f.aplicado,
                            f.importe_nc,
                            f.id_pagos,
                            f.nc_aplicadas,
                             iif(f.id_pagos = '' or f.id_pagos is null,0,
						    ((select (FOLIO_X_banco||' $ '||cast(monto as decimal(7,2)))
						    from carga_pagos where
						    id = iif( char_length(f.id_pagos) = 1,substring(f.id_pagos from 1 for 1),
						    iif(char_length(f.id_pagos) = 2,substring(f.id_pagos from 1 for 2),
						    iif(char_length(f.id_pagos) = 3,substring(f.id_pagos from 1 for 3),
						    iif(char_length(f.id_pagos) = 4, substring(f.id_pagos from 1 for 4),
						    iif(char_length(f.id_pagos) = 5, substring(f.id_pagos from 1 for 5),
						     '0')))))))) as info_pago
                            FROM  factf01 f
                            WHERE trim(f.cve_clpv) = trim('$cliente')
                            and f.status <> 'C'
                            and (deuda2015 = 1 or deuda2015 is null)
                            order by f.fecha_vencimiento asc";
            //echo $this->query;
        } else {
            $this->query = "SELECT  f.cve_clpv,
            				f.cve_doc,
            				f.fechaelab,
            				f.fecha_cr,
            				f.fecha_vencimiento,
            				'Guia' as guia,
            				f.importe,
                            datediff(day, current_timestamp, f.fecha_vencimiento) as dias,
                            f.contrarecibo_cr,
                            f.cve_pedi as pedido,
                            f.saldofinal,
                            f.aplicado,
                            f.importe_nc,
                            f.id_pagos,
                            f.nc_aplicadas,
                            iif(f.id_pagos = '' or f.id_pagos is null,0,
						    ((select (FOLIO_X_banco||' $ '||cast(monto as decimal(7,2)))
						    from carga_pagos where
						    id = iif( char_length(f.id_pagos) = 1,substring(f.id_pagos from 1 for 1),
						    iif(char_length(f.id_pagos) = 2,substring(f.id_pagos from 1 for 2),
						    iif(char_length(f.id_pagos) = 3,substring(f.id_pagos from 1 for 3),
						    iif(char_length(f.id_pagos) = 4, substring(f.id_pagos from 1 for 4),
						    iif(char_length(f.id_pagos) = 5, substring(f.id_pagos from 1 for 5),
						     '0')))))))) as info_pago
                            FROM  factf01 f
                            left join aplicaciones a on f.cve_doc = a.documento  and a.cancelado = 0
                            WHERE trim(f.cve_clpv) = trim('$cliente') and saldoFinal > 2
                            and f.status <> 'C'
                            and (deuda2015 = 1 or deuda2015 is null)
                            order by f.fecha_vencimiento asc";
            //echo $this->query;
        }
        $resultado = $this->QueryObtieneDatosN();
        //echo $this->query;
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeDatacliente($cliente) {
        $this->query = "SELECT c.nombre, rfc, telefono, fax, emailpred,clave,lista_prec,v.nombre as vendedor,descuento, (iif(calle is null, '', calle ||', ')|| iif(numext is null, '',numext|| ', ') || iif(numint is null, '',numint||', ')|| iif(municipio is null, '', municipio||', ')|| iif(estado is null, '', estado||', ')||iif(pais is null, '',pais||', ')||iif(codigo is null, '', codigo) ) as direccion, c.diascred FROM clie01 c left join vend01 v on c.cve_vend = v.cve_vend WHERE trim(c.clave) =trim('$cliente')";
        //echo $this->query;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($this->query);
        return $data;
    }

    function SaldosDelCliente($cliente) {
        $this->query = "SELECT ca.linea_cred,
            					 ca.plazo,
            					 (select sum(importe) from factp01 where doc_sig is null and status <> 'C' and trim(cve_clpv) = trim($cliente)) as pedidos,
            					 (select sum(saldofinal) from factf01 where trim(cve_clpv) = trim('$cliente') and status <> 'C' ) as facturas,
            					 (select sum(saldofinal) from factf01 where trim(cve_clpv) = trim('$cliente') and datediff(day, CURRENT_DATE, fecha_vencimiento) <= 0 and status <> 'C' ) as saldo_vencido,
            					 (select sum(saldofinal) from factf01 where trim(cve_clpv) = trim('$cliente') and datediff(day, CURRENT_DATE, fecha_vencimiento) > 0 and status <> 'C' ) as saldo_corriente,
            					 (select sum(monto) from acreedores where trim(cliente) = trim('$cliente')) as acreedores
                          FROM clie01 c
                          left JOIN cartera ca on ca.idcliente = c.clave
                          where trim(c.clave) = trim('$cliente')";
        //echo $this->query;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function saldoVencidoCliente($cliente) {
        $this->query = "SELECT SUM(SALDO) as saldovencido from factf01 WHERE trim(cve_clpv) = trim($cliente)";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $saldoVencido = $row->SALDOVENCIDO;
        return $saldoVencido;
    }

    function saldoComprometido($cliente) {
        $this->query = "SELECT SUM(IMPORTE) as Saldo
        					FROM FACTP01
        					WHERE trim(CVE_CLPV) = trim($cliente)
        					and (doc_sig is null or doc_sig = '')";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $saldoSinSig = $row->SALDO;

        $this->query = "SELECT SUM(p.IMPORTE) as saldo
        					FROM FACTP01 p
        					inner join factf01 f on p.doc_sig = f.cve_doc and
        					WHERE TRIM(CVE_CLPV) = TRIM($cliente)";
        return $saldoComprometido;
    }

    function saldoCliente($cliente) {
        $this->query = "SELECT SUM(SALDO) as saldo FROM FACTF01 WHERE TRIM(CVE_CLPV) = TRIM($cliente)";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $saldo = $row->SALDO;
        return $saldo;
    }

    function ContactosDelCliente($cliente) {     //12072016
        $this->query = "SELECT ncontacto,nombre,direccion,telefono,email,tipocontac FROM contac01 WHERE cve_clie = '$cliente'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardaCompCaja($caja, $ruta) {
        $this->query = "INSERT INTO comprobantes_caja(idcaja,archivo) VALUES($caja,'$ruta')";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    function pedidosAnticipados() {
        $this->query = "SELECT a.cotiza, SUM(a.rec_faltante) AS FALTANTE,
    					max(a.NOM_CLI) as nom_cli,
    					max(a.CLIEN) as clien,
    					max(c.codigo) as codigo,
    					max(b.doc_sig) as doc_sig,
    					max(a.FECHASOL) as fechasol,
    					max(b.IMPORTE) as importe,
    					datediff(day,max(b.FECHAELAB),current_date) AS DIAS,
    					max(b.CITA) as cita,
    					iif(max(a.factura) IS NOT NULL, max(a.factura), max(a.remision)) AS documento,
    					iif(max(fecha_fact) is not null, max(fecha_fact), max(fecha_rem)) as fecha_fact,
    					sum(a.recepcion) as recibido,
    					sum(a.empacado) as empacado
              		FROM preoc01 a
              		LEFT JOIN FACTP01 b on a.cotiza = b.cve_doc
              		LEFT JOIN CLIE01 c on b.cve_clpv = c.Clave
              		where fechasol > '15.05.2016' /*AND (b.STATUS_MAT <> 'OK' OR b.STATUS_MAT IS NULL)*/
              		group by a.cotiza
              		HAVING SUM(REC_FALTANTE) >= 0
                		and sum(recepcion) > 0
                		AND max(a.REMISION) IS NULL
                		and max(a.FACTURA) IS NULL";
        /*  HAVING SUM(REC_FALTANTE) > 0 and  ((sum(a.empacado) < sum(a.FACTURADO)) OR (sum(a.empacado) < sum(a.REMISIONADO))) AND (a.REMISION IS NOT NULL OR a.FACTURA IS NOT NULL)"   Condicion original */
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ParFactMaterialPar($docf) {     //26072016 correccion a la consulta
        $parfact = "SELECT * FROM PAQUETES WHERE (EMBALADO IS NULL OR EMBALADO ='S') AND DOCUMENTO = '$docf'";
        $this->query = $parfact;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function anticipadosUrgencias() {
        /* $a="SELECT a.cotiza, SUM(a.rec_faltante) AS FALTANTE,a.NOM_CLI,a.CLIEN, c.codigo, b.doc_sig, a.FECHASOL, b.IMPORTE, datediff(day,
          b.FECHAELAB,current_date) AS DIAS, b.CITA, iif(a.factura IS NOT NULL, a.factura, a.remision) AS documento, iif(fecha_fact is not null,
          fecha_fact,fecha_rem) as fecha_fact, sum(a.recepcion) as recibido,  sum(a.empacado) as empacado
          FROM preoc01 a
          LEFT JOIN FACTP01 b on a.cotiza = b.cve_doc
          LEFT JOIN CLIE01 c on b.cve_clpv = c.Clave
          where fechasol > '15.05.2016'  AND urgente = 'U'
          group by a.cotiza
          HAVING SUM(REC_FALTANTE) > 0 and  ((sum(a.empacado) = 0) OR (sum(a.empacado) = 0)) AND (a.REMISION IS NOT NULL OR a.FACTURA IS NOT NULL)   AND sum(a.recepcion) = 0"; */
        $b = "SELECT * FROM PEDIDO";
        $this->query = $b;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function facturasDelDia() {
        $a = date("N");
        if ($a == 1) {
            $this->query = "SELECT f.cve_doc,iif(f.status='E', 'Emitida','Cancelado') as STATUS, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.importe, fechaelab as dia, iif(ca.status_log is null, 'En Bodega', ca.status_log) as Logistica,  iif(ca.aduana is null, 'Sin Aduana', ca.aduana) as aduana, datediff(day, f.fechaelab, CURRENT_DATE) as DIAS
                FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' and (ca.status_log='Entregado' or ca.status_log='admon' or ca.status_log='secuencia' or ca.status_log ='nueva' or ca.status_log is null) and Aduana is null order by f.cve_doc asc ";
        } else {
            $this->query = "SELECT f.cve_doc,iif(f.status='E', 'Emitida','Cancelado') as STATUS, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.importe, fechaelab as dia, iif(ca.status_log is null, 'En Bodega', ca.status_log) as Logistica,  iif(ca.aduana is null, 'Sin Aduana', ca.aduana) as aduana, datediff(day, f.fechaelab, CURRENT_DATE) as DIAS
                FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' and (ca.status_log != 'Total' or ca.status_log = 'admon' or ca.status_log = 'secuencia' or ca.status_log ='nueva' or ca.status_log is null) order by f.cve_doc";
        }

        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function resumenFacturasDelDia() {
        $b = date("N");
        if ($b == 1) {
            $a = "SELECT count(CVE_DOC) as factot FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' ";
            $this->query = $a;
        } else {
            $a = "SELECT count(CVE_DOC) as factot FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' ";
            $this->query = $a;
        }
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $totfact = $row->FACTOT;
        return $totfact;
    }

    function resumenFacturasDelDiaAduana() {

        $b = date("N");
        if ($b == 1) {
            $a = "SELECT count(CVE_DOC) as factot FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' and aduana is not null";
            $this->query = $a;
        } else {
            $a = "SELECT count(CVE_DOC) as factot FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' and aduana is not null";
            $this->query = $a;
        }
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $totaduana = $row->FACTOT;
        return $totaduana;
    }

    function resumenFacturasDelDiaLogistica() {
        $b = date("N");
        if ($b == 1) {
            $a = "SELECT count(CVE_DOC) as factot FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' and (ca.status_log = 'admon' or ca.status_log = 'secuencia' or ca.status_log is null)";
            $this->query = $a;
        } else {
            $a = "SELECT count(CVE_DOC) as factot FROM factf01 f
                INNER JOIN clie01 c on f.cve_clpv = c.clave
                left join cajas ca on ca.factura = f.cve_doc
                WHERE f.fechaelab >= '01.08.2016' and  (ca.status_log = 'admon' or ca.status_log = 'secuencia' or ca.status_log is null)";
            $this->query = $a;
        }
        $result = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($result);
        $totlog = $row->FACTOT;
        return $totlog;
    }

    function facturasAyer() {
        $this->query = "SELECT f.cve_doc,f.status, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.importe
						FROM factf01 f INNER JOIN clie01 c on f.cve_clpv = c.clave
						WHERE cast(f.fecha_doc as date) = dateadd(-1 day to current_date)";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function UtilidadFacturas($fechaini, $fechafin, $rango, $utilidad, $letras, $status) {        //01082016
        $filtroLetras = (empty($letras) ? "" : "WHERE substring(f.doc_ant FROM 2 FOR 1) IN({$letras}) ");

        $filtroStatus = (empty($status) ? "" : "AND f.status IN({$status})");

        $this->query = "SELECT f.cve_doc,iif(f.tip_doc_ant = 'P',f.doc_ant,'') as pedido,f.doc_sig as nc,f.status, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.can_tot,round(sum(pf.cost * pf.cant),2) as costo,
					round(((f.can_tot - sum(pf.cost * pf.cant)) * 100)/f.can_tot,2) AS utilidad,
                                        (f.can_tot - sum(pf.cost * pf.cant)) as monto_utilidad,
					iif(sum(d.importe)/10 is null, 0,sum(d.importe)/10) as cobrado ,
					f.importe as importe_Total, f.fecha_vencimiento,
					f.importe - iif(sum(d.importe)/10 is null, 0,sum(d.importe)/10) as saldo
					FROM ((factf01 f
                                        left join cuen_det01 d on f.cve_doc = d.refer)
					INNER JOIN clie01 c on c.clave = f.cve_clpv)
					INNER JOIN par_factf01 pf ON f.cve_doc = pf.cve_doc
                                        {$filtroLetras}
					GROUP BY f.cve_doc,f.status, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.can_tot,f.importe,f.fecha_vencimiento,iif(f.tip_doc_ant = 'P',f.doc_ant,''),f.doc_sig
					HAVING f.fecha_doc BETWEEN '$fechaini' AND '$fechafin' AND (((f.can_tot - sum(pf.cost * pf.cant)) * 100)/f.can_tot) {$rango} {$utilidad} {$filtroStatus} ";


        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($this->query);
        return $data;
    }

    function UtilidadFacturasTot($fechaini, $fechafin, $rango, $utilidad, $letras, $status) { //01082016
        $filtroLetras = (empty($letras) ? "" : "AND substring(f.doc_ant FROM 2 FOR 1) IN({$letras}) ");
        $filtroStatus = (empty($status) ? "" : "AND f.status IN({$status})");
        $this->query = "SELECT SUM(pf.tot_partida) as IMPORTE, SUM(pf.cost * pf.cant) AS COSTO,
					(SUM(pf.tot_partida) - SUM(pf.cost * pf.cant)) * 100 / SUM(pf.tot_partida) AS utilidadp,
					(SUM(pf.tot_partida) - SUM(pf.cost * pf.cant)) AS utilidad_monto,
					SUM(pf.tot_partida + pf.totimp4) as Importe_Total,
					SUM(iif(d.importe is null,0,d.importe)) AS COBRADO,
					SUM(pf.tot_partida + pf.totimp4) - SUM(iif(d.importe is null,0,d.importe)) AS SALDO
                    FROM ((factf01 f left join cuen_det01 d on f.cve_doc = d.refer)
                    INNER JOIN clie01 c on c.clave = f.cve_clpv)
                    INNER JOIN par_factf01 pf ON f.cve_doc = pf.cve_doc
                    WHERE f.status != 'C' AND f.fecha_doc BETWEEN '$fechaini' AND '$fechafin'  {$filtroLetras}  {$filtroStatus}
                    HAVING (SUM(f.can_tot) - SUM(pf.cost * pf.cant)) * 100 / SUM(f.can_tot) {$rango} {$utilidad}";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        //var_dump($this->query);
        return $data;
    }

    function UtilidadXFacturaHead($fact) {
        if (substr($fact, 0, 1) == 'N') {
            $tabla = "factd01";
            $tabla2 = "par_factd01";
        } elseif (substr($fact, 0, 1) == 'F') {
            $tabla = "factf01";
            $tabla2 = "par_factf01";
        }
        $this->query = "SELECT f.cve_doc,f.status, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.can_tot,round(sum(pf.cost * pf.cant),2) as costo,  round(((f.can_tot - sum(pf.cost * pf.cant)) * 100)/f.can_tot,2) AS utilidad, (f.can_tot - sum(pf.cost * pf.cant)) as monto_utilidad
						FROM ({$tabla} f
						LEFT JOIN clie01 c on c.clave = f.cve_clpv)
						INNER JOIN {$tabla2} pf ON f.cve_doc = pf.cve_doc
						WHERE f.status != 'C'
						GROUP BY f.cve_doc,f.status, f.cve_clpv,c.nombre,c.rfc,f.fecha_doc,f.imp_tot4,f.can_tot
						HAVING f.cve_doc = '$fact' ";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function UtilidadXFactura($fact) {
        if (substr($fact, 0, 1) == 'N') {
            $tabla = "factd01";
            $tabla2 = "par_factd01";
        } elseif (substr($fact, 0, 1) == 'F') {
            $tabla = "factf01";
            $tabla2 = "par_factf01";
        }
        $this->query = "SELECT pf.cve_doc, pf.cve_art, i.descr, pf.cant, pf.prec, pf.cost, pf.tot_partida, pf.cost * pf.cant as tot_costo, ((tot_partida-(pf.cost*pf.cant)) * 100) /pf.tot_partida AS utilidad,(tot_partida-(pf.cost*pf.cant)) as monto_utilidad
						FROM {$tabla2} pf
						INNER JOIN inve01 i on pf.cve_art = i.cve_art
						WHERE pf.cve_doc = '$fact' ";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function TotalesUtilidadxFactura($fact) {
        if (substr($fact, 0, 1) == 'N') {
            $tabla = "factd01";
            $tabla2 = "par_factd01";
        } elseif (substr($fact, 0, 1) == 'F') {
            $tabla = "factf01";
            $tabla2 = "par_factf01";
        }
        $this->query = "
					SELECT
					sum(pf.cant) as cantidad_total,
					sum(pf.tot_partida) as partida_total,
					sum(pf.cost * pf.cant) as tot_costo,
					sum((tot_partida-(pf.cost*pf.cant))) as monto_utilidad_total,
					sum(    iif((((tot_partida-(pf.cost*pf.cant)) * 100) /pf.tot_partida)=100, 25,(((tot_partida-(pf.cost*pf.cant)) * 100) /pf.tot_partida)     ))/count(pf.cve_doc)  AS utilidad_total_ponderada,
					iif(sum(((tot_partida-(pf.cost*pf.cant)) * 100) /pf.tot_partida   )/count(pf.cve_doc) = 100,'si','no' ) as oro
					FROM {$tabla2} pf
					INNER JOIN inve01 i on pf.cve_art = i.cve_art
					WHERE pf.cve_doc = '$fact'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function deslindecr() {
        $a = "SELECT a.*, a.id as caja, c.nombre as cliente, cr.CARTERA_REVISION, datediff(day,a.fecha_deslinde_revision,current_date) AS DIAS, f.importe as fimporte, p.importe as rimporte, f.fechaelab as fechafac, r.fechaelab as fecharem
    		from cajas a
    		left join factp01 p on p.cve_doc = a.cve_fact
    		left join clie01 c on c.clave = p.cve_clpv
    		left join CARTERA cr on cr.idcliente = p.cve_clpv
    		left join factf01 f on f.cve_doc = a.factura
    		left join factr01 r on r.cve_doc = a.remision
    		where a.STATUSCR_CR = 'D' and a.contrarecibo_cr is not null";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function deslindearevision($caja, $docf, $docr, $sol, $cr) {
        $a = "UPDATE CAJAS SET sol_deslinde = '$sol', statuscr_cr = 'N', fecha_sol = current_timestamp where id = $caja";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function guardarXmlDocF($doc, $archivo) {  //03082016
        $this->query = "INSERT INTO xmldocven(cve_doc,archivo,tip_doc) VALUES('$doc','$archivo','F')";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function guardarXmlDocD($doc, $archivo) {   //03082016
        $this->query = "INSERT INTO xmldocven(cve_doc,archivo,tip_doc) VALUES('$doc','$archivo','D')";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function revConDosPasos($cr) {  //11082016
        $this->query = "SELECT
            caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
            factura,impfact, remision,imprec,cr
            FROM
            (SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
            c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
            c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND c.CR = '$cr'
            UNION
            SELECT
            'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
            null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
            null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            where c.ADUANA = 'Revision2p'
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            AND c.CR = '$cr'
            GROUP BY cl.nombre
            UNION
            SELECT
            'Total general' as caja,null as pedido, null as resultado,null as aduana, 'Total General' as nombre,
            null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
            null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            where c.ADUANA = 'Revision2p'
            --where (c.ADUANA = 'Revision2' or c.ADUANA = 'Facturado')
            AND c.CR = '$cr')
            ORDER BY
            nombre,caja";

        /* 	$this->query="SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
          c.factura,c.remision,c.cr
          from cajas c
          left join factp01 p on c.cve_fact = p.cve_doc
          left join clie01 cl on p.cve_clpv = cl.clave
          where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado' or c.ADUANA = 'Devuelto') AND c.status_log='Recibido'  AND c.rev_dospasos = 'S' AND c.CR = '$cr'"; */
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function revConDosPasosNoCr() {  //05082016
        $this->query = "SELECT
            caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
            factura,impfact, remision,imprec,cr
            FROM
            (SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
            c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
            c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND (c.CR is null or c.CR = '')

            UNION
            SELECT
            'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
            null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
            null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND (c.CR is null or c.CR = '')
            GROUP BY cl.nombre
            UNION
            SELECT
            'Total general' as caja,null as pedido, null as resultado,null as aduana, 'Total General' as nombre,
            null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
            null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND (c.CR is null or c.CR = '') )
            ORDER BY
            nombre,caja";
        /* $this->query="SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
          c.factura,c.remision,c.cr
          from cajas c
          left join factp01 p on c.cve_fact = p.cve_doc
          left join clie01 cl on p.cve_clpv = cl.clave
          where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado' or c.ADUANA = 'Devuelto') AND c.status_log='Recibido'  AND c.rev_dospasos = 'S' AND c.CR is null"; */
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function revConDosPasosDia($cr) {  //05082016        documentos de revision dos pasos del día
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }

        $this->query = "SELECT
            caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
            factura,impfact, remision,imprec,cr
            FROM
            (SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
            c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
            c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
           -- where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia')

            UNION
            SELECT
            'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
            null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
            null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia')
            GROUP BY cl.nombre
            UNION
            SELECT
            'Total general' as caja,null as pedido, null as resultado,null as aduana, 'Total General' as nombre,
            null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
            null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
            from cajas c
            left join factp01 p on c.cve_fact = p.cve_doc
            left join clie01 cl on p.cve_clpv = cl.clave
            left join factf01 f on f.cve_doc = c.factura
            left join factr01 r on r.cve_doc = c.remision
            --where (c.ADUANA = 'Revision2p' or c.ADUANA = 'Facturado')
            where c.ADUANA = 'Revision2p'
            AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia'))
            ORDER BY
            nombre,caja";


        /* $this->query="SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
          c.factura,c.remision,c.cr
          from cajas c
          left join factp01 p on c.cve_fact = p.cve_doc
          left join clie01 cl on p.cve_clpv = cl.clave
          where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado' or c.ADUANA = 'Devuelto') AND c.status_log='Recibido'  AND c.rev_dospasos = 'S' AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia')"; */
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function revSinDosPasosDia($cr) {      //05082016
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "
         SELECT
			caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
			factura,impfact, remision,imprec,cr
			FROM
			(SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, (cl.nombre||'( '||cl.clave||' )') as nombre,
			c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
			c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia')

			UNION
			SELECT
			'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia')
			GROUP BY cl.nombre
			UNION
			SELECT
			'Total general' as caja,null as pedido, null as resultado,null as aduana, 'Total General' as nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND c.CR = '$cr' AND c.dias_revision CONTAINING('$dia'))
			ORDER BY
			nombre,caja";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function revSinDosPasos($cr) {      //05082016
        $this->query = "
         SELECT
			caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
			factura,impfact, remision,imprec,cr
			FROM
			(SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
			c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
			c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND c.CR = '$cr'

			UNION
			SELECT
			'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND c.CR = '$cr'
			GROUP BY cl.nombre
			UNION
			SELECT
			'Total general' as caja,null as pedido, null as resultado,null as aduana, 'zTotal General' as nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND c.CR = '$cr')
			ORDER BY
			nombre,caja";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function revSinDosPasosNoCr() {      //05082016
        $this->query = "
         SELECT
			caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
			factura,impfact, remision,imprec,cr
			FROM
			(SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
			c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
			c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND (c.CR is null or c.Cr ='')

			UNION
			SELECT
			'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND (c.CR is null or c.Cr ='')
			GROUP BY cl.nombre
			UNION
			SELECT
			'Total general' as caja,null as pedido, null as resultado,null as aduana, 'zTotal General' as nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cr
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Revision'
			AND (c.CR is null or c.CR =''))
			ORDER BY
			nombre,caja";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function statusDeslindeConDP($caja, $numcr) {
        $this->query = "UPDATE cajas SET ADUANA='Deslinde2P', fecha_deslinde_revision = current_timestamp WHERE ID = $caja";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function statusDeslindeSinDP($caja, $numcr) {
        $this->query = "UPDATE cajas SET ADUANA='Deslinde', DESLINDE_REVISION = '$numcr', fecha_deslinde_revision = current_timestamp WHERE ID = $caja";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function DeslindeDosPasos($cr) {     //05082016      Trae las cajas enviadas a delinde dos pasos donde su cartera revisión coincide con $cr
        $this->query = "SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
                        c.factura,c.remision, c.cr
                        from cajas c
                        left join factp01 p on c.cve_fact = p.cve_doc
                        left join clie01 cl on p.cve_clpv = cl.clave
                        where c.ADUANA = 'Deslinde2P' AND c.cr = '$cr'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DeslindeDosPasosDia($cr) {     //05082016      Trae las cajas enviadas a delinde dos pasos donde su cartera revisión coincide con $cr del día
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
                        c.factura,c.remision, c.cr
                        from cajas c
                        left join factp01 p on c.cve_fact = p.cve_doc
                        left join clie01 cl on p.cve_clpv = cl.clave
                        where c.ADUANA = 'Deslinde2P' AND c.cr = '$cr' AND c.dias_revision CONTAINING('$dia')";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DeslindeNoDosPasos($cr) {   //05082016      Trae las cajas enviadas a delinde que no son dos pasos donde su cartera revisión  coincide con $cr
        $this->query = "SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
                        c.factura,c.remision, c.cr
                        from cajas c
                        left join factp01 p on c.cve_fact = p.cve_doc
                        left join clie01 cl on p.cve_clpv = cl.clave
                        where c.ADUANA = 'Deslinde' AND c.cr = '$cr'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DeslindeNoDosPasosDia($cr) {   //05082016      Trae las cajas enviadas a delinde que no son dos pasos donde su cartera revisión  coincide con $cr
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
                        c.factura,c.remision, c.cr
                        from cajas c
                        left join factp01 p on c.cve_fact = p.cve_doc
                        left join clie01 cl on p.cve_clpv = cl.clave
                        where c.ADUANA = 'Deslinde' AND c.cr = '$cr' AND c.dias_revision CONTAINING('$dia')";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function salvaMotivoDesDP($caja, $motivo) {
        $this->query = "UPDATE cajas SET ADUANA = 'Solucion Deslinde 2p', SOL_DESLINDE = '$motivo', FECHA_SOL= current_timestamp WHERE ID = $caja";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function salvaMotivoDesNoDP($caja, $motivo) {
        $this->query = "UPDATE cajas SET ADUANA = 'Solucion Deslinde', SOL_DESLINDE = '$motivo', FECHA_SOL= current_timestamp WHERE ID = $caja";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function avanzarCajaCobranza($caja, $numcr) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "UPDATE cajas SET ADUANA = 'Revision', USUARIO_REVDP = '$usuario', FECHA_REVDP = current_timestamp WHERE ID = $caja";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function CajaCobranza($caja, $revdp, $numcr) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE CAJAS SET ADUANA = 'Cobranza', usuario_rev = '$usuario', fecha_rev = current_timestamp, contraRecibo_cr = '$numcr' where id = $caja";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    /* creado por GDELEON 3/Ago/2016 */

    function delClaGasto($id) {
        $this->query = "UPDATE CLA_GASTOS
        					SET ACTIVO = 'N'
        					WHERE ID = $id";
        $resultado = $this->EjecutaQuerySimple();
        return $resultado;
    }

    /* function created by GDELEON 3/Ago/2016 */

    function TraePresupuestoConceptGasto($concept) {
        $this->query = "SELECT
								presupuesto
							FROM CAT_GASTOS
							WHERE ID = $concept";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /* functoin created by GDELEON 3/Ago/2016 */

    function CuentasBancos() {
        $this->query = "SELECT id,
    						banco || ' - ' || NUM_CUENTA as banco
    					FROM pg_bancos";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function deslindeaduana() {
        $a = "SELECT c.*, datediff(day, c.fecha_aduana, current_timestamp) as dias FROM CAJAS c WHERE ADUANA = 'Deslinde' or aduana = 'DeslindeNC'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DesaAdu($caja, $solucion) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $datos = "SELECT * FROM CAJAS WHERE ID = $caja";
        $this->query = $datos;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $pedido = $row->CVE_FACT;
        $factura = $row->FACTURA;
        $status_log = $row->STATUS_LOG;

        $l = "INSERT INTO DESLINDES_ADUANA (IDCAJA, FECHA_DESLINDE, PEDIDO, FACTURA, USUARIO, STATUS_LOG, SOLUCION)
    		values ($caja, current_timestamp, '$pedido', '$factura', '$usuario', '$status_log', '$solucion')";
        $this->query = $l;
        $result = $this->EjecutaQuerySimple();

        $a = "UPDATE CAJAS SET STATUS_LOG = 'Deslinde', ADUANA= NULL, sol_des_aduana = '$solucion', fecha_sol_desadu = current_timestamp, usuario_des_adu = '$usuario' where id = $caja";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function traeCajasxPedido($docp) {
        $this->query = "SELECT a.*, f.importe as impfac,f.fecha_doc as fechafac, r.importe as imprec, r.fecha_doc as fecharec, fecha_aduana as fechaa
    	FROM cajas a
    	left join factf01 f on a.factura = f.cve_doc
    	left join factr01 r on a.remision = r.cve_doc
    	WHERE a.cve_fact = upper('$docp')";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function RecibirDocsRevision() {
        $a = "SELECT a.*, f.fechaelab, c.nombre, datediff(day, fecha_ini_cob, current_timestamp) as dias, iif(docs_cobranza is null, 'No', docs_cobranza) as EnCobranza
    		from cajas a
    		left join factf01 f on f.cve_doc = a.factura
    		left join clie01 c on f.cve_clpv = c.clave
    		where ADUANA = 'Cobranza' and (docs_cobranza = 'No' or docs_cobranza is null or docs_cobranza = 'S') and imp_cierre = 1 ORDER BY c.nombre asc";
        $this->query = $a;
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function recDocCob($idc) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE cajas set docs_cobranza = 'S', usuario_rec_cobranza = '$usuario', fecha_rec_cobranza = current_timestamp where id = $idc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function desDocCob($idc) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE CAJAS SET aduana = 'Deslinde Cobranza', usuario_rec_cobranza = 'usuario', fecha_rec_cobranza = current_timestamp where id = $idc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function VerCobranza() {      //05082016
        $this->query = "
         SELECT
			caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
			factura,impfact, remision,imprec,cc
			FROM
			(SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
			c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
			c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza' and imp_cierre = 1 and docs_cobranza = 'Si'
			AND (c.CC is null or c.CC ='')

			UNION
			SELECT
			'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza' and imp_cierre = 1 and docs_cobranza = 'Si'
			AND (c.CC is null or c.CC ='')
			GROUP BY cl.nombre
			UNION
			SELECT
			'Total general' as caja,null as pedido, null as resultado,null as aduana, 'zTotal General' as nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza' and imp_cierre = 1 and docs_cobranza = 'Si'
			AND (c.CC is null or c.CC =''))
			ORDER BY
			nombre,caja";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function VerCobranzaDia($cc) {
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "
         SELECT
			caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
			factura,impfact, remision,imprec,cc
			FROM
			(SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
			c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
			c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza' and imp_cierre = 1 and docs_cobranza = 'Si'
			AND c.Cc = '$cc' AND c.dias_pago CONTAINING( iif(dias_pago is null, 'L, Ma, Mi, J,  V, S, D', dias_pago))

			UNION
			SELECT
			'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza' and imp_cierre = 1 and docs_cobranza = 'Si'
			AND c.Cc = '$cc' AND c.dias_pago CONTAINING( iif(dias_pago is null, 'L, Ma, Mi, J,  V, S, D', dias_pago))
			GROUP BY cl.nombre
			UNION
			SELECT
			'Total general' as caja,null as pedido, null as resultado,null as aduana, 'Total General' as nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza' and imp_cierre = 1 and docs_cobranza = 'Si'
			AND c.Cc = '$cc' AND c.dias_pago CONTAINING( iif(dias_pago is null, 'L, Ma, Mi, J,  V, S, D', dias_pago)))
			ORDER BY
			nombre,caja";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function VerCobranzaC($cc) {
        $this->query = "
         SELECT
			caja,pedido,resultado,aduana, nombre,fecha_secuencia,docs,rev_dospasos,dias,
			factura,impfact, remision,imprec,cc
			FROM
			(SELECT c.id as caja,c.cve_fact as pedido, c.status_log as resultado,c.aduana, cl.nombre,
			c.fecha_secuencia,c.docs,c.rev_dospasos,datediff(day, c.fecha_secuencia, current_timestamp) as dias,
			c.factura,f.importe as impfact,c.remision,r.importe as imprec,c.cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza'
			AND c.CC = '$cc'

			UNION
			SELECT
			'Total cliente' as caja,null as pedido, null as resultado,null as aduana, cl.nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza'
			AND c.CC = '$cc'
			GROUP BY cl.nombre
			UNION
			SELECT
			'Total general' as caja,null as pedido, null as resultado,null as aduana, 'zTotal General' as nombre,
			null as fecha_secuencia, null as docs,null as rev_dospasos,null as dias,
			null as factura,sum(f.importe) as impfact, null as remision,sum(r.importe) as imprec,null as cc
			from cajas c
			left join factp01 p on c.cve_fact = p.cve_doc
			left join clie01 cl on p.cve_clpv = cl.clave
			left join factf01 f on f.cve_doc = c.factura
			left join factr01 r on r.cve_doc = c.remision
			--where (c.ADUANA = 'Revision' or c.ADUANA = 'Facturado')
			where c.ADUANA = 'Cobranza'
			AND c.CC = '$cc')
			ORDER BY
			nombre,caja";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeProveedoresGasto() {
        $a = "SELECT * FROM PROV01
    		INNER JOIN PROV_CLIB01 ON CLAVE = CVE_PROV
    		WHERE (UPPER(CAMPLIB2) STARTING WITH UPPER('G'))";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function cajabodeganc($idc, $docf) {
        $a = "UPDATE CAJAS SET STATUS='NC', STATUS_LOG='BodegaNC' WHERE ID=$idc and cve_fact='$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function paquetedevolucion($idc, $docf) {
        $folio = "SELECT MAX(FOLIO_DEV) as folio FROM PAQUETES";
        $this->query = $folio;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $folact = $row->FOLIO;
        $folsig = $folact + 1;

        $a = "UPDATE PAQUETES SET IMPRESION_DEV = 'Si', FOLIO_DEV = $folsig, fecha_ultima_dev = current_date WHERE IDCAJA = $idc and documento = '$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
#### PENDIENTE colocar la diferencia entre factura y remision.

        $folsigstr = (string) $folsig;
        $c = "UPDATE CAJAS SET FOLIO_DEV = ('DNC'||'$folsigstr') where id =$idc and cve_fact = '$docf'";
        $this->query = $c;
        $result = $this->EjecutaQuerySimple();


        $b = "SELECT factura, c.nombre as cliente, f.fechaelab as fecha_factura, a.cve_fact as pedido, id as caja, a.unidad, a.status_log
    		FROM CAJAS a
    		left join factf01 f on f.cve_doc = a.factura
    		left join clie01 c on c.clave = f.cve_clpv
    	    WHERE ID = $idc AND CVE_FACT = '$docf'";
        $this->query = $b;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function ImprimirDevolucion($idc, $docf) {
        $a = "SELECT * FROM PAQUETES WHERE IDCAJA =$idc AND DOCUMENTO = '$docf' and DEVUELTO > 0 ";
        $this->query = $a;
        echo $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ImprimirDevolucionEntrega($idc, $docf) {
        $a = "SELECT * FROM PAQUETES WHERE IDCAJA =$idc AND DOCUMENTO = '$docf' and DEVUELTO = 0 ";
        $this->query = $a;
        echo $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verCajasLogistica() {
        $a = "SELECT a.*, c.nombre, c.estado, c.codigo, p.fechaelab, f.fechaelab as fechfact, datediff(day, a.fecha_creacion, current_date) as dias
    		from cajas a
    		left join factp01 p on a.cve_fact = p.cve_doc
    		left join clie01 c on c.clave = p.cve_clpv
    		left join factf01 f on f.cve_doc = a.factura
    		where a.fecha_creacion >= '01.07.2016' and ADUANA IS NULL AND a.status_log != 'nuevo' and a.status_log != 'Depurado' order by a.fecha_creacion asc";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function cambiarStatus($idcaja, $docp, $secuencia, $unidad, $idu, $ntipo) {

        switch ($ntipo) {
            case 'nuevo':
                $getlog = "SELECT * FROM CAJAS WHERE ID = $idcaja";
                $this->query = $getlog;
                $result = $this->QueryObtieneDatosN();
                $row = ibase_fetch_object($result);
                $hstatus = $row->STATUS;
                $hvueltas = $row->VUELTAS;
                $hstatuslog = $row->STATUS_LOG;
                $hunidad = $row->UNIDAD;
                $hidu = $row->IDU;
                $hfechasecuencia = $row->FECHA_SECUENCIA;
                $husuariolog = $row->USUARIO_LOG;
                if (empty($hidu)) {
                    $hidu = '0';
                }
                if (empty($hfechasecuencia)) {
                    $hfechasecuencia = '01.01.2016';
                }

                $log = "INSERT INTO HISTORIA_CAJA(IDCAJA, FECHA_MOV, H_STATUS, H_VUELTAS, H_STATUS_LOG, H_UNIDAD, H_IDU, H_FECHA_SECUENCIA, H_USUARIO_LOG, MOVIMIENTO)
    						VALUES
    						($idcaja, current_timestamp,'$hstatus',$hvueltas, '$hstatuslog', '$hunidad', $hidu, '$hfechasecuencia', '$husuariolog','Cambio')";
                $this->query = $log;
                $result = $this->EjecutaQuerySimple();
                $a = "UPDATE CAJAS SET STATUS_LOG = 'nuevo', unidad = null, fecha_secuencia = null, idu = null, horai = null, horaf = null, secuencia = null, vueltas = (iif(vueltas is null, 0, vueltas) +  1),  ruta = 'N' where id = $idcaja";

                break;
            case 'sec':
                $a = "UPDATE CAJAS SET STATUS_LOG = 'sec' where id = $idcaja";
            case 'admin':
                $a = "UPDATE CAJAS SET STATUS_LOG = 'admon' where id = $idcaja";
            default:
                # code...
                break;
        }
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function DesNC($idc) {
        $a = "UPDATE CAJAS SET ADUANA = 'DeslindeNC' where id = $idc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function verLoteEnviar() {
        $a = "SELECT a.* , c.nombre, c.estado, c.codigo, f.fechaelab, datediff(day,f.fechaelab, current_date) as dias, a.remision as remisiondoc
    	FROM CAJAS a
    	left join factp01 p on p.cve_doc = a.cve_fact
    	left join clie01 c on c.clave = p.cve_clpv
    	left join factf01 f on f.cve_doc = a.factura
    	left join factr01 r on r.cve_doc = a.remision
    	WHERE a.ruta = 'N' and fecha_creacion >='08.08.2016' and  (IMP_COMP_REENRUTAR = 'No')
    	order by factura asc";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verLoteEnviarReenrutar() {
        /* $a="SELECT a.* , c.nombre, c.estado, c.codigo, f.fechaelab, datediff(day,f.fechaelab, current_date) as dias, a.remision as remisiondoc
          FROM CAJAS a
          left join factp01 p on p.cve_doc = a.cve_fact
          left join clie01 c on c.clave = p.cve_clpv
          left join factf01 f on f.cve_doc = a.factura
          left join factr01 r on r.cve_doc = a.remision
          WHERE a.ruta = 'N' and fecha_creacion >='08.08.2016' and reenvio = 'Si'"; */

        $a = "SELECT a.*, c.nombre, c.estado, c.codigo, datediff(day, a.fechaelab, current_date) as dias, idc as id, DOC_ANT AS CVE_FACT, ca.DOCS, a.cve_doc as FACTURA, ca.remision as remisiondoc, ca.U_bodega, ca.u_logistica, ca.status_ctrl_doc_entrega
    		FROM FACTF01 a
    		LEFT JOIN CLIE01 c ON c.clave = a.cve_clpv
    		LEFT JOIN CAJAS ca on ca.id = a.idc
    		WHERE fechaelab > '01.08.2016' and Lote='Faltante'
    		ORDER BY a.cve_doc";

        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function entaduana($idc, $docf, $docp) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE FACTF01 SET ENTREGA_BODEGA = 'Si', U_ENTREGA = '$usuario', fecha_entrega = current_timestamp where cve_doc = '$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function recbodega($idc, $docf, $docp) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE FACTF01 SET ENTREGA_BODEGA = 'Bd', U_RECIBE = '$usuario', FECHA_RECIBE=current_timestamp where cve_doc = '$docf'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function reclogistica($idc, $docf, $docp) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE CAJAS SET
		U_ENTREGA = (select U_ENTREGA FROM FACTF01 WHERE idc = $idc),
		FECHA_U_ENTREGA = (SELECT FECHA_ENTREGA FROM FACTF01 WHERE idc = $idc),
		U_BODEGA = (select U_RECIBE from factf01 where idc = $idc),
		FECHA_U_BODEGA =(SELECT FECHA_RECIBE FROM FACTF01 WHERE IDC= $idc),
		U_LOGISTICA = '$usuario',
		FECHA_U_LOGISTICA = current_timestamp where id = $idc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function impLoteDia() {
        $a = "SELECT * FROM CAJAS WHERE IMP_COMP_REENRUTAR = 'Nu'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function impLoteReeenrutar() {
        $a = "SELECT * FROM CAJAS WHERE IMP_COMP_REENRUTAR = 'No'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function totfactn() {
        $a = "SELECT COUNT(id) AS TOTFACT FROM CAJAS WHERE IMP_COMP_REENRUTAR = 'Nu'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $total = $row->TOTFACT;
        return $total;
    }

    function totfactr() {
        $a = "SELECT COUNT(id) AS TOTFACT FROM CAJAS WHERE IMP_COMP_REENRUTAR = 'No'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        $total = $row->TOTFACT;
        return $total;
    }

    function actimpcajas() {
        $a = "UPDATE CAJAS SET IMP_COMP_REENRUTAR = 'Si' WHERE (IMP_COMP_REENRUTAR = 'No' and IMP_COMP_REENRUTAR = 'Nu')";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function VerInventarioEmpaque() {
        $a = "SELECT p.status,
        p.id,
        fechasol,
        cotiza,
        par,
        prod,
        nomprod,
        UM,
        CANT_ORIG,
        REST,
        (cant_orig - recepcion) as Restante,
        RECEPCION,
        EMPACADO,
        (recepcion - empacado) as BODEGA,
        FACTURADO,
        REMISIONADO,
        FACTURAS,
        (p.costo * p.cant_orig) as ppto_compra,
        pe.tot_partida AS ppto_venta,
        f.fechaelab as ffac,
        trim(iif(REMISIONES is null, p.remision, remisiones)) as remisiones,
        r.fechaelab as frem ,
        c.status_log,
          (select sum(tot_partida) from par_compo01 where id_preoc = p.id group by id_preoc) as costo_real,
          ( (p.costo * p.cant_orig) - (select sum(tot_partida) from par_compo01 where id_preoc = p.id group by id_preoc))AS DIFERENCIA
            from preoc01 p
            left join cajas c on c.factura = p.factura
            left join par_factp01 pe on pe.id_preoc = p.id
            left join factf01 f on p.factura = f.cve_doc
            left join factr01 r on p.remision = r.cve_doc
            where
                recepcion > 0
                and fechasol > '01.05.2016'
                and (recepcion - empacado)> 0
                and facturas is null
                and p.factura is null
                 and p.remision is null
                  and remisiones is null
                  order by cotiza, fechasol";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    /* function verPedidosPendientes2(){

      $a="SELECT COTIZA FROM PREOC01 where id >= 51847 GROUP BY COTIZA";
      $this->query = $a;
      $result=$this->QueryObtieneDatosN();
      while($tsArray=ibase_fetch_object($result)){
      $data[]=$tsArray;
      }

      foreach ($data as $cotiza) {
      $doc = $cotiza->COTIZA;

      $b="SELECT max(par) as PAR from preoc01 where cotiza = '$doc' group by cotiza";
      $this->query=$b;
      $result=$this->QueryObtieneDatosN();
      $row = ibase_fetch_object($result);
      $partidas=$row->PAR;

      $c="SELECT iif(count(id)= 0, 0,count(id)) as PARPEND from preoc01 where emp_status = 'pendiente' and cotiza = '$doc' group by cotiza";
      $this->query= $c;
      $result = $this->QueryObtieneDatosN();
      $row = ibase_fetch_object($result);
      $parpen = $row->PARPEND;


      $d="SELECT iif(count(id) is null, 0, count(id)) as PARPAR from preoc01 where emp_status = 'parcial' and cotiza = '$doc' group by cotiza";
      $this->query= $d;
      $result = $this->QueryObtieneDatosN();
      $row = ibase_fetch_object($result);
      $parpar = $row->PARPAR;

      if ($parpen == $partidas){
      $update= "UPDATE FACTP01 SET EMP_STATUS='pendiente' where cve_doc = '$doc'";
      }elseif ($partidas == $parcom){
      $update= "UPDATE FACTP01 SET EMP_STATUS='completo' where cve_doc = '$doc'";
      }elseif($partidas == $parpar){
      $update= "UPDATE FACTP01 SET EMP_STATUS='parcial' where cve_doc = '$doc'";
      }else{
      $update= "UPDATE FACTP01 SET EMP_STATUS='eparcial' where cve_doc = '$doc'";
      }
      $this->query=$update;
      $result = $this->EjecutaQuerySimple();
      }

      $r="SELECT a.cotiza, sum(rec_faltante) as Faltante, MAX(NOM_CLI) AS NOM_CLI, MAX (CLIEN) AS CLIEN, max(c.codigo) as CODIGO,
      MAX(b.doc_sig) as FACTURA, max (a.fechasol) as FECHASOL, max(b.importe) as IMPORTE, max(datediff(day,b.FECHAELAB,current_date)) as DIAS,
      MAX(b.CITA) as CITA, max(factura) as factura, max(fecha_fact) as fecha_factu, sum(a.recepcion) as recibido,  sum(a.empacado) as empacado, max(f.fechaelab) as fecha_fact
      FROM preoc01 a
      LEFT JOIN FACTP01 b on a.cotiza = b.cve_doc
      LEFT JOIN CLIE01 c on b.cve_clpv = c.Clave
      left join factf01 f on f.cve_doc = a.factura
      left join factr01 r on r.cve_doc = a.remision
      where fechasol > '13.03.2016'
      group by cotiza
      HAVING SUM(REC_FALTANTE) = 0 and sum(a.empacado) = sum(a.cant_orig) ";
      $this->query=$r;
      $result=$this->QueryObtieneDatosN();
      while ($tsArray=ibase_fetch_object($result)){
      $data[]=$tsArray;
      }
      return @$data;
      } */

    function verPedidosPendientes() {
        $a = "SELECT * FROM PEDIDO ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function docfact($docfact, $idc) {
        $this->query = "UPDATE CAJAS SET DOCFACT = 'si' where id = $idc";
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function listadoGastos() {
        $this->query = " SELECT A.ID,
        A.STATUS,
        A.CVE_CATGASTOS,
        B.CONCEPTO,
        A.CVE_PROV,
        B.PROVEEDOR,
        A.MONTO_PAGO,
        A.TIPO_PAGO,
        B.PRESUPUESTO,
        A.FECHA_CREACION,
        A.CLASIFICACION,
        C.DESCRIPCION
        FROM GASTOS A
        left JOIN CAT_GASTOS B ON A.CVE_CATGASTOS = B.ID
        left JOIN CLA_GASTOS C ON C.ID = A.CLASIFICACION
        WHERE A.STATUS = 'E'
        and (AUTORIZACION ='' or AUTORIZACION = '1')";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function PagosGastos($identificador) {
        $this->query = "SELECT a.ID, d.CONCEPTO, d.PROVEEDOR, a.MONTO_PAGO, a.FECHA_CREACION, a.SALDO, a.TIPO_PAGO, a.CLASIFICACION, c.DESCRIPCION
                        from GASTOS a
                        left JOIN CLA_GASTOS c ON a.clasificacion = c.id
                        left JOIN CAT_GASTOS d ON a.CVE_CATGASTOS = d.ID
                        where a.status <> 'C' and FECHA_CREACION > '03/14/2016' AND d.ACTIVO = 'S' AND AUTORIZACION = '1'
                        AND a.ID = '$identificador'
                        ORDER BY a.FECHA_CREACION asc ";
        //echo 'Esta es la consulta:'.var_dump($this);
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
            return @$data;
        }
        return;
    }

    function traeFacturaxCancelar($docp) {
        $this->query = "SELECT f.*, c.nombre, ca.id, ca.status_log, ca.aduana, ca.factura, ca.unidad, ca.fecha_secuencia, ca.fecha_creacion, ca.remision, r.importe as IMPREC ,  r.fechaelab as FECHAREC, ca.fecha_aduana as FECHAA, ca.cr , ca.cc
    				  from factf01 f
    				  left join clie01 c on f.cve_clpv = c.clave
    				  left join cajas ca on ca.factura = f.cve_doc
    				  left join factr01 r on ca.remision = r.cve_doc
    				  where f.cve_doc = '$docp'";
        $resultado = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($resultado)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function CancelaF($docf, $idc) {
        $a = "UPDATE CAJAS set status = 'cancelada' where factura = '$docf' and id = $idc";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();

        $b = "UPDATE PAQUETES set  status= 'cancelado' where idcaja = $idc";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function UtilidadBaja() {
        $a = "SELECT co.cve_doc, coti.fechaelab, c.nombre, cl.num_part, iif(co.prec = 0, 1, co.prec) * co.cant as PrecioVenta, (iif((cl.camplib3 is null or cl.camplib3 = 0), 1, cl.camplib3) * co.cant) as Costo, (((iif(co.prec = 0, 1, co.prec) * co.cant)/(iif((cl.camplib3 is null or cl.camplib3 = 0), 1, cl.camplib3) * co.cant)-1)*100) as UtilidadCalculada, co.cant as cantidad, co.cve_art as clave_prod, i.descr as Producto
    			from par_factc_clib01 cl
    			left join par_factc01 co on cl.clave_doc = co.cve_doc and cl.num_part = co.num_par
    			left join factc01 coti on co.cve_doc = coti.cve_doc
    			left join clie01 c on c.clave = coti.cve_clpv
    			left join inve01 i on co.cve_art = i.cve_art
    			WHERE (((iif(co.prec = 0, 1, co.prec) * co.cant)/(iif((cl.camplib3 is null or cl.camplib3 = 0), 1, cl.camplib3) * co.cant)-1)*100)<23
    			and coti.fechaelab >= '01.08.2016'
    			and co.autoriza = 'No'
    			and coti.doc_sig is null
    			and SOLICITA_AUTORIZACION = 'No'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function solAutoUB($docc, $par) {
        $user = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE PAR_FACTC01 SET SOLICITA_AUTORIZACION='Si', FECHA_SOLICITUD = current_timestamp, USUARIO_SOLICITUD = '$user' where cve_doc = '$docc' and num_par = $par";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function verSolicitudesUB() {
        $a = "SELECT co.cve_doc, coti.fechaelab, c.nombre, cl.num_part, iif(co.prec = 0, 1, co.prec) * co.cant as PrecioVenta, (iif((cl.camplib3 is null or cl.camplib3 = 0), 1, cl.camplib3) * co.cant) as Costo, (((iif(co.prec = 0, 1, co.prec) * co.cant)/(iif((cl.camplib3 is null or cl.camplib3 = 0), 1, cl.camplib3) * co.cant)-1)*100) as UtilidadCalculada, co.cant as cantidad, co.cve_art as clave_prod, i.descr as Producto
    			from par_factc_clib01 cl
    			left join par_factc01 co on cl.clave_doc = co.cve_doc and cl.num_part = co.num_par
    			left join factc01 coti on co.cve_doc = coti.cve_doc
    			left join clie01 c on c.clave = coti.cve_clpv
    			left join inve01 i on co.cve_art = i.cve_art
    			WHERE (((iif(co.prec = 0, 1, co.prec) * co.cant)/(iif((cl.camplib3 is null or cl.camplib3 = 0), 1, cl.camplib3) * co.cant)-1)*100)<23
    			and coti.fechaelab >= '01.08.2016'
    			and co.autoriza = 'No'
    			and coti.doc_sig is null
    			and SOLICITA_AUTORIZACION = 'Si'";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function AutorizarUB($docc, $par) {
        $user = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE PAR_FACTC01 SET AUTORIZA = 'Si', fecha_autorizacion = current_timestamp, usuario_autoriza = '$user' where cve_doc = '$docc' and num_par = $par
    	 ";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function RechazoUB($docc, $par) {
        $user = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE PAR_FACTC01 SET AUTORIZA = 'Ne', fecha_autorizacion= current_timestamp, usuario_autoriza = '$user' where cve_doc = '$docc' and num_par = $par";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function guardarNuevoGasto($concepto, $proveedor, $referencia, $autorizacion, $presupuesto, $tipopago, $monto, $movpar, $numpar, $usuario, $fechadoc, $fechaven, $exec, $clasificacion) {
        foreach ($exec AS $impu) {
            if ($impu->CAUSA_IVA == 'SI')
                $ivacausado = 0.16;
            else
                $ivacausado = 0;
            $iva_generado = $ivacausado * $monto;
            $iva_retenido = ($impu->IVA / 100) * $monto;
            $isr_retenido = ($impu->ISR / 100) * $monto;
            $flete_retenido = ($impu->FLETE / 100) * $monto;
        }
        $total = $monto + $iva_generado + $iva_retenido + $isr_retenido + $flete_retenido;
        echo "monto = $monto, presupuesto = ($presupuesto+20)";
        if ($monto > ($presupuesto + 20)) {
            $autorizacion = 'X';
            $estatus = 'X';
        } else {
            $autorizacion = '1';
            $estatus = 'P';
        }
        $this->query = "INSERT INTO GASTOS(STATUS, CVE_CATGASTOS, CVE_PROV, REFERENCIA, AUTORIZACION, PRESUPUESTO, TIPO_PAGO, MONTO_PAGO, MOV_PAR, NUM_PAR, USUARIO, IVA_GEN, IVA_RET, ISR_RET, FLETE_RET, FECHA_DOC, VENCIMIENTO,TOTAL,SALDO,CLASIFICACION)
                        	VALUES ('$estatus',$concepto,'$proveedor','$referencia','$autorizacion',$presupuesto,'$tipopago',$monto,'$movpar',$numpar,'$usuario',$iva_generado,$iva_retenido,$isr_retenido,$flete_retenido,'$fechadoc','$fechaven',$total,$monto,$clasificacion);";
        $resultado = $this->EjecutaQuerySimple();
        var_dump($this->query);
        return $resultado;
    }

    function GuardaPagoGastoCorrecto($cuentaBancaria, $documento, $tipopago, $monto, $proveedor, $claveProveedor, $fechadocumento) {
        //$TIME = time();
        $HOY = date("Y-m-d"); // H:i:s", $TIME);
        $res = $this->guardarPagoGasto($documento, $cuentaBancaria, $monto, $fechadocumento, $tipopago);
        //echo "res = $res";
        if ($res) {
            $this->query = "UPDATE GASTOS
                            	SET FECHA_APLICACION = '$HOY', STATUS = 'P', SALDO = (MONTO_PAGO - $monto)
                        	WHERE ID = '$documento'";
            $rs = $this->EjecutaQuerySimple();
            //$rs+= $this->ActPagoParOC($documento, $tipopago, $monto, $proveedor, $clavePago, $fechadocumento);
            $rs += $this->GuardaCuentaBan($documento, $cuentaBancaria);
            return $rs;
        } else {
            return -1;
        }
    }

    function generaFolio($medioPago) {
//    	$medioPago = "SELECT TIPO_PAGO FROM GASTOS WHERE ID = '$documento'";
        $folio = "SELECT coalesce(MAX(IDSECUENCIA), 1) FROM PAGO_GASTO_FOLIOS WHERE MEDIO_PAGO = upper('$medioPago')";
        $this->query = "UPDATE PAGO_GASTO_FOLIOS SET IDSECUENCIA = ($folio)+1 WHERE MEDIO_PAGO = upper('$medioPago');";
        //echo $this->query;
        $rs = $this->EjecutaQuerySimple();
        $this->query = "SELECT 'G' || upper('$medioPago') || IDSECUENCIA AS FOLIO
        	FROM PAGO_GASTO_FOLIOS WHERE MEDIO_PAGO = upper('$medioPago');";
        //echo $this->query;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardarPagoGasto($documento, $cuentaBancaria, $monto, $fecha, $tipopago) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $folioPago = $this->generaFolio($tipopago);
        $folio = '';
        foreach ($folioPago as $data):
            $folio = $data->FOLIO;
            //echo "folioPago = $folio -- ";
        endforeach;
        if ($folioPago != null) {
            $AUTOINCREMENT = "SELECT coalesce(MAX(ID), 0) FROM PAGO_GASTO";
            $this->query = "INSERT INTO PAGO_GASTO (ID, IDGASTO, CUENTA_BANCARIA,MONTO, FECHA_REGISTRO,USUARIO_REGISTRA,FECHA_PAGO,CONCILIADO, FOLIO_PAGO) "
                    . "VALUES (($AUTOINCREMENT)+1, '$documento','$cuentaBancaria',$monto,'$HOY','" . $_SESSION['user']->USER_LOGIN . "','$fecha','N','$folio')";
            //echo $this->query;
            $rs = $this->EjecutaQuerySimple();
            //echo "rs = $rs";
        } else {
            echo "Fallo al obtener el folio de pago.";
            return null;
        }
        return $rs;
    }

    function listadoXautorizar() {
        $this->query = "SELECT 'ORDEN DE COMPRA' AS TIPO, CVE_DOC AS IDENTIFICADOR, FECHA_PAGO, CVE_CLPV AS CLAVE_PROVEEDOR, NOMBRE, PAGO_TES AS MONTO, (PAGO_TES - IMPORTE) AS DIFERENCIA  "
                . "FROM COMPO01 A INNER JOIN  PROV01 B "
                . "ON A.CVE_CLPV = B.CLAVE WHERE STATUS_PAGO = 'XP' AND TP_TES <> '' AND PAGO_TES > 0 ORDER BY FECHA_PAGO; ";
        $result = $this->QueryObtieneDatosN();
        $data = null;
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        // (SELECT FIRST 1 cuenta FROM pg_pagoBanco WHERE documento = IDGASTO) AS CUENTA,
        $this->query = "SELECT 'GASTO' AS TIPO, ID AS IDENTIFICADOR, FECHA_CREACION AS FECHA_PAGO, A.CVE_PROV AS CLAVE_PROVEEDOR, NOMBRE, MONTO_PAGO AS MONTO, (A.MONTO_PAGO-PRESUPUESTO) AS DIFERENCIA
                     FROM GASTOS A INNER JOIN PROV01 B ON A.CVE_PROV = B.CLAVE WHERE A.AUTORIZACION = 'X' ORDER BY FECHA_CREACION;";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function xAutorizar($tipo, $identificador) {
        $data = null;
        if ($tipo == "GASTO") {
            // (SELECT FIRST 1 cuenta FROM pg_pagoBanco WHERE documento = IDGASTO) AS CUENTA,
            $this->query = "SELECT 'GASTO' AS TIPO, ID AS IDENTIFICADOR, FECHA_CREACION AS FECHA_PAGO, A.CVE_PROV AS CLAVE_PROVEEDOR, NOMBRE, MONTO_PAGO AS MONTO, (A.MONTO_PAGO-PRESUPUESTO) AS DIFERENCIA
        	FROM GASTOS A INNER JOIN PROV01 B ON A.CVE_PROV = B.CLAVE WHERE A.AUTORIZACION = 'X' AND ID = '$identificador';";

            $result = $this->QueryObtieneDatosN();
            while ($tsArray = (ibase_fetch_object($result))) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT 'ORDEN DE COMPRA' AS TIPO, CVE_DOC AS IDENTIFICADOR, FECHA_PAGO, CVE_CLPV AS CLAVE_PROVEEDOR, NOMBRE, PAGO_TES AS MONTO "
                    . "FROM COMPO01 A INNER JOIN  PROV01 B "
                    . "ON A.CVE_CLPV = B.CLAVE WHERE STATUS_PAGO = 'XP' AND TP_TES <> '' AND PAGO_TES > 0 WHERE CVE_DOC = '$identificador'; ";

            $result = $this->QueryObtieneDatosN();
            while ($tsArray = (ibase_fetch_object($result))) {
                $data[] = $tsArray;
            }
        }
        return $data;
    }

    function Pagos() {
        $this->query = "SELECT a.cve_doc, b.nombre, a.importe, a.fechaelab, a.fecha_doc, doc_sig as Recepcion, a.enlazado, c.camplib1 as TipoPagoR, c.camplib3 as FER,c.camplib2 as TE, c.camplib4 as Confirmado, a.tp_tes as PagoTesoreria, a.pago_tes, pago_entregado, c.camplib6, a.cve_clpv, a.URGENTE, datediff(day, a.fechaelab, current_date ) as Dias
                        from compo01 a
                        left join Prov01 b on a.cve_clpv = b.clave
                        LEFT JOIN compo_clib01 c on a.cve_doc = c.clave_doc
                        where a.status <> 'C' and TP_TES is null and fechaelab > '03/14/2016' order by a.fechaelab asc";
        ///echo $this->query;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT s.idsol as cve_doc, p.nombre, s.monto as importe, s.fecha as fechaelab, 'Ver Documentos' as Recepcion, 'NA' as enlazado, tipo as tipopagoR, 'NA' as fer, 'NA' as TE, usuario as CONFIRMADO, TIPO AS PagoTesoreria, 'NA' as pago_entregado, 'NA' as camplib6, s.proveedor as CVE_CLPV, 'NA' as urgente, 'NA' as Dias
        		from SOLICITUD_PAGO s
        		inner join prov01 p on p.clave = s.proveedor
        		where s.status = '0' ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function Cheques() {
        $a = "SELECT P.*, B.BANCO
    		FROM P_CHEQUES P
    		LEFT JOIN PG_PAGOBANCO B ON B.DOCUMENTO = P.DOCUMENTO
      		WHERE FOLIO_REAL is null and p.id > 1842 ";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function folioReal() {
        $a = "SELECT max(folio_real) as FOLIO_REAL
    			FROM P_CHEQUES";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $folio = $row->FOLIO_REAL;
        $folion = $folio + 1;

        return $folion;
    }

    function DatosCheque($cheque) {
        $a = "SELECT *
    		FROM P_CHEQUES
    		WHERE CHEQUE='$cheque'";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);

        return $row;
    }

    function impChBanamex($cheque, $fecha, $folio) {
        $f = split('-', $fecha);

        $fech = $f[0] . '.' . $f[1] . '.' . $f[2];

        $this->query = "UPDATE P_CHEQUES SET FOLIO_REAL = $folio, FECHA_APLI='$fech' WHERE CHEQUE='$cheque'";

        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function listadoPagosImpresion() {
        $data = null;
        $this->query = "SELECT 'GASTO' AS TIPO, IDGASTO AS IDENTIFICADOR, FECHA_PAGO, B.CVE_PROV AS CLAVE_PROVEEDOR, NOMBRE, MONTO "
                . "FROM PAGO_GASTO A INNER JOIN GASTOS B "
                . "ON A.IDGASTO = B.ID INNER JOIN PROV01 C ON B.CVE_PROV = C.CLAVE "
                . "WHERE B.STATUS = 'P' ORDER BY FECHA_PAGO;";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function DatosPago($identificador) {
        $a = "SELECT g.*, p.nombre, pg.*
			from GASTOS g
			INNER JOIN PROV01 p ON p.clave = g.cve_prov
			INNER JOIN PAGO_GASTO pg on pg.idgasto = g.id
			WHERE g.ID = $identificador";
        $this->query = $a;
        $result = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($result);
        return $row;
    }

    function cancelarPedidos() {
        $a = "SELECT p.*, pr.nombre
			from FACTP01 p
			left join prov01 pr on pr.clave = p.cve_clpv
			WHERE ENLAZADO = 'T' AND FECHA_CANCELA IS NULL  AND DOC_SIG IS NULL";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function cancelaPedido($pedido, $motivo) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "UPDATE FACTP01 SET ENLAZADO = 'O', MOTIVO_CANCELACION = ('$usuario'||' : '||'$motivo') WHERE CVE_DOC = '$pedido'";
        $this->query = $a;
        $rs = $this->EjecutaQuerySimple();

        $b = "UPDATE PAR_FACTP01 SET PXS = CANT WHERE CVE_DOC = '$pedido'";
        $this->query = $b;
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function listaClientes() {
        $a = "SELECT C.*, (select sum(SALDO) from carga_pagos CPA WHERE C.CLAVE = CPA.cliente group BY CPA.CLIENTE) AS saldoxa FROM CLIE01 C ;";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function cargaPago($cliente) {
        $a = "SELECT cl.*
			from clie01 cl
			where 	TRIM(clave) = TRIM('$cliente')";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function listarCuentasBancarias() {
        $this->query = "SELECT ID, BANCO, NUM_CUENTA, B.DESCR FROM PG_BANCOS A INNER JOIN MONED01 B ON A.MONEDA = B.NUM_MONED;";
        $result = $this->QueryObtieneDatosN();
        $data = null;
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function obtenerEdoCtaDetalle($identificador) {
        $this->query = "SELECT FIRST 20 B.ID AS IDREGISTRO, A.ID AS IDENTIFICADOR, A.BANCO, A.NUM_CUENTA, B.FEREGISTRO, B.DSREGISTRO, B.DCREGISTRO
                    FROM ESTADOCUENTA_REGISTRO B INNER JOIN PG_BANCOS A ON A.ID = B.IDCTABAN
                    WHERE B.IDCTABAN = '$identificador' ORDER BY FEREGISTRO DESC;";
        $result = $this->QueryObtieneDatosN();
        $data = null;
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function estadoCuentaRegistrar($idcuenta, $fecha, $descripcion, $monto) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $USUARIO = $_SESSION['user']->USER_LOGIN;
        $AUTOINCREMENT = "SELECT coalesce(MAX(ID), 0) FROM ESTADOCUENTA_REGISTRO";
        $this->query = "INSERT INTO ESTADOCUENTA_REGISTRO VALUES (";
        $this->query .= "($AUTOINCREMENT)+1, $idcuenta, '$fecha', '$descripcion',$monto,'$HOY','$USUARIO');";
        //echo "query: ".$this->query;
        $result = $this->EjecutaQuerySimple();
        return $result;
    }

    function obtenerEdoCtaDetalleDia($identificador, $dia) {
        //$TIME = time();
        //$HOY = date("Y-m-d H:i:s", $TIME);
        $this->query = "SELECT FIRST 20 B.ID AS IDREGISTRO, A.ID AS IDENTIFICADOR, A.BANCO, A.NUM_CUENTA, B.FEREGISTRO, B.DSREGISTRO, B.DCREGISTRO
                    FROM ESTADOCUENTA_REGISTRO B INNER JOIN PG_BANCOS A ON A.ID = B.IDCTABAN
                    WHERE B.IDCTABAN = '$identificador' AND B.FEREGISTRO = CAST('$dia' AS TIMESTAMP) ORDER BY FEREGISTRO DESC;";

        $result = $this->QueryObtieneDatosN();
        //$data[] = null;
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function listadoXrecibir() {
        $this->query = "SELECT ('Gasto'||'-'||cat.gasto) AS TIPO, A.ID AS IDENTIFICADOR, FECHA_DOC AS FECHA_PAGO, A.CVE_PROV AS CLAVE_PROVEEDOR, NOMBRE, MONTO_PAGO AS MONTO, (A.MONTO_PAGO-A.PRESUPUESTO) AS DIFERENCIA, P.CUENTA_BANCARIA AS BANCO
                   FROM GASTOS A INNER JOIN PROV01 B ON A.CVE_PROV = B.CLAVE
                   				INNER JOIN PAGO_GASTO P ON A.ID = P.IDGASTO
                   				INNER JOIN CAT_GASTOS cat ON cat.id = A.CVE_CATGASTOS
                   				WHERE A.STATUS = 'I';";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'ORDEN DE COMPRA' AS TIPO, CVE_DOC AS IDENTIFICADOR, FECHA_PAGO, CVE_CLPV AS CLAVE_PROVEEDOR, NOMBRE, PAGO_TES AS MONTO, A.BANCO AS BANCO "
                . "FROM COMPO01 A INNER JOIN  PROV01 B "
                . "ON A.CVE_CLPV = B.CLAVE WHERE STATUS_PAGO = 'I'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function marcarRecibido($tipo, $identificador, $fecha, $banco, $monto) {
        //	echo $tipo;
        $tipo = strtoupper(substr($tipo, 0, 5));
        //	echo $tipo;
        //	break;
        if ($tipo == "GASTO") {
            $this->query = "UPDATE GASTOS SET STATUS = 'V' WHERE ID = '$identificador';";
            //       echo $this->query;
            //       break;
        } else {
            $this->query = "UPDATE COMPO01 SET STATUS_PAGO = 'V' WHERE CVE_DOC = '$identificador';";
        }
        $result = $this->EjecutaQuerySimple();
        /// Actualizacion para el control del saldo del estado de cuenta desde PG_Bancos
        $campo = 'MOVR' . substr($fecha, 5, 2);
        $camposf = 'SALDOF' . substr($fecha, 5, 2);
        //echo 'Asi llega el Banco'.$banco;
        if ($banco != '') {
            $cuenta = split('-', $banco);
        }
        $cuenta2 = trim($cuenta[1]);

        /// Actualizamos los movimientos de cargo mensual segun el mes:
        $this->query = "UPDATE PG_BANCOS SET $campo= ($campo + $monto), $camposf=($camposf + $monto) where trim(NUM_CUENTA) = trim('$cuenta2')";
        $rs = $this->EjecutaQuerySimple();
        /// Actualizamos el saldo: de la cuenta:
        $this->query = "UPDATE PG_BANCOS SET SALDO= (SALDOF01 + SALDOF02 + SALDOF03 + SALDOF04 + SALDOF05 + SALDOF07 + SALDOF08 + SALDOF09 + SALDOF10 + SALDOF11 + SALDOF12 + SALDOI)";
        $rs = $this->EjecutaQuerySimple();


        return $result;
    }

    function listadoXconciliar() {
        $this->query = "SELECT 'GASTO' AS TIPO, ID AS IDENTIFICADOR, FECHA_CREACION AS FECHA_PAGO, A.CVE_PROV AS CLAVE_PROVEEDOR, NOMBRE, MONTO_PAGO AS MONTO, (A.MONTO_PAGO-PRESUPUESTO) AS DIFERENCIA
                   FROM GASTOS A INNER JOIN PROV01 B ON A.CVE_PROV = B.CLAVE WHERE A.STATUS = 'V';";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'ORDEN DE COMPRA' AS TIPO, CVE_DOC AS IDENTIFICADOR, FECHA_PAGO, CVE_CLPV AS CLAVE_PROVEEDOR, NOMBRE, PAGO_TES AS MONTO "
                . "FROM COMPO01 A INNER JOIN  PROV01 B "
                . "ON A.CVE_CLPV = B.CLAVE WHERE STATUS_PAGO = 'V'";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function pagoAconciliar($tipo, $identificador) {
        if ($tipo == "GASTO") {
            $this->query = "SELECT 'GASTO' AS TIPO, ID AS IDENTIFICADOR, FECHA_CREACION AS FECHA_PAGO, A.CVE_PROV AS CLAVE_PROVEEDOR, NOMBRE, MONTO_PAGO AS MONTO, (A.MONTO_PAGO-PRESUPUESTO) AS DIFERENCIA
                       FROM GASTOS A INNER JOIN PROV01 B ON A.CVE_PROV = B.CLAVE WHERE A.STATUS = 'V' AND A.ID = '$identificador';";
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = (ibase_fetch_object($result))) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT 'ORDEN DE COMPRA' AS TIPO, CVE_DOC AS IDENTIFICADOR, FECHA_PAGO, CVE_CLPV AS CLAVE_PROVEEDOR, NOMBRE, PAGO_TES AS MONTO "
                    . "FROM COMPO01 A INNER JOIN  PROV01 B "
                    . "ON A.CVE_CLPV = B.CLAVE WHERE STATUS_PAGO = 'V' AND CVE_DOC = '$identificador';";
            $result = $this->QueryObtieneDatosN();
            while ($tsArray = (ibase_fetch_object($result))) {
                $data[] = $tsArray;
            }
        }
        return @$data;
    }

    function pagoConciliar($tipo, $identificador, $fecha) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $rs = $this->xAutorizarDictamen($tipo, $identificador, "Z", "Pago conciliado: $HOY", $fecha);
        return $rs;
    }

    function xAutorizarDictamen($tipo, $identificador, $dictamen, $comentarios) {
        if ($tipo == "GASTO") {
            $dictamen = $dictamen == 'A' ? 'E' : 'R';
            $this->query = "UPDATE GASTOS
                            SET STATUS = '$dictamen', Autorizacion = '1'
                        WHERE ID = '$identificador'";
            $rs = $this->EjecutaQuerySimple();
        } else {
            //$dictamen = $dictamen=='A'?'PP':'PR';
            $this->query = "UPDATE compo01
                            SET STATUS_PAGO = '$dictamen'
                        WHERE CVE_DOC = '$identificador'";
            $rs = $this->EjecutaQuerySimple();
        }
        $AUTOINCREMENT = "SELECT coalesce(MAX(ID), 0) FROM PAGO_AUTORIZACION";
        $this->query = "INSERT INTO PAGO_AUTORIZACION (ID, IDPAGO, TXCOMENTARIO, FECHA_DICTAMEN,USUARIO_REGISTRA) "
                . "VALUES (($AUTOINCREMENT)+1, '$identificador','$comentarios',current_timestamp ,'" . $_SESSION['user']->USER_LOGIN . "')";
        $rs += $this->EjecutaQuerySimple();
        return $rs;
    }

    function ActStatusImp($identificador) {
        $a = "UPDATE GASTOS SET STATUS = 'I' WHERE ID = $identificador";
        $this->query = $a;
        $rs = $this->EjecutaQuerySimple();
        return @$rs;
    }

    function guardaPago($cliente, $monto, $fechaA, $fechaR, $banco) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $a = "INSERT INTO CARGA_PAGOS (CLIENTE, FECHA, MONTO, SALDO, USUARIO, BANCO, Fecha_Apli, Fecha_Recep )
  					VALUES ('$cliente',current_timestamp, $monto, $monto, '$usuario', '$banco','$fechaA', '$fechaR')";
        $this->query = $a;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function regPagos($cliente) {
        $a = "SELECT * from CARGA_PAGOS where TRIM(cliente) = TRIM('$cliente')";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function saldoXaplicar() {
        $a = "SELECT cliente, sum(SALDO) from carga_pagos group by cliente;";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function aplicarPago($cliente) {
        $a = "SELECT c.*,
     	(select SUM(s.SALDO) from carga_pagos s where trim(c.clave) = trim(s.cliente)) as sxa,
     	ca.*,
     	(SELECT SUM(IMPORTE) FROM FACTP01 WHERE DOC_ANT = '' AND TRIM(CVE_CLPV) = TRIM('$cliente')) as MONTO_PEDIDOS,
     	((SELECT SUM(IMPORTE) FROM factf01 WHERE TRIM(CVE_CLPV) = TRIM('$cliente')) -
     	(SELECT SUM(IMPORTE) FROM CUEN_DET01 WHERE TRIM(CVE_CLIE) = TRIM('$cliente') AND SIGNO = -1 )) AS MONTO_FACTURADO,
     	(SELECT SUM(IMPORTE) FROM FACTF01 WHERE FECHA_VEN > current_date AND TRIM(CVE_CLPV) = TRIM('$cliente')) AS VENCIDO,
     	ca.LINEA_CRED -(((SELECT SUM(IMPORTE) FROM FACTP01 WHERE DOC_ANT = '' AND TRIM(CVE_CLPV) = TRIM('$cliente'))+((SELECT SUM(IMPORTE) FROM factf01 WHERE TRIM(CVE_CLPV) = TRIM('$cliente')))) -
     	(SELECT SUM(IMPORTE) FROM CUEN_DET01 WHERE TRIM(CVE_CLIE) = TRIM('$cliente') AND SIGNO = -1 ))  AS DISPONIBLE
            FROM CLIE01 c
            left join cartera ca on trim(ca.idcliente) = trim(c.clave)
            WHERE TRIM(CLAVE)=TRIM('$cliente')";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function traeFacturas($cliente) {
        $a = "SELECT FIRST 100 f.*, c.fecha_rec_cobranza, c.contrarecibo_cr, datediff(day, c.fecha_rec_cobranza,current_timestamp ) as dias
    		from FACTF01 f
    		left join CAJAS c on f.cve_doc = c.factura
    		where trim(cve_clpv) = trim('$cliente')
    		order by f.fechaelab desc";
        $this->query = $a;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function CuentasBancarias($banco, $cuenta) {
        $this->query = "SELECT b.*,
        	(SELECT SUM(MONTO) FROM CARGA_PAGOS p
            where extract(month from fecha_recep) = extract(month from current_date)
            and (b.banco||' - '||b.num_cuenta) = p.banco
            GROUP BY BANCO) as ABONOS_ACTUAL,
            (SELECT SUM(MONTO) FROM CARGA_PAGOS p
            where extract(month from fecha_recep)-1 = extract(month from current_date)-1
            and (b.banco||' - '||b.num_cuenta) = p.banco
            GROUP BY BANCO) as ABONOS_ANTERIOR,
            (SELECT SUM(SALDO) FROM CARGA_PAGOS p
            where extract(month from fecha_recep)-1 = extract(month from current_date)-1
            and (b.banco||' - '||b.num_cuenta) = p.banco
            GROUP BY BANCO) as MOV_X_REL_AC,
            (SELECT SUM(SALDO) FROM CARGA_PAGOS p
            where extract(month from fecha_recep)-1 = extract(month from current_date)-1
            and (b.banco||' - '||b.num_cuenta) = p.banco
            GROUP BY BANCO) as MOV_X_REL_AN
            FROM PG_BANCOS b
           	where b.banco = '$banco' and b.num_cuenta='$cuenta'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function pagosTotalMensual($banco, $cuenta) {
        $this->query = "SELECT SUM(MONTO), BANCO FROM CARGA_PAGOS where extract(month from fecha_recep) = extraxt(month from current_date) GROUP BY BANCO";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function traePagosActual($banco, $cuenta) {
        $this->query = "SELECT * FROM CARGA_PAGOS WHERE extract(MONTH from FECHA_RECEP) = extract(MONTH from CURRENT_DATE) and banco = ('$banco'||' - '||'$cuenta') AND STATUS <> 'C'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function traePagosAnterior($banco, $cuenta) {
        $this->query = "SELECT * FROM CARGA_PAGOS WHERE extract(month from FECHA_RECEP)-1 = extract(month from current_date)-1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function ingresarPago($banco, $monto, $fecha, $ref) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        if (trim(substr($banco, 0, 8)) == 'Banamex') {
            $folio_1 = 'BNMX';
        } elseif (trim(substr($banco, 0, 8)) == 'Bancomer') {
            $folio_1 = 'BBVA';
        } elseif (trim(substr($banco, 0, 8)) == 'Multiva') {
            $folio_1 = 'MTVA';
        } elseif (trim(substr($banco, 0, 8)) == 'Inbursa') {
            $folio_1 = 'INBU';
        } elseif (trim(substr($banco, 0, 8)) == 'Banco Az') {
            $folio_1 = 'BAZT';
        }
        $this->query = "SELECT MAX(cast(substring(FOLIO_X_BANCO from 6 for 6) as int)) as ULTIMO
    			FROM CARGA_PAGOS
    			WHERE FOLIO_X_BANCO STARTING WITH '$folio_1'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        if (empty($row)) {
            $folio = 1;
        } else {
            $folio = $row->ULTIMO + 1;
        }

        $this->query = "INSERT INTO CARGA_PAGOS (FECHA, MONTO, SALDO, USUARIO, BANCO, FECHA_RECEP, FOLIO_X_BANCO)
    					VALUES (current_timestamp, $monto, $monto, '$usuario', '$banco', '$fecha', '$folio_1'||'-'||'$folio')";
        //var_dump($this);
        $rs = $this->EjecutaQuerySimple();

        $campo = 'MOVR' . substr($fecha, 3, 2);
        //echo 'valor del campo.'.$campo;
        $campoA = 'MOVS' . substr($fecha, 3, 2);
        $camposf = 'SALDOF' . substr($fecha, 3, 2);
        if ($banco != '') {
            $cuenta = split('-', $banco);

            $this->query = "UPDATE PG_BANCOS SET $campoA = iif($campoA is null,0,$campoA) + $monto where NUM_CUENTA=trim('$cuenta[1]') ";
            $rs = $this->EjecutaQuerySimple();
        }

        $this->query = "UPDATE PG_BANCOS SET $camposf= ($camposf + $monto)
	    where num_cuenta = trim('$cuenta[1]')";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PG_BANCOS SET SALDO = (SALDOF01 + SALDOF02 + SALDOF03 + SALDOF04 + SALDOF05 + SALDOF07 + SALDOF08 + SALDOF09 + SALDOF10 + SALDOF11 + SALDOF12 + SALDOI)";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function estado_de_cuenta($banco, $cuenta) {
        $data[] = null;
        $this->query = "SELECT 'Venta' AS TIPO, FOLIO_X_BANCO AS CONSECUTIVO, FECHA_RECEP AS FECHAMOV, MONTO AS MONTO, SALDO AS SALDO, BANCO AS BANCO, USUARIO AS USUARIO, tipo_pago as TP, ID as IDENTIFICADOR
    		   from carga_pagos where BANCO = ('$banco'||' - '||'$cuenta')";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        //$this->query="SELECT 'Gasto' AS TIPO, FOLIO_PAGO AS CONSECUTIVO, FECHA AS FECHAMOV, MONTO AS MONTO, 0 AS SALDO, TRIM(SUBSTRING(CUENTA_BANCARIA FROM 1 FOR 9)) AS BANCO, USUARIO_REGISTRA AS USUARIO
        //		FROM PAGO_GASTO WHERE CUENTA_BANCARIA = ('$banco'||' - '||'$cuenta') and status = 'V'";
        //$rs=$this->QueryObtieneDatosN();
        //while ($tsArray=ibase_fetch_object($rs)){
        //	$data[]=$tsArray;
        //}
        $this->query = "SELECT 'Compra' AS TIPO, CVE_DOC AS CONSECUTIVO, FECHAELAB AS FECHAMOV, IMPORTE AS MONTO, 0 AS SALDO, BANCO AS BANCO, '' AS USUARIO, 'tipoCompra' as TP, CVE_DOC as IDENTIFICADOR FROM COMPR01 WHERE BANCO = ('$banco'||' - '||'$cuenta')";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function estado_de_cuenta_mes($mes, $banco, $cuenta, $anio) {
        /* $this->query="SELECT 'Venta' AS TIPO,FOLIO_X_BANCO AS CONSECUTIVO, FECHA_RECEP AS FECHAMOV, MONTO AS ABONO, 0 AS CARGO, SALDO AS SALDO, BANCO AS BANCO, USUARIO AS USUARIO, tipo_pago as TP, id as identificador, registro as registro, folio_acreedor as FA , fecha_recep as fe, '' as comprobado
          from carga_pagos where BANCO = ('$banco'||' - '||'$cuenta') and extract(month from fecha_recep) = $mes and extract(year from fecha_recep) = $anio AND STATUS <> 'C' order by fecha_recep asc";
          $rs=$this->QueryObtieneDatosN();
          while($tsArray=ibase_fetch_object($rs)){
          $data[]=$tsArray;
          } */
        /// Pendientes edo de cuenta
        /// Ordenar por fecha.
        /// cambiar el formato de los numero
        $this->query = "SELECT 'Gasto' AS TIPO, pg.ID AS CONSECUTIVO, iif(fecha_edo_cta is null, FECHA_DOC, fecha_edo_cta) AS FECHAMOV, 0 AS ABONO, g.MONTO_PAGO AS CARGO, 0 AS SALDO, pg.CUENTA_BANCARIA AS BANCO, pg.USUARIO_REGISTRA AS USUARIO, pg.FOLIO_PAGO as TP, ('GTR'||g.id) as identificador, '' as registro, '' as FA, '' as fe, FECHA_EDO_CTA_OK as comprobado
    			FROM GASTOS g
    			left join pago_gasto pg on pg.idgasto = g.id
    			WHERE pg.CUENTA_BANCARIA = ('$banco'||' - '||'$cuenta') and iif(fecha_edo_cta is null,extract(month from g.FECHA_DOC), extract(month from fecha_edo_cta)) = $mes and iif(fecha_edo_cta is null, extract(year from g.FECHA_DOC), extract(month from fecha_edo_cta)) = $anio and g.status = 'V'";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'Compra' AS TIPO, CVE_DOC AS CONSECUTIVO, iif(edocta_fecha is null, fecha_doc, edocta_fecha) AS FECHAMOV, 0 AS ABONO, IMPORTE AS CARGO, 0 AS SALDO, BANCO AS BANCO, '' AS USUARIO, 'Compra' as TP, cve_doc as identificador, registro as registro, 'FA' as FA, edocta_fecha as fe, fecha_edo_cta_ok as comprobado
    				FROM COMPO01
    				WHERE BANCO = ('$banco'||' - '||'$cuenta') and extract(month from edocta_fecha) = $mes and extract(year from edocta_fecha) = $anio order by edocta_fecha asc";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'Compra' as TIPO, factura as consecutivo, fecha_edo_cta as fechamov, 0 as abono, importe as CARGO, 0 as saldo,  ('$banco'||' - '||'$cuenta') as BANCO, usuario as usuario, 'Compra' as TP, ('CD-'||id) as identificador,registro as registro, 'FA' as FA , fecha_edo_cta as fe, FECHA_EDO_CTA_OK as comprobado
    		FROM CR_DIRECTO
    		where BANCO = '$banco' and cuenta = '$cuenta' and extract(month from fecha_EDO_CTA) = $mes and extract(year from fecha_edo_cta) = $anio and tipo = 'compra' order by fecha_edo_cta asc ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'Gasto Directo' as TIPO, factura as consecutivo, fecha_edo_cta as fechamov, 0 as abono, importe as CARGO, 0 as saldo,  ('$banco'||' - '||'$cuenta') as BANCO, usuario as usuario, 'Compra' as TP, ('CD-'||id) as identificador,registro as registro, 'FA' as FA, fecha_edo_cta as fe , FECHA_EDO_CTA_OK as comprobado
       		FROM CR_DIRECTO
    		where BANCO = '$banco' and cuenta = '$cuenta' and extract(month from fecha_EDO_CTA) = $mes and extract(year from fecha_EDO_CTA) = $anio and tipo = 'gasto' order by fecha_edo_cta asc ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'Deudor' as TIPO, ('D'||iddeudor) as consecutivo, fechaedo_cta as fechamov, 0 as abono, importe as CARGO, 0 as saldo, ('$banco'||' - '||'$cuenta') as BANCO, usuario as usuario, 'Deudor' as TP, ('D'||iddeudor) as identificador, 'registro' as registro, 'FA' as FA, fechaedo_cta as fe, FECHA_EDO_CTA_OK as comprobado
    		from deudores
    		where extract(month from fechaedo_cta) = $mes and extract(year from fechaedo_cta) = $anio and banco = ('$banco'||' - '||'$cuenta') order by fechaedo_cta asc";

        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT 'Compra' as TIPO, ('SOL-'||idsol) as consecutivo, fecha_edo_cta as fechamov, 0 as abono, monto_final as cargo, 0 as saldo, '$banco' as BANCO, usuario_pago as usuario, 'Compra' as TP, ('SOL-'||idsol) as identificador, registro as registro, 'FA' as FA, fecha_edo_cta as fe, FECHA_EDO_CTA_OK as comprobado
			FROM SOLICITUD_PAGO
			WHERE EXTRACT(month from fecha_edo_cta) = $mes and extract(year from fecha_edo_cta) = $anio and banco_final=('$banco'||' - '||'$cuenta') order by fecha_edo_cta asc";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "DELETE from CARGOS_MENSUALES";
        $rs = $this->EjecutaQuerySimple();

        foreach ($data as $key) {
            $this->query = "INSERT INTO CARGOS_MENSUALES(TIPO, CONSECUTIVO, FECHAMOV, ABONO, CARGO, SALDO, BANCO, USUARIO, TP, IDENTIFICADOR, REGISTRO, FA, FE, COMPROBADO, MES, ANIO)
    						VALUES('$key->TIPO', '$key->CONSECUTIVO', '$key->FECHAMOV', $key->ABONO, $key->CARGO,$key->SALDO, '$key->BANCO', '$key->USUARIO', '$key->TP', '$key->IDENTIFICADOR', '$key->REGISTRO', '$key->FA', '$key->FE', '$key->COMPROBADO', $mes,$anio)";
            $rs = $this->EjecutaQuerySimple();
        }

        return @$data;
    }

    function traeMeses() {
        $this->query = "SELECT NOMBRE, NUMERO FROM PERIODOS_2016";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeNombreMes($mes) {
        $this->query = "SELECT NOMBRE, NUMERO FROM PERIODOS_2016 where numero = $mes";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeMes($mes) {
        $this->query = "SELECT NOMBRE, FECHA_INI, FECHA_FIN, NUMERO FROM PERIODOS_2016 WHERE NUMERO = $mes";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        return $row;
    }

    function traeFactura($docf) {
        $this->query = "SELECT f.* , c.nombre, c.clave, ca.status_log , ca.aduana
    		FROM FACTF01 f
    		left join clie01 c on c.clave = f.cve_clpv
    		left join cajas ca on ca.factura = f.cve_doc
    		WHERE f.STATUS <> 'C' and cve_doc CONTAINING('$docf')";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function cambiarFactura($docf1, $tipo) {

        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "SELECT COUNT(ID) AS CAJA FROM CAJAS WHERE FACTURA = '$docf1'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $caja = $row->CAJA;

        if ($caja == 0) {
            $this->query = "SELECT DOC_ANT, CVE_CLPV AS CLIENTE from factf01 where cve_doc = '$docf1'";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $docp = $row->DOC_ANT;
            $cvecli = $row->CLIENTE;

            $this->query = "SELECT CARTERA_COBRANZA AS CC, CARTERA_REVISION AS CR, DIAS_REVISION AS DR, DIAS_PAGO AS DP, REV_DOSPASOS AS RDP, ENVIO FROM CARTERA WHERE TRIM(IDCLIENTE) = TRIM('$cvecli')";
            $rs = $this->EjecutaQuerySimple();
            $row = ibase_fetch_object($rs);
            if (empty($row)) {
                $carteraR = 's/i';
                $carteraC = 's/i';
                $dr = 's/i';
                $dp = 's/i';
                $envio = 'S/i';
                $rev2 = 'N';
            } else {
                $carteraR = $row->CR;
                $carteraC = $row->CC;
                $dr = $row->DR;
                $dp = $row->DP;
                $envio = $row->ENVIO;
                $rev2 = $row->RDP;
            }
            if (empty($docp) or substr($docp, 0, 1) == 'C') {
                $docp = 'directa';
            }

            if (substr($docp, 0, 1) == 'P') {
                $this->query = "INSERT INTO CAJAS (FECHA_CREACION, FECHA_CIERRE, STATUS, CVE_FACT, REMISION, FACTURA, NC, UNIDAD, STATUS_LOG, COMPLETA, RUTA, FECHA_SECUENCIA, IDU, PESO, SECUENCIA, HORAI, HORAF, DOCS, CIERRE_UNI, CIERRE_TOT, CAJAS, EMBALAJE, MOTIVO, STATUS_MER, CONTRARECIBO, VAL_ADUANA, CONTRARECIBO_CR, STATUSCR_CR, ENVIO, REV_DOSPASOS, GUIA_FLETERA, FLETERA, LOGISTICA, ADUANA, VUELTAS, USUARIO_LOG, USUARIO_CAJA, FECHA_DESLINDE_REVISION, SOL_DESLINDE, FECHA_SOL, CR, DIAS_REVISION, CC, DIAS_PAGO, USUARIO_ADUANA, FECHA_ADUANA, SOL_DES_ADUANA, FECHA_SOL_DESADU, USUARIO_DES_ADU, FECHA_GUIA, FECHA_ENTREGA, FECHA_U_LOGISTICA, FECHA_U_BODEGA, STATUS_CTRL_DOC_ENTREGA, IMP_COMP_REENRUTAR, FOLIO_DEV, FOLIO_RECMCIA, DOCFACT)
            		VALUES( current_timestamp, current_timestamp, 'cerrado','$docp','directa','$docf1','', '99', 'Entregado', '1', 'A', current_timestamp,99, 9.99,1,current_time, current_time, 'No','ok','ok', 1, 'Total', '','Total', 'Directo', null,null,  'N', '$envio' ,'$rev2',null,null, 'Total', 'Cobranza',0,'$usuario', '$usuario',null, null, null, iif('$carteraR' is null, 'n/C','$carteraR'), '$dr', '$carteraC', '$dp', '$usuario',current_timestamp, null, null, '$usuario', null, null, null, null, 'Nu', null, null, 'no','')";

                $rs = $this->EjecutaQuerySimple();
            } elseif ($docp == 'directa') {
                echo 'vale';
                $this->query = "INSERT INTO CAJAS (FECHA_CREACION, FECHA_CIERRE, STATUS, CVE_FACT, REMISION, FACTURA, NC, UNIDAD, STATUS_LOG, COMPLETA, RUTA, FECHA_SECUENCIA, IDU, PESO, SECUENCIA,HORAI, HORAF, DOCS, CIERRE_UNI, CIERRE_TOT, CAJAS, EMBALAJE, MOTIVO, STATUS_MER, CONTRARECIBO, VAL_ADUANA, CONTRARECIBO_CR, STATUSCR_CR, ENVIO, REV_DOSPASOS, GUIA_FLETERA, FLETERA, LOGISTICA, ADUANA, VUELTAS, USUARIO_LOG, USUARIO_CAJA, FECHA_DESLINDE_REVISION, SOL_DESLINDE, FECHA_SOL, CR, DIAS_REVISION, CC, DIAS_PAGO, USUARIO_ADUANA, FECHA_ADUANA, SOL_DES_ADUANA, FECHA_SOL_DESADU, USUARIO_DES_ADU, FECHA_GUIA, FECHA_ENTREGA, FECHA_U_LOGISTICA, FECHA_U_BODEGA, STATUS_CTRL_DOC_ENTREGA, IMP_COMP_REENRUTAR, FOLIO_DEV, FOLIO_RECMCIA, DOCFACT )
            		 VALUES( current_timestamp, current_timestamp, 'cerrado','directa','directa','$docf1','', '99', 'Entregado', '1', 'A', current_timestamp,99, 9.99,1,current_time, current_time,  'No',
                    'ok','ok', 1, 'Total', '','Total', 'Directo', null,null,  'N', '$envio' ,'$rev2',null,null, 'TOtal', 'Cobranza',
                    0,'$usuario', '$usuario',null, null, null, '$carteraR', '$dr', '$carteraC', '$dp', '$usuario',current_timestamp, null, null, '$usuario',
                    null, null, null, null, 'Nu', null, null, 'no','')";

                $rs = $this->EjecutaQuerySimple();
            } elseif (trim(substr($docp, 10, 1)) == 0) {

                $this->query = "SELECT DOC_ANT from factr01 where cve_doc = '$docp'";

                $rs = $this->EjecutaQuerySimple();
                $row = ibase_fetch_object($rs);
                $docp = $row->DOC_ANT;

                $this->query = "INSERT INTO CAJAS (FECHA_CREACION, FECHA_CIERRE, STATUS, CVE_FACT, REMISION, FACTURA, NC, UNIDAD, STATUS_LOG, COMPLETA, RUTA, FECHA_SECUENCIA, IDU, PESO, SECUENCIA,HORAI, HORAF, DOCS, CIERRE_UNI, CIERRE_TOT, CAJAS, EMBALAJE, MOTIVO, STATUS_MER, CONTRARECIBO, VAL_ADUANA, CONTRARECIBO_CR, STATUSCR_CR, ENVIO, REV_DOSPASOS, GUIA_FLETERA, FLETERA, LOGISTICA, ADUANA, VUELTAS, USUARIO_LOG, USUARIO_CAJA, FECHA_DESLINDE_REVISION, SOL_DESLINDE, FECHA_SOL, CR, DIAS_REVISION, CC, DIAS_PAGO, USUARIO_ADUANA, FECHA_ADUANA, SOL_DES_ADUANA, FECHA_SOL_DESADU, USUARIO_DES_ADU, FECHA_GUIA, FECHA_ENTREGA, FECHA_U_LOGISTICA, FECHA_U_BODEGA, STATUS_CTRL_DOC_ENTREGA, IMP_COMP_REENRUTAR, FOLIO_DEV, FOLIO_RECMCIA, DOCFACT )
            		 VALUES(current_timestamp, current_timestamp, 'cerrado','$docp','directa','$docf1','', '99', 'Entregado', '1', 'A', current_timestamp,99, 9.99,1,current_time, current_time,  'No','ok','ok', 1, 'Total', '','Total', 'Directo', null,null, 'N', '$envio' ,'$rev2',null,null, 'Total', 'Cobranza', 0,'$usuario', '$usuario',null, null, null, iif('$carteraR' is null, 'n/C','$carteraR'), '$dr', '$carteraC', '$dp', '$usuario',current_timestamp, null, null, '$usuario',null, null, null, null, 'Nu', null, null, 'no','')";

                $rs = $this->EjecutaQuerySimple();
            }
        } else {
            $this->query = "UPDATE CAJAS SET ADUANA = 'Cobranza', fecha_rec_cobranza = current_timestamp where factura = '$docf1'";
            $rs = $this->EjecutaQuerySimple();
            return $rs;
        }
        return $rs;
    }

    function porFacturarEmbalar($docp) {  //01072016
        $this->query = "SELECT a.cotiza, sum(rec_faltante) as Faltante, MAX(NOM_CLI) AS NOM_CLI, MAX (CLIEN) AS CLIEN, max(c.codigo) as CODIGO,
                MAX(b.doc_sig) as FACTURA, max (a.fechasol) as FECHASOL, max(b.importe) as IMPORTE, max(datediff(day,b.FECHAELAB,current_date)) as DIAS,
                MAX(b.CITA) as CITA, max(factura) as factura, max(fecha_fact) as fecha_factu, sum(a.recepcion) as recibido,  sum(a.empacado) as empacado, max(f.fechaelab) as fecha_fact,
                sum(a.facturado) as facturado, sum(a.remisionado) as remisionado, sum(pendiente_facturar) as penfact, sum(pendiente_remisionar) as penrem
                FROM preoc01 a
                LEFT JOIN FACTP01 b on a.cotiza = b.cve_doc
                LEFT JOIN CLIE01 c on b.cve_clpv = c.Clave
                left join factf01 f on f.cve_doc = a.factura
                left join factr01 r on r.cve_doc = a.remision
                group by cotiza
                HAVING cotiza = '$docp'";
        /* and (max(f.fechaelab) > '01.08.2016' or max(r.fechaelab) > '01.07.2016')"; /*PENDIENTE */
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function regCompras($mes) {
        $this->query = "SELECT c.*, p.nombre
					FROM COMPO01 c
					LEFT JOIN PROV01 p ON c.cve_clpv = p.clave
					WHERE c.status != 'C'and edocta_fecha is null and extract(month from fechaelab) = $mes";
        $rs = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function regCompEdoCta($fecha, $docc, $mes, $pago, $banco, $tptes) {
        $this->query = "UPDATE COMPO01 SET EDOCTA_FECHA = '$fecha', edocta_reg = current_timestamp, edocta_status = 'I' where cve_doc='$docc'";
        $rs = $this->EjecutaQuerySimple();

        $campo = 'MOVR' . substr($fecha, 0, 2);
        $campoA = 'MOVS' . substr($fecha, 0, 2);
        $camposf = 'SALDOF' . substr($fecha, 0, 2);
        if ($banco != '') {
            $cuenta = split('-', $banco);

            $this->query = "UPDATE PG_BANCOS SET $campo = iif($campo is null,0,$campo) + iif($pago IS NULL,0,$pago) where NUM_CUENTA=trim('$cuenta[1]') ";
            $rs = $this->EjecutaQuerySimple();
        }

        $this->query = "UPDATE PG_BANCOS SET $camposf= ($camposf - iif($pago IS NULL,0,$pago))
	    where num_cuenta = trim('$cuenta[1]')";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PG_BANCOS SET SALDO = (SALDOF01 + SALDOF02 + SALDOF03 + SALDOF04 + SALDOF05 + SALDOF07 + SALDOF08 + SALDOF09 + SALDOF10 + SALDOF11 + SALDOF12 + SALDOI)";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function listarPagosCredito() {
        //SELECT ID, BENEFICIARIO, MONTO, DOCUMENTO, FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO FROM GASTOS_PAGOS_CREDITO;
        //SELECT ID, BENEFICIARIO, MONTO, DOCUMENTO, FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO FROM OC_PAGOS_CREDITO;
        $this->query = "SELECT 'RECEPCION' AS TIPO, ID, BENEFICIARIO, MONTO, OC, RECEPCION, FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO FROM OC_PAGOS_CREDITO where STATUS_CREDITO = 1";
        //$this->query.= " UNION ";
        // $this->query.= "SELECT 'GASTO' AS TIPO, ID, BENEFICIARIO, MONTO, DOCUMENTO, FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO FROM GASTOS_PAGOS_CREDITO;";

        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        //echo 'Esta es la consulta';
        //        $this->query = "SELECT 'GASTO' AS TIPO, ID, BENEFICIARIO, MONTO, DOCUMENTO, FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO FROM GASTOS_PAGOS_CREDITO ORDER BY PROMESA_PAGO;";
        //        $result = $this->QueryObtieneDatosN();
        //        while ($tsArray = (ibase_fetch_object($result))) {
        //            $data[] = $tsArray;
        //        }
        return @$data;
    }

    function detallePagoCredito($tipo, $identificador) {
        if ($tipo == "GASTO") {
            $this->query = "SELECT 'GASTO' AS TIPO, ID, BENEFICIARIO, MONTO, MAIL, DOCUMENTO, FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO FROM GASTOS_PAGOS_CREDITO WHERE ID = $identificador;";
        } else {
            $this->query = "SELECT 'RECEPCION' AS TIPO, ID, BENEFICIARIO, MONTO, MAIL, OC, RECEPCION , FECHA_DOC, DIASCRED, VENCIMIENTO, PROMESA_PAGO, FACTURA, MONTOR, recepcion as documento  FROM OC_PAGOS_CREDITO WHERE ID = '$identificador';";
        }
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function actualizarRecepcion($identificador) {
        $this->query = "UPDATE COMPR01 SET STATUS_CREDITO = 2 WHERE TRIM(CVE_DOC) = TRIM('$identificador')";
        $act = $this->EjecutaQuerySimple();
        //echo $this->query;
        return $act;
    }

    function actualizaPagoCreditoContrarecibo($tipo, $identificador) {
        if ($tipo == "GASTO") {
            $this->query = "UPDATE GASTOS SET STATUS = 'I' WHERE ID = $identificador;";
        } else {
            $this->query = "UPDATE P_CREDITO SET STATUS = 'I' WHERE ID = $identificador;";
        }
        //echo "query: ".$this->query;
        $respuesta = $this->EjecutaQuerySimple();
        return $respuesta;
    }

    /* function actualizaPagoCreditoContrarecibo($tipo, $identificador){

      }

      function listarCreditosContrarecibo() {

      }

      function detalleCreditoContrarecibo($tipo, $identificador){

      }

      function pagarCreditoContrarecibo($tipo, $identificador, $cuenta, $medio, $monto){

      }
     */

    function listarOCAduana($mes, $anio) {
        //status_log debe de ser Total, Fallido o PNR.
        $this->query = "SELECT CVE_DOC AS IDENTIFICADOR,FECHA_DOC AS FECHA_DOCUMENTO, IMPORTE AS MONTO, NOMBRE FROM COMPO01 A INNER JOIN PROV01 B ON A.CVE_CLPV = B.CLAVE WHERE STATUS_LOG IN ('Total','Fallido','PNR') AND CVE_DOC NOT IN (SELECT CVE_DOC FROM OC_ADUANA)";
        if ($mes != '' && $anio != '') {
            $this->query .= " AND EXTRACT(MONTH FROM FECHA_DOC) = $mes AND EXTRACT(YEAR FROM FECHA_DOC) = $anio;";
        }
        //echo "query = ".$this->query;
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = (ibase_fetch_object($result))) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function registrarOCAduana($identificador, $aduana) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "INSERT INTO OC_ADUANA VALUES ('$identificador','A','$HOY','$usuario','$aduana')";
        $respuesta = $this->EjecutaQuerySimple();
        return $respuesta;
    }

    function traeTipoGasto() {
        $this->query = "SELECT * FROM CLA_GASTOS";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verFallidas() {
        $this->query = "SELECT a.*, b.NOMBRE, c.OPERADOR, d.cve_doc as Recepcion
   				  from compo01 a
   				  left join prov01 b on a.cve_clpv = b.clave
   				  left join unidades c on a.unidad = c.numero
   				  left join compr01 d on a.doc_sig = d.cve_doc
   				  where a.doc_sig is null AND a.FECHAELAB > '01.09.2016' or (a.cve_doc = 'OE16317' or a.CVE_DOC = 'OE16318') ";
        $result = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function fallarOC($doco) {
        $usuario = $_SESSION['user']->USER_LOGIN;

        $this->query = "SELECT MAX(FOLIO_FALSO) as fs from COMPO01 WHERE DOC_SIG STARTING WITH ('F')";
        $rs = $this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $f = $row->FS;
        $folio = $f + 1;

        $this->query = "UPDATE COMPO01 SET DOC_SIG = ('F'||$folio), FOLIO_FALSO=$folio, usuario_recibe= '$usuario' where cve_doc = '$doco'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function impFallido($doco) {
        $this->query = "SELECT c.*, p.nombre
   				from compo01 c
   				left join prov01 p on p.clave = c.cve_clpv
   				where cve_doc ='$doco'";
        $rs = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function impFallidoPar($doco) {
        $this->query = "SELECT c.*, i.descr
   				from par_compo01 c
   				left join inve01 i on c.cve_art = i.cve_art
   				where cve_doc ='$doco'";
        $rs = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verSaldoFacturas($cveclie) {
        $this->query = "SELECT f.*, c.*, cl.RFC
   				FROM factf01 f
   				left join clie01 cl on cl.clave =  f.cve_clpv
   				LEFT JOIN cajas c on f.cve_doc = c.factura
   				where trim(f.cve_clpv)=trim('$cveclie') and f.saldo > 0 ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verPagos2($clie, $docf, $rfc) {
        $this->query = "SELECT * from carga_pagos where (cliente is null or trim(rfc)=trim('$rfc')) and saldo > 0";
        //var_dump($this);
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function treaSaldoFacturas($docf, $clie) {
        $this->query = "SELECT f.*, c.* , cl.nombre as cliente, cl.RFC as rfc
   				FROM factf01 f
   				LEFT JOIN cajas c on f.cve_doc = c.factura
   				left join clie01 cl on cl.clave= f.cve_clpv
   				where trim(f.cve_clpv)=trim('$clie') and f.cve_doc='$docf'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function aplicarPagoxFactura($docf, $idpago, $monto, $saldof, $clie, $rfc) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        if ($monto > $saldof) {
            $saldodoc = 0;
            $pago = $saldof;
            $saldopago = $monto - $saldof;
        } else {
            $saldodoc = $saldof - $monto;
            $pago = $monto;
            $saldopago = 0;
        }
        $this->query = "UPDATE FACTF01 SET SALDO=$saldodoc, pagos=(pagos+$pago) where CVE_DOC ='$docf'";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE CARGA_PAGOS SET SALDO=$saldopago, cliente = $clie where ID=$idpago";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "INSERT INTO APLICACIONES (FECHA, IDPAGO, DOCUMENTO, MONTO_APLICADO, SALDO_DOC, SALDO_PAGO, USUARIO, RFC)
   					  VALUES (current_timestamp, $idpago, '$docf', $pago, $saldodoc, $saldopago, '$usuario', '$rfc')";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "SELECT SALDO FROM FACTF01 WHERE CVE_DOC ='$docf'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->SALDO;

        return $data;
    }

    function verFacturas2($clie, $id) {
        $this->query = "SELECT * FROM FACTF01 WHERE SALDO > 0 AND trim(CVE_CLPV)=trim('$clie') and status != 'C'";
        //var_dump($this);
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verPagoaAplicar($clie, $id) {
        $this->query = "SELECT * FROM CARGA_PAGOS WHERE ID = $id";
        //var_dump($this);
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function aplicaPagoFactura($clie, $id, $docf, $monto, $saldof, $rfc, $tipo) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        // calculo de aplicacion.
        if ($monto >= $saldof) {
            //echo 'El monto: '.$monto.' es mayor al saldo del documento: '.$saldof;
            $saldoD = 0; // Saldo Documento
            $saldoP = $monto - $saldof;
            $aplicar = $saldof;
        } elseif ($saldof > $monto) {
            //echo 'El monto: '.$monto.' es menor al saldo del documento: '.$saldof;
            $saldoP = 0;
            $saldoD = $saldof - $monto;
            $aplicar = $monto;
        }
        if ($monto == 0) {
            echo 'Ya no hay saldo para aplicar a esta factura... Favor de revisar los datos.';
            return $monto;
        }
        if (substr($id, 0, 1) == 'N') {
            $this->query = "INSERT INTO APLICACIONES (FECHA, FORMA_PAGO, DOCUMENTO, MONTO_APLICADO, SALDO_DOC, SALDO_PAGO, USUARIO, RFC)
   						VALUES (current_timestamp, '$id', '$docf', $aplicar, $saldoD, $saldoP, '$usuario', '$rfc')";
            $rs = $this->EjecutaQuerySimple();
        } else {
            $this->query = "INSERT INTO APLICACIONES (FECHA, IDPAGO, DOCUMENTO, MONTO_APLICADO, SALDO_DOC, SALDO_PAGO, USUARIO, RFC, FORMA_PAGO)
   						VALUES (current_timestamp, $id, '$docf', $aplicar, $saldoD, $saldoP, '$usuario', '$rfc', '$tipo')";
            $rs = $this->EjecutaQuerySimple();
        }
        echo $tipo;
        if ($rs and substr($id, 0, 1) == 'N') {
            echo 'Actualizando Saldos ';
            $this->query = "UPDATE FACTF01 SET SALDOFINAL = $saldoD, PAGOS = (PAGOS + $aplicar) WHERE CVE_DOC = '$docf'";
            $rs = $this->EjecutaQuerySimple();
            $this->query = "UPDATE FACTD01 SET SALDO = $saldoP where CVE_DOC = '$id'";
            $rs = $this->EjecutaQuerySimple();
            $this->query = "SELECT SALDO FROM FACTD01 WHERE CVE_DOC = '$id'";
            $rs = $this->EjecutaQuerySimple();
            $row = ibase_fetch_object($rs);
            $rs = $row->SALDO;
        } elseif ($rs) {
            echo 'Actualizando Saldo';
            $this->query = "UPDATE FACTF01 SET SALDOFINAL = $saldoD, PAGOS = (PAGOS + $aplicar) WHERE CVE_DOC = '$docf'";
            $rs = $this->EjecutaQuerySimple();
            $this->query = "UPDATE CARGA_PAGOS SET SALDO = $saldoP where ID = $id";
            $rs = $this->EjecutaQuerySimple();

            $this->query = "SELECT extract(month from fechaelab) as mes, extract(year from fechaelab) as anio, cve_maestro as maestro where cve_doc = '$docf'";
            $result = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($result);
            $mes = $row->MES;
            $anio = $row->ANIO;
            $maestro = $row->MAESTRO;
            $campo = 'SALDO_' . $anio;

            $this->query = "UPDATE MAESTROS SET $campo = $campo - $aplicar where clave = '$maestro'";
            $rs = $this->EjecutaQuerySimple();

            $this->query = "SELECT SALDO FROM CARGA_PAGOS WHERE ID = $id";
            $rs = $this->EjecutaQuerySimple();
            $row = ibase_fetch_object($rs);
            $rs = $row->SALDO;
        }
        return $rs;
    }

    function crImpreso($tipo, $identificador) {
        $this->query = "UPDATE P_CREDITO SET STATUS = 'A', fecha_aceptacion = current_timestamp WHERE ID = $identificador";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function traeProv() {
        $this->query = "SELECT * FROM PROV01";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function guardaCompra($fact, $prov, $monto, $ref, $tipopago, $fechadoc, $fechaedocta, $banco, $tipo, $idg) {
        if ($banco != '') {
            $cuenta = split('-', $banco);
        }
        $bank = trim($cuenta[0]);
        $cuenta = trim($cuenta[1]);

        $usuario = $_SESSION['user']->NOMBRE;

        $this->query = "INSERT INTO CR_DIRECTO( FACTURA, FECHA_FACTURA, FECHA_MOV, PROVEEDOR, IMPORTE, FECHA_EDO_CTA, TP_TES, REFERENCIA, BANCO, CUENTA, TIPO, idgasto, usuario)
   							VALUES ('$fact', '$fechadoc', current_timestamp, '$prov', $monto, '$fechaedocta', '$tipopago', '$ref', '$bank', '$cuenta', '$tipo', $idg, '$usuario')";
        echo $this->query;
        //break;
        $rs = $this->EjecutaQuerySimple();
        //echo'Esta es la consulta: '.var_dump($this);
        /*
          if($rs){
          echo 'Esto se ejecuta si se insterta : ';
          $campo='MOVR'.substr($fechaedocta, 3,2);
          $campoA='MOVS'.substr($fechaedocta, 3,2);
          $camposf='SALDOF'.substr($fechaedocta, 3,2);

          $this->query="UPDATE PG_BANCOS SET $campo = iif($campo is null,0,$campo) + iif($monto IS NULL,0,$monto) where NUM_CUENTA=trim('$cuenta') ";
          $rs=$this->EjecutaQuerySimple();
          //echo 'Este es el update de Saldo:'.var_dump($this);


          $this->query="UPDATE PG_BANCOS SET $camposf= ($camposf - iif($monto IS NULL,0,$monto))
          where num_cuenta = trim('$cuenta')";
          $rs=$this->EjecutaQuerySimple();
          //echo 'Este es el update de Saldo Final: '.var_dump($this);

          $this->query="UPDATE PG_BANCOS SET SALDO = (SALDOF01 + SALDOF02 + SALDOF03 + SALDOF04 + SALDOF05 + SALDOF07 + SALDOF08 + SALDOF09 + SALDOF10 + SALDOF11 + SALDOF12 + SALDOI)";

          //echo 'Este es el update del saldo: '.var_dump($this);
          $rs=$this->EjecutaQuerySimple();
          }
         */

        return $rs;
    }

    function verAplicaciones() {

        $this->query = "SELECT a.*,f.cve_clpv as clave,  c.nombre as cliente, f.importe
   						FROM APLICACIONES a
   						left join factf01 f on a.documento = f.cve_doc
   						left join clie01 c on f.cve_clpv = c.clave
   						where a.status = 'E'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function impAplicacion($ida) {
        $this->query = "UPDATE APLICACIONES SET STATUS = 'I' WHERE ID = $ida";
        $rs = $this->EjecutaQuerySimple();


        $this->query = "SELECT a.*,f.cve_clpv as clave,  c.nombre as cliente, f.importe
   						FROM APLICACIONES a
   						left join factf01 f on a.documento = f.cve_doc
   						left join clie01 c on f.cve_clpv = c.clave
   						where  ID = $ida";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verPagosActivos($monto) {
        $this->query = "SELECT * FROM CARGA_PAGOS c WHERE SALDO > 2 and monto containing('$monto') and tipo_pago is null and status <> 'C'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $this->query = "SELECT cve_doc as ID,'N/A' as FOLIO_X_BANCO, CVE_CLPV as cliente, fechaelab as FECHA_RECEP, importe as Monto, 'N/A' as BANCO, RFC as RFC, saldo FROM FACTD01 WHERE SALDO > 2 AND (cve_doc containing('$monto') or importe containing('$monto'))";
        $rs = $this->QueryObtieneDatosN();
        //echo $this->query;
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verPagoActivo($idp, $tipo) {

        if (substr($idp, 0, 2) == 'NR') {
            $this->query = "SELECT cve_doc as ID, CVE_CLPV as cliente, fechaelab as FECHA_RECEP, importe as Monto, 'N/A' as BANCO, RFC as RFC, saldo FROM FACTD01 WHERE SALDO > 0 AND cve_doc = '$idp'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT * FROM CARGA_PAGOS WHERE ID=$idp";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }
        return $data;
    }

    function listaFacturas() {
        $this->query = "SELECT f.*--, c.NOMBRE
   					FROM FACTF01 f
   					--inner join clie01 c on f.cve_clpv=c.clave
   					WHERE f.SALDO > 2
   					and f.status != 'C'
   					and f.fechaelab > '10.10.2015'
   					and f.fechaelab <= '01.02.2016'
   					";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function IdvsComp() {
        $this->query = "SELECT p.id, p.cotiza,pe.cve_pedi, p.prod, p.nomprod, pr.nombre, pr.clave, poc.cost, p.cant_orig, oc.fecha_doc, pv.prec
 ,(((iif(pv.prec=0,1,pv.prec)/iif(poc.cost=0, 1, poc.cost))-1)*100) as utilidad
        from preoc01 p
        inner join par_compo01 poc on p.id = poc.id_preoc
        inner join compo01 oc on oc.cve_doc = poc.cve_doc
        inner join prov01 pr on pr.clave = oc.cve_clpv
        inner join par_factp01 pv on pv.id_preoc = p.id
        inner join factp01 pe on pe.cve_doc = pv.cve_doc
        where p.NOM_CLI containing ('LIVERPOOL')
        ORDER BY P.PROD ASC";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function listaFacturasOK($docf) {
        $this->query = "SELECT f.*, c.NOMBRE
   					FROM FACTF01 f
   					left join clie01 c on f.cve_clpv=c.clave
   					WHERE f.SALDO > 2
   					and cve_doc = '$docf'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function traeValidacion($doco) {
        $this->query = "SELECT * FROM VALIDA_RECEPCION WHERE DOCUMENTO ='$doco'";
        $rs = $this->QueryObtieneDatosN($doco);
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function verAplivsFact() {
        $this->query = "SELECT * FROM carga_pagos WHERE saldo <> monto and (status is null or status = '')";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function infoPago($idp) {
        $this->query = "SELECT * FROM carga_pagos where id = $idp";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function movimientosPago($idp) {
        $this->query = "SELECT a.*, f.*, c.*
					from aplicaciones a
					left join factf01 f on f.cve_doc = a.documento
					inner join clie01 c on c.clave = f.cve_clpv
					where a.idpago = $idp";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "UPDATE CARGA_PAGOS SET STATUS = 'I' WHERE ID = $idp";
        $result = $this->EjecutaQuerySimple();


        return @$data;
    }

    function almacenarFolioContrarecibo($tipo, $identificador, $montor, $facturap) {
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $usuario = $_SESSION['user']->USER_LOGIN;
        $AUTOINCREMENT = "SELECT coalesce(MAX(FOLIO), 0) AS FOLIO FROM OC_CREDITO_CONTRARECIBO";
        $this->query = $AUTOINCREMENT;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $folio = 1;
        foreach ($data as $row):
            $folio = $row->FOLIO + 1;
        endforeach;
        if ($tipo == "GASTO") {
            $id = $identificador;
        } else {
            $id = "(SELECT RECEPCION FROM OC_PAGOS_CREDITO WHERE ID = '$identificador')";
        }
        $this->query = "INSERT INTO OC_CREDITO_CONTRARECIBO VALUES ($folio,'$HOY','$tipo',$id,'$usuario','PD');";

        $respuesta = $this->EjecutaQuerySimple();
        $this->query = "UPDATE COMPR01 SET MONTO_REAL = $montor, FACT_PROV = '$facturap' where TRIM(CVE_DOC) = TRIM('$identificador')";
        $rs = $this->EjecutaQuerySimple();
        return $respuesta >= 1 ? $folio : -1;
    }

    function actualizarFolioContrarecibo($folio) {
        $this->query = "UPDATE OC_CREDITO_CONTRARECIBO SET STATUS = 'IM' WHERE FOLIO = $folio";
        //echo "query: ".$this->query;
        $respuesta = $this->EjecutaQuerySimple();
        return $respuesta;
    }

    function listarOCContrarecibos() {
        $this->query = "SELECT A.FOLIO, A.FECHA_IMPRESION, B.PROMESA_PAGO, A.TIPO, A.IDENTIFICADOR, A.USUARIO, B.RECEPCION, B.OC, B.FACTURA, B.MONTOR, B.BENEFICIARIO
                          FROM OC_CREDITO_CONTRARECIBO A
                          INNER JOIN OC_PAGOS_CREDITO B ON TRIM(A.IDENTIFICADOR) = TRIM(B.RECEPCION)
                          INNER JOIN COMPR01 C ON A.IDENTIFICADOR = C.CVE_DOC
                         WHERE A.STATUS = 'IM' AND (C.STATUS_PAGO <> 'PP' or C.STATUS_PAGO IS NULL)
                         ORDER BY B.BENEFICIARIO ASC";
        $rs = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function pagarOCContrarecibos($folios) {

        $this->query = "SELECT A.FOLIO, A.FECHA_IMPRESION, B.PROMESA_PAGO, A.TIPO, B.RECEPCION, B.OC, B.FACTURA , B.MONTOR, B.BENEFICIARIO
                          FROM OC_CREDITO_CONTRARECIBO A INNER JOIN OC_PAGOS_CREDITO B ON A.IDENTIFICADOR = B.RECEPCION
                         WHERE STATUS = 'IM' AND A.FOLIO IN ($folios)
                         ORDER BY B.PROMESA_PAGO;";
        //echo "query ". $this->query;
        $rs = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function encontrarInformacionFolioOCC($folio) {
        $this->query = "SELECT A.FOLIO, A.IDENTIFICADOR, B.FECHA_DOC, B.RECEPCION , B.BENEFICIARIO, B.MONTO
    					FROM OC_CREDITO_CONTRARECIBO A
    					INNER JOIN OC_PAGOS_CREDITO B ON A.IDENTIFICADOR = B.RECEPCION
    					WHERE A.FOLIO = $folio";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        $data = array();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function registrarPagoCreditoOC($monto, $cuentaBanco, $medio) {
        $AUTOINCREMENT = "SELECT coalesce(MAX(IDENTIFICADOR), 0) AS IDENTIFICADOR FROM PAGO_CREDITOS_OC;";
        $this->query = $AUTOINCREMENT;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $identificador = 1;
        foreach ($data as $row):
            $identificador = $row->IDENTIFICADOR + 1;
        endforeach;
        //echo "El identificador dado es: $identificador";
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query = "INSERT INTO PAGO_CREDITOS_OC (IDENTIFICADOR,FECHA_PAGO,CUENTA_BANCO,MEDIO_PAGO,MONTO_PAGO,USUARIO,STATUS)";
        $this->query .= "VALUES";
        $this->query .= "($identificador, '$HOY', '$cuentaBanco', '$medio', $monto, '$usuario', 'PD')";
        //echo $this->query;
        //echo "SQL PAGO_CREDITO_OC: ".$this->query;
        $respuesta = $this->EjecutaQuerySimple();
        if ($respuesta > 0) {
            return $identificador;
        } else {
            return $respuesta;
        }
    }

    function actualizarPagoCreditoOCAplicado($identificador) {
        $this->query = "UPDATE PAGO_CREDITOS_OC SET STATUS = 'AP' WHERE IDENTIFICADOR = $identificador";
        $respuesta = $this->EjecutaQuerySimple();
        return $respuesta > 0;
    }

    function pagarOCContrarecibosAplicar($identificador, $folio, $cuentaBancaria, $documento, $medio, $monto, $proveedor, $claveProveedor, $fechaDocumento) {
        //echo "por ir a GuardaPagoCorrecto";
        $respuesta = $this->GuardaPagoCorrecto($cuentaBancaria, $documento, $medio, $monto, $proveedor, $claveProveedor, $fechaDocumento);
        //echo "respuesta al registro de pago: $respuesta";
        if ($respuesta > 0) {
            $this->query = "INSERT INTO PAGO_CREDITOS_OC_DETALLE (IDENTIFICADOR, FOLIO, DOCUMENTO, MONTO_PAGO, FECHA_DOCUMENTO, CLV_PROV)";
            $this->query .= "VALUES";
            $this->query .= "($identificador, $folio, '$documento',$monto,'$fechaDocumento','$claveProveedor');";
            //echo "sql: ".$this->query;
            $respuesta = $this->EjecutaQuerySimple();
            return $respuesta;
        }
        return 0;
    }

    function IngresarBodega($desc, $cant, $marca, $proveedor, $costo, $unidad) {
        $this->query = "INSERT INTO INGRESOBODEGA (DESCRIPCION, CANT, FECHA, MARCA, Proveedor, Costo, unidad)
    	VALUES ('$desc', $cant, current_timestamp, '$marca', '$proveedor', $costo, '$unidad')";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function editIngresoBodega($idi, $costo, $proveedor, $cant, $unidad) {
        $this->query = "UPDATE INGRESOBODEGA SET PROVEEDOR = '$proveedor', costo = $costo, cant = $cant, unidad = '$unidad' where id = $idi";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function verIngresoBodega() {
        $this->query = "SELECT * FROM INGRESOBODEGA";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function emitirCierreCR($cr) {
        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }

        $this->query = "SELECT MAX(FOLIO_CIERRE) AS FC FROM CAJAS WHERE  upper(cr) = upper('$cr')";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $fn = $row->FC;
        $fn = $fn + 1;

        $this->query = "SELECT a.id, a.aduana , a.cve_fact,a.status_log, a.Remision,
    			c.importe as ImpRem,  a.FACTURA, d.importe as ImpFac,
    			c.fechaelab as FECHAREM, d.FECHAELAB as FECHAFAC, a.cr,
    			datediff(day,a.fecha_secuencia,current_date) AS dias,
    			a.CONTRARECIBO_CR , cl.nombre as CLIENTE, a.cr as CARTERA_REV
    			FROM CAJAS a
    			left join FACTR01 c on c.cve_doc = a.remision
    			left join FACTF01 d on d.cve_doc = a.factura
    			left join factp01 p on p.cve_doc = a.cve_fact
    			left join clie01 cl on p.cve_clpv = cl.clave
    			WHERE
        			a.aduana = 'Cobranza'
    			and a.CR = '$cr'
    			and a.contrarecibo_cr is not null
    			and a.imp_cierre = 0";

        /* "SELECT * FROM CAJAS
          where upper(cr) = upper('$cr')
          and (fecha_rev between CAST('TODAY' AS DATE) AND CAST('TOMORROW' AS DATE))
          and  contraRecibo_cr is not null"; */
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        foreach ($data as $key) {
            $caja = $key->ID;
            $this->query = "UPDATE cajas set imp_cierre = 1, folio_cierre = $fn
    			where id = $caja";
            //echo $this->query;
            $rs = $this->EjecutaQuerySimple();
        }

        //$this->query="UPDATE CAJAS SET IMP_CIERRE = 1 WHERE upper(cr) = upper('$cr') and fecha_ultimo_motivo = cast('now' as date)";
        //echo $this->query;
        //$rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function impRecCobranza() {
        $this->query = "SELECT max(folio_rec_cobranza) as FM from cajas";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $fn = $row->FM + 1;

        $this->query = "UPDATE CAJAS SET docs_cobranza = 'Si', folio_rec_cobranza = $fn, imp_rec_cobranza = 1 where docs_cobranza = 'S'";
        $rs = $this->EjecutaQuerySimple();

        return $fn;
    }

    function recepcionCobranza($folio) {
        $this->query = "SELECT c.*, cl.nombre, f.fechaelab, f.importe
    				  FROM CAJAS c
    				  left join factf01 f on f.cve_doc = c.factura
    				  left join CLIE01 cl on cl.clave = f.cve_clpv
    				  where docs_cobranza = 'Si'
    				  and imp_rec_cobranza = 1
    				  and folio_rec_cobranza = $folio
    				  ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function impresionCierre() {
        $this->query = "SELECT iif(COUNT(ID) is null, 0, COUNT(ID)) as VAL FROM CAJAS WHERE docs_cobranza = 'S' and imp_rec_cobranza = 0";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $result = $row->VAL;
        return $result;
    }

    function habilitaImpresionCierreEnt($idr) {
        $this->query = "SELECT COUNT(ID) FROM CAJAS WHERE DOCS = ";
    }

    function imprimeCierreEnt() {
        $this->query = "SELECT iif(Max(FOLIO_CIERRE_LOGISTICA) is null, 0, max(FOLIO_CIERRE_LOGISTICA)) as FA from cajas";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $fn = $row->FA + 1;

        $this->query = "UPDATE CAJAS SET FOLIO_CIERRE_LOGISTICA = $fn where cierre_uni = 'ok' and FOLIO_CIERRE_LOGISTICA=0";
        $rs = $this->EjecutaQuerySimple();

        return $fn;
    }

    function cierre_uni_ent($folio) {
        $this->query = "SELECT c.*, cl.nombre,
    				  iif(c.factura is null or c.factura = '', c.remision, c.factura) as Documento,
    				  iif(c.factura is null or c.factura = '', r.importe, f.importe) as importe,
    				  iif(c.factura is null or c.factura = '', r.fechaelab, f.fechaelab) as fechaelab
    				  From cajas c
    				  left join factp01 p on c.cve_fact = p.cve_doc
    				  left join factf01 f on c.factura = f.cve_doc
    				  left join factr01 r on c.remision = r.cve_doc
    				  left join clie01 cl on cl.clave = p.cve_clpv
    				  where FOLIO_CIERRE_LOGISTICA = $folio";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verCierreVal() {
        $this->query = "SELECT first 100 c.*, p.nombre , o.cve_doc as OC, o.fechaelab as OC_FECHAELAB, o.importe as OC_IMPORTE, o.STATUS_REC as OC_STATUS_VAL,  o.usuario_recibe AS OC_USUARIO_VAL
    			from compr01 c
    			left join prov01 p on c.cve_clpv = p.clave
    			left join compo01 o on o.cve_doc = c.doc_ant
    			where (c.status_rec = 'Ok'
    			or c.status_rec = 'par')
    			and c.Rec_Contabilidad = 'No'
    			and c.imp_cierre = 'No'
    			and c.fechaelab >= '01.08.2016'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function guardaFacturaProv($docr, $factura) {
        $this->query = "UPDATE COMPR01 SET FACTURA_PROV = '$factura' where cve_doc  = '$docr' ";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function impCierreVal() {
        $this->query = "SELECT max(FOLIO_IMP_CIERRE_VAL) as FA from compr01";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $fn = $row->FA + 1;

        echo "Este es el folio '$fn'";

        $this->query = "UPDATE COMPR01 SET FOLIO_IMP_CIERRE_VAL = $fn, IMP_CIERRE = 'Si' where imp_cierre = 'No' and factura_prov != 'Pendiente'";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "SELECT c.*, p.nombre , o.cve_doc as OC, o.fechaelab as OC_FECHAELAB, o.importe as OC_IMPORTE, o.STATUS_REC as OC_STATUS_VAL,  o.usuario_recibe AS OC_USUARIO_VAL
    			from compr01 c
    			left join prov01 p on c.cve_clpv = p.clave
    			left join compo01 o on o.cve_doc = c.doc_ant
    			where FOLIO_IMP_CIERRE_VAL = $fn";

        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function guardaCargoFinanciero($monto, $fecha, $banco) {

        $this->query = "SELECT BANCO, NUM_CUENTA FROM PG_BANCOS WHERE ID = $banco";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $cuenta = $row->NUM_CUENTA;
        $bank = $row->BANCO;

        $this->query = "INSERT INTO CARGO_FINANCIERO (FECHA_RECEP, MONTO, BANCO, CUENTA, SALDO) VALUES ('$fecha', $monto, '$bank','$cuenta',$monto )";
        $rs = $this->EjecutaQuerySimple();
        echo $this->query;
        return $rs;
    }

    function asociaCF() {
        $this->query = "SELECT cf.*, (select max(nombre) from clie01 c where c.rfc = cf.rfc) as cliente
   						from CARGO_FINANCIERO cf
   						where cf.saldo > 0";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function traeCF($idcf) {
        $this->query = "SELECT cf.*, (select max(nombre) from clie01 c where c.rfc = cf.rfc) as cliente
   						FROM CARGO_FINANCIERO cf
   						where ID = $idcf";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function traePagos($monto) {
        $this->query = "SELECT * FROM CARGA_PAGOS WHERE MONTO CONTAINING('$monto') and status <> 'C'";
        $rs = $this->EjecutaQuerySimple();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function cargaCF($idcf, $idp, $monto) {
        $this->query = "UPDATE CARGA_PAGOS SET CF= (iif(CF is null, '$idcf', CF||'-'||'$idcf')), saldo = saldo + $monto where id = $idp";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE CARGO_FINANCIERO SET SALDO = 0 WHERE ID = $idcf";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function verPagosConSaldo() {
        $this->query = "SELECT * FROM CARGA_PAGOS WHERE SALDO > 0 and status <> 'C' ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function enviaAcreedor($idp, $saldo, $rfc) {
        if ($rfc == 'XXX-AAA-XXX') {
            $cliente = 'XXX-AAA-XXX';
        } else {
            $this->query = "SELECT iif(MAX(CLAVE) is null, 0, max(clave)) AS CLAVE FROM CLIE01 WHERE upper(RFC) = upper('$rfc')";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $cliente = $row->CLAVE;
        }

        if ($cliente != 0 or $cliente == 'XXX-AAA-XXX') {
            $usuario = $_SESSION['user']->USER_LOGIN;
            $this->query = "INSERT INTO ACREEDORES (ID_PAGO, MONTO, CLIENTE, FECHA, FECHA_TS, usuario_in) VALUES ($idp, $saldo, '$cliente', current_date, current_timestamp, '$usuario')";
            $rs = $this->EjecutaQuerySimple();
            $this->query = "SELECT MAX(ID) AS FOLIO FROM ACREEDORES";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $folioA = $row->FOLIO;
            $this->query = "UPDATE CARGA_PAGOS SET FOLIO_ACREEDOR = $folioA, saldo = 0 where id = $idp";
            $rs = $this->EjecutaQuerySimple();
            return 0;
        } else {

            echo 'No se econtro el cliente';
            return 1;
        }
    }

    function traeClientes() {
        $this->query = "SELECT RFC, max(NOMBRE) AS NOMBRE FROM CLIE01 WHERE UPPER(STATUS) != UPPER('S') GROUP BY RFC ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verAcreedores() {
        $this->query = "SELECT ac.*, (c.nombre ||' ( '|| c.clave||' )') as CL, cp.banco as BANCO
   				from Acreedores ac
   				left join clie01 c on c.clave = ac.cliente
   				left join carga_pagos cp on cp.id = ac.id_pago
   				where contabilizado = 0";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function contabilizarAcreedor($ida) {
        $this->query = "UPDATE ACREEDORES SET contabilizado = 1 where id= $ida";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function facturasxaplicar($idp) {
        $this->query = "SELECT a.*, cl.nombre as cliente, f.fechaelab, f.importe
    				from APLICACIONES a
    				left join factf01 f on f.cve_doc = a.documento
    				left join clie01 cl on f.cve_clpv = cl.clave
    				where idpago = $idp and a.cancelado = 0";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function cancelaAplicacion($idp, $docf, $idap, $montoap) {
        $usuario = $_SESSION['user']->USER_LOGIN;

        $this->query = "UPDATE APLICACIONES SET CANCELADO = 1 WHERE id=$idap";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE CARGA_PAGOS SET SALDO = SALDO + $montoap where id = $idp";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE factf01 set saldo = saldo + $montoap, pagos = pagos - $montoap where cve_doc = '$docf'";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function procesarPago($idp, $tipo) {

        $this->query = "UPDATE CARGA_PAGOS SET TIPO_PAGO ='$tipo' where id = $idp";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function regEdoCta($idtrans, $monto, $tipo, $cargo, $anio, $nvaFechComp, $nf, $valor) {
        $usuario = $_SESSION['user']->USER_LOGIN;
        $nombre = $_SESSION['user']->NOMBRE;
        echo $idtrans;
        echo $tipo;

        if ($tipo == 'Venta') {
            $this->query = "INSERT INTO REG_EDOCTA (TRANSACCION, MONTO, SIGNO, FECHAELAB, FECHA_TRANSACCION, FECHA_APLICACION, USUARIO)
    					VALUES ('$idtrans', $monto, 1, current_timestamp, current_date, current_date, '$nombre')";
            $rs = $this->EjecutaQuerySimple();

            $this->query = "UPDATE CARGA_PAGOS SET REGISTRO = 1 WHERE ID = $idtrans";
            $rs = $this->EjecutaQuerySimple();
            return $rs;
        } elseif (substr($idtrans, 0, 1) == 'O') {
            $this->query = "INSERT INTO REG_EDOCTA (TRANSACCION, MONTO, SIGNO, FECHAELAB, FECHA_TRANSACCION, FECHA_APLICACION, USUARIO)
    					VALUES ('$idtrans', $cargo, -1, current_timestamp, current_date, current_date, '$nombre')";
            $rs = $this->EjecutaQuerySimple();
            if ($nf == '1' and $valor == '1') {
                $this->query = "UPDATE COMPO01 SET edocta_fecha = '$nvaFechComp', fecha_edo_cta_ok = $valor WHERE CVE_DOC = '$idtrans'";
                $rs = $this->EjecutaQuerySimple();
                echo $this->query;
                return $rs;
            } elseif ($nf == '1' and $valor == '0') {
                $this->query = "UPDATE COMPO01 SET fecha_edo_cta_ok = $valor WHERE CVE_DOC = '$idtrans'";
                $rs = $this->EjecutaQuerySimple();
                return $rs;
            } else {
                $this->query = "UPDATE COMPO01 SET REGISTRO = 1 WHERE CVE_DOC = '$idtrans'";
                $rs = $this->EjecutaQuerySimple();
                return $rs;
            }
        } elseif (substr($idtrans, 0, 3) == 'CD-') {
            $this->query = "INSERT INTO REG_EDOCTA (TRANSACCION, MONTO, SIGNO, FECHAELAB, FECHA_TRANSACCION, FECHA_APLICACION, USUARIO)
    					VALUES ('$idtrans', $cargo, -1, current_timestamp, current_date, current_date, '$nombre')";
            $rs = $this->EjecutaQuerySimple();

            if ($nf == '1' and $valor == '1') {
                $this->query = "UPDATE CR_DIRECTO SET fecha_edo_cta = '$nvaFechComp', fecha_edo_cta_ok = $valor WHERE id = substring('$idtrans' from 4 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($nf == '1' and $valor == '0') {
                $this->query = "UPDATE CR_DIRECTO SET  fecha_edo_cta_ok = $valor WHERE id = substring('$idtrans' from 4 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            } else {
                $this->query = "UPDATE CR_DIRECTO SET REGISTRO = 1 WHERE id = substring('$idtrans' from 4 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            }
        } elseif (substr($idtrans, 0, 3) == 'SOL') {
            $this->query = "INSERT INTO REG_EDOCTA (TRANSACCION, MONTO, SIGNO, FECHAELAB, FECHA_TRANSACCION, FECHA_APLICACION, USUARIO)
    					VALUES ('$idtrans', $cargo, -1, current_timestamp, current_date, current_date, '$nombre')";
            $rs = $this->EjecutaQuerySimple();
            if ($nf == '1' and $valor == '1') {
                $this->query = "UPDATE SOLICITUD_PAGO SET fecha_edo_cta = '$nvaFechComp', fecha_edo_cta_ok = $valor WHERE id = substring('$idtrans' from 4 for 6) ";

                $rs = $this->EjecutaQuerySimple();
            } elseif ($nf == '1' and $valor == '0') {
                $this->query = "UPDATE SOLICITUD_PAGO SET  fecha_edo_cta_ok = $valor WHERE id = substring('$idtrans' from 4 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            } else {
                $this->query = "UPDATE SOLICITUD_PAGO SET registro=1 WHERE id = substring('$idtrans' from 4 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            }
        } elseif (substr($idtrans, 0, 3) == 'GTR') {
            $this->query = "INSERT INTO REG_EDOCTA (TRANSACCION, MONTO, SIGNO, FECHAELAB, FECHA_TRANSACCION, FECHA_APLICACION, USUARIO)
    					VALUES ('$idtrans', $cargo, -1, current_timestamp, current_date, current_date, '$nombre')";
            $rs = $this->EjecutaQuerySimple();
            if ($nf == '1' and $valor == '1') {
                $this->query = "UPDATE GASTOS SET fecha_edo_cta = '$nvaFechComp', fecha_edo_cta_ok = $valor WHERE id = substring('$idtrans' from 4 for 6)";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($nf == '1' and $valor == '0') {
                $this->query = "UPDATE GASTOS SET fecha_edo_cta_ok = $valor WHERE id = substring('$idtrans' from 4 for 6)";
                $rs = $this->EjecutaQuerySimple();
            } else {
                $this->query = "UPDATE GASTOS SET registro=1 WHERE id = substring('$idtrans' from 4 for 6)";
                $rs = $this->EjecutaQuerySimple();
            }
        } elseif (substr($idtrans, 0, 1) == 'D') {
            $this->query = "INSERT INTO REG_EDOCTA (TRANSACCION, MONTO, SIGNO, FECHAELAB, FECHA_TRANSACCION, FECHA_APLICACION, USUARIO)
    					VALUES ('$idtrans', $cargo, -1, current_timestamp, current_date, current_date, '$nombre')";
            $rs = $this->EjecutaQuerySimple();
            if ($nf == '1' and $valor == '1') {
                $this->query = "UPDATE DEUDORES SET fechaedo_cta = '$nvaFechComp', fecha_edo_cta_ok = $valor WHERE iddeudor = substring('$idtrans' from 2 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            } elseif ($nf == '1' and $valor == '0') {
                $this->query = "UPDATE DEUDORES SET  fecha_edo_cta_ok = $valor WHERE iddeudor = substring('$idtrans' from 2 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            } else {
                $this->query = "UPDATE DEUDORES SET registro=1 WHERE iddeudor = substring('$idtrans' from 2 for 6) ";
                $rs = $this->EjecutaQuerySimple();
            }
        }

        return $rs;
    }

    function NewSolicitudPago($cuentaBancaria, $medio, $importe, $misFolios) {
        $usuario = $_SESSION['user']->NOMBRE;
        $folios = implode(",", $misFolios);
        $this->query = "SELECT CVE_CLPV as CLAVE
    				   FROM COMPR01
    					WHERE TRIM(CVE_DOC) IN (SELECT TRIM(IDENTIFICADOR) FROM OC_CREDITO_CONTRARECIBO WHERE FOLIO IN ($folios)) group by cve_clpv";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        $prov = count($data);
        if (count($data) > 1) {
            $rs = 2;
            return $rs;
        } else {
            $cveclpv = $data[0]->CLAVE;
            $this->query = "INSERT INTO SOLICITUD_PAGO(MONTO, FECHA, USUARIO, TIPO, FECHAELAB, BANCO, PROVEEDOR)
    				  VALUES ($importe, current_date, '$usuario', '$medio', current_timestamp, '$cuentaBancaria', '$cveclpv')";
            $rs = $this->EjecutaQuerySimple();

            $this->query = "SELECT MAX(IDSOL) as id FROM SOLICITUD_PAGO";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $folio = $row->ID;
            return $folio;
        }
    }

    function asignaFolioDocumento($folio, $creaSP) {
        $this->query = "SELECT IDENTIFICADOR as DOCU FROM OC_CREDITO_CONTRARECIBO WHERE folio = $folio";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $docu = $row->DOCU;

        $this->query = "UPDATE compr01 set id_solicitud = $creaSP, STATUS_PAGO = 'PP' where trim(cve_doc) = trim('$docu')";
        $rs = $this->EjecutaQuerySimple();
        //echo $this->query;
        return $rs;
    }

    function FolioValidaRecepcion($docr, $doco) {
        $usuario = $_SESSION['user']->NOMBRE;
        $this->query = "SELECT IMPORTE FROM COMPO01 WHERE Trim(CVE_DOC) = trim('$doco')";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $importeoc = $row->IMPORTE;

        if (substr(trim($docr), 0, 1) == 'F') {
            $importer = 0;
        } else {
            $this->query = "SELECT IMPORTE FROM COMPR01 WHERE TRIM(CVE_DOC) = TRIM('$docr')";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $importer = $row->IMPORTE;
        }
        $this->query = "INSERT INTO VALIDACIONES (OC, RECEPCION, IMPORTE_OC, IMPORTE_RECEP, FECHA_VALIDACION, USUARIO)
    					VALUES ('$doco', '$docr', $importeoc, $importer, current_timestamp, '$usuario')";
        $rs = $this->EjecutaQuerySimple();
        $this->query = "SELECT MAX(IDVAL) AS FOLIO FROM VALIDACIONES";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $folio = $row->FOLIO;

        return $folio;
    }

    function verValidaciones() {
        $this->query = "SELECT v.*, p.nombre as proveedor, r.importe as importe_val,'NA' as RESULTADO
    				FROM VALIDACIONES v
    				left join compr01 r on r.cve_doc = v.recepcion
    				left join prov01 p on p.clave = r.cve_clpv
    				WHERE IMPRESO = 0";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function datosValidacion($idval) {
        $this->query = "SELECT v.*, p.nombre as Proveedor, 'NA' AS RESULTADO
    					FROM VALIDACIONES v
    					left join compo01 oc on oc.cve_doc = v.oc
    					left join prov01 p on oc.cve_clpv = p.clave
    					WHERE IDVAL = $idval";
        //			echo $this->query;
        $rs = $this->QueryObtieneDatosN();


        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function ValidacionPartidad($idval) {
        $this->query = "SELECT v.idval as FOLIO_VALIDACION, poc.*, oc.DOC_SIG,  i.descr, i.UNI_ALT
    					FROM PAR_COMPO01 poc
    					left join validaciones v on v.oc = poc.cve_doc
    					left join compo01 oc on oc.cve_doc = poc.cve_doc
    					LEFT JOIN PROV01 p ON p.clave = oc.cve_clpv
    					left join compr01 r on oc.doc_sig = r.cve_doc
    					left join inve01 i on poc.cve_art = i.cve_art
    					where v.idval = $idval";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function verSolicitudes() {
        $this->query = "SELECT s.*, p.nombre as NOM_PROV FROM
    				SOLICITUD_PAGO s
    				left join prov01 p on s.proveedor = p.clave
    				WHERE IMPRESO = 0 ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function datosSolicitud($idsol) {
        $this->query = "SELECT s.*, p.nombre as NOM_PROV FROM
    				SOLICITUD_PAGO s
    				left join prov01 p on s.proveedor = p.clave
    				WHERE IDSOL = $idsol ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function crSolicitud($idsol) {
        $this->query = "SELECT r.*, iif(r.monto_real = 0, r.importe, r.monto_real) as importe_REAL , p.NOMBRE AS NOM_PROV, o.cve_doc as CVE_DOC_OC, o.importe as IMPORTE_OC, o.fechaelab as FECHAELAB_OC
    					FROM COMPR01 r
    					left join prov01 p on r.cve_clpv = p.clave
    					LEFT JOIN COMPO01 o on o.cve_doc = r.doc_ant
    				    WHERE id_solicitud = $idsol";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function ctrlImpresiones($idsol) {
        $this->query = "UPDATE SOLICITUD_PAGO SET IMPRESO = IMPRESO + 1 WHERE IDSOL = $idsol";
        $rs = $this->EjecutaQuerySimple();
        $this->query = "SELECT IMPRESO FROM SOLICITUD_PAGO WHERE IDSOL = $idsol";
        $res = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($res);
        $num_impresiones = $row->IMPRESO;
        return $num_impresiones;
    }

    function Solicitudes($doc) {
        $this->query = "SELECT r.*, p.nombre, datediff(day, fechaelab, current_date) as DIAS, current_date as HOY
    					from COMPR01 r
    					left join prov01 p on r.cve_clpv = p.clave
    					where id_solicitud = $doc";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verSol($doc) {
        $this->query = "SELECT s.*, p.nombre
    					from SOLICITUD_PAGO s
    					left join prov01 p on p.clave = s.proveedor
    					where s.idsol = $doc";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verPagoSolicitudes() {
        $this->query = "SELECT s.*, p.nombre as nom_prov
    				FROM SOLICITUD_PAGO s
    				left join prov01 p on p.clave = s.proveedor
    				WHERE s.STATUS = 'Pagado'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verCompras($docr) {

        if ($docr) {
            $this->query = "SELECT c.* , p.nombre
    				  from compr01 c
    				  left join prov01 p on c.cve_clpv = p.clave
    				  where cve_doc = '$docr'";
        } else {
            $this->query = "SELECT c.* , p.nombre
    				  from compr01 c
    				  left join prov01 p on c.cve_clpv = p.clave
    				  where costeo is null
    				  and finalizado is null
    				  and c.status <> 'C'";
        }
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        
        return @$data;
    }

    function verPartidasCompras($docr) {
        $this->query = "SELECT par.* , i.descr, lt.pedimento as ped
    					from par_compr01 par
    					left join inve01 i on i.cve_art = par.cve_art
                        left join enlace_ltpd01 eltpd  on eltpd.E_LTPD = par.E_LTPD
                        left join ltpd01 lt on lt.reg_ltpd = eltpd.reg_ltpd
    					 where cve_doc = '$docr'";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function recConta($folio) {
        $usuario = $_SESSION['user']->NOMBRE;

        if (substr($folio, 0, 2) == 'ch') {
            $tabla = 'P_CHEQUES';
            $campo = 'CHEQUE';
        } elseif (substr($folio, 0, 2) == 'tr') {
            $tabla = 'P_TRANS';
            $campo = 'TRANS';
        } elseif (substr($folio, 0, 1) == 'e') {
            $tabla = 'P_EFECTIVO';
            $campo = 'EFECTIVO';
        } elseif (substr($folio, 0, 3) == 'CR-') {
            $tabla = 'SOLICITUD_PAGO';
            $campo = 'IDSOL';
            $vttf = substr($folio, 3, 2);
            $vf = substr($folio, 6, 6);
            $this->query = "UPDATE $tabla set status = 'Recibido', fecha_rec_conta = current_timestamp, usuario_recibe = '$usuario' where upper(TP_TES_FINAL) = '$vttf' and folio = $vf";
            $rs = $this->EjecutaQuerySimple();
            return $rs;
        }

        $this->query = "UPDATE $tabla SET STATUS_CONTABILIDAD = 1, fecha_rec_conta = current_timestamp, usuario_recibe = '$usuario' where $campo = '$folio'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function verComprasRecibidas() {
        $this->query = "SELECT BENEFICIARIO, MONTO, DOCUMENTO, STATUS, FECHA_REC_CONTA, BANCO, USUARIO_RECIBE, CHEQUE AS FOLIO FROM p_cheques p where fecha >= '01.01.2017' and STATUS_CONTABILIDAD = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT BENEFICIARIO, MONTO, DOCUMENTO, STATUS, FECHA_REC_CONTA, BANCO, USUARIO_RECIBE, TRANS AS FOLIO FROM p_TRANS p where fecha >='01.11.2016' and STATUS_CONTABILIDAD = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT BENEFICIARIO, MONTO, DOCUMENTO, STATUS, FECHA_REC_CONTA, BANCO, USUARIO_RECIBE, EFECTIVO AS FOLIO FROM P_EFECTIVO p where fecha >='01.11.2016' and STATUS_CONTABILIDAD = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT p.nombre as BENEFICIARIO, s.MONTO_FINAL AS MONTO, ('SOL'||'-'||s.IDSOL) AS DOCUMENTO, s.STATUS, s.FECHA_REC_CONTA, s.BANCO_FINAL, s.USUARIO_RECIBE, ('CR'||'-'||UPPER(s.TP_TES_FINAL)||'-'||s.FOLIO) AS FOLIO, s.BANCO
    				 FROM SOLICITUD_PAGO s
    				 left join prov01 p on p.clave = s.proveedor
    				 WHERE s.STATUS = 'Recibido'";
        $rs = $this->QueryObtieneDatosN();
        //echo $this->query;
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function regCompraEdoCta($folio, $doc, $fecha) {
        $usuario = $_SESSION['user']->NOMBRE;

        if (substr($doc, 0, 3) == 'SOL') {
            $this->query = "UPDATE SOLICITUD_PAGO SET FECHA_EDO_CTA = '$fecha', usuario_edo_cta = '$usuario', status = 'Registrado'
    				where idsol = (substring('$doc' from 5 for 6))";
            $rs = $this->EjecutaQuerySimple();
            return $rs;
        } elseif (substr($folio, 0, 2) == 'ch') {
            $tabla = 'P_CHEQUES';
            $campo = 'CHEQUE';
        } elseif (substr($folio, 0, 2) == 'tr') {
            $tabla = 'P_TRANS';
            $campo = 'TRANS';
        } elseif (substr($folio, 0, 1) == 'e') {
            $tabla = 'P_EFECTIVO';
            $campo = 'EFECTIVO';
        }

        $this->query = "UPDATE $tabla set FECHA_EDO_CTA = '$fecha', usuario_edo_cta = '$usuario', status_contabilidad = 2 where $campo = '$folio'";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "SELECT BANCO FROM $tabla where $campo = '$folio '";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $banco = $row->BANCO;

        if (empty($banco)) {
            $banco = 'Sin Banco';
        }


        $this->query = "UPDATE COMPO01 SET EDOCTA_FECHA= '$fecha', edocta_reg = current_timestamp, usuario_recibe='$usuario', fecha_edo_cta_ok = '1' where cve_doc ='$doc' ";
        $rs = $this->EjecutaQuerySimple();



        return $rs;
    }

    function buscarPagos($campo) {

        $this->query = "SELECT ID , 'NA' AS DOCUMENTO, BANCO, monto, saldo, FOLIO_X_BANCO, USUARIO, FOLIO_ACREEDOR, fecha_recep from CARGA_PAGOS WHERE (MONTO CONTAINING('$campo') OR upper(FOLIO_X_BANCO) CONTAINING (upper('$campo'))) AND STATUS = ''";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        /* if(!empty($data)){
          $this->query="SELECT cp.ID, DOCUMENTO, cp.BANCO, cp.FOLIO_X_BANCO, cp.folio_acreedor, cp.monto, cp.saldo , a.USUARIO
          FROM APLICACIONES a
          left join CARGA_PAGOS cp on cp.id = a.idpago
          WHERE UPPER(DOCUMENTO) = UPPER('$campo') AND CANCELADO = 0";
          $rs=$this->QueryObtieneDatosN();
          while($tsArray=ibase_fetch_object($rs)){
          $data[] = $tsArray;
          }
          } */
        return @$data;
    }

    function cancelarPago($idp) {
        $this->query = "UPDATE CARGA_PAGOS SET STATUS = 'C' WHERE ID = $idp";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function enviarConta($creaSP) {

        $this->query = "UPDATE SOLICITUD_PAGO set MONTO_FINAL = MONTO, BANCO_FINAL = BANCO, fecha_reg_pago_final = FECHA, FECHA_PAGO = FECHAELAB, STATUS = 'Pagado', usuario_pago=(USUARIO||'- Directo'), TP_TES_FINAL =tipo WHERE IDSOL = $creaSP";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "SELECT TP_TES_FINAL AS TIPO FROM SOLICITUD_PAGO WHERE IDSOL = $creaSP";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $tipop = $row->TIPO;

        $this->query = "SELECT MAX(FOLIO) as FOLIO FROM SOLICITUD_PAGO WHERE TP_TES_FINAL ='$tipop'";
        $res = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($res);
        $folioNuevo = $row->FOLIO + 1;
        $this->query = "UPDATE SOLICITUD_PAGO SET FOLIO = $folioNuevo where idsol = $creaSP";
        $result = $this->EjecutaQuerySimple();

        echo 'El pago se ha enviado a contabilidad, no se encontrara en la Area de Pagos...';
        return $rs;
    }

    function totalMensual($mes, $banco, $cuenta, $anio) {
        $this->query = "SELECT SUM(MONTO) AS MONTO FROM CARGA_PAGOS
    				WHERE EXTRACT(MONTH FROM FECHA_RECEP) = $mes and Banco = (trim('$banco')||' - '||trim('$cuenta')) and status <> 'C'
    				and extract(year from fecha_recep) = $anio";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->MONTO;
        return @$data;
    }

    function ventasMensual($mes, $banco, $cuenta, $anio) {
        $this->query = "SELECT SUM(MONTO) AS MONTO FROM CARGA_PAGOS WHERE EXTRACT(MONTH FROM FECHA_RECEP) = $mes and extract(year from fecha_recep) = $anio and Banco = (trim('$banco')||' - '||trim('$cuenta')) and status <> 'C' and tipo_pago is null";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->MONTO;
        return @$data;
    }

    function transfer($mes, $banco, $cuenta, $anio) {
        $this->query = "SELECT iif(SUM(MONTO) is null, 0, SUM(MONTO)) AS MONTO FROM CARGA_PAGOS WHERE extract(year from fecha_recep)=$anio and EXTRACT(MONTH FROM FECHA_RECEP) = $mes and Banco =(trim('$banco')||' - '||trim('$cuenta')) and status <> 'C' and tipo_pago = 'oTEC'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->MONTO;
        return @$data;
    }

    function devCompra($mes, $banco, $cuenta, $anio) {
        $this->query = "SELECT iif(SUM(MONTO) is null, 0, SUM(MONTO)) AS MONTO FROM CARGA_PAGOS
    	WHERE extract(year from fecha_recep)=$anio and  EXTRACT(MONTH FROM FECHA_RECEP) = $mes and Banco =(trim('$banco')||' - '||trim('$cuenta')) and status <> 'C' and tipo_pago = 'DC'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->MONTO;
        return @$data;
    }

    function devGasto($mes, $banco, $cuenta, $anio) {
        $this->query = "SELECT iif(SUM(MONTO) is null, 0, SUM(MONTO)) AS MONTO FROM CARGA_PAGOS
    	WHERE extract(year from fecha_recep)=$anio and EXTRACT(MONTH FROM FECHA_RECEP) = $mes and Banco =(trim('$banco')||' - '||trim('$cuenta')) and status <> 'C' and tipo_pago = 'DG'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->MONTO;
        return @$data;
    }

    function pcc($mes, $banco, $cuenta, $anio) {
        $this->query = "SELECT iif(SUM(MONTO) is null, 0, SUM(MONTO)) AS MONTO FROM CARGA_PAGOS WHERE extract(year from fecha_recep) = $anio and  EXTRACT(MONTH FROM FECHA_RECEP) = $mes and Banco =(trim('$banco')||' - '||trim('$cuenta')) and status <> 'C' and tipo_pago = 'oPCC'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $data = $row->MONTO;
        return @$data;
    }

    function pagosAplicados($mes, $banco, $anio, $cuenta) {

        $this->query = "SELECT sum(monto) as Total, sum(saldo) as Faltante
    				from carga_pagos
    				WHERE extract(month from fecha_recep) = $mes
    				and extract(year from fecha_recep) = $anio
    				and banco  = '$banco'||' - '||'$cuenta'
    				and status <>'C'
    				and (tipo_pago is null or tipo_pago = '')
    				";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function pagosAcreedores($mes, $banco, $anio, $cuenta) {
        $this->query = "SELECT sum(a.monto) as ACREEDORES, sum(cp.saldo) as Faltante
    				from acreedores a
    				left join carga_pagos cp on cp.id = a.id_pago
    				WHERE extract(month from cp.fecha_recep) = $mes
    				and extract(year from cp.fecha_recep) = $anio
    				and cp.banco  = '$banco'||' - '||'$cuenta'
    				and cp.status <>'C'
    				and (cp.tipo_pago is null or tipo_pago = '')
    				and a.status <> 99
    				";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function infoPagos($idp) {
        $this->query = "SELECT * FROM CARGA_PAGOS WHERE ID = $idp";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function pagoFacturas($idp) {
        $this->query = "SELECT a.*, c.nombre AS CLIENTE, c.clave, f.importe
    					FROM APLICACIONES a
    					left join factf01 f on a.documento = f.cve_doc
    					left join clie01 c on c.clave = f.cve_clpv
    					WHERE IDPAGO = $idp
    					and cancelado = 0
    					order by SALDO_PAGO DESC ";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function montoAplicado($idp) {
        $this->query = "SELECT SUM(MONTO_APLICADO) as MONTO FROM APLICACIONES WHERE IDPAGO = $idp and cancelado = 0";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $totalAplicado = $row->MONTO;

        return $totalAplicado;
    }

    function totalCompras($mes, $banco, $anio, $cuenta) {
        $this->query = "SELECT iif(SUM(iif(monto_final = 0, importe, monto_final)) is null, 0, SUM(iif(monto_final = 0, importe, monto_final))) as totCompras from compo01 where fecha_edo_cta_ok = '1' and extract(month from edocta_fecha) = $mes and extract(year from edocta_fecha) = $anio";
        $rs = $this->QueryObtieneDatosN();
        //echo $this->query;
        $row = ibase_fetch_object($rs);
        $totc = $row->TOTCOMPRAS;
        //var_dump($data);
        return $totc;
    }

    function totalGasto($mes, $banco, $anio, $cuenta) {
        $this->query = "SELECT SUM(pg.monto) AS TOTGASTO
    					FROM GASTOS g
    					left join PAGO_GASTO pg on pg.idgasto = g.id
    					where extract(month from g.fecha_EDO_CTA)=$mes
    					and extract(year from g.fecha_edo_cta) = $anio
    					and fecha_edo_cta_ok = '1'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $totg = $row->TOTGASTO;


        $this->query = "SELECT iif(SUM(IMPORTE) IS NULL, 0 , SUM(IMPORTE)) as GastoDirecto
    				  FROM CR_DIRECTO
    				  WHERE extract(month from fecha_edo_cta) = $mes
    				  and extract(year from fecha_edo_cta) = $anio
    				  and banco = '$banco'
    				  and fecha_edo_cta_ok='1'";
        $rs = $this->QueryObtieneDatosN();
        //echo $this->query;
        $row = ibase_fetch_object($rs);
        $totgd = $row->GASTODIRECTO;

        $totg = $totg + $totgd;

        return $totg;
    }

    function totalDeudores($mes, $banco, $anio, $cuenra) {
        $this->query = "SELECT iif(SUM(importe)is null, 0, Sum(importe)) as TOTALDEUDORES FROM DEUDORES WHERE extract(month from FECHAEDO_CTA)= $mes and extract(year from fechaedo_cta)= $anio and fecha_edo_cta_ok ='1'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $totg = $row->TOTALDEUDORES;

        return $totg;
    }

    function totalCredito($mes, $banco, $anio, $cuenta) {
        $this->query = "SELECT iif(sum(monto_final) is null,0, sum(monto_final)) as totalcredito from SOLICITUD_PAGO
    				where extract(month from fecha_edo_cta) = $mes
    				and extract(year from fecha_edo_cta) = $anio
    				and fecha_edo_cta_ok = '1'
    				and banco_final = ('$banco'||' - '||'$cuenta')";
        $rs = $this->QueryObtieneDatosN();

        $row = ibase_fetch_object($rs);
        $totCr = $row->TOTALCREDITO;

        return $totCr;
    }

    function buscarContrarecibos($campo) {
        $this->query = "SELECT cr.*, r.fechaelab, p.nombre , r.monto_real
    				FROM OC_CREDITO_CONTRARECIBO cr
    				left join compr01 r on r.cve_doc = cr.identificador
    				left join prov01 p on r.cve_clpv = p.clave
    				where trim(identificador) containing (trim($campo))";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function obtenerFolio($identificador) {
        $this->query = "SELECT folio AS FOLIO FROM OC_CREDITO_CONTRARECIBO WHERE IDENTIFICADOR ='$identificador'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $folio = $row->FOLIO;
        return $folio;
    }

    function traeGasto() {
        $this->query = "SELECT ID AS IDG, CONCEPTO AS NOMBRE FROM CAT_GASTOS WHERE ACTIVO = 'S'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function procesoAplicaciones() {
        $this->query = "SELECT ID AS IDA, IDPAGO AS IDP, documento as Documento, monto_aplicado as monto FROM APLICACIONES where cancelado = 0 and id >= 1 and id < 5000";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        //var_dump($data);
        //break;
        $i = 0;
        foreach ($data as $key) {
            $i = $i + 1;
            $ida = $key->IDA;
            $idp = $key->IDP;
            $documento = $key->DOCUMENTO;
            $monto = $key->MONTO;
            $this->query = "UPDATE FACTF01 set Aplicado = (Aplicado + $monto), saldoFinal = Importe - (Aplicado + $monto) where trim(cve_doc) = trim('$documento')";
            $rs = $this->EjecutaQuerySimple();
            //echo 'Actualiza Factura'.$this->query;
            $this->query = "UPDATE APLICACIONES SET PROCESADO = 1 WHERE ID = $ida";
            $rs = $this->EjecutaQuerySimple();

            //echo 'Actualiza Aplicaciones'.$this->query;
            //echo $i;
        }
        echo 'Proceso Terminado, se procesaron : ' . $i . ' registros.';    }
#################### TOTALES DE FACTURACION
    // COLOCAR PAGOS //

    function dirVerFacturas($mes, $vend, $anio) {
        if ($anio == 99) {
            $this->query = "SELECT f.CVE_DOC, f.CVE_CLPV, f.importe, f.fechaelab, f.can_tot, f.imp_tot4, f.saldo, f.doc_sig, f.cve_vend, d.importe, f.importe - d.importe as SALDONC, d.importe as importenc, d.fechaelab as fechaelabnc, c.nombre, f.aplicado , f.saldofinal, f.status, f.id_pagos, f.id_aplicaciones
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					deuda2015 = 1
	    					UNION
	    					SELECT 'TOTAL' as CVE_DOC, '' as CVE_CLPV, sum(f.importe), '' as fechaelab, sum(f.can_tot), sum(f.imp_tot4), sum(f.saldo), '' as doc_sig, '' as cve_vend, sum(d.importe), sum(f.importe - d.importe) as SALDONC, sum(d.importe) as importenc, '' as fechaelabnc, '' as nombre, sum(f.aplicado) as aplicado, sum(saldofinal) as saldofinal, '' as  status, '' as id_pagos, '' as id_aplicaciones
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    				    deuda2015 = 1";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }

            $this->query = "SELECT f.CVE_DOC, f.CVE_CLPV, f.importe, f.fechaelab, f.can_tot, f.imp_tot4, f.saldo, f.doc_sig, f.cve_vend, d.importe, f.importe - d.importe as SALDONC, d.importe as importenc, d.fechaelab as fechaelabnc, c.nombre, f.aplicado , f.saldofinal, f.status, f.id_pagos, f.id_aplicaciones
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					f.serie = 'G'  and deuda2015 = 1
	    					UNION
	    					SELECT 'TOTAL' as CVE_DOC, '' as CVE_CLPV, sum(f.importe), '' as fechaelab, sum(f.can_tot), sum(f.imp_tot4), sum(f.saldo), '' as doc_sig, '' as cve_vend, sum(d.importe), sum(f.importe - d.importe) as SALDONC, sum(d.importe) as importenc, '' as fechaelabnc, '' as nombre, sum(f.aplicado) as aplicado, sum(saldofinal) as saldofinal, '' as status, '' as id_pagos, '' as id_aplicaciones
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					f.serie = 'G'  and deuda2015 = 1";
            //echo $this->query;

            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }

            $this->query = "SELECT f.CVE_DOC, f.CVE_CLPV, f.importe, f.fechaelab, f.can_tot, f.imp_tot4, f.saldo, f.doc_sig, f.cve_vend, d.importe, f.importe - d.importe as SALDONC, d.importe as importenc, d.fechaelab as fechaelabnc, c.nombre, f.aplicado , f.saldofinal, f.status, f.id_pagos, f.id_aplicaciones
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					f.serie = 'E'  and deuda2015 = 1
	    					UNION
	    					SELECT 'TOTAL' as CVE_DOC, '' as CVE_CLPV, sum(f.importe), '' as fechaelab, sum(f.can_tot), sum(f.imp_tot4), sum(f.saldo), '' as doc_sig, '' as cve_vend, sum(d.importe), sum(f.importe - d.importe) as SALDONC, sum(d.importe) as importenc, '' as fechaelabnc, '' as nombre, sum(f.aplicado) as aplicado, sum(saldofinal) as saldofinal, '' as status, '' as id_pagos, '' as id_aplicaciones
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    				    f.serie = 'E'  and deuda2015 = 1";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT f.CVE_DOC, f.CVE_CLPV, f.importe, f.fechaelab, f.can_tot, f.imp_tot4, f.saldo, f.doc_sig, f.cve_vend, d.importe, f.importe - d.importe as SALDONC, d.importe as importenc, d.fechaelab as fechaelabnc, c.nombre, f.aplicado , f.saldofinal, f.id_pagos, f.id_aplicaciones, f.status
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and f.serie = 'FAA'
	    					UNION
	    					SELECT 'TOTAL' as CVE_DOC, '' as CVE_CLPV, sum(f.importe), '' as fechaelab, sum(f.can_tot), sum(f.imp_tot4), sum(f.saldo), '' as doc_sig, '' as cve_vend, sum(d.importe), sum(f.importe - d.importe) as SALDONC, sum(d.importe) as importenc, '' as fechaelabnc, '' as nombre, sum(f.aplicado) as aplicado, sum(saldofinal) as saldofinal , '' as id_pagos, '' as id_aplicaciones, '' as status
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and f.serie = 'FAA'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }

            $this->query = "SELECT f.CVE_DOC, f.CVE_CLPV, f.importe, f.fechaelab, f.can_tot, f.imp_tot4, f.saldo, f.doc_sig, f.cve_vend, d.importe, f.importe - d.importe as SALDONC, d.importe as importenc, d.fechaelab as fechaelabnc, c.nombre, f.aplicado , f.saldofinal, id_pagos , f.id_aplicaciones, f.status
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and  f.serie = 'G'
	    					UNION
	    					SELECT 'TOTAL' as CVE_DOC, '' as CVE_CLPV, sum(f.importe), '' as fechaelab, sum(f.can_tot), sum(f.imp_tot4), sum(f.saldo), '' as doc_sig, '' as cve_vend, sum(d.importe), sum(f.importe - d.importe) as SALDONC, sum(d.importe) as importenc, '' as fechaelabnc, '' as nombre, sum(f.aplicado) as aplicado, sum(saldofinal) as saldofinal , '' as id_pagos, '' as id_aplicaciones, '' as status
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and f.serie = 'G'";
            //echo $this->query;

            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }

            $this->query = "SELECT f.CVE_DOC, f.CVE_CLPV, f.importe, f.fechaelab, f.can_tot, f.imp_tot4, f.saldo, f.doc_sig, f.cve_vend, d.importe, f.importe - d.importe as SALDONC, d.importe as importenc, d.fechaelab as fechaelabnc, c.nombre, f.aplicado , f.saldofinal, id_pagos , id_aplicaciones, f.status
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and f.serie = 'E'
	    					UNION
	    					SELECT 'TOTAL' as CVE_DOC, '' as CVE_CLPV, sum(f.importe), '' as fechaelab, sum(f.can_tot), sum(f.imp_tot4), sum(f.saldo), '' as doc_sig, '' as cve_vend, sum(d.importe), sum(f.importe - d.importe) as SALDONC, sum(d.importe) as importenc, '' as fechaelabnc, '' as nombre, sum(f.aplicado) as aplicado, sum(saldofinal) as saldofinal, '' as id_pagos, '' as id_aplicaciones, '' as status
	    					FROM FACTF01 f
	    					left join factd01 d on d.cve_doc = f.doc_sig and d.status <> 'C'
	    					left join clie01 c on f.cve_clpv = c.clave
	    					WHERE
	    					extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and  f.serie = 'E'";

            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
            //echo $this->query;
        }


        return $data;
    }

    function ventasMes($mes, $vend, $anio) {

        if ($anio == 99) {
            //echo 'entro al 2015';
            $this->query = "SELECT SUM(IMPORTE) as TOTAL, sum(CAN_TOT) AS SUBTOTAL, SUM(IMP_TOT4) AS IVA
    				  FROM FACTF01
    				  WHERE deuda2015 = 1 and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT SUM(IMPORTE) as TOTAL, sum(CAN_TOT) AS SUBTOTAL, SUM(IMP_TOT4) AS IVA
    				  FROM FACTF01
    				  WHERE extract(month from fechaelab) = $mes and extract(year from fechaelab) = $anio and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
            //var_dump($data);
        }
        return $data;
    }

    function saldoFacturas($mes, $vend, $anio) {
        if ($anio == 99) {
            $this->query = "SELECT SUM(SALDOFINAL) as total, (SUM(SALDOFINAL) / 1.16) as subtotal , (sum(SALDOFINAL)*.16) as iva
    				  FROM FACTF01
    				  WHERE deuda2015 =1 and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT SUM(SALDOFINAL) as total, (SUM(SALDOFINAL) / 1.16) as subtotal , (sum(SALDOFINAL)*.16) as iva
    				  FROM FACTF01
    				  WHERE extract(month from fechaelab) = $mes and extract(year from fechaelab) = $anio and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }
        return $data;
    }

    function NotasCreditoMes($mes, $vend, $anio) {

        if ($anio == 99) {
            $this->query = "SELECT iif(SUM(IMPORTE) is null or sum(IMPORTE) = 0,0,0) as total, iif(SUM(CAN_TOT) is null or SUM(CAN_TOT) =0, 0,0) AS SUBTOTAL, iif(SUM(IMP_TOT4) is null or sum(IMP_TOT4) = 0, 0,0) AS IVA
    				  FROM FACTD01
    				  WHERE extract(year from fechaelab) = 2000 and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT SUM(IMPORTE) as total, SUM(CAN_TOT) AS SUBTOTAL, SUM(IMP_TOT4) AS IVA
    				  FROM FACTD01
    				  WHERE extract(month from fechaelab) = $mes and extract(year from fechaelab) = $anio and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }
        //var_dump($data);
        return $data;
    }

    function NCaplicadas($mes, $vend, $anio) {
        if ($anio == 99) {
            $this->query = "SELECT sum(importe_nc) as Importe_NC
    					FROM FACTF01 f
    					WHERE
    				 	deuda2015 = 1 and f.status <>'C' and f.serie = 'FAA'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT sum(importe_nc) as Importe_NC
    					FROM FACTF01 f
    					WHERE
    				 	extract(month from f.fechaelab)= $mes and extract(year from f.fechaelab) = $anio and f.status <>'C' and f.serie = 'FAA'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }

        return $data;
    }

    function facturasPagadasMes($mes, $vend, $anio) {

        if ($anio == 99) {
            $this->query = "SELECT SUM(APLICADO) AS TOTAL, SUM(APLICADO) / 1.16 AS SUBTOTAL, SUM(APLICADO) *.16 AS IVA
    					FROM FACTF01
    					WHERE  deuda2015 = 1 and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT SUM(APLICADO) AS TOTAL, SUM(APLICADO) / 1.16 AS SUBTOTAL, SUM(APLICADO) *.16 AS IVA
    					FROM FACTF01
    					WHERE  extract(month from fechaelab) = $mes and extract(year from fechaelab) = $anio and status <>'C'";
            //echo $this->query;
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }

        return $data;
    }

    function ventaTotalMes($mes, $vend, $anio) {

        if ($anio == 99) {
            $this->query = "SELECT SUM(IMPORTE) as TOTAL, sum(CAN_TOT) AS SUBTOTAL, SUM(IMP_TOT4) AS IVA
    				  FROM FACTF01
    				  WHERE deuda2015 = 1 and status <>'C'";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $ftotal = $row->TOTAL;
            $fsubTotal = $row->SUBTOTAL;
            $fiva = $row->IVA;

            $ventaTotal = $ftotal;
        } else {
            $this->query = "SELECT SUM(IMPORTE) as TOTAL, sum(CAN_TOT) AS SUBTOTAL, SUM(IMP_TOT4) AS IVA
    				  FROM FACTF01
    				  WHERE extract(month from fechaelab) = $mes and extract(year from fechaelab) = $anio and status <>'C'";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $ftotal = $row->TOTAL;
            $fsubTotal = $row->SUBTOTAL;
            $fiva = $row->IVA;

            $this->query = "SELECT SUM(IMPORTE) as total, SUM(CAN_TOT) AS SUBTOTAL, SUM(IMP_TOT4) AS IVA
    				  FROM FACTD01
    				  WHERE extract(month from fechaelab) = $mes and extract(year from fechaelab) = $anio and status <>'C'";
            $rs = $this->QueryObtieneDatosN();
            $row = ibase_fetch_object($rs);
            $nctotal = $row->TOTAL;
            $ncsubtotal = $row->SUBTOTAL;
            $nciva = $row->IVA;

            $ventaTotal = $ftotal - $nctotal;
        }

        //echo '---'.number_format($ftotal,2);
        //echo '---'.number_format($nctotal,2);
        //echo '---'.number_format($ventaTotal,2);
        //break;
        return $ventaTotal;
    }

    function serieFAA($mes, $vend, $anio) {

        if ($anio == 99) {
            $this->query = "SELECT sum(CAN_TOT) AS SUBTOTAL, sum(IMP_TOT4) AS  iva , sum(IMPORTE) AS TOTAL FROM FACTF01 WHERE deuda2015 = 1 and status <>'C' and serie = 'A'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT sum(CAN_TOT) AS SUBTOTAL, sum(IMP_TOT4) AS  iva , sum(IMPORTE) AS TOTAL FROM FACTF01 WHERE EXTRACT(MONTH FROM FECHAELAB) = $mes  and extract(year from fechaelab) = $anio and status <>'C' and serie = 'FAA'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }

        return @$data;
    }

    function serieG($mes, $vend, $anio) {

        if ($anio == 99) {
            $this->query = "SELECT sum(CAN_TOT) AS SUBTOTAL, sum(IMP_TOT4) AS  iva , sum(IMPORTE) AS TOTAL FROM FACTF01 WHERE deuda2015=1 and status <>'C' and serie = 'G'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT sum(CAN_TOT) AS SUBTOTAL, sum(IMP_TOT4) AS  iva , sum(IMPORTE) AS TOTAL FROM FACTF01 WHERE EXTRACT(MONTH FROM FECHAELAB) = $mes  and extract(year from fechaelab) = $anio and status <>'C' and serie = 'G'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }

        return @$data;
    }

    function serieE($mes, $vend, $anio) {

        if ($anio == 99) {
            $this->query = "SELECT sum(CAN_TOT) AS SUBTOTAL, sum(IMP_TOT4) AS  iva , sum(IMPORTE) AS TOTAL FROM FACTF01 WHERE deuda2015 = 1 and status <>'C' and serie = 'E'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        } else {
            $this->query = "SELECT sum(CAN_TOT) AS SUBTOTAL, sum(IMP_TOT4) AS  iva , sum(IMPORTE) AS TOTAL FROM FACTF01 WHERE EXTRACT(MONTH FROM FECHAELAB) = $mes  and extract(year from fechaelab) = $anio and status <>'C' and serie = 'E'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
                $data[] = $tsArray;
            }
        }

        return @$data;
    }

    function traeVendedores() {
        $this->query = "SELECT * FROM vend01";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function traeOC($campo) {
        $this->query = "SELECT oc.*, p.nombre
    					FROM COMPO01 oc
    					left join prov01 p on oc.cve_clpv = p.clave
    					WHERE upper(oc.CVE_DOC) CONTAINING upper('$campo')";
        //echo $this->query ;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return $data;
    }

    function procesarOC($doco, $idb, $fechaedo, $montof, $factura, $tpf) {

        $usuario = $_SESSION['user']->NOMBRE;

        $this->query = "UPDATE COMPO01 SET
    		BANCO = '$idb',
    		edocta_fecha = '$fechaedo',
    		edocta_reg = current_timestamp,
    		usuario_recibe = '$usuario',
    		edocta_status = 'I',
    		monto_final = $montof,
    		factura_proveedor = '$factura'
    		where CVE_DOC = '$doco'";

        echo $this->query;
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function deudores() {
        $this->query = "SELECT D.*, iif(P.NOMBRE is null, 'NO IDENTIFICDO', P.NOMBRE) AS NOMBRE
    						FROM DEUDORES D
    						LEFT JOIN PROV01 P ON P.CLAVE = D.PROVEEDOR
    						WHERE APLICADO = 0";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verProveedores() {
        $this->query = "SELECT * FROM PROV01";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function guardaDeudor($fechaedo, $monto, $proveedor, $banco, $tpf, $referencia, $destino) {

        $usuario = $_SESSION['user']->NOMBRE;
        $this->query = "INSERT INTO DEUDORES (PROVEEDOR, FECHAEDO_CTA, FECHAELAB, IMPORTE, BANCO, TIPO, REFERENCIA, CUENTA_DESTINO, USUARIO)
    				VALUES ('$proveedor', '$fechaedo', current_timestamp, $monto, '$banco', '$tpf', '$referencia', '$destino','$usuario')";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function transferyprestamo($fechaedo, $bancoO) {
        $this->query = "SELECT * FROM DEUDORES WHERE tipo_deudor = 'Transferencia' or tipo_deudor = 'Prestamo'";
        $rs = $this->QueryObtieneDatosN();
        //echo $this->query;
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function guardaTransPago($fechaedo, $monto, $bancoO, $bancoD, $tpf, $TT, $referencia) {

        $usuario = $_SESSION['user']->NOMBRE;
        $this->query = "INSERT INTO DEUDORES (FECHAEDO_CTA, FECHAELAB, IMPORTE, BANCO, TIPO, REFERENCIA, CUENTA_DESTINO, USUARIO, TIPO_DEUDOR)
    					VALUES('$fechaedo', current_timestamp, $monto, '$bancoO', '$tpf', '$referencia', '$bancoD', '$usuario', '$TT')";
        //echo $this->query;
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function facturapagomaestro($maestro) {
        $this->query = "SELECT f.cve_doc, cl.nombre, f.fechaelab, f.importe, f.aplicado, f.saldofinal as saldo, f.id_pagos, f.id_aplicaciones, f.importe_nc, f.nc_aplicadas , f.FECHA_VENCIMIENTO, datediff(day, f.FECHA_VENCIMIENTO, current_date) as dias
    			from factf01 f
    			left join clie01 cl on cl.clave = f.cve_clpv
    			where f.cve_maestro = '$maestro'
    			and (deuda2015 <> 99 or deuda2015 is null)
    			and saldofinal > 3
    			order by datediff(day, f.FECHA_VENCIMIENTO, current_date) desc";
        //echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function factura($docf) {
        $this->query = "SELECT f.*, cl.nombre, cl.rfc
    				FROM FACTF01 f
    				left join clie01 cl on f.cve_clpv = cl.clave
    				WHERE CVE_DOC = '$docf'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traePagoMaestro($maestro) {
        $this->query = "SELECT ID, FOLIO_X_BANCO, monto, saldo, fecha_recep, banco, usuario, fecha  FROM CARGA_PAGOS WHERE SALDO > 3 and Status <> 'C' and tipo_pago is null";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function traeMaestros() {
        $this->query = "SELECT * FROM MAESTROS";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function calendarioCxC($cartera) {

        switch (date('w')) {
            case '0':
                $dia = 'D';
                break;
            case '1':
                $dia = 'L';
                break;
            case '2':
                $dia = 'MA';
                break;
            case '3':
                $dia = 'MI';
                break;
            case '4':
                $dia = 'J';
                break;
            case '5':
                $dia = 'V';
                break;
            case '6':
                $dia = 'S';
                break;
            default:
                break;
        }
        $this->query = "SELECT c.id, c.cc, f.cve_doc, f.saldofinal, f.fecha_vencimiento, c.contrarecibo, c.contrarecibo_cr, c.factura, f.fecha_vencimiento, cl.nombre as cliente, f.importe
		        from cajas c
		        left join factf01 f on f.cve_doc = c.factura
		        left join clie01 cl on cl.clave = f.cve_clpv
		        where cc= 'CCA'
		            and c.factura is not null
		            and factura != ''
		            and c.contrarecibo_cr is not null
		            and f.saldofinal > 5
		            and c.dias_pago containing('$dia')";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function totalSemanaCalendar($cartera) {
        $this->query = "SELECT sum(f.saldofinal) as totalsemana
                        from cajas c
                        left join factf01 f on f.cve_doc = c.factura
                        where c.cc = '$cartera'
                        and f.saldofinal > 5
                        and f.fecha_vencimiento >= current_date
                        and (c.docs_cobranza = 'Si')";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $totsemana = $row->TOTALSEMANA;
        return $totsemana;
    }

    function totalesCanlendar($cartera) {
        $this->query = "SELECT sum(f.saldofinal) as total
                        from cajas c
                        left join factf01 f on f.cve_doc = c.factura
                        where c.cc = '$cartera'
                        and f.saldofinal > 5
                        and c.docs_cobranza = 'Si'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $totcartera = $row->TOTAL;

        return $totcartera;
    }

    function saldoIndMaestro($cve_maestro) {
        $this->query = "SELECT * FROM MAESTROS WHERE CLAVE = '$cve_maestro'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function obtieneMaestro($docf) {
        $this->query = "SELECT CVE_MAESTRO FROM FACTF01 WHERE CVE_DOC = '$docf'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $maestro = $row->CVE_MAESTRO;

        return $maestro;
    }

    function saldoVCD() {
        $this->query = "SELECT ca.CC,ca.dias_pago, iif(SUM(f.SALDOFINAL) is null, 0, sum(f.saldofinal)) as total
    				FROM CAJAS ca
    				LEFT JOIN FACTF01 f ON ca.factura = f.cve_doc
    				where f.fecha_vencimiento >= current_date
    				GROUP BY ca.CC, ca.dias_pago";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function saldoCD() {
        $this->query = "SELECT ca.CC,ca.dias_pago, iif(SUM(f.SALDOFINAL) is null, 0, sum(f.saldoFinal)) as total
    				FROM CAJAS ca
    				LEFT JOIN FACTF01 f ON ca.factura = f.cve_doc
    				where f.saldofinal > 3
    				GROUP BY ca.CC, ca.dias_pago";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function verMaestros($cartera) {
        if ($cartera = 99) {
            $this->query = "SELECT * FROM MAESTROS";
            $rs = $this->QueryObtieneDatosN();
        } else {
            $this->query = "SELECT * FROM MAESTROS where cartera in ( '$cartera')";
            $rs = $this->QueryObtieneDatosN();
        }
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function editarMaestro($idm) {
        $this->query = "SELECT * FROM MAESTROS WHERE ID = $idm";
        $rs = $this->QueryObtieneDatosN();
        $data[] = ibase_fetch_object($rs);
        //var_dump($data);
        return $data;
    }

    function editaMaestro($idm, $cr, $cc) {
        if (count($cr)) {
            $cr = implode(",", $cr);
        }
        if (count($cc)) {
            $cc = implode(",", $cc);
        }
        $this->query = "UPDATE MAESTROS SET CARTERA ='$cr', CARTERA_REVISION = '$cc' where id=$idm";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function altaMaestro($nombre, $cc, $cr) {
        if (count($cr)) {
            $cr = implode(",", $cr);
        }
        if (count($cc)) {
            $cc = implode(",", $cc);
        }

        $this->query = "SELECT max(CLAVE) as CLAVE FROM MAESTROS WHERE upper(SUBSTRING(CLAVE FROM 1 FOR 3)) = upper(SUBSTRING('$nombre' from 1 for 3))";
        echo $this->query;
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);

        if (empty($row->CLAVE)) {
            $clave = substr($nombre, 0, 3);
            $clave = $clave . '1';
        } else {
            $consecutivo = substr($row->CLAVE, 3, 5);
            $clave = substr($nombre, 0, 3);
            $consecutivo = $consecutivo + 1;
            $clave = $clave . '-' . $consecutivo;
        }

        $this->query = "INSERT INTO MAESTROS (clave,nombre, cartera, cartera_revision) VALUES (UPPER('$clave'),'$nombre', '$cr', '$cc')";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function calcularCosto($cimpuesto, $cflete, $cseguro, $caduana, $pedimento, $docr) {

        $this->query = "SELECT SUM(CANT) as CANTIDAD FROM PAR_COMPR01 WHERE CVE_DOC = '$docr'";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $factor = $row->CANTIDAD;
        $cimp = $cimpuesto / $factor;
        $cfte = $cflete / $factor;
        $cseg = $cseguro / $factor;
        $cadu = $caduana / $factor;

        $this->query = "UPDATE COMPR01 SET C_IMPUESTO = $cimpuesto, C_ADUANA = $caduana, C_FLETE = $cflete, C_SEGURO = $cseguro, pedimento = '$pedimento' where cve_doc = '$docr'";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PAR_COMPR01
    				SET costo_impuestos = $cimp,
    				 costo_flete = $cfte,
    				 costo_seguro = $cseg,
    				 costo_agente = $cadu,
    				 pedimento = '$pedimento'
    				 where cve_doc  = '$docr'";
        $rs = $this->EjecutaQuerySimple();

        return $rs;
    }

    function costoFOB($cfob, $tc, $docr, $par, $pedimento) {
        /// Manejo de pedimento por si no existe:

        $this->query = "SELECT E_LTPD as Lote FROM par_compr01 WHERE CVE_DOC = '$docr' and NUM_PAR = $par";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $lote = $row->LOTE;

        if ($lote == 0) {

            /// Inserta Pedimento
            $this->query = "SELECT MAX(REG_LTPD) as ltpd FROM LTPD01";
            $result = $this->QueryObtieneDatosN();
            $rowFolio = ibase_fetch_object($result);
            $folion = $rowFolio->LTPD + 1;

            $this->query = "SELECT * FROM PAR_COMPR01 WHERE CVE_DOC = '$docr' and num_par = $par";
            $rs = $this->QueryObtieneDatosN();
            $rowPar = ibase_fetch_object($rs);


            $this->query = "INSERT INTO LTPD01 VALUES ('$rowPar->CVE_ART','','$pedimento', $rowPar->NUM_ALM, null, current_timestamp, current_timestamp, 'LAZARO CARDENAS', $rowPar->CANT, $folion, 0, 'MICHOACAN', NULL, NULL, '510', 'A' , 1  )";
            $RS = $this->EjecutaQuerySimple();
            echo $this->query;

            $this->query = "UPDATE PAR_COMPR01 SET E_LTPD = $folion where cve_doc = '$docr' and num_par = $par";
            $rs = $this->EjecutaQuerySimple();
        } else {

            $this->query = "SELECT PEDIMENTO FROM LTPD01 WHERE REG_LTPD = $lote";
            $rs = $this->QueryObtieneDatosN();
            $rowLtpd = ibase_fetch_object($rs);
            $pedi = $rowLtpd->PEDIMENTO;

            if ($pedi != $pedimento and !empty($pedi)) { /// Actualiza Pedimento
                $this->query = "UPDATE LTPD01 SET PEDIMENTO = '$pedimento' where reg_ltpd = $lote";
                $res = $this->EjecutaQuerySimple();

                $this->query = "UPDATE PAR_COMPR01 SET PEDIMENTO = '$pedimento' where cve_doc = '$docr' and num_par = $par";
                $rs = $this->EjecutaQuerySimple();
            }
        }


        $this->query = "UPDATE PAR_COMPR01
    				  SET COSTO_FOB = $cfob,
    				  tc = $tc,
    				  pedimento = '$pedimento'
    				  where cve_doc = '$docr' and num_par = $par";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PAR_COMPR01 SET COSTOFINAL = (
    				(COSTO_FOB * TC) +
    				COSTO_FLETE +
    				COSTO_SEGURO +
    				COSTO_IMPUESTOS +
    				COSTO_AGENTE
    				) where cve_doc = '$docr' and num_par = $par";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function piezas($docr) {
        $this->query = "SELECT SUM(CANT) AS CANTIDAD FROM PAR_COMPR01 WHERE CVE_DOC ='$docr'";
        $rs = $this->QueryObtieneDatosN();

        $row = ibase_fetch_object($rs);
        $cantidad = $row->CANTIDAD;

        return $cantidad;
    }

    function finalizaCosteo($docr) {
        $this->query = "UPDATE COMPR01 SET FINALIZADO = 1 where cve_doc = '$docr'";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PAR_COMPR01 SET COST = costofinal where cve_doc = '$docr'";
        $rs = $this->EjecutaQuerySimple();
        /// Obetenermos la clave del producto.

        $this->query = "SELECT PEDIMENTO FROM COMPR01 WHERE CVE_DOC = '$docr'";
        $res = $this->QueryObtieneDatosN();

        $row = ibase_fetch_object($res);
        $pedimento = $row->PEDIMENTO;

        $this->query = "SELECT NUM_PAR, CVE_ART, NUM_MOV, cost, cant FROM PAR_COMPR01 WHERE CVE_DOC = '$docr'";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        foreach ($data as $key) {
            $this->query = "UPDATE MINVE01 SET COSTO = $key->COST, COSTO_PROM_INI = $key->COST, COSTO_PROM_FIN = $key->COST where NUM_MOV = $key->NUM_MOV and cve_art = '$key->CVE_ART'";
            $res = $this->EjecutaQuerySimple();

            $this->query = "UPDATE INVE01 set COSTO_PROM = $key->COST, ULT_COSTO = $key->COST where cve_art ='$key->CVE_ART'";
            $result = $this->EjecutaQuerySimple();

            /*$this->query = "UPDATE ltpd01 SET PEDIMENTO = '$pedimento', actualizado = 1 where cve_art = '$key->CVE_ART' and cantidad =$key->CANT and Actualizado is null";
            $rs = $this->EjecutaQuerySimple();*/
        }

        return $rs;
    }

    function verFacturas() {
        $a = "SELECT f.*, cl.nombre, cl.codigo
            	from factf01 f
            	left join clie01 cl on cl.clave = f.cve_clpv
            	where f.enviado is null and f.status!='C' and (seleccion is null or seleccion <> 2)";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }


        $b = "SELECT f.*, cl.nombre, cl.codigo
            	from factr01 f
            	left join clie01 cl on cl.clave = f.cve_clpv
            	where f.enviado is null and f.status!='C' and (f.seleccion is null or seleccion <> 2)";
        $this->query = $b;
        $result = $this->EjecutaQuerySimple();

        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

   function selectFactura($docf, $select) {

        if (substr($docf, 0, 1) == 'F') {
            if ($select == 0) {
                $this->query = "UPDATE FACTF01 SET SELECCION = 1 WHERE CVE_DOC = '$docf'";
            } elseif ($select == 1) {
                $this->query = "UPDATE FACTF01 SET SELECCION = 0 WHERE CVE_DOC = '$docf'";
            }
        } else {
            if ($select == 0) {
                $this->query = "UPDATE FACTR01 SET SELECCION = 1 WHERE CVE_DOC = '$docf'";
            } elseif ($select == 1) {
                $this->query = "UPDATE FACTR01 SET SELECCION = 0 WHERE CVE_DOC = '$docf'";
            }
        }
        $rs = $this->EjecutaQuerySimple();
                if(ibase_affected_rows() == 1){

                         return array("status"=>'ok');
                }else{
                    return array("status"=>'no');
                }
    }

    function datosFacturas($embarque) {
        $this->query = "SELECT p.*,
                        (SELECT calle from infenvio01 where cve_info = f.dat_envio) as calle,
                        (SELECT calle from infenvio01 where cve_info = r.dat_envio) as calle2,
                        (SELECT ccl.CAMPLIB1 FROM factf01 fa inner join infenvio01 ie on f.dat_envio= ie.cve_info
                            inner join clie01 cl on cl.clave = ie.cve_cons 
                            inner join clie_clib01 ccl on cl.clave = ccl.cve_clie  
                            where fa.cve_doc = f.cve_doc ) as Sucursal,
                        (SELECT ccl.CAMPLIB8 FROM factf01 fa inner join infenvio01 ie on f.dat_envio= ie.cve_info
                            inner join clie01 cl on cl.clave = ie.cve_cons 
                            inner join clie_clib01 ccl on cl.clave = ccl.cve_clie  
                            where fa.cve_doc = f.cve_doc ) as NomSucursal
                        FROM par_embarques  p
                        LEFT JOIN FActF01 f on f.cve_doc = p.documento
                        left join factr01 r on r.cve_doc = p.documento
                        WHERE p.EMBARQUE = $embarque";
                        
        $rs = $this->QueryObtieneDatosN();

       /* SELECT * FROM factf01 f
inner join infenvio01 ie on f.dat_envio= ie.cve_info
inner join clie01 cl on cl.clave = ie.cve_cons 
inner join clie_clib01 ccl on cl.clave = ccl.cve_clie  
where f.cve_doc = :cve_doc
*/


        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return $data;
    }

    function datosReporteSalida() {
        $this->query = "SELECT COUNT(CVE_DOC) as facturas FROM FACTF01 WHERE SELECCION = 1";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $facturas = $row->FACTURAS;

        $this->query = "SELECT COUNT(CVE_DOC) as remisiones FROM FACTR01 WHERE SELECCION = 1";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $remisiones = $row->REMISIONES;

        $docs = $facturas + $remisiones;

        if ($docs == 0) {
            return "Debe de seleccionar por lo menos un documento para envio.";
        } else {
            $this->query = "SELECT F.*, CL.NOMBRE FROM FACTF01 F
						LEFT JOIN CLIE01 CL ON CL.CLAVE = F.CVE_CLPV
						 WHERE SELECCION = 1";
            $rs = $this->QueryObtieneDatosN();

            while ($tsarray = ibase_fetch_object($rs)) {
                $data[] = $tsarray;
            }

            $this->query = "SELECT F.*, CL.NOMBRE FROM FACTR01 F
						LEFT JOIN CLIE01 CL ON CL.CLAVE = F.CVE_CLPV
						 WHERE SELECCION = 1";
            $rs = $this->QueryObtieneDatosN();

            while ($tsarray = ibase_fetch_object($rs)) {
                $data[] = $tsarray;
            }

            return $data;
        }
    }

    function registraEmbarque($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha) {
        $this->query = "INSERT INTO EMBARQUES VALUES(null, '1', '$operador', '$observaciones', '$vehiculo', '$placas','$fecha', current_timestamp, '$cajas', 'Transito', 0)";
        $rs = $this->EjecutaQuerySimple();
        $this->query = "SELECT MAX(ID) as folio FROM EMBARQUES";
        $res = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($res);
        $folio = $row->FOLIO;

        $this->query = "UPDATE FACTF01 SET EMBARQUE ='$folio', seleccion = 2 where seleccion = 1";
        $result = $this->EjecutaQuerySimple();

        $this->query = "UPDATE FACTR01 SET EMBARQUE ='$folio', seleccion = 2 where seleccion = 1";
        $result = $this->EjecutaQuerySimple();

        $this->query = "SELECT f.*, cl.nombre FROM FACTF01 f inner join clie01 cl on cl.clave = f.cve_clpv WHERE EMBARQUE = '$folio'";
        $res = $this->QueryObtieneDatosN();
        echo 'Obtiene los nuevos datos:';
        echo $this->query;
        while ($tsarray = ibase_fetch_object($res)) {
            $data[] = $tsarray;
        }
        foreach ($data as $datos) {
            echo 'Crea la Partida:' . '<p>';
            $documento = $datos->CVE_DOC;
            $cliente = $datos->NOMBRE;
            $fecha_elaboracion = $datos->FECHAELAB;
            $importe = $datos->IMPORTE;
            $pedido = $datos->CVE_PEDI;
            $embarque = $datos->EMBARQUE;
            $this->query = "INSERT INTO PAR_EMBARQUES VALUES (NULL, '$documento', '$cliente', '$fecha_elaboracion', $importe, '$pedido', $embarque, null, current_timestamp, null, null, 0)";
            $rs = $this->EjecutaQuerySimple();
        }

        unset($data);
        $this->query = "SELECT f.*, cl.nombre FROM FACTR01 f inner join clie01 cl on cl.clave = f.cve_clpv WHERE EMBARQUE = '$folio'";
        $res = $this->QueryObtieneDatosN();
        echo 'Obtiene los nuevos datos:';
        echo $this->query;
        while ($tsarray = ibase_fetch_object($res)) {
            $data[] = $tsarray;
        }
        foreach ($data as $datos) {
            echo 'Crea la Partida:' . '<p>';
            $documento = $datos->CVE_DOC;
            $cliente = $datos->NOMBRE;
            $fecha_elaboracion = $datos->FECHAELAB;
            $importe = $datos->IMPORTE;
            $pedido = $datos->CVE_PEDI;
            $embarque = $datos->EMBARQUE;
            $this->query = "INSERT INTO PAR_EMBARQUES VALUES (NULL, '$documento', '$cliente', '$fecha_elaboracion', $importe, '$pedido', $embarque, null, current_timestamp, null, null, 0)";
            $rs = $this->EjecutaQuerySimple();
        }

        return $folio;
    }

    function verClientes() {
        $this->query = "SELECT cl.* , clib.camplib2 as DEPTONUMBER, clib.camplib3 as DEPTONAME
					FROM CLIE01 cl
					left join CLIE_CLIB01 clib on clib.cve_clie = cl.clave
					WHERE STATUS ='A'";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        sort($data);

        return $data;
    }

    function traeProducto($cliente, $numdepto) {
        $this->query = "SELECT CVE_ART, DESCR, UNI_MED, LIN_PROD, EXIST, sku
						FROM INVE01 i
						left join codigos_clientes cc on i.cve_art = cc.producto";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        sort($data);

        return $data;
    }

    function asociarSKU($cliente, $numdepto, $cprod, $sku) {
        $this->query = "SELECT ID FROM codigos_clientes WHERE Cliente = '$cliente' and producto = '$cprod'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        if (!empty($data)) {
            $this->query = "UPDATE codigos_clientes set sku = '$sku' where cliente = '$cliente' and producto='$cprod'";
            $rs = $this->EjecutaQuerySimple();
            //echo $this->query;
            //break;
            return $rs;
        } else {
            $this->query = "INSERT INTO codigos_clientes (cliente, producto, sku, depto) values ('$cliente', '$cprod', '$sku', 0)";
            $rs = $this->EjecutaQuerySimple();
            echo $this->query;

            //$this->query="UPDATE PAR_FACTF01 SET SKU = '$sku' where "
            //break;
            return $rs;
        }
    }

    function verReportes() {
        $this->query = "SELECT * FROM EMBARQUES order by id desc";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }
        return @$data;
    }

    function reporteEmbarque($idr) {
        $this->query = "SELECT * from EMBARQUES  where ID = $idr";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return @$data;
    }

    function reporteEmbarqueFacturas($idr) {
        $this->query = "SELECT p.*, iif((select fecha_ent from  factf01 where cve_doc = documento) is null,
    				(select fecha_ent from factr01 where trim(cve_doc) = trim(documento)), (select fecha_ent from  factf01 where cve_doc = documento)) as fechaent
                    FROM PAR_EMBARQUES  p
                    WHERE EMBARQUE = $idr";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return @$data;
    }

    function guardaCaja($idr, $docf, $cajas) {
        $this->query = "UPDATE PAR_EMBARQUES SET CAJAS = $cajas where Embarque = $idr and documento = '$docf'";
        $rs = $this->EjecutaQuerySimple();
        return $response=array('status'=>'ok');
    }

    function cancelaEmbarque($idr) {
        $this->query = "UPDATE EMBARQUES SET ESTATUS = 'Cancelado' where id = $idr";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE FACTF01 SET seleccion = null, embarque = null where embarque = $idr";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE FACTR01 SET SELECCION = NULL, EMBARQUE =  NULL WHERE EMBARQUE =$idr";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PAR_EMBARQUES SET fecha_cancelacion = current_timestamp where embarque = $idr";
        $rs = $this->EjecutaQuerySimple();

        return;
    }

    function cambiarReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha, $idr) {
        $this->query = "UPDATE EMBARQUES SET vehiculo = '$vehiculo', cajas =$cajas, placas='$placas', operador='$operador', observaciones='$observaciones', fecha_reporte='$fecha' where id = $idr ";
        //echo $this->query;
        //break;

        $rs = $this->EjecutaQuerySimple();

        return;
    }

    function reimprimirEmbarque($idr) {
        $this->query = "SELECT * FROM EMBARQUES WHERE ID = $idr";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return $data;
    }

    function validaImpresion($idr) {
        $this->query = "SELECT count(id) as ID FROM EMBARQUES WHERE IMPRESO = 1 and id = $idr";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $valida = $row->ID;
        echo $valida;
        return $valida;
    }

    function verFacturasFecha() {
        $this->query = "SELECT f.*, cl.nombre, cl.diascred from FACTF01 f inner join clie01 cl on cl.clave = f.cve_clpv where (fecha_ok = 0 or fecha_ok is null) and f.status <> 'C'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }
        return @$data;
    }

    function cambiaFecha($docf, $nuevaFecha, $cliente) {
        $this->query = "SELECT DIASCRED FROM CLIE01 WHERE clave = '$cliente'";
        $res = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($res);
        $dias = $row->DIASCRED;

        if (substr($docf, 0, 1) == 'F') {
            $this->query = "UPDATE factf01 set FECHA_ENT = '$nuevaFecha', fecha_ven = dateadd(day, $dias, cast('$nuevaFecha' as date)) where cve_doc = '$docf'";
            $rs = $this->EjecutaQuerySimple();
            
            $this->query="UPDATE CUEN_M01 SET FECHA_VENC = dateadd(day, $dias, cast('$nuevaFecha' as date)) where trim(refer) = trim('$docf')";
            $this->EjecutaQuerySimple();
       
        } else {
            $this->query = "UPDATE factr01 set FECHA_ENT = '$nuevaFecha', fecha_ven = dateadd(day, $dias, cast('$nuevaFecha' as date)) where trim(cve_doc) = trim('$docf')";
            $rs = $this->EjecutaQuerySimple();

             }
        //echo 'Consulta de actualizacion'.$this->query;
        return;
    }

    function cerrarFecha($docf) {
        $this->query = "UPDATE factf01 set fecha_ok = 1 where cve_doc ='$docf'";
        $rs = $this->EjecutaQuerySimple();

        return;
    }

    function verCambiosFechas() {
        $this->query = "SELECT f.*, cl.nombre, cl.diascred
			FROM FACTF01 f inner join clie01 cl on cl.clave = f.cve_clpv
			WHERE f.fecha_ok = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }
        return @$data;
    }


    function GuardaObs($datos) {
        foreach ($datos as $key ) {
            $docf = $key[4];
            $obs=$key[1];
            $idr = $key[3];
            $cajas=$key[0];
            $nvaFecha = $key[2];
        
                $this->query = "UPDATE PAR_EMBARQUES set observacion = '$obs', cajas = $cajas, status = 1
                    where documento = '$docf' and embarque = $idr ";
                    $rs = $this->EjecutaQuerySimple();

                    //echo 'Set observaciones'.$this->query;

                $this->query = "SELECT COUNT(DOCUMENTO) as DOCS FROM PAR_EMBARQUES WHERE embarque =$idr";
                    $res = $this->QueryObtieneDatosN();
                    $row = ibase_fetch_object($res);
                    $par = $row->DOCS;

                $this->query = "SELECT COUNT(DOCUMENTO) AS DOCS2 FROM PAR_EMBARQUES WHERE embarque = $idr and observacion is not null";
                    $res2 = $this->QueryObtieneDatosN();
                    $row2 = ibase_fetch_object($res2);
                    $parok = $row2->DOCS2;

                if ($parok == $par) {
                        $this->query = "UPDATE EMBARQUES SET ESTATUS = 'Cerrado' where id = $idr";
                        $rs = $this->EjecutaQuerySimple();
                } else {
                        $this->query = "UPDATE EMBARQUES SET ESTATUS = 'Proceso' where id = $idr";
                        $rs = $this->EjecutaQuerySimple();
                }

                if (substr($docf, 0, 1) == 'F') {
                        $this->query = "SELECT CVE_CLPV as cliente FROM FACTF01 where cve_doc = '$docf' ";
                        $rs = $this->QueryObtieneDatosN();
                        $row = ibase_fetch_object($rs);
                        $cliente = $row->CLIENTE;
                } else {
                        $this->query = "SELECT CVE_CLPV as cliente FROM FACTR01 where cve_doc = '$docf' ";
                        $rs = $this->QueryObtieneDatosN();
                        $row = ibase_fetch_object($rs);
                        $cliente = $row->CLIENTE;
                }

                $nuevaFecha = $nvaFecha;
                $rs += $this->cambiaFecha($docf, $nuevaFecha, $cliente);    
            
        }
        //$rs += $this->GuardaCuentaBan($docu, $cuentaban);
        return array("status"=>'ok');
    }


    
    function verRecepProcesadas() {
        $this->query = "SELECT c.* , p.nombre
    				  from compr01 c
    				  left join prov01 p on c.cve_clpv = p.clave
    				  where finalizado is not null";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function liberarRecepcion($docr) {
        $this->query = "UPDATE COMPR01 SET FINALIZADO = NULL WHERE CVE_DOC = '$docr'";
        $rs = $this->EjecutaQuerySimple();

        return;
    }

    function verES($fechaini, $fechafin) {
        $this->query = "SELECT mi.CVE_ART,
    			(select i.descr from inve01 i where mi.cve_art = i.cve_art),
				
                (SELECT iif((SUM(IIF(m2.SIGNO = 1, m2.CANT, 0)) - SUM(IIF(m2.SIGNO = -1, m2.CANT, 0 ))) is null, 0,(SUM(IIF(m2.SIGNO = 1, m2.CANT, 0)) - SUM(IIF(m2.SIGNO = -1, m2.CANT, 0 )))) FROM MINVE01 m2 where fecha_docu < '$fechaini' and m2.cve_art = mi.cve_art) as inicial,
					SUM(IIF(mi.SIGNO = 1, mi.CANT, 0)) AS ENTRADAS , SUM(IIF(mi.SIGNO = -1,mi.CANT, 0 )) AS SALIDAS,
					(SUM(IIF(SIGNO = 1, CANT, 0)) - SUM(IIF(SIGNO = -1,CANT, 0 ))) EXISTENCIAS
					FROM MINVE01 mi
					WHERE mi.FECHA_DOCU >= '$fechaini' and mi.fecha_docu <= '$fecafin' and almacen = 1 group BY mi.CVE_ART";
        $rs = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function traeListadoFacturas() {
        $data = array();
        $this->query = "SELECT r.CVE_DOC, r.CVE_CLPV || ' - ' || c.NOMBRE AS PROVEEDOR, r.STATUS, r.IMPORTE
                        FROM FACTF01 r
                        JOIN CLIE01 c ON r.CVE_CLPV=c.CLAVE
                        left join FTC_APERAK a on a.factura = r.cve_doc and a.STATUS = 'ACCEPTED'
                        WHERE r.RFC = 'TSO991022PB6' and fechaelab >= '01.10.2018' and a.factura is null and r.status != 'C' and r.refacturacion is null";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return $data;
    }

    function recalcularPrecio(){
        $this->query="SELECT * FROM FACTF01 WHERE STATUS <> 'C' and fecha_doc >= '01.10.2017' order by cve_doc";
        $rs=$this->EjecutaQuerySimple();

            while($tsarray=ibase_fetch_object($rs)){
                $data[]=$tsarray;   
            }
        foreach ($data as $key ) {
                echo 'Procesa: '.$key->CVE_DOC.'<p>';

                $this->query="SELECT COUNT(NUM_PAR) FROM PAR_FACTF01 WHERE CVE_DOC ='$key->CVE_DOC' and TIPO_PROD ='K'";
                $res=$this->EjecutaQuerySimple();
                $row=ibase_fetch_object($res);
                $valida= $row->COUNT;

                        if($valida  > 0 ){
                            $this->query="SELECT * FROM PAR_FACTF01 WHERE CVE_DOC = '$key->CVE_DOC' and TIPO_PROD= 'K' order by num_par";
                            $rs=$this->EjecutaQuerySimple();

                            while($tsarray=ibase_fetch_object($rs)){
                            $data2[]=$tsarray;
                            }
                            
                            $tipo= '';

                            foreach ($data2 as $key2) {
                                $tipo =$key2->TIPO_PROD;

                                if($tipo == 'K'){

                                    $this->query="SELECT * FROM PAR_FACTF01 WHERE CVE_DOC = '$key2->CVE_DOC' AND num_par = $key2->NUM_PAR + 1";
                                    $res =$this->EjecutaQuerySimple();
                                    $row=ibase_fetch_object($res);

                                    echo $this->query.'<p>';
                                        $this->query="SELECT CANTIDAD FROM KITS01 WHERE CVE_ART = '$key2->CVE_ART' and CVE_PROD='$row->CVE_ART'";
                                        $result=$this->EjecutaQuerySimple();
                                        $row2 = ibase_fetch_object($result);
                                        $factor = $row2->CANTIDAD;
                                    
                                    echo 'Obtener la cantidad: '.$this->query.'<p>';
                                        // ACTUALIZAMOS EL MOVIMIENTO AL INVENTARIO
                                        $precio = $key2->PREC / $factor;
                                        echo 'Precio '.$row->PREC.' factor '.$factor.'<p>';
                                        $this->query="UPDATE MINVE01 SET PRECIO = $precio where num_mov = $row->NUM_MOV";
                                        $resultado =$this->EjecutaQuerySimple();
                                    
                                    echo 'Actualizar el resultado: '.$this->query.'<p>';
                                    }   
                            }
                        }else{
                            echo '  No tiene Kits'.'<p>';
                    }
                }
                
            return;
    }

    function recalcularCosto(){
        $this->query="SELECT * FROM FACTF01 WHERE STATUS <> 'C' and fecha_doc >= '01.10.2017' order by cve_doc";
        $rs=$this->EjecutaQuerySimple();

        while($tsarray = ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }

        foreach ($data as $key) {
            $this->query="SELECT cve_art, num_mov, tipo_elem, tipo_prod  FROM PAR_FACTF01 WHERE CVE_DOC = '$key->CVE_DOC' and tipo_prod = 'P'";
            $rs1=$this->EjecutaQuerySimple();

                while($tsarray2 = ibase_fetch_object($rs1)){
                    $data1[]=$tsarray2;
                }

                foreach ($data1 as $key2) {
                    $nummov=$key2->NUM_MOV;
                    $art = $key2->CVE_ART;

                    if($nummov > 0 ){
                        $this->query="SELECT ult_costo from inve01 where cve_art = '$art'";
                        $rs2=$this->EjecutaQuerySimple();
                        $row=ibase_fetch_object($rs2);
                        $costo = $row->ULT_COSTO;

                        $this->query="UPDATE MINVE01 SET COSTO = $costo WHERE NUM_MOV = $nummov";
                        $rs4 =$this->EjecutaQuerySimple();
                    }
                }
        }
        
    }


  function refacturaciones(){
        $this->query="SELECT * FROM FACTF01 WHERE STATUS = 'C'";
        $rs=$this->EjecutaQuerySimple();
            while($tsarray=ibase_fetch_object($rs)){
                $data[]=$tsarray;
            }
            foreach($data as $key){
            $docf = $key->CVE_DOC;
            $this->query="UPDATE MINVE01 SET CANT = 0 WHERE TRIM(REFER) = TRIM('$docf')";
            $rs=$this->EjecutaQuerySimple();
            }
        //// Cancelacion de movimientos de recepcion canceladas:
        $this->query = "SELECT * FROM COMPR01 WHERE STATUS = 'C'";
        $rs=$this->EjecutaQuerySimple();
            while($tsarray=ibase_fetch_object($rs)){
                $data1[]=$tsarray;
            }

            foreach ($data1 as $key1){
                $docc = $key1->CVE_DOC;
                $this->query="UPDATE MINVE01 SET CANT = 0 WHERE Trim(REFER) = trim('$docc')";
               $rs=$this->EjecutaQuerySimple();
            }


        $this->query = "SELECT CVE_ART FROM INVE01 WHERE TIPO_ELE  = 'P'";
        $res = $this->EjecutaQuerySimple();
        while($tsArray = ibase_fetch_object($res)){
            $inve[]=$tsArray;
        }
                $it = 0;
                foreach ($inve as $i){   
                   $it = $it + 1;
                   $this->query = "UPDATE MINVE01 SET  EXISTENCIA_CALCULADA = 0 WHERE CVE_ART='$i->CVE_ART'";
                   $this->EjecutaQuerySimple();
                   //echo 'Articulo'.$i->CVE_ART;
                   $this->query= "SELECT CVE_ART, NUM_MOV, (CANT * SIGNO) AS CANTIDAD FROM MINVE01 WHERE CVE_ART = '$i->CVE_ART' and almacen = 1 order by extract(year from fecha_docu) asc,  extract(month from fecha_docu) asc,  num_mov asc";
                   $result = $this->EjecutaQuerySimple();
                        while ($inve = ibase_fetch_object($result)) {
                                    $mov[]=$inve;
                            }
                        $nuevaExistencia = 0 ;  
                        if(isset($mov)){
                            foreach ($mov as $mi) {
                                $articulo = $mi->CVE_ART; 
                                $movimiento = $mi->NUM_MOV;
                                $cantidad = $mi->CANTIDAD;
                                $nuevaExistencia= $nuevaExistencia + $cantidad;  /// sumamos a la variable la cantidad.
                               // echo 'Articulo: '.$it.' '.$articulo.'movimiento: '.$movimiento.'Cantidad: '.$cantidad.'nuevaExistencia: '.$nuevaExistencia.'<p>';
                                $this->query="UPDATE MINVE01 SET existencia = $nuevaExistencia,  EXISTENCIA_CALCULADA = $nuevaExistencia, existencia_sae = existencia  where cve_art = '$articulo' and num_mov = $movimiento";
                                $this->grabaBD();
                                //echo 'Consulta de Actulizacion: '.$this->query;
                                //break;
                            }   
                        }
                            $nuevaExistencia= 0;
                            unset($mov);
                            unset($inve);
            }
           // break;
        return;
    }


    function inventarioAunaFecha($fi, $ff){

        $this->query="SELECT * FROM INVE01 WHERE TIPO_ELE = 'P'";
        $rs=$this->EjecutaQuerySimple();

        while($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
            foreach ($data as $key) {
                // inicializamos las variables por si no existen movimientos ya sea de entrada o salida.
                $eInicial = 0;
                $sInicial = 0;
                $existencia = 0;
                $prod = $key->CVE_ART;
                $nombre = $key->DESCR;
                $uCosto = $key->ULT_COSTO;
                ///calculamos las entradas. 
                $this->query="SELECT SUM(CANT) AS ENTRADAS FROM MINVE01 WHERE CVE_ART = '$prod' and cve_cpto in (select cve_cpto from conm01 where tipo_mov = 'E') and fecha_docu < '$fi' and almacen = 1";
                $rs=$this->EjecutaQuerySimple();

                $row = ibase_fetch_object($rs);
                $eInicial = $row->ENTRADAS;

                //// Calculamos las salidas.

                $this->query="SELECT SUM(CANT) AS SALIDAS FROM MINVE01 WHERE CVE_ART = '$prod' and cve_cpto in (select cve_cpto from conm01 where tipo_mov='S') and fecha_docu < '$fi' and almacen = 1";
                $rs=$this->EjecutaQuerySimple();
                $row = ibase_fetch_object($rs);
                $sInicial = $row->SALIDAS;

                //// el resultado de las entradas - las salidas a una fecha es la existecia a la fecha misma.

                $inicial = $eInicial - $sInicial;

                /// Calculamos las entradas despues de la fecha inicial.

                $this->query = "SELECT SUM(CANT) AS ENT FROM MINVE01 WHERE CVE_ART = '$prod' and cve_cpto in (select cve_cpto from conm01 where tipo_mov = 'E')  and fecha_docu >= '$fi' and fecha_docu <= '$ff' and almacen = 1";
                $rs=$this->EjecutaQuerySimple();
                $row=ibase_fetch_object($rs);
                $entradas = $row->ENT;
                //echo $this->query.'<p>';
                //// Calculamos las salidas despues de la fecha inicial.
                $this->query = "SELECT SUM(CANT) AS SAL FROM MINVE01 WHERE CVE_ART= '$prod' and cve_cpto in (select cve_cpto from conm01 where tipo_mov='S') and fecha_docu >= '$fi' and fecha_docu <= '$ff' and almacen = 1";
                $rs=$this->EjecutaQuerySimple();
                //echo $this->query;
              //  break;
                $row=ibase_fetch_object($rs);
                $salidas = $row->SAL;
                $existencia = $inicial + $entradas - $salidas; 

                $linea = 'Clave '.$prod.', Nombre '.$nombre.', Ultimo Costo '.$uCosto.', inicial: '.$inicial.', entradas: '.$entradas.', salidas: '.$salidas.', existencias actuales: '. $existencia;

                $info[] =array($prod, $nombre, $uCosto, $inicial, $entradas, $salidas, $existencia); 
            }

            //break;
            //var_dump($info);
        return $info;
    }


    function recalcularKardex(){
        $data2=array();
        //$this->query="";
        $this->query="SELECT * FROM INVE01 WHERE TIPO_ELE = 'P'";
        $rs=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
       } 
       
        for ($i=1; $i < 4 ; $i++) { /// Almacenes inicimos con el 1 y hasta el 3   
           foreach ($data as $key) { // leemos los 28 productos.
               unset($data2);   /// destruimos data para que no quede almacenado en Memoria
               $existencia = 0; /// Limpiamos la existencia.  

               $clave = $key->CVE_ART;  // obtenemos la clave del articulo.
               $this->query="SELECT * FROM MINVE01 WHERE CVE_ART = '$clave' and almacen = $i order by fecha_docu asc, num_mov asc"; ///seleccionamos todos los movimientos del Articulo con el almacen 1;                
               $rs2=$this->EjecutaQuerySimple();
               while($tsArray=ibase_fetch_object($rs2)){
                    $data2[]=$tsArray;
               }
               /// si existen los movimientos por almacen se calculan y leen.
               if(isset($data2)){
                   foreach ($data2 as $key2){
                   $this->query="SELECT iif(MAX(ID) is null, 0 , max(id)) as id FROM FTC_KARDEX where articulo = '$key2->CVE_ART' and almacen = $i";
                   $rs=$this->EjecutaQuerySimple();
                   $row = ibase_fetch_object($rs);
                   $id = $row->ID; 
                   $existencia = $key2->CANT;

                   if($id != 0){
                    $this->query="SELECT EXISTENCIA FROM FTC_KARDEX WHERE ID = $id and almacen = $i";
                    $rs=$this->EjecutaQuerySimple();
                    $row2=ibase_fetch_object($rs);
                    $canto =$row2->EXISTENCIA; 
                    $existencia = $canto + ($key2->CANT * $key2->SIGNO);
                   }

                   $this->query="INSERT INTO FTC_KARDEX (ID, ARTICULO, CONCEPTO, CANTIDAD, SIGNO, EXISTENCIA, PROCESADO, STATUS, NUM_MOV, almacen ) VALUES(NULL, '$key2->CVE_ART', $key2->CVE_CPTO, $key2->CANT, $key2->SIGNO, $existencia, 0, 0, $key2->NUM_MOV, $i)";
                   $this->EjecutaQuerySimple();
               } 
            }      
        }
       }
       return;
    }


     function verAuxSaldosCxc($fi, $ff){
        $this->query="SELECT  * from clie01";
        $rs=$this->EjecutaQuerySimple();

        while($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }

        foreach($data as $key){
            $this->query="SELECT clave, nombre,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_m01 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov= 'C' and trim(cve_clie)  = trim('$key->CLAVE')) as Cargos_iniciales,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_det01 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov = 'A' and num_cpto >= 23  and trim(cve_clie)  = trim('$key->CLAVE')) as Pagos_iniciales,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_m01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and trim(cve_clie)  = trim('$key->CLAVE')) as cargos,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_det01  where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A' and trim(cve_clie)  = trim('$key->CLAVE')) as pagos
                            from clie01 where trim(clave) = trim('$key->CLAVE')";
            $rs=$this->EjecutaQuerySimple();
            while($tsarray=ibase_fetch_object($rs)){
                $data2[]=$tsarray;
            } 
        }
        return $data2;
    }

    function saldoFinal($fi, $ff){

            $this->query="SELECT iif(sum(importe)is null, 0, sum(importe)/ 1.16) AS CARGOS_INICIALES from cuen_m01 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov= 'C'";
            $rs=$this->EjecutaQuerySimple();
            $row =ibase_fetch_object($rs);
            $ci = $row->CARGOS_INICIALES;

            $this->query="SELECT iif(sum(importe) is null, 0, sum(importe)/1.16) as Pagos_iniciales from cuen_det01 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov = 'A'";
            $rs2 = $this->EjecutaQuerySimple();
            $row2 = ibase_fetch_object($rs2);
            $pi = $row2->PAGOS_INICIALES;

            $this->query="SELECT sum(importe)/1.16 as cargos from cuen_m01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C'";
            $rs3 = $this->EjecutaQuerySimple();
            $row3 = ibase_fetch_object($rs3);
            $cargos = $row3->CARGOS;

            $this->query="SELECT sum(importe)/1.16 as pagos from cuen_det01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A'";
            $rs4 = $this->EjecutaQuerySimple();
            $row4 = ibase_fetch_object($rs4);
            $pagos = $row4->PAGOS;

          //  echo 'Consulta Pagos: '.$this->query.'<p>';
          //  echo 'Cargos Iniciales: $ '.$ci.'<p>';
          //  echo 'Pagos Iniciales: $ '.$pi.'<p>';
          //  echo 'Cargos: $ '.$cargos.'<p>';
          //  echo 'Pagos: $ '.$pagos.'<p>';

            $totalSaldo = $ci - $pi + $cargos - $pagos;

        return $totalSaldo;
    }


    function saldoVentasBrutas($fi, $ff){
        $this->query="SELECT 
                           iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as cargos from cuen_m01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and num_cpto = 1";
            $rs3 = $this->EjecutaQuerySimple();
            $row3 = ibase_fetch_object($rs3);
            $cargos = $row3->CARGOS;
            //echo 'Detecto el cambio';

            return ;

    }


    function ventasBrutas($fi, $ff){
             /// brutas es todas las ventas - los abonos que no son transferencias
        $data2=array();
        $this->query= "SELECT * FROM CLIE01 WHERE TIPO_EMPRESA = 'M'";
        $rs=$this->EjecutaQuerySimple();
        while($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }
        $facutras = 0;
        foreach ($data as $clie) {
            $cliente = $clie->CLAVE;
            $this->query="SELECT CLAVE, nombre,
                            (select iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as fact from cuen_m01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and num_cpto = 1  and trim(cve_clie) = trim('$cliente') ) as Facturas,
                            (select iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as ncs from cuen_det01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and num_cpto =16 and trim(cve_clie) = trim('$cliente') ) as imptNC,                            
                            (select iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as cnp from cuen_det01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A' and num_cpto >= 23 and trim(cve_clie) = trim('$cliente')) as AbonosNoPagos
                            from Clie01
                            where trim(clave) = trim('$cliente')";
                             //echo $this->query.'<p>';
            $rs2=$this->EjecutaQuerySimple();
            while($tsarray= ibase_fetch_object($rs2)){
                $data2[]=$tsarray;
            }
        }



        return $data2;
    }


    function saldoVentasNetas($fi, $ff){
        $this->query="SELECT  iif(sum(importe/1.16) is null,0,sum(importe/1.16)) as cargos from cuen_m01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C'";
            $rs3 = $this->EjecutaQuerySimple();
            $row3 = ibase_fetch_object($rs3);
            $cargos = $row3->CARGOS;

        $this->query="SELECT iif(sum(importe/1.16) is null,0,sum(importe/1.16)) as pagos from cuen_m01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A'";
        $rs2=$this->EjecutaQuerySimple();
        $row2 = ibase_fetch_object($rs2);
        $abonosM = $row2->PAGOS;

        $this->query="SELECT iif(sum(importe/1.16) is null,0,sum(importe/1.16)) as pagos from cuen_det01 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A' 
            and num_cpto >= 23";
        $rs=$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $abonosD = $row->PAGOS;


        $total = $cargos - $abonosM - $abonosD;
        
            return $total;

    }


    function verListaDePrecios($cliente){
        $this->query="SELECT pp.cve_art, max(precio) as precio, max(i.descr) as descripcion, cl.nombre, 
        (select camplib10 from inve_clib01 where cve_prod = pp.cve_art) as CodigoBarras,
        (select camplib11 from inve_clib01 where cve_prod = pp.cve_art) as SKU,
        max(cl.clave) as clave
        from  precio_x_prod01 pp
        left join clie01 cl on cl.lista_prec = pp.cve_precio
        left join inve01 i on i.cve_art = pp.cve_art
        where trim(cl.clave) = trim('$cliente') 
        and precio > 0
        group by pp.cve_art, cl.nombre
         order by cl.nombre asc";
         $rs=$this->EjecutaQuerySimple();
         
        while($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }
        return @$data;
    }

    function verClientesMizco(){
        $this->query="SELECT * FROM clie01 where STATUS = 'A' and tipo_empresa= 'M'";
        $rs=$this->EjecutaQuerySimple();
        

        while($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }
        return @$data;
    }

    function costoPromedio(){
        $this->query="SELECT * FROM INVE01 WHERE tipo_ele = 'P' ";
        $rs=$this->EjecutaQuerySimple();
        //echo $this->query;
        //break;
        while ($tsArray = ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        foreach ($data as $inve){
            $this->query="SELECT * FROM MINVE01 WHERE CVE_ART = '$inve->CVE_ART' and costeado_ff is null ORDER BY NUM_MOV ASC ";
            $res=$this->EjecutaQuerySimple();
            while ($tsarray=ibase_fetch_object($res)){
                $data2[]=$tsarray;
            }
            if(count($data2) > 0){
                foreach ($data2 as $min1 ) {
                    if($min1->TIPO_DOC  == 'r' and $min1->CVE_CPTO == 1){  ///SI DETECTA UNA ENTRADA
                        $costoBase = 0;
                        // OBTENEMOS LOS VALORES DE LA CANTIDAD Y COSTO DESDE LA TABLA DE PARTIDAS DE RECEPCIONES.
                        $this->query="SELECT * FROM PAR_COMPR01 WHERE CVE_DOC = '$min1->REFER' AND NUM_MOV = $min1->NUM_MOV";
                        $result=$this->EjecutaQuerySimple();
                        $row =ibase_fetch_object($result);
                        $cantidad = $row->CANT;
                        $costo = $row->COST;
                        //echo 'Obtenemos la informacion desde la partida: '.$this->query.'<p>';
                        /// BUSCAMOS SI HAY OTRAS RECEPCIONES MAS ANTIGUAS.
                        $this->query="SELECT * FROM PAR_COMPR01 WHERE NUM_MOV < $min1->NUM_MOV AND CVE_ART = '$min1->CVE_ART'";
                        $resultado=$this->EjecutaQuerySimple();
                        $row2=ibase_fetch_object($resultado);
                        //echo 'Buscamos documento anterior : '.$this->query.'<p>';
                        if(!empty($row2)){  //// SI HAY RECEPCIONES MAS ANTIGUA
                            /// OBTENEMOS COSTO Y EXISTENCIA  DEL ULTIMO MOVIMIENTO CALCULADO.
                            $this->query="SELECT  MAX(num_mov) AS umc FROM MINVE01 WHERE CVE_ART = '$min1->CVE_ART' AND COSTO_NUEVO IS NOT NULL";
                            $rs=$this->EjecutaQuerySimple();
                            $row4 = ibase_fetch_object($rs);
                        //    echo 'Obtenemos el ultimo movimiento: '.$this->query.'<p> Ultimo Movimiento: '.$row4->UMC.'<p>';
                            $this->query="SELECT * FROM MINVE01 WHERE NUM_MOV = $row4->UMC";
                            $rs = $this->EjecutaQuerySimple();
                            $row6 = ibase_fetch_object($rs);
                            $costoBase = $row6->COSTO_NUEVO;
                            $existBase = $row6->EXISTENCIA_CALCULADA;
                            $base = $costoBase * $existBase;
                            $costoNuevo = (($base) + ($costo * $min1->CANT)) / ($existBase + $min1->CANT);
                        //    echo 'Obtenemos los datos del ultimo movimiento : '.$this->query.'<p>';
                            $this->query = "UPDATE minve01 set COSTO_NUEVO = $costoNuevo, costeado_ff = 1 where num_mov = $min1->NUM_MOV";
                            $this->EjecutaQuerySimple();
                          //  echo 'Actualizamos con el costo : '.$this->query.'<p>';             
                        }else{
                            $this->query="UPDATE MINVE01 SET COSTO_NUEVO = $costo, costeado_ff = 1 where num_mov = $min1->NUM_MOV";
                            $this->EjecutaQuerySimple();
                        //    echo 'Costo directo: '.$this->query;

                        }
                        // 500.00 + 480.00 = 980.00 / 2  = 490.00
                        /// costo base por cantidad actual + entradas por costo nuevo, entre cantidad final:
                    }else{
                        //echo 'Entra a costo simple <p>';
                        $this->query="SELECT MAX(NUM_MOV) AS ULTIMO_MOVIMIENTO from minve01 where cve_art = '$min1->CVE_ART' and num_mov < $min1->NUM_MOV";
                        $res= $this->EjecutaQuerySimple();
                        $row3 = ibase_fetch_object($res);
                        $umov = $row3->ULTIMO_MOVIMIENTO; 
                      //  echo 'No es recepcion, seleccionamos el ultimo movimiento'.$this->query;
                        $this->query="UPDATE MINVE01 SET COSTO_NUEVO = (select COSTO_NUEVO FROM MINVE01 WHERE NUM_MOV = $umov), costeado_ff  = 1, costo = (select COSTO_NUEVO FROM MINVE01 WHERE NUM_MOV = $umov)  where num_mov = $min1->NUM_MOV";
                        $ok=$this->grabaBD();
                        if(empty($ok)){
                         //   echo 'error: '.$this->query;
                        }
                        //echo 'Actualizacmos minve : '.$this->query.'<p>';
                    }
                }
            }
        $this->query="UPDATE INVE01 SET 
                   COSTO_PROM = (SELECT FIRST 1  COSTO FROM MINVE01 WHERE CVE_ART = '$inve->CVE_ART' ORDER BY NUM_MOV DESC ), 
                    ULT_COSTO = (SELECT FIRST 1 COSTO FROM MINVE01 WHERE CVE_ART = '$inve->CVE_ART' AND tipo_DOC = 'r' AND (COSTO IS NOT NULL OR COSTO >0) ORDER BY FECHA_DOCU DESC) 
                    WHERE CVE_ART = '$inve->CVE_ART'";
        $this->grabaBD();
        //echo $this->query;
        }
        //break;
       return;
    }

    function guardaAperak($folio, $aperak){
            $aperak=utf8_decode($aperak);
            $this->query="INSERT INTO FTC_APERAK 
                        (ID, FACTURA, APERAK, FECHA) 
                        VALUES (NULL, '$folio','$aperak', current_timestamp) ";
            echo $this->query;
            $this->EjecutaQuerySimple();
            $file = fopen("C:\\xampp\\htdocs\\Aperak\\Aperak_WS_".$folio.".xml", "a");
            fwrite($file, $aperak.PHP_EOL);
            fclose($file);
        return;
    }

    function ObtieneXml($folio){
        $this->query="SELECT CAST(XML_DOC AS VARCHAR(32765)) AS XML_DOC FROM CFDI01 WHERE CVE_DOC='$folio'";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        return $row->XML_DOC;
    }
   
   function actualizaAperak(){
        $data=array();
        $this->query="SELECT FACTURA, ID, CAST(APERAK AS VARCHAR(32765)) AS APERAK FROM FTC_APERAK WHERE STATUS IS NULL";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        if(count($data) > 0){
            foreach ($data as $key) {
                $xml = simplexml_load_string($key->APERAK);
                $sta=$xml['documentStatus'];
                if($sta == 'ACCEPTED'){
                    $this->query="UPDATE FTC_APERAK SET STATUS = '$sta'where id = $key->ID";     
                }else{
                $m=$xml->messageError->errorDescription->text;
                    $this->query="UPDATE FTC_APERAK SET STATUS = '$sta', Motivo = '$m' where id = $key->ID";
                }
                $this->EjecutaQuerySimple();
                //exit(print_r($xml));                
            }
        }
        return;
   }

   function datosAperak($folio){
        $this->query="SELECT first 1 A.*, cast(a.aperak as VARCHAR(3000)) as APRK FROM FTC_APERAK A WHERE a.FACTURA = '$folio' order by id desc";
        $res=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }
        return $data;
   }

   function noenviar($docf){
    $this->query="UPDATE FACTF01 SET REFACTURACION = 9 WHERE CVE_DOC = '$docf'";
    $this->EjecutaQuerySimple();
    return array("status"=>'ok');
   }

   function insertaCFDI($xml, $folio){
        
        $this->query="INSERT INTO CFDI01 (TIPO_DOC, CVE_DOC, XML_DOC ) values ('F', '$folio', trim('$xml'))";
        $this->grabaBD();
        $this->query="UPDATE CFDI01 SET XML_DOC = SUBSTRING(XML_DOC FROM 4 ) WHERE CVE_DOC ='$folio'";
        $this->EjecutaQuerySimple();
        return $folio;
   }

   function apolo(){
        $path="C:\\inetpub\\ftproot\\out";
        $dir=scandir($path);
        for ($i=0; $i < count($dir) ; $i++) { 
            if(!is_dir($path.'\\'.$dir[$i])){
                $file= explode("_", $dir[$i]);
                $this->query="SELECT COUNT(*) as val FROM FTC_APOLO WHERE FOLIO = '$file[3]' and num_doc = '$file[4]'";
                $res=$this->EjecutaQuerySimple();
                if(ibase_fetch_object($res)->VAL >= 1){
                    //echo '<br/>Archivo ya cargado';
                }else{
                    $this->query="INSERT INTO FTC_APOLO VALUES (null, '$dir[$i]', '$file[1]', '$file[2]', '$file[3]', '$file[4]','', 0) returning id";
                    $res=$this->EjecutaQuerySimple();
                    $id = ibase_fetch_object($res)->ID;
                    $xml=$this->leerXMLApolo($path, $dir[$i], $id);
                    $this->regApolo($id, 'Carga de Archivo');
                }
            }
        }
        $this->query="SELECT A.*, 
                (SELECT  max(O.FECHA) FROM FTC_APOLO_OBS O WHERE A.ID = O.ID_A ) AS FECHA,
                DATEADD(2 day to (SELECT O.FECHA FROM FTC_APOLO_OBS O WHERE A.ID = O.ID_A and status = 0)) AS FECHA_LIM
                FROM FTC_APOLO A WHERE A.STATUS < 10 ";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
   }

   function leerXMLApolo($path, $dir, $id){
        $file = $path.'\\'.$dir;
        $myFile = fopen($file, "r") or die ("No se ha logrado abrir el archivo ($archivo)!");
        $xmlData = fread($myFile, filesize($file));
        $xml= simplexml_load_file($file) or die ("No se ha logrado leer el XML ($archivo)!");
        /// Datos Emisor
        $rfc_e= $xml->Emisor['Rfc']; 
        $nom_e= $xml->Emisor['Nombre'];
        $reg_e= $xml->Emisor['RegimenFiscal'];
        $reg_e_d= $xml->Emisor['RegimenFiscalDesc'];
        /// Datos Receptor
        $rfc_r= $xml->Receptor['Rfc']; 
        $res_r= $xml->Receptor['ResidenciaFiscal'];
        $res_r_d= $xml->Receptor['ResidenciaFiscalDesc'];
        $uso_r= $xml->Receptor['UsoCFDI'];
        $uso_r_d= $xml->Receptor['UsoCFDIDesc'];
        $cat_r= $xml->Receptor['catalogado'];
        /// Generales
        $serie = $xml['Serie'];
        $folio = $xml['Folio'];
        $fecha = $xml['Fecha'];
        $fm = $xml['FormaPago'];
        $fm_d = $xml['FormaPagoDesc'];
        $subtotal = $xml['SubTotal'];
        $desc = $xml['Descuento'];
        $moneda = $xml['Moneda'];
        $moneda_d = $xml['MonedaDesc'];
        $tipCam = $xml['TipoCambio'];
        $total = $xml['Total'];
        $tipoComp = $xml['TipoDeComprobante'];
        $tipoComp_d = $xml['TipoDeComprobanteDesc'];
        $metPag = $xml['MetodoPago'];
        $metPag_d = $xml['MetodoPagoDesc'];
        $lugar = $xml['LugarExpedicion'];
        $moneda_dec = $xml['MonedaDecimales'];
        $empresa_dec = $xml['EmpresaDecimales'];
        $imp_let = $xml['ImporteConLetras'];
        
        $this->query="INSERT INTO FTC_APOLO_CABECERA (ID_C, ID_A, E_RFC, E_NOMBRE, E_REGIMEN, E_REGIMEN_DESC, R_RFC, R_NOMBRE, R_RESIDENCIA_FISCAL, R_RESIDENCIA_FISCAL_DESC, R_USO_CFDI, R_USO_CFDI_DESC, R_CATALOGADO, SERIE, FOLIO, FECHA, FORMA_PAGO, FORMA_PAGO_DESC, METODO_PAGO, METODO_PAGO_DESC, LUGAR_EXPEDICION, MONEDA_DECIMALES, EMPRESA_DECIMALES, IMPORTE_LETRAS) 
        VALUES (null, $id, '$rfc_e', '$nom_e', '$reg_e', '$reg_e_d', '$rfc_r', '', '$res_r', '$res_r_d', '$uso_r', '$uso_r_d', '$cat_r', '$serie', '$folio', '$fecha', '$fm', '$fm_d', '$metPag', '$metPag_d', '$lugar', '$moneda_dec', '$empresa_dec', '$imp_let')";
        $this->grabaBD();

        foreach ($xml->Conceptos->Concepto as $con){
            //echo '<br/>'.$con['Descripcion'];
            $clave_sat=$con['ClaveProdServ'];
            $identificador=$con['NoIdentificacion'];
            $cant= $con['Cantidad'];
            $uni_sat=$con['ClaveUnidad'];
            $uni_sat_d=$con['UnidadDesc'];
            $unidad = $con['Unidad'];
            $desc=$con['Descripcion'];
            $valUni=$con['ValorUnitario'];
            $importe=$con['Importe'];
            $descPor=$con['DescuentoPorcentual'];
            $porDesc=$con['PorcentajeDescuento'];
            $descuento=$con['Descuento'];
            $idprod=$con['Idproducto'];
            $prodCat=$con['Catalogado'];
            $editando=$con['Editando'];  

            $this->query="INSERT INTO FTC_APOLO_DETALLE (ID_AD, ID_A, CANTIDAD, UNITARIO, PRODUCTO, DESCRIPCION, SUBTOTAL, TOTAL, IMPORTE, UNIDAD_DESC, CLAVE_UNIDAD, UNIDAD, NO_IDENTIFICACION, CLAVE_PROD_SERV, DESC_PORCENTUAL, PORCENTAJE_DESC, DESCUENTO, ID_PRODUCTO, CATALOGADO, EDITANDO) VALUES (NULL, $id, $cant, $valUni, '', '$desc', $cant * $valUni, 0, $importe, '$uni_sat_d', '$uni_sat', '$unidad', '$identificador', '$clave_sat', '$descPor', $porDesc, $descuento, '$idprod', '$prodCat', '$editando') returning ID_AD";
            $res=$this->grabaBD();
            $idp = ibase_fetch_object($res)->ID_AD;
            foreach ($con->Impuestos->Traslados->Traslado as $tras) {
                $tipo_imp = $tras['Impuesto'];
                $base = $tras['Base'];
                $imp_d = $tras['ImpuestoDesc'];
                $tipo_fact=$tras['TipoFactor'];
                $tasa = $tras['TasaOCuota'];
                $imp_imp = $tras['Importe'];
                $this->query="INSERT INTO FTC_APOLO_DETALLE_IMP (ID_AI, ID_A, ID_AD, TIPO_IMP, BASE, IMPUESTO, DESCRIPCION, TIPO, TASA_O_CUOTA, IMPORTE) VALUES (NULL, $id, $idp, 'Traslado', $base, '$tipo_imp', '$imp_d', '$tipo_fact', $tasa, $imp_imp )";
                $this->EjecutaQuerySimple();
            }
            if ($con->Impuestos->Retenciones->Retencion){
                foreach ($con->Impuestos->Retenciones->Retencion as $ret) {
                    $tipo_imp = $ret['Impuesto'];
                    $base = $ret['Base'];
                    $imp_d = $ret['ImpuestoDesc'];
                    $tipo_fact=$ret['TipoFactor'];
                    $tasa = $ret['TasaOCuota'];
                    $imp_imp = $ret['Importe'];
                    $this->query="INSERT INTO FTC_APOLO_DETALLE_IMP (ID_AI, ID_A, ID_AD, TIPO_IMP, BASE, IMPUESTO, DESCRIPCION, TIPO, TASA_O_CUOTA, IMPORTE) VALUES (NULL, $id, $idp, 'Retencion', $base, '$tipo_imp', '$imp_d', '$tipo_fact', $tasa, $imp_imp )";
                    $this->EjecutaQuerySimple();
                }
            }
        }
        return; 
   }

   function regApolo($id, $obs){
        $usuario = $_SESSION['user']->NOMBRE;
        $opc = '';
        if($obs=='envio'){
            if($id!='all'){
                $opc = ' where id = '.$id;
            }else{
                $opc = ' where status = 0 ';
            }
            $this->query="SELECT * FROM FTC_APOLO $opc";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)){
                $data[]=$tsArray;
            }
            foreach ($data as $v){
                $this->query="INSERT INTO FTC_APOLO_OBS (ID, ID_A, OBSERVACIONES, FECHA, USUARIO, STATUS) VALUES (NULL, $v->ID, 'Envio de correo electronico por $usuario', current_timestamp, '$usuario', 1)";
                $this->grabaBD();
                $this->query="UPDATE FTC_APOLO SET STATUS = 1 WHERE ID = $v->ID";
                $this->EjecutaQuerySimple();
            }
        return;
        }
        $this->query="INSERT INTO FTC_APOLO_OBS (ID, ID_A, OBSERVACIONES, FECHA, USUARIO, STATUS) VALUES (NULL, $id, '$obs', current_timestamp, '$usuario', (SELECT STATUS FROM FTC_APOLO WHERE ID = $id))";
        $this->grabaBD();
        return;
   }

   function correoApolo($id){
        if($id == 'all'){
            $opc = ' where status = 0';
        }else{
            $opc = ' where id = '.$id;
        }
        $this->query="SELECT * FROM FTC_APOLO_EMAIL $opc";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
   }

}
?>
