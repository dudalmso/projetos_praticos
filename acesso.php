<?php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 0);

session_start();
require_once $GLOBALS['RAIZ_DO_PROJETO'] . "classes/BancoDa.php";
verificarSessao();

$action = isset($_POST['action']) ? $_POST['action'] : '';

$variaveis['gerencia'] = isset($_POST['gerencia']) ? $_POST['gerencia'] : '';
$variaveis['matricula'] = isset($_POST['matricula']) ? $_POST['matricula'] : '';
$variaveis['empresa'] = isset($_POST['empresa']) ? $_POST['empresa'] : '';
$variaveis['nome_prestador'] = isset($_POST['nome_prestador']) ? $_POST['nome_prestador'] : '';
$variaveis['senha'] = isset($_POST['senha']) ? $_POST['senha'] : '';
$variaveis['email'] = isset($_POST['email']) ? $_POST['email'] : '';
$variaveis['situacao'] = isset($_POST['situacao']) ? $_POST['situacao'] : '';

if ($action) {
    switch ($action) {
        case 'incluir':
            try {
                // Chama a função para inserir um novo correspondente
                $resultadoInclusao = Custa::inserir(
                    $_POST['gerencia'],
                    $_POST['matricula'],
                    $_POST['empresa'],
                    $_POST['nome_prestador'],
                    $_POST['senha'],
                    $_POST['email']
                );
                echo $resultadoInclusao; 
            } catch (Exception $e) {
                echo "Erro na inclusão do correspondente. Erro: " . $e->getMessage();
            }
            break;

        case 'update':
            try {
                $resultadoAtualizacao = Custa::atualizar(
                    $_POST['senha'],
                    $_POST['email'],
                    $_POST['situacao'],
                    $_POST['matricula']
                );
                echo $resultadoAtualizacao;
            } catch (Exception $e) {
                echo "Erro na atualização do correspondente. Erro: " . $e->getMessage();
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

    <!-- Favicons -->

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
        --modal-width: 80%; /* Largura do modal */
        --modal-max-width: 600px; /* Largura máxima do modal */
        --modal-padding: 1rem;
        --font-size-large: 20px;
        --button-padding: 10px;
        --button-padding-wide: 10px 20px;
    }
    .mt {
        margin-top: 10px;
    }

    /* Custom styles for table */
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
        max-width: var(--modal-max-width); /* Limita a largura do modal */
        margin: auto; /* Centraliza o modal */
    }

    .modal-header, .modal-body, .modal-footer {
        padding: var(--modal-padding);
    }

    .modal-header {
        border-bottom: 1px solid #e5e5e5;
        font-weight: bold; /* Destaca o título do modal */
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .modal-content {
            width: 90%; /* Aumenta a largura em telas pequenas */
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
        <!--header start-->
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
        </header>
        <!--header end-->
        <!-- **********************************************************************************************************************************************************
                    MAIN SIDEBAR MENU
                    *********************************************************************************************************************************************************** -->
        <!--sidebar start-->
        <?php
        include_once "../../componentes/menu_dinamico.php";
        ?>
        <!--sidebar end-->
        <!-- **********************************************************************************************************************************************************
                    MAIN CONTENT
                    *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper site-min-height">
                <h3 style="margin-bottom: 20px;">Acesso dos Correspondentes</h3>
                <div class="row mt">
                    <div class="col-md-12 mt">
                        <div class="content-panel">
                            <td>
                                <div style="display: inline-block; margin-right: 10px; margin-left: 10px">
                                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Pesquisar">
                                    </div>
                                    <div style="display: inline-block; margin-right: 10px;">
                                        <button id="searchButton" class="btn btn-sm btn-secondary btn-localizar">
                                            <i class="fas fa-search"></i> 
                                        </button>
                                    </div>
                                    <div style="float: right; width: auto; margin-right: 10px;">
                                        <button class="btn btn-sm btn-primary btn-adicionar">Adicionar</button>
                                    </div>
                            </td>
                            <table class="table table-bordered table-striped" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>Gerencia</th>
                                        <th>Matrícula</th>
                                        <th>Empresa</th>
                                        <th>Nome Prestador</th>
                                        <th>Senha</th>
                                        <th>Email</th>
                                        <th>Senha Expiração</th>
                                        <th>Conta Expiração</th>
                                        <th>Situação</th>
                                        <th>Status Alteração</th>
                                        <th>Data de Atualização</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * 
                                        FROM tbl_ficticia
                                        ORDER BY 
                                            nome_prestador";

                                    $result = BancoDa::Selectfic($sql);

                                    foreach ($result as $row) {
                                        echo "<tr>";
                                        echo "<td>{$row['gerencia']}</td>";
                                        echo "<td>{$row['matricula']}</td>";
                                        echo "<td>{$row['empresa']}</td>";
                                        echo "<td>{$row['nome_prestador']}</td>";
                                        echo "<td>{$row['senha']}</td>";
                                        echo "<td>{$row['email']}</td>";
                                        echo "<td>{$row['dt_senha_expiracao']}</td>";
                                        echo "<td>{$row['dt_conta_expiracao']}</td>";
                                        echo "<td>{$row['situacao']}</td>";
                                        echo "<td>{$row['status_alteracao']}</td>";
                                        echo "<td>{$row['dt_atualizacao']}</td>";
                                        echo "<td>
                                                    <div class='btn-group' role='group' aria-label='Ações'>
                                                        <button class='btn btn-sm btn-primary btn-edit'>Editar</button>                                                        
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
                                            <h5 class="modal-title" id="editModalLabel">Editar Usuário</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Campos para edição -->
                                            <div class="form-group">
                                                <label for="editmatricula">Matrícula</label>
                                                <input type="text" class="form-control" id="editmatricula">
                                            </div>
                                            <div class="form-group">
                                                <label for="editnome">Nome do Usuário</label>
                                                <input type="text" class="form-control" id="editnome">
                                            </div>
                                            <div class="form-group">
                                                <label for="editsenha">Senha</label>
                                                <input type="text" class="form-control" id="editsenha">
                                            </div>
                                            <div class="form-group">
                                                <label for="editemail">Email</label>
                                                <input type="text" class="form-control" id="editemail">
                                            </div>
                                            <div class="form-group">
                                                <label for="editsituacao">Situação</label>
                                                <select id="editsituacao" class="form-control">
                                                    <option value="0" hidden>Altere a situação do usuário</option>
                                                    <option value="ATIVO" selected>ATIVO</option>
                                                    <option value="DESATIVADO">DESATIVADO</option>
                                                </select>
                                            </div>

                                            <!-- Adicione mais campos conforme necessário -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-primary" id="saveChanges">Salvar alterações</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Incluir -->
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
                                            <p><b>Verifique se o Usuário já existe antes de incluir!</b></p>
                                            <div class="form-group">
                                                <label for="adicionargeren">Gerência</label>
                                                <input type="text" class="form-control" id="adicionargeren">
                                            </div>
                                            <div class="form-group">
                                                <label for="adicionarmatricula">Matrícula</label>
                                                <input type="text" class="form-control" id="adicionarmatricula">
                                            </div>
                                            <div class="form-group">
                                                <label for="adicionarempresa">Empresa</label>
                                                <input type="text" class="form-control" id="adicionarempresa">
                                            </div>
                                            <div class="form-group">
                                                <label for="adicionarnome">Nome do Prestador</label>
                                                <input type="text" class="form-control" id="adicionarnome">
                                            </div>
                                            <div class="form-group">
                                                <label for="adicionarsenha">Senha</label>
                                                <input type="text" class="form-control" id="adicionarsenha">
                                            </div>
                                            <div class="form-group">
                                                <label for="adicionaremail">Email</label>
                                                <input type="text" class="form-control" id="adicionaremail">
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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.btn-edit').click(function() {
                    let tr = $(this).closest('tr');

                    // Preenche os campos do modal com os dados da linha selecionada
                    $('#editmatricula').val(tr.find('td:eq(1)').text());
                    $('#editnome').val(tr.find('td:eq(3)').text());
                    $('#editsenha').val(tr.find('td:eq(4)').text());
                    $('#editemail').val(tr.find('td:eq(5)').text());
                    $('#editsituacao').val(tr.find('td:eq(7)').text());

                    // Mostra o modal
                    $('#editModal').modal('show');
                });

                $('#saveChanges').click(function() {
                    let matricula = $('#editmatricula').val();
                    let nome = $('#editnome').val();
                    let senha = $('#editsenha').val();
                    let email = $('#editemail').val();
                    let situacao = $('#editsituacao').val();

                    // Verifique se os campos não estão vazios
                    if (!matricula || !nome || !senha || !situacao || !email) {
                        alert('Todos os campos devem ser preenchidos.');
                        return;
                    }
                    // Exemplo: faça uma chamada AJAX para salvar as alterações
                    $.ajax({
                        type: 'POST',
                        url: 'acesso.php',
                        data: {
                            action: 'update',
                            matricula: matricula,
                            nome: nome,
                            senha: senha,
                            email: email,
                            situacao: situacao
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Usuário editado com sucesso!",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: "Erro ao editar!",
                                text: "Ocorreu um erro ao tentar editar usuário.",
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
                    let gerencia = $('#adicionargeren').val();
                    let matricula = $('#adicionarmatricula').val();
                    let empresa = $('#adicionarempresa').val();
                    let nome_prestador = $('#adicionarnome').val();
                    let senha = $('#adicionarsenha').val();
                    let email = $('#adicionaremail').val();

                    if (!gerencia || !matricula || !empresa || !nome_prestador || !senha || !email) {
                        alert('Todos os campos devem ser preenchidos.');
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: 'acesso.php',
                        data: {
                            action: 'incluir',
                            gerencia: gerencia,
                            matricula: matricula,
                            empresa: empresa,
                            nome_prestador: nome_prestador,
                            senha: senha,
                            email: email

                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Usuário incluído com sucesso!",
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
                    $('#editModal').modal('hide');
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

        <!-- importação de bibliotecas js -->
        <script src="../../lib/jquery/jquery.min.js"></script>
        <script src="../../lib/bootstrap/js/bootstrap.min.js"></script>
        <script class="include" type="text/javascript" src="../../lib/jquery.dcjqaccordion.2.7.js"></script>
        <script src="../../lib/jquery.scrollTo.min.js"></script>
        <script src="../../lib/jquery.nicescroll.js" type="text/javascript"></script>
        <script src="../../lib/jquery.sparkline.js"></script>

        <!--importação de scripts de todas as paginas-->
        <script src="../../lib/common-scripts.js"></script>
        <script type="text/javascript" src="../../lib/gritter/js/jquery.gritter.js"></script>
        <script type="text/javascript" src="../../lib/gritter-conf.js"></script>

        <!-- importação de script para esta página-->
        <script type="text/javascript" src="../../lib/gened/sidebar.js"></script>
        <script src="../../lib/sparkline-chart.js"></script>

</body>

</html>
