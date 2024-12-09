<?php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 0);

session_start();
require_once $GLOBALS['RAIZ_DO_PROJETO'] . "classes/BancoDa.php";
verificarSessao();

$action = isset($_POST['action']) ? $_POST['action'] : '';

$variaveis['ticket'] = isset($_POST['ticket']) ? $_POST['ticket'] : '';
$variaveis['assunto'] = isset($_POST['assunto']) ? $_POST['assunto'] : '';
$variaveis['descricao'] = isset($_POST['descricao']) ? $_POST['descricao'] : '';
$variaveis['dt_abertura'] = isset($_POST['dt_abertura']) ? $_POST['dt_abertura'] : '';
$variaveis['data_homologacao'] = isset($_POST['data_HMO']) ? $_POST['data_HMO'] : ''; 
$variaveis['data_producao'] = isset($_POST['data_PRD']) ? $_POST['data_PRD'] : ''; 
$variaveis['tipo'] = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$variaveis['modulo'] = isset($_POST['modulo']) ? $_POST['modulo'] : '';
$variaveis['area'] = isset($_POST['area']) ? $_POST['area'] : '';
$variaveis['autor'] = isset($_POST['autor']) ? $_POST['autor'] : '';
$variaveis['status'] = isset($_POST['status']) ? $_POST['status'] : '';
$variaveis['situacao'] = isset($_POST['situacao']) ? $_POST['situacao'] : '';

if ($action) {
    switch ($action) {
        case 'incluir':
            try {
                $resultadoAtualizacao = Custa::incluirTick(
                    $variaveis['ticket'],
                    $variaveis['assunto'],
                    $variaveis['descricao'],
                    $variaveis['dt_abertura'],
                    $variaveis['data_homologacao'],
                    $variaveis['data_producao'],
                    $variaveis['tipo'],
                    $variaveis['modulo'],
                    $variaveis['area'],
                    $variaveis['autor'],
                    $variaveis['status'],
                    $variaveis['situacao']
                );
                echo $resultadoAtualizacao;
            } catch (Exception $e) {
                echo "Erro na atualização do ticket. Erro: " . $e->getMessage();
            }
            break;

        case 'update':
            try {
                $resultadoAtualizacao = Custa::atualizarTick(
                    $variaveis['ticket'],
                    $variaveis['assunto'],
                    $variaveis['descricao'],
                    $variaveis['dt_abertura'],
                    $variaveis['data_homologacao'],
                    $variaveis['data_producao'],
                    $variaveis['tipo'],
                    $variaveis['modulo'],
                    $variaveis['area'],
                    $variaveis['autor'],
                    $variaveis['status'],
                    $variaveis['situacao']
                );
                echo $resultadoAtualizacao; 
            } catch (Exception $e) {
                echo "Erro na atualização do ticket. Erro: " . $e->getMessage();
            }
            break;

        case 'delete':
            try {
                $resultadoExclusao = Custa::excluirTick($variaveis['ticket']);
                echo $resultadoExclusao;
            } catch (Exception $e) {
                echo "Erro na exclusão do ticket. Erro: " . $e->getMessage();
            }
            break;

        default:
            echo "Ação não reconhecida.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <title>Financeira</title>

    <!-- Bootstrap core CSS -->
    <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="../../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../lib/gritter/css/jquery.gritter.css">
    <!-- Custom styles for this template -->
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/style-responsive.css" rel="stylesheet">
    <script src="../../lib/chart-master/Chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
    :root {
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --border-radius: 0.3rem;
        --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --modal-width: 80%; 
        --modal-max-width: 600px;
        --modal-padding: 1rem;
        --font-size-large: 20px;
        --button-padding: 10px;
        --button-padding-wide: 10px 20px;
    }
    .mt {
        margin-top: 10px;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }

    .modal-content {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.125);
        width: var(--modal-width);
        max-width: var(--modal-max-width);
        margin: auto;
    }

    .modal-header, .modal-body, .modal-footer {
        padding: var(--modal-padding);
    }

    .modal-header {
        border-bottom: 1px solid #e5e5e5;
        font-weight: bold; 
    }

    .modal-footer {
        border-top: 1px solid #e5e5e5;
        text-align: right;
    }

    .swal2-styled.swal2-confirm,
    .swal2-styled.swal2-cancel {
        font-size: var(--font-size-large);
        padding: var(--button-padding);
    }

    .swal2-styled.swal2-cancel {
        padding: var(--button-padding-wide);
    }

    .btn-custom {
        width: 100px;
        margin-right: 10px;
    }

    .btn-custom:last-child {
        margin-right: 0;
    }

    @media (max-width: 768px) {
        .modal-content {
            width: 90%; 
        }
        
        .btn-custom {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .btn-custom:last-child {
            margin-right: 0;
            margin-bottom: 0;
        }
    }
    </style>


    <!-- =======================================================
          
            ======================================================= -->
</head>

<body>

    <section id="container">
        <!-- **********************************************************************************************************************************************************
                    TOP BAR CONTENT & NOTIFICATIONS
                    *********************************************************************************************************************************************************** -->
        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Navegação"></div>
            </div>
            <a href="../../index.php" class="logo"><b><span>FINANCEIRA</span></b></a>
            <div class="nav notify-row" id="top_menu">
                <?php
                include_once "../../componentes/barra_notificacao_sup.php";
                ?>
            </div>
            <?php
            @require_once '../../componentes/topbar.php';
            ?>
        </header>
        <!--header end-->
        <!-- **********************************************************************************************************************************************************
                    MAIN SIDEBAR MENU
                    *********************************************************************************************************************************************************** -->
        <?php
        include_once "../../componentes/menu_dinamico.php";
        ?>
        <!--sidebar end-->
        <!-- **********************************************************************************************************************************************************
                    MAIN CONTENT
                    *********************************************************************************************************************************************************** -->
        <section id="main-content">
            <section class="wrapper site-min-height">
                <div class="row mt">
                    <div class="col-md-12 mt">
                    <h3 style="margin-bottom: 20px;">Controle de Gestão</h3>
                        <div class="content-panel">
                            <td>
                                <div style="display: inline-block; margin-right: 10px; margin-left: 10px">
                                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Pesquisar">
                                </div>
                                <div style="float: right; width: auto; margin-right: 10px;">
                                    <button class="btn btn-sm btn-primary btn-adicionar">Adicionar ticket</button>
                                </div>
                            </td>

                            <table class="table table-bordered table-striped" style="margin-top: 20px;">
                                <thead>
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Assunto</th>
                                        <th>Descricao</th>
                                        <th>Data abertura</th>
                                        <th>Data HMO</th>
                                        <th>Data PRD</th>
                                        <th>Duração</th>
                                        <th>Tipo</th>
                                        <th>Modulo</th>
                                        <th>Area</th>
                                        <th>Autor</th>
                                        <th>Status</th>
                                        <th>Situacao</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php

                                    $sql = "SELECT 
                                                *
                                            FROM tbl_fic t1
                                            LEFT JOIN tbl_fic2 t2 ON t1.ticket = t2.ticket
                                            ORDER BY t1.dt_abertura DESC";

                                    $result = BancoDados::SelectSIG($sql);
                                    
                                    foreach ($result as $row) {
                                        echo "<tr>";
                                        echo "<td>{$row['ticket']}</td>";
                                        echo "<td>{$row['assunto']}</td>";
                                        echo "<td>{$row['descricao']}</td>";
                                        echo "<td>{$row['dt_abertura']}</td>";
                                        echo "<td>{$row['data_homologacao']}</td>";
                                        echo "<td>{$row['data_producao']}</td>";
                                        echo "<td>{$row['prz_duracao']} dias</td>";
                                        echo "<td>{$row['tipo']}</td>";
                                        echo "<td>{$row['modulo']}</td>";
                                        echo "<td>{$row['area']}</td>";
                                        echo "<td>{$row['autor']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td>{$row['situacao']}</td>";
                                        echo "<td> <div class='btn-group' role='group' aria-label='Ações'>
                                                        <button class='btn btn-sm btn-warning btn-custom btn-edit'>Editar</button>                                                    
                                                    </div>
                                                <div 
                                                    <button class='btn btn-sm btn-danger btn-custom btn-delete'>Excluir</button>
                                                </div>
                                            </td>";
                                    echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                                <!-- Modal Editar -->
                                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog custom-width" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Editar</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="editTicket">Ticket</label>
                                                    <input type="text" class="form-control" id="editTicket" name="ticket" placeholder="Insira o ticket" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editAssunto">Assunto</label>
                                                    <input type="text" class="form-control" id="editAssunto" name="assunto" placeholder="Insira o assunto" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editDescricao">Descrição</label>
                                                    <input type="text" class="form-control" id="editDescricao" name="descricao" placeholder="Insira a descrição" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editAbertura">Data Abertura</label>
                                                    <input type="text" class="form-control" id="editAbertura" name="dt_abertura" placeholder="Insira a data de abertura (aaaa-mm-dd)" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editHMO">Data HMO</label>
                                                    <input type="text" class="form-control" id="editHMO" name="data_homologacao" placeholder="Insira a data HMO (aaaa-mm-dd)" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editPRD">Data PRD</label>
                                                    <input type="text" class="form-control" id="editPRD" name="data_producao" placeholder="Insira a data PRD (aaaa-mm-dd)" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editTipo">Tipo</label>
                                                    <input type="text" class="form-control" id="editTipo" name="tipo" placeholder="Insira o tipo" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editModulo">Módulo</label>
                                                    <input type="text" class="form-control" id="editModulo" name="modulo" placeholder="Insira o módulo" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editArea">Área</label>
                                                    <input type="text" class="form-control" id="editArea" name="area" placeholder="Insira a área" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editAutor">Autor</label>
                                                    <input type="text" class="form-control" id="editAutor" name="autor" placeholder="Insira o autor" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editStatus">Status</label>
                                                    <input type="text" class="form-control" id="editStatus" name="status" placeholder="Insira o status" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="editSituacao">Situação</label>
                                                    <select id="editSituacao" class="form-control">
                                                        <option value="0" hidden>Altere a situação das custas</option>
                                                        <option value="ABERTO" selected>ABERTO</option>
                                                        <option value="FECHADO">FECHADO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                <button type="button" class="btn btn-primary" id="saveChanges">Salvar alterações</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="incluirModal" tabindex="-1" role="dialog" aria-labelledby="incluirModalLabel" aria-hidden="true">
                                    <div class="modal-dialog custom-width" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="incluirModalLabel">Incluir Usuário</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="addTicket">Ticket</label>
                                                    <input type="text" class="form-control" id="addTicket" name="ticket" placeholder="Insira o ticket">
                                                </div>
                                                <div class="form-group">
                                                    <label for="addAssunto">Assunto</label>
                                                    <input type="text" class="form-control" id="addAssunto" name="assunto" placeholder="Insira o assunto" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addDescricao">Descrição</label>
                                                    <input type="text" class="form-control" id="addDescricao" name="descricao" placeholder="Insira a descrição" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addAbertura">Data Abertura</label>
                                                    <input type="text" class="form-control" id="addAbertura" name="dt_abertura" placeholder="Insira a data de abertura (aaaa-mm-dd)" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addHMO">Data HMO</label>
                                                    <input type="text" class="form-control" id="addHMO" name="data_homologacao" placeholder="Insira a data HMO (aaaa-mm-dd)">
                                                </div>
                                                <div class="form-group">
                                                    <label for="addPRD">Data PRD</label>
                                                    <input type="text" class="form-control" id="addPRD" name="data_producao" placeholder="Insira a data PRD (aaaa-mm-dd)">
                                                </div>
                                                <div class="form-group">
                                                    <label for="addTipo">Tipo</label>
                                                    <input type="text" class="form-control" id="addTipo" name="tipo" placeholder="Insira o tipo" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addModulo">Módulo</label>
                                                    <input type="text" class="form-control" id="addModulo" name="modulo" placeholder="Insira o módulo" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addArea">Área</label>
                                                    <input type="text" class="form-control" id="addArea" name="area" placeholder="Insira a área" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addAutor">Autor</label>
                                                    <input type="text" class="form-control" id="addAutor" name="autor" placeholder="Insira o autor" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addStatus">Status</label>
                                                    <input type="text" class="form-control" id="addStatus" name="status" placeholder="Insira o status" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="addSituacao">Situação</label>
                                                    <select id="addSituacao" class="form-control">
                                                        <option value="0" hidden>Altere a situação das custas</option>
                                                        <option value="ABERTO" selected>ABERTO</option>
                                                        <option value="FECHADO">FECHADO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                <button type="button" class="btn btn-primary" id="saveChanges_add">Salvar alterações</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </section>
        <footer class="site-footer">
            <div class="text-center">
                <p>
                    &copy;<strong>FINANCEIRA</strong>
                </p>
                <div class="credits">
                </div>
                <a href="#" class="go-top">
                    <i class="fa fa-angle-up"></i>
                </a>
            </div>
        </footer>
        <!-- Importação de bibliotecas JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="../../lib/jquery/jquery.min.js"></script>
        <script src="../../lib/bootstrap/js/bootstrap.min.js"></script>
        <script class="include" type="text/javascript" src="../../lib/jquery.dcjqaccordion.2.7.js"></script>
        <script src="../../lib/jquery.scrollTo.min.js"></script>
        <script src="../../lib/jquery.nicescroll.js" type="text/javascript"></script>
        <script src="../../lib/jquery.sparkline.js"></script>

        <!-- Importação de scripts de todas as páginas -->
        <script src="../../lib/common-scripts.js"></script>
        <script type="text/javascript" src="../../lib/gritter/js/jquery.gritter.js"></script>
        <script type="text/javascript" src="../../lib/gritter-conf.js"></script>

        <!-- Importação de script para esta página -->
        <script type="text/javascript" src="../../lib/gened/sidebar.js"></script>
        <script src="../../lib/sparkline-chart.js"></script>

        <script>
            $(document).ready(function() {
                $('.btn-edit').click(function() {
                    let tr = $(this).closest('tr');

                    $('#editTicket').val(tr.find('td:eq(0)').text());
                    $('#editAssunto').val(tr.find('td:eq(1)').text());
                    $('#editDescricao').val(tr.find('td:eq(2)').text());
                    $('#editAbertura').val(tr.find('td:eq(3)').text());
                    $('#editHMO').val(tr.find('td:eq(4)').text());
                    $('#editPRD').val(tr.find('td:eq(5)').text());
                    $('#editTipo').val(tr.find('td:eq(7)').text());
                    $('#editModulo').val(tr.find('td:eq(8)').text());
                    $('#editArea').val(tr.find('td:eq(9)').text());
                    $('#editAutor').val(tr.find('td:eq(10)').text());
                    $('#editStatus').val(tr.find('td:eq(11)').text());
                    $('#editSituacao').val(tr.find('td:eq(12)').text());

                    $('#editModal').modal('show');
                });

                $('#saveChanges').click(function() {
                    // Captura os valores dos campos do modal
                    let ticket = $('#editTicket').val();
                    let assunto = $('#editAssunto').val();
                    let descricao = $('#editDescricao').val();
                    let dt_abertura = $('#editAbertura').val();
                    let data_HMO = $('#editHMO').val();
                    let data_PRD = $('#editPRD').val();
                    let tipo = $('#editTipo').val();
                    let modulo = $('#editModulo').val();
                    let area = $('#editArea').val();
                    let autor = $('#editAutor').val();
                    let status = $('#editStatus').val();
                    let situacao = $('#editSituacao').val();

                    $.ajax({
                        type: 'POST',
                        url: 'controle_gestao.php',
                        data: {
                            action: 'update',
                            ticket: ticket,
                            assunto: assunto,
                            descricao: descricao,
                            dt_abertura: dt_abertura,
                            data_HMO: data_HMO,
                            data_PRD: data_PRD,
                            tipo: tipo,
                            modulo: modulo,
                            area: area,
                            autor: autor,
                            status: status,
                            situacao: situacao
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Ticket editado com sucesso!",
                                icon: "success"
                            }).then(() => {
                                location.reload(); 
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: "Erro ao editar!",
                                text: "Ocorreu um erro ao tentar editar ticket.",
                                icon: "error"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });

                    $('#editModal').modal('hide');
                });
                $('.btn-adicionar').click(function() {
                    $('#incluirModal').modal('show');
                });

                $('#saveChanges_add').click(function() {
                    let ticket = $('#addTicket').val();
                    let assunto = $('#addAssunto').val();
                    let descricao = $('#addDescricao').val();
                    let dt_abertura = $('#addAbertura').val();
                    let data_HMO = $('#addHMO').val();
                    let data_PRD = $('#addPRD').val();
                    let tipo = $('#addTipo').val();
                    let modulo = $('#addModulo').val();
                    let area = $('#addArea').val();
                    let autor = $('#addAutor').val();
                    let status = $('#addStatus').val();
                    let situacao = $('#addSituacao').val();
                    
                    $.ajax({
                        type: 'POST',
                        url: 'controle_gestao.php',
                        data: {
                            action: 'incluir',
                            ticket: ticket,
                            assunto: assunto,
                            descricao: descricao,
                            dt_abertura: dt_abertura,
                            data_HMO: data_HMO,
                            data_PRD: data_PRD,
                            tipo: tipo,
                            modulo: modulo,
                            area: area,
                            autor: autor,
                            status: status,
                            situacao: situacao
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Ticket incluído com sucesso!",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: "Erro ao incluir!",
                                text: "Ocorreu um erro ao tentar incluir ticket.",
                                icon: "error"
                            }).then(() => {
                                location.reload(); 
                            });
                        }
                    });

                    $('#incluirModal').modal('hide');
                });

                $('.btn-delete').click(function() {
                    let tr = $(this).closest('tr');
                    let ticket = tr.find('td:eq(0)').text();
                    let assunto = tr.find('td:eq(1)').text();
                    let descricao = tr.find('td:eq(2)').text();
                    let dt_abertura = tr.find('td:eq(3)').text();
                    let data_HMO = tr.find('td:eq(4)').text();
                    let data_PRD = tr.find('td:eq(5)').text();
                    let tipo = tr.find('td:eq(7)').text();
                    let modulo = tr.find('td:eq(8)').text();
                    let area = tr.find('td:eq(9)').text();
                    let autor = tr.find('td:eq(10)').text();
                    let status = tr.find('td:eq(11)').text();
                    let situacao = tr.find('td:eq(12)').text(); 

                    Swal.fire({
                        title: 'Deseja realmente excluir o ticket ' + ticket + '?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Excluir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: 'controle_gestao.php',
                                data: {
                                    action: 'delete',
                                    ticket: ticket,
                                    assunto: assunto,
                                    descricao: descricao,
                                    dt_abertura: dt_abertura,
                                    data_HMO: data_HMO,
                                    data_PRD: data_PRD,
                                    tipo: tipo,
                                    modulo: modulo,
                                    area: area,
                                    autor: autor,
                                    status: status,
                                    situacao: situacao
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: "Ticket excluído!",
                                        text: "O ticket foi excluído com sucesso.",
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        title: "Erro ao excluir!",
                                        text: "Ocorreu um erro ao tentar excluir o ticket.",
                                        icon: "error"
                                    }).then(() => {
                                        location.reload(); 
                                    });
                                }
                            });
                        }
                    });
                });

                $('#searchInput').on('input', function() {
                    let searchText = $(this).val().toLowerCase();

                    $('.table tbody tr').each(function() {
                        let row = $(this);
                        let rowText = row.text().toLowerCase();

                        if (rowText.includes(searchText)) {
                            row.show(); 
                        } else {
                            row.hide();
                        }
                    });
                });
            });
        </script>
    </body>
</html>
