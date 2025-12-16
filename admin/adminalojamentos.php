<?php

session_start();
require '../dbconnection.php'; 

// Configuração das Colunas da Base de Dados
$tabela_alojamentos = "alojamento"; 
$coluna_id = "id_alojamento";
$coluna_titulo = "nome"; 
$coluna_preco = "preco_base"; 
$coluna_visibilidade = "estado"; 

$tabela_imagem = "imagens"; 
$coluna_id_imagem = "id_imagem";
$coluna_caminho_imagem = "caminho_ficheiro";
$coluna_principal = "imagem_principal"; 
$upload_dir = "../imagens/"; 

$alojamentos = [];
$num_alojamentos = 0;
$error_message = null;
$success_message = null;
$homeLink = "admin.php"; 


// 1. LÓGICA DE PROCESSAMENTO (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    // INSERIR NOVO ALOJAMENTO
    if ($acao === 'inserir') {
        $nome = $_POST['nome'] ?? '';
        $regiao = $_POST['regiao'] ?? '';
        $localizacao = $_POST['localizacao'] ?? '';
        $ponto_forte = $_POST['ponto_forte'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $preco_base = (int)($_POST['preco_base'] ?? 0);
        $preco_final = !empty($_POST['preco_final']) ? (int)$_POST['preco_final'] : 0;
        $comodidades = $_POST['comodidades'] ?? '';
        $check_in_out = $_POST['check_in_out'] ?? '00:00/00:00';
        $contacto = !empty($_POST['contacto']) ? (int)$_POST['contacto'] : 0;
        $disponibilidade = (int)($_POST['disponibilidade'] ?? 1);
        $estado = (int)($_POST['estado'] ?? 1);

        $query_insert = "INSERT INTO $tabela_alojamentos 
            (nome, regiao, localizacao, ponto_forte, descricao, preco_base, preco_final, comodidades, check_in_out, contacto, disponibilidade, $coluna_visibilidade) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($link, $query_insert);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sssssiisssii', 
                $nome, $regiao, $localizacao, $ponto_forte, $descricao, 
                $preco_base, $preco_final, $comodidades, $check_in_out, 
                $contacto, $disponibilidade, $estado
            );

            if (mysqli_stmt_execute($stmt)) {
                $novo_alojamento_id = mysqli_insert_id($link); // Obtém o ID gerado
                $success_message = "Novo alojamento inserido com sucesso!";

                // PROCESSAR UPLOAD DE IMAGENS NA INSERÇÃO
                if (isset($_FILES['fotos_inserir']) && !empty($_FILES['fotos_inserir']['name'][0])) {
                    $fotos = $_FILES['fotos_inserir'];
                    foreach ($fotos['name'] as $i => $name) {
                        if ($fotos['error'][$i] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                                $novoNome = "alojamento_" . $novo_alojamento_id . "_" . time() . "_" . rand(100, 999) . "." . $ext;
                                if (move_uploaded_file($fotos['tmp_name'][$i], $upload_dir . $novoNome)) {
                                    $caminhoBD = "imagens/" . $novoNome;
                                    $principal = ($i === 0) ? 1 : 0; // Define a primeira foto como principal automaticamente
                                    
                                    $q_img = "INSERT INTO $tabela_imagem (id_alojamento, caminho_ficheiro, imagem_principal, estado) VALUES (?, ?, ?, 1)";
                                    $st_img = mysqli_prepare($link, $q_img);
                                    mysqli_stmt_bind_param($st_img, 'isi', $novo_alojamento_id, $caminhoBD, $principal);
                                    mysqli_stmt_execute($st_img);
                                    mysqli_stmt_close($st_img);
                                }
                            }
                        }
                    }
                }
            } else {
                $error_message = "Erro ao inserir: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        }
    }

    // TOGGLE ESTADO (Ocultar/Mostrar)
    if ($acao === 'toggle_estado') {
        $idToggle = isset($_POST['alojamento_id']) ? (int)$_POST['alojamento_id'] : 0;
        $novoEstado = isset($_POST['novo_estado']) ? (int)$_POST['novo_estado'] : -1;
        
        if ($idToggle > 0 && ($novoEstado === 0 || $novoEstado === 1)) {
            $queryToggle = "UPDATE $tabela_alojamentos SET $coluna_visibilidade = ? WHERE $coluna_id = ?";
            $stmt = mysqli_prepare($link, $queryToggle);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ii', $novoEstado, $idToggle);
                if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
                    $acaoTexto = ($novoEstado == 1) ? 'Visível' : 'Oculto';
                    //$success_message = "Alojamento ID: #{$idToggle} atualizado para {$acaoTexto} com sucesso!";
                } else {
                    //$error_message = "Nenhuma alteração efetuada no estado ou ID #{$idToggle} não encontrado.";
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_message = "Erro ao preparar a query de atualização de estado.";
            }
        } else {
            $error_message = "Dados de estado inválidos.";
        }
    }

    if ($acao === 'editar') {
        $alojamento_id = isset($_POST['id_alojamento']) ? (int)$_POST['id_alojamento'] : 0;
        
        if ($alojamento_id > 0) {
            
            // 1. Atualizar Dados Principais do Alojamento
            $nome = $_POST['nome'] ?? '';
            $regiao = $_POST['regiao'] ?? '';
            $localizacao = $_POST['localizacao'] ?? '';
            $ponto_forte = $_POST['ponto_forte'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $preco_base = $_POST['preco_base'] ?? 0;
            $preco_final = $_POST['preco_final'] ?? null;
            $comodidades = $_POST['comodidades'] ?? '';
            $check_in_out = $_POST['check_in_out'] ?? '00:00/00:00';
            $contacto = $_POST['contacto'] ?? null;
            $disponibilidade = $_POST['disponibilidade'] ?? 1;

            $query_update = "UPDATE $tabela_alojamentos SET 
                                nome=?, regiao=?, localizacao=?, ponto_forte=?, descricao=?, 
                                preco_base=?, preco_final=?, comodidades=?, check_in_out=?, 
                                contacto=?, disponibilidade=? 
                            WHERE $coluna_id = ?";

            $stmt = mysqli_prepare($link, $query_update);

            $preco_final = ($preco_final === null || $preco_final === '') ? 0 : (int)$preco_final;
            $contacto = ($contacto === null || $contacto === '') ? 0 : (int)$contacto;
            

            mysqli_stmt_bind_param($stmt, 'sssssiisssii', 
                $nome, $regiao, $localizacao, $ponto_forte, $descricao, 
                $preco_base, $preco_final, $comodidades, $check_in_out, 
                $contacto, $disponibilidade, $alojamento_id
            );

            if (!mysqli_stmt_execute($stmt)) {
                $error_message = "Erro ao atualizar dados principais: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);

            // 2. Processar Imagens
            // 2.1 Lógica de Ocultar/Mostrar
            $ocultar_imagens_marcadas = $_POST['remover_imagens'] ?? []; 
            
            // 2.1.1 Reativar TUDO (estado=1) para o alojamento atual
            $query_reset_visibilidade = "UPDATE $tabela_imagem SET estado = 1 WHERE id_alojamento = ?";
            $stmt_reset_visibilidade = mysqli_prepare($link, $query_reset_visibilidade);
            mysqli_stmt_bind_param($stmt_reset_visibilidade, 'i', $alojamento_id);
            mysqli_stmt_execute($stmt_reset_visibilidade);
            mysqli_stmt_close($stmt_reset_visibilidade);

            // 2.1.2 Aplicar OCULTAR (estado=0) APENAS nas imagens marcadas no POST
            if (!empty($ocultar_imagens_marcadas)) {
                $ids_marcados = array_map('intval', $ocultar_imagens_marcadas);
                $placeholders = implode(',', array_fill(0, count($ids_marcados), '?'));
                $types = str_repeat('i', count($ids_marcados));

                $query_ocultar_final = "UPDATE $tabela_imagem SET estado = 0 WHERE $coluna_id_imagem IN ($placeholders)";
                $stmt_ocultar_final = mysqli_prepare($link, $query_ocultar_final);
                
                if ($stmt_ocultar_final) {
                    mysqli_stmt_bind_param($stmt_ocultar_final, $types, ...$ids_marcados);
                    if (!mysqli_stmt_execute($stmt_ocultar_final)) {
                        $error_message .= " Erro ao ocultar imagens: " . mysqli_error($link);
                    }
                    mysqli_stmt_close($stmt_ocultar_final);
                }
            }
            
            // 2.2 Adicionar Novas Imagens
            $novas_imagens = $_FILES['novas_imagens'] ?? [];
            $is_first_new_image = true; 

            if (isset($novas_imagens['name']) && !empty($novas_imagens['name'][0])) {
                // Verificar se já existe uma imagem principal (existente ou a ser definida agora)
                $query_check_main = "SELECT $coluna_id_imagem FROM $tabela_imagem WHERE $coluna_id = ? AND $coluna_principal = 1 LIMIT 1";
                $stmt_check = mysqli_prepare($link, $query_check_main);
                mysqli_stmt_bind_param($stmt_check, 'i', $alojamento_id);
                mysqli_stmt_execute($stmt_check);
                $result_check = mysqli_stmt_get_result($stmt_check);
                $has_existing_main = (mysqli_num_rows($result_check) > 0 || isset($_POST['imagem_principal_id']));
                mysqli_stmt_close($stmt_check);

                foreach ($novas_imagens['name'] as $index => $name) {
                    if ($novas_imagens['error'][$index] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                        if (in_array($ext, $permitidos)) {
                            $novoNome = "alojamento_" . $alojamento_id . "_" . time() . "_" . rand(100, 999) . "." . $ext;
                            $destino = $upload_dir . $novoNome;
                            $caminhoBD = "imagens/" . $novoNome; 

                            if (move_uploaded_file($novas_imagens['tmp_name'][$index], $destino)) {
                                $imagem_principal_flag = 0;
                                $estado_flag = 1; 
                                
                                if (!$has_existing_main && $is_first_new_image) {
                                    $imagem_principal_flag = 1;
                                    $is_first_new_image = false;
                                }

                                $query_insert = "INSERT INTO $tabela_imagem ($coluna_id, $coluna_caminho_imagem, $coluna_principal, estado) VALUES (?, ?, ?, ?)";
                                $stmt_insert = mysqli_prepare($link, $query_insert);
                                // PARÂMETROS: alojamento_id (i), caminhoBD (s), principal_flag (i), estado_flag (i)
                                mysqli_stmt_bind_param($stmt_insert, 'isii', $alojamento_id, $caminhoBD, $imagem_principal_flag, $estado_flag);
                                if (!mysqli_stmt_execute($stmt_insert)) {
                                    $error_message .= " Erro ao inserir nova imagem: " . $name . ".";
                                }
                                mysqli_stmt_close($stmt_insert);
                            } else {
                                $error_message .= " Falha ao mover o ficheiro de imagem: " . $name . ".";
                            }
                        } else {
                            $error_message .= " Formato de imagem inválido para: " . $name . ".";
                        }
                    }
                }
            }

            // 2.3 Definir Imagem Principal
            $nova_principal_id = isset($_POST['imagem_principal_id']) ? (int)$_POST['imagem_principal_id'] : 0;
            if ($nova_principal_id > 0) {
                // 1. Resetar todas as flags 'principal' para 0
                $query_reset = "UPDATE $tabela_imagem SET $coluna_principal = 0 WHERE $coluna_id = ?";
                $stmt_reset = mysqli_prepare($link, $query_reset);
                mysqli_stmt_bind_param($stmt_reset, 'i', $alojamento_id);
                mysqli_stmt_execute($stmt_reset);
                mysqli_stmt_close($stmt_reset);

                // 2. Definir a nova flag 'principal' para 1
                $query_set = "UPDATE $tabela_imagem SET $coluna_principal = 1 WHERE $coluna_id_imagem = ? AND $coluna_id = ?";
                $stmt_set = mysqli_prepare($link, $query_set);
                mysqli_stmt_bind_param($stmt_set, 'ii', $nova_principal_id, $alojamento_id);
                mysqli_stmt_execute($stmt_set);
                mysqli_stmt_close($stmt_set);
            }

            if (!$error_message) {
                 $success_message = "Alojamento e gestão de imagens atualizados com sucesso!";
            } else {
                 $error_message = "A atualização terminou com erros: " . $error_message;
            }
        } else {
            $error_message = "ID de alojamento inválido para edição.";
        }
    }
} 

// Função para buscar o caminho da imagem principal
function get_imagem_principal($link, $alojamento_id) {
    global $tabela_imagem, $coluna_caminho_imagem, $coluna_principal;
    
    $safe_id = mysqli_real_escape_string($link, $alojamento_id);
    $img_query = "SELECT $coluna_caminho_imagem FROM $tabela_imagem 
                  WHERE id_alojamento = '$safe_id' AND $coluna_principal = 1 LIMIT 1";
    
    $img_result = mysqli_query($link, $img_query);

    if ($img_result && mysqli_num_rows($img_result) > 0) {
        $img_data = mysqli_fetch_assoc($img_result);
        mysqli_free_result($img_result);
        return "../" . $img_data[$coluna_caminho_imagem]; 
    }
    return "../imagens/placeholder.png";
}


// 3. BUSCAR DADOS (Refrescados após qualquer POST)
if (isset($link) && $link) { 
    $query = "SELECT * FROM $tabela_alojamentos ORDER BY $coluna_id DESC";
    $result = mysqli_query($link, $query);
    
    if ($result) {
        $alojamentos = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $num_alojamentos = count($alojamentos);
        mysqli_free_result($result);
    } else {
        $error_message = "Erro ao carregar alojamentos (MySQLi): " . mysqli_error($link);
    }
} else {
    $error_message = "A conexão à base de dados (\$link) não foi estabelecida.";
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GERIR ALOJAMENTOS | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="../imagens/FAVICON.ico">
    <script src="https://cdn.tailwindcss.com"></script> 
    <style>
        .bg-brand-dark { background-color: #565656; }
        .text-brand-dark { color: #565656; }
        .border-brand-light { border-color: #c8c8b2; }
        .hover-brand-dark:hover { background-color: #3a3a3a; }
        .img-thumb {
            width: 80px; 
            height: 80px; 
            object-fit: cover;
            border-radius: 4px;
        }
        .img-thumb-preview { width: 100px; height: 100px; object-fit: cover; }
    </style>
</head>

<header class="w-full py-3 relative bg-[#e5e5dd] header-compact">
    <div class="flex items-center justify-between px-2 sm:px-3 lg:px-5 xl:px-6">

        <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 flex-shrink-0 logo-title">
            <a href="<?php echo $homeLink; ?>"> 
                <img src="../imagens/LOGO MAIOR.png" alt="Logo" class="w-12 sm:w-14 md:w-16 lg:w-20 h-auto">
            </a>
            <div class="flex flex-col">
                <h1 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-4xl font-bold text-brand-dark">
                    Gestão de Alojamentos
                </h1>
            </div>
        </div>
    </div>
</header> 

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    <nav class="h-2 bg-[#c8c8b2]"></nav>

<main class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 mt-10 flex-grow mb-12">
    <div class="mx-auto max-w-7xl">
        
        <div class="flex justify-between items-center mb-6 border-b-2 border-brand-light pb-2">
            <h2 class="text-3xl font-bold text-brand-dark">
                Catálogo de alojamentos (<span id="total-count"><?php echo $num_alojamentos; ?></span>)
            </h2>
            <button onclick="openInsertModal()" class="flex items-center justify-center bg-brand-dark hover:bg-brand-darker text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Inserir novo alojamento
            </button>
        </div>

        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erro de Base de Dados!</strong>
                <span class="block sm:inline"><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline"><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($num_alojamentos > 0): ?>
        
        <div class="overflow-x-auto bg-white shadow-2xl rounded-xl border border-brand-light">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#e5e5dd]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-brand-dark uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-brand-dark uppercase tracking-wider">Imagem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-brand-dark uppercase tracking-wider">Título do Alojamento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-brand-dark uppercase tracking-wider">Preço (€/Noite)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-brand-dark uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-brand-dark uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <?php foreach ($alojamentos as $alojamento): 
                        $image_path = get_imagem_principal($link, $alojamento[$coluna_id]);
                        $is_visible = ($alojamento[$coluna_visibilidade] == 1); 
                        $status_text = $is_visible ? 'Visível' : 'Oculto';
                        $status_color = $is_visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    ?>
                    <tr id="alojamento-<?php echo $alojamento[$coluna_id]; ?>" class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($alojamento[$coluna_id]); ?></td>
                        
                        <td class="px-6 py-4 whitespace-nowrap"> 
                            <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                 alt="Imagem Principal" 
                                 class="img-thumb shadow-md" 
                                 onerror="this.onerror=null;this.src='../imagens/placeholder.png';">
                        </td>
                        
                        <td class="px-6 py-4 text-sm text-gray-700 font-semibold"><?php echo htmlspecialchars($alojamento[$coluna_titulo]); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?php 
                                if (isset($alojamento[$coluna_preco])) {
                                    echo number_format($alojamento[$coluna_preco], 2, ',', '.') . ' €';
                                } else {
                                    echo 'N/A';
                                }
                            ?>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_color; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                            <button type="button"
                                    data-alojamento='<?php echo htmlspecialchars(json_encode($alojamento), ENT_QUOTES, 'UTF-8'); ?>'
                                    onclick="openEditModal(this)"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-200 text-gray-600 shadow-sm hover:bg-brand-dark hover:text-gray hover:border-brand-dark transition-all duration-200 group"
                                    title="Editar Alojamento">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            
                            <button type="button" 
                                    onclick="promptToggleStatus(<?php echo $alojamento[$coluna_id]; ?>, '<?php echo htmlspecialchars($alojamento[$coluna_titulo]); ?>', <?php echo $is_visible ? 0 : 1; ?>, '<?php echo $is_visible ? 'ocultar' : 'mostrar'; ?>')"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg border transition-all duration-200 <?php echo $is_visible ? 'border-red-100 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white' : 'border-green-100 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white'; ?>"
                                    title="<?php echo $is_visible ? 'Ocultar Alojamento' : 'Tornar Visível'; ?>">
                                
                                <?php if ($is_visible): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                <?php endif; ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                </tbody>
            </table>
        </div>
        
        <?php else: ?>
            <p class="text-gray-600 p-6 bg-white rounded-lg shadow-md border-t-4 border-brand-dark">Não foram encontrados alojamentos na base de dados.</p>
        <?php endif; ?>

    </div>
</main>

<div id="sucesso-popup" class="fixed bottom-5 right-5 hidden items-center justify-center p-4 rounded-lg bg-green-500 text-white shadow-xl transition-opacity duration-300 opacity-0 z-50">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    <span class="font-semibold text-sm">Ação concluída com sucesso.</span>
</div>

<div id="insert-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-3xl transform transition-transform duration-300 scale-95 overflow-y-auto max-h-[90vh]">  
    <h3 class="text-3xl font-bold mb-6 text-brand-dark border-b pb-2 flex items-center">
            Inserir novo alojamento
        </h3>
        
        <form id="insert-form" method="POST" action="adminalojamentos.php" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="inserir">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-4">
                    
                    <h4 class="text-lg font-semibold text-brand-dark border-b-2 border-brand-light pb-1">Detalhes Principais</h4>
                    
                    <div class="form-group">
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">1. Título do Alojamento *</label>
                        <input type="text" id="nome" name="nome" required maxlength="50"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                    <div class="form-group">
                        <label for="localizacao" class="block text-sm font-medium text-gray-700 mb-1">2. Localização (Cidade/Vila) *</label>
                        <input type="text" id="localizacao" name="localizacao" required maxlength="30"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                    <div class="form-group">
                        <label for="regiao" class="block text-sm font-medium text-gray-700 mb-1">3. Região/Distrito</label>
                        <input type="text" id="regiao" name="regiao" maxlength="30"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>
                    
                    <div class="form-group">
                        <label for="ponto_forte" class="block text-sm font-medium text-gray-700 mb-1">4. Ponto forte</label>
                        <input type="text" id="ponto_forte" name="ponto_forte" maxlength="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                    <div class="form-group">
                        <label for="check_in_out" class="block text-sm font-medium text-gray-700 mb-1">5. Horário Check-in/out (Ex: 15:00/11:00)</label>
                        <input type="text" id="check_in_out" name="check_in_out" maxlength="15" value="00:00/00:00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                </div>

                <div class="space-y-4">
                    
                    <h4 class="text-lg font-semibold text-brand-dark border-b-2 border-brand-light pb-1">Gestão e Valores</h4>
                    
                    <div class="form-group">
                        <label for="preco_base" class="block text-sm font-medium text-gray-700 mb-1">6. Preço Base (€/Noite) *</label>
                        <input type="number" id="preco_base" name="preco_base" required step="1" min="0" value="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                    <div class="form-group">
                        <label for="preco_final" class="block text-sm font-medium text-gray-700 mb-1">7. Preço Final (Opcional)</label>
                        <input type="number" id="preco_final" name="preco_final" step="1" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                    <div class="form-group">
                        <label for="contacto" class="block text-sm font-medium text-gray-700 mb-1">8. Contacto</label>
                        <input type="number" id="contacto" name="contacto" maxlength="11" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>

                    <div class="form-group">
                        <label for="disponibilidade" class="block text-sm font-medium text-gray-700 mb-1">9. Quartos/Unidades Disponíveis *</label>
                        <input type="number" id="disponibilidade" name="disponibilidade" required min="1" value="1" maxlength="3"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">10. Visibilidade (Estado)</label>
                        <select id="estado" name="estado" 
                                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark">
                            <option value="1" selected>1 - Visível (Ativo)</option>
                            <option value="0">0 - Oculto (Inativo)</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="mt-6 border-t pt-4">
                <h4 class="text-lg font-semibold text-brand-dark mb-2">Imagens do Alojamento</h4>
                <div class="form-group">
                    <label for="fotos_inserir" class="block text-sm font-medium text-gray-700 mb-1">Selecionar Fotos (A primeira será a principal)</label>
                    <input type="file" id="fotos_inserir" name="fotos_inserir[]" multiple accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="form-group">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">11. Descrição (Texto Livre)</label>
                    <textarea id="descricao" name="descricao" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="comodidades" class="block text-sm font-medium text-gray-700 mb-1">12. Comodidades (Separadas por vírgulas, Texto Livre)</label>
                    <textarea id="comodidades" name="comodidades" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-8 pt-4 border-t">
                <button type="button" onclick="closeInsertModal()" class="px-5 py-2 text-brand-dark bg-gray-200 hover:bg-gray-300 font-semibold rounded-lg transition duration-150">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 text-white bg-brand-dark hover:bg-brand-dark font-bold rounded-lg transition duration-150">
                    Guardar e Publicar
                </button>
            </div>
        </form>
    </div>
</div>

<div id="toggle-confirm-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-transform duration-300 scale-95">
        
        <form id="toggle-status-form" method="POST" action="adminalojamentos.php">
            <input type="hidden" name="acao" value="toggle_estado">
            <input type="hidden" name="alojamento_id" id="toggle-alojamento-id">
            <input type="hidden" name="novo_estado" id="toggle-novo-estado">

            <h3 id="toggle-modal-title" class="text-2xl font-bold mb-4 text-brand-dark">Atenção!</h3>
            <p id="toggle-modal-message" class="text-gray-700 mb-6"></p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeToggleConfirmModal()" class="px-5 py-2 text-brand-dark bg-gray-200 hover:bg-gray-300 font-semibold rounded-lg transition duration-150">
                    Cancelar
                </button>
                <button type="submit" id="toggle-modal-confirm-btn" class="px-5 py-2 text-white bg-red-600 hover:bg-red-700 font-bold rounded-lg transition duration-150">
                    Confirmar Ação
                </button>
            </div>
        </form>

    </div>
</div>

<div id="edit-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-4xl transform transition-transform duration-300 scale-95 overflow-y-auto max-h-[95vh]">
        <h3 class="text-3xl font-bold mb-6 text-brand-dark border-b  pb-2 flex items-center">
            Editar Alojamento 
            <span id="edit-alojamento-id-display" class="text-base text-gray-500 ml-4 font-normal"></span>
        </h3>
        
        <form id="edit-form" method="POST" action="adminalojamentos.php" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id_alojamento" id="edit-id-alojamento">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-brand-dark border-b-2 border-brand-light pb-1">Detalhes Principais</h4>
                    
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                        <input type="text" id="edit-nome" name="nome" required maxlength="50" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Localização *</label>
                        <input type="text" id="edit-localizacao" name="localizacao" required maxlength="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Região/Distrito</label>
                        <input type="text" id="edit-regiao" name="regiao" maxlength="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Ponto forte</label>
                        <input type="text" id="edit-ponto_forte" name="ponto_forte" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Horário Check-in/out</label>
                        <input type="text" id="edit-check_in_out" name="check_in_out" maxlength="15" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Contacto</label>
                        <input type="number" id="edit-contacto" name="contacto" maxlength="11" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-brand-dark border-b-2 border-brand-light pb-1">Gestão e Valores</h4>
                    
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Preço Base (€/Noite) *</label>
                        <input type="number" id="edit-preco_base" name="preco_base" required step="1" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Preço Final (Opcional)</label>
                        <input type="number" id="edit-preco_final" name="preco_final" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Quartos/Unidades Disponíveis *</label>
                        <input type="number" id="edit-disponibilidade" name="disponibilidade" required min="1" maxlength="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea id="edit-descricao" name="descricao" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div class="form-group"><label class="block text-sm font-medium text-gray-700 mb-1">Comodidades</label>
                    <textarea id="edit-comodidades" name="comodidades" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
            </div>

            <div class="mt-6 space-y-4 border-t pt-4">
                <h4 class="text-lg font-semibold text-brand-dark border-b-2 border-brand-light pb-1">Gestão de Imagens</h4>
                
                <div id="edit-current-images" class="flex flex-wrap gap-4 p-4 border rounded-lg bg-gray-50 min-h-24">
                    <p class="text-gray-600 italic">A carregar imagens...</p>
                </div>

                <div class="form-group mt-4">
                    <label for="novas_imagens" class="block text-sm font-bold text-brand-dark mb-1">Adicionar Novas Imagens</label>
                    <input type="file" name="novas_imagens[]" id="novas_imagens" multiple accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                    <p class="text-xs text-gray-500 mt-1">Pode selecionar múltiplos ficheiros.</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-8 pt-4 border-t">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2 text-brand-dark bg-gray-200 hover:bg-gray-300 font-semibold rounded-lg transition duration-150">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 text-white bg-brand-dark font-bold rounded-lg transition duration-150">
                    Guardar todas as alterações
                </button>
            </div>
        </form>
    </div>
</div>

<div id="imagem-ajax-endpoint" data-endpoint="alojamentoimagens.php" class="hidden"></div>

<footer class="mt-2 py-2 sm:py-4 lg:py-6 text-center text-gray-700 bg-[#e5e5dd] mt-auto">
    <p class="mb-0 text-xs sm:text-sm lg:text-base">&copy; <?php echo date('Y'); ?> - ADMINISTRAÇÃO | ESPAÇO LUSITANO</p>
</footer>

<script>
    // VARIÁVEIS GLOBAIS E POPUP
    // Função para mostrar popup (usada apenas para Inserção, o Toggle e Edição usam o PHP após POST)
    function showSuccessPopup(message) {
        const popup = document.getElementById('sucesso-popup');
        const span = popup.querySelector('span');
        span.textContent = message;
        
        popup.classList.remove('hidden', 'opacity-0');
        popup.classList.add('flex', 'opacity-100'); 
        
        setTimeout(() => {
            popup.classList.remove('opacity-100');
            setTimeout(() => popup.classList.add('hidden'), 300);
        }, 3000);
    } 

    // MODAL DE INSERÇÃO
    function openInsertModal() {
        const modal = document.getElementById('insert-modal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('opacity-100'), 10);
    }

    function closeInsertModal() {
        const modal = document.getElementById('insert-modal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('insert-form').reset();
        }, 300);
    }

    // Apenas para inserção
    function handleInsertSubmit(event) {
        event.preventDefault(); 
        const form = document.getElementById('insert-form');
        const formData = new FormData(form); // FormData já lida com ficheiros automaticamente
        const submitButton = form.querySelector('button[type="submit"]');

        const originalText = submitButton.textContent;
        submitButton.textContent = 'A processar ficheiros...';
        submitButton.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData, // Envia o objeto formData completo
        })
        .then(response => response.text()) // Primeiro recebemos como texto para debugar se necessário
        .then(html => {
            closeInsertModal();
            atualizarContador(); // Atualiza o número (id) dinamicamente
            showSuccessPopup("Alojamento inserido!");
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar o formulário.');
        });
    }

    // MODAL DE EDIÇÃO
    function openEditModal(button) {
        const modal = document.getElementById('edit-modal');
        const data = JSON.parse(button.getAttribute('data-alojamento'));
        
        // Popula campos principais
        document.getElementById('edit-id-alojamento').value = data.id_alojamento;
        document.getElementById('edit-alojamento-id-display').textContent = `(#${data.id_alojamento})`;
        document.getElementById('edit-nome').value = data.nome || '';
        document.getElementById('edit-localizacao').value = data.localizacao || '';
        document.getElementById('edit-regiao').value = data.regiao || '';
        document.getElementById('edit-ponto_forte').value = data.ponto_forte || '';
        document.getElementById('edit-check_in_out').value = data.check_in_out || '00:00/00:00';
        document.getElementById('edit-contacto').value = data.contacto || '';
        document.getElementById('edit-preco_base').value = data.preco_base || 0;
        document.getElementById('edit-preco_final').value = data.preco_final || '';
        document.getElementById('edit-disponibilidade').value = data.disponibilidade || 1;
        document.getElementById('edit-descricao').value = data.descricao || '';
        document.getElementById('edit-comodidades').value = data.comodidades || '';
        
        loadAlojamentoImages(data.id_alojamento);

        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('opacity-100'), 10);
    }
    
    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('edit-form').reset();
            document.getElementById('edit-current-images').innerHTML = '<p class="text-gray-600 italic">A carregar imagens...</p>';
        }, 300);
    }
    
    function loadAlojamentoImages(alojamentoId) {
    const imageContainer = document.getElementById('edit-current-images');
    imageContainer.innerHTML = '<p class="text-gray-600 italic">A carregar imagens...</p>';
    
    const endpoint = 'alojamentoimagens.php'; 
    
    fetch(`${endpoint}?id=${alojamentoId}`)
        .then(response => {
            if (!response.ok) { throw new Error('Erro de rede ao buscar imagens.'); }
            return response.json();
        })
        .then(data => {
            imageContainer.innerHTML = '';
            if (data.success && data.images.length > 0) {
                data.images.forEach(img => {
                    const isPrincipal = img.imagem_principal == 1;
                    const isOculta = img.estado == 0;
                    const caminhoRelativo = `../${img.caminho_ficheiro}`;
                    
                    const html = `
                        <div class="relative border-4 p-1 rounded-lg shadow-md 
                            ${isPrincipal ? 'border-blue-500' : (isOculta ? 'border-gray-500 opacity-60' : 'border-gray-300')}">
                            <img src="${caminhoRelativo}" alt="Imagem" class="w-24 h-24 object-cover">
                            
                            <div class="mt-2 text-center space-y-1">
                                <p class="text-xs font-semibold ${isPrincipal ? 'text-blue-600' : (isOculta ? 'text-gray-600' : 'text-green-600')}">
                                    ${isPrincipal ? 'PRINCIPAL' : (isOculta ? 'OCULTA' : 'VISÍVEL')}
                                </p>
                                
                                <label class="flex items-center text-red-600 text-sm cursor-pointer">
                                    <input type="checkbox" name="remover_imagens[]" value="${img.id_imagem}" ${isOculta ? 'checked' : ''} class="mr-1">
                                    ${isOculta ? 'Ocultar' : 'Ocultar'}
                                </label>

                                ${isOculta ? '' : `
                                <label class="flex items-center text-blue-600 text-sm cursor-pointer">
                                    <input type="radio" name="imagem_principal_id" value="${img.id_imagem}" ${isPrincipal ? 'checked' : ''} class="mr-1" ${isOculta ? 'disabled' : ''}>
                                    Tornar Principal
                                </label>`}
                            </div>
                        </div>
                    `;
                    imageContainer.insertAdjacentHTML('beforeend', html);
                });
            } else {
                imageContainer.innerHTML = '<p class="text-gray-600 italic">Nenhuma imagem associada.</p>';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            imageContainer.innerHTML = '<p class="text-red-600 font-bold">Erro ao carregar imagens.</p>';
        });
    }

    // MODAL DE CONFIRMAÇÃO
    function openToggleConfirmModal() {
        const modal = document.getElementById('toggle-confirm-modal');
        modal.classList.remove('hidden');
        modal.classList.add('opacity-100');
    }

    function closeToggleConfirmModal() {
        const modal = document.getElementById('toggle-confirm-modal');
        modal.classList.add('hidden');
        modal.classList.remove('opacity-100');
    }

    // Ocultar/Mostrar
    function promptToggleStatus(alojamentoId, titulo, novoStatus, acaoTexto) {
        const modalTitle = document.getElementById('toggle-modal-title');
        const modalMessage = document.getElementById('toggle-modal-message');
        const confirmBtn = document.getElementById('toggle-modal-confirm-btn');

        // 1. INSERIR VALORES NO FORMULÁRIO
        document.getElementById('toggle-alojamento-id').value = alojamentoId;
        document.getElementById('toggle-novo-estado').value = novoStatus;


        // 2. ATUALIZAR O TEXTO DO MODAL
        modalTitle.textContent = `Confirmação de ação: ${acaoTexto.toUpperCase()}`;
        modalMessage.innerHTML = `Tem certeza que deseja ${acaoTexto} o alojamento: <strong>${titulo}</strong>?`;
        
        confirmBtn.textContent = `Sim, ${acaoTexto}`;
        
        // Ajustar cor do botão no modal
        if (novoStatus === 0) { // Ocultar
             confirmBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
             confirmBtn.classList.add('bg-red-600', 'hover:bg-red-700');
        } else { // Mostrar/Publicar
             confirmBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
             confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
        }

        // 3. Abrir o Modal
        openToggleConfirmModal();
    }

    function atualizarContador() {
    fetch('totalalojamentos.php')
        .then(response => response.json())
        .then(data => {
            const el = document.getElementById('total-count');
            if (el) {
                el.textContent = data.total;
            }
        })
        .catch(error => console.error('Erro ao atualizar contador:', error));
}
</script>

</body>
</html>