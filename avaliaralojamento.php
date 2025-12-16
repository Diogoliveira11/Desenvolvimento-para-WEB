<?php
session_start();
require 'dbconnection.php'; 

// 1. VERIFICAÇÃO DE LOGIN E OBTENÇÃO DE DADOS
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    if (isset($_GET['modal'])) { 
        echo '<p class="text-red-500 p-4">Sessão expirada. Por favor, <a href="auth/login.php" class="underline">faça login</a> novamente.</p>';
        exit;
    }
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: auth/login.php'); 
    exit;
}

$alojamento_id = isset($_GET['id_alojamento']) ? mysqli_real_escape_string($link, $_GET['id_alojamento']) : null;
$reserva_id = isset($_GET['id_reserva']) ? mysqli_real_escape_string($link, $_GET['id_reserva']) : null; 
$user_id = $_SESSION['id_user']; 
$is_modal = isset($_GET['modal']) && $_GET['modal'] === 'true'; 

// Verificação de IDs e Lógica de Avaliação 
$erro_fatal = false;
$nome_alojamento = "Alojamento Desconhecido";

if (empty($alojamento_id) || empty($reserva_id)) {
    $erro_fatal = "Erro: IDs de Alojamento e/ou Reserva não fornecidos para avaliação.";
} else {
    // 2. OBTENÇÃO DO NOME DO ALOJAMENTO
    $query_nome = "SELECT nome FROM alojamento WHERE id_alojamento = $alojamento_id";
    $result_nome = mysqli_query($link, $query_nome);
    
    if (!$result_nome || mysqli_num_rows($result_nome) === 0) {
        $erro_fatal = "Alojamento não encontrado.";
    } else {
        $alojamento = mysqli_fetch_assoc($result_nome);
        $nome_alojamento = htmlspecialchars($alojamento['nome']);
    }
}

if ($erro_fatal) {
    if ($is_modal) {
        echo '<p class="text-red-600 font-semibold p-4">' . $erro_fatal . '</p>';
        exit;
    }
}

if (!$is_modal) : 
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVALIAR ALOJAMENTO | ESPAÇO LUSITANO</title>
    <link rel="icon" type="image/png" href="imagens/FAVICON.ico">
    <link href="css/nav.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'cor-principal': '#565656', 
              'cor-secundaria': '#c8c8b2',
            }
          }
        }
      }
    </script>
</head>

<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    <?php include 'includes/header.php'; ?>
    
    <nav class="bg-cor-secundaria h-2"></nav>

    <main class="flex-grow w-full flex items-center justify-center p-4 py-10">
        
        <?php if ($erro_fatal): ?>
            <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-xl border border-red-200 text-center">
                <h1 class="text-3xl font-bold text-red-600 mb-4">Acesso Bloqueado</h1>
                <p class="text-gray-700"><?php echo $erro_fatal; ?></p>
                <div class="mt-6">
                    <a href="index.php" class="inline-block py-2 px-4 bg-cor-secundaria text-cor-principal font-bold rounded-lg hover:bg-cor-principal hover:text-white transition">Voltar à Home</a>
                </div>
            </div>
        <?php else: ?>
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-xl border border-gray-200">
                
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-cor-principal border-b border-gray-200 pb-2">Avaliar Alojamento</h1>
                    <h2 class="text-xl text-gray-500 mt-2"><?php echo $nome_alojamento; ?></h2>
                </div>
<?php endif; ?>
<?php endif; ?>


<?php if (!$erro_fatal) :  ?>

<div class="space-y-4">
    <p class="mb-4 text-gray-700 border-l-4 border-cor-secundaria pl-3 italic bg-[#f7f7f2] p-2 rounded-md">
        Partilhe a sua experiência e ajude outros viajantes a escolherem o seu próximo destino.
    </p>

    <form id="avaliacao-form-quill" action="processaavaliacao.php" method="POST" class="space-y-4"> 
        <input type="hidden" name="id_alojamento" value="<?php echo $alojamento_id; ?>">
        <input type="hidden" name="id_reserva" value="<?php echo $reserva_id; ?>"> 
        <input type="hidden" name="id_user" value="<?php echo $user_id; ?>">
        <input type="hidden" name="nome_alojamento_display" value="<?php echo $nome_alojamento; ?>">
        
        <input type="hidden" name="comentario" id="comentario-hidden-input">

        <div>
            <label for="avaliacao" class="block text-lg font-semibold text-gray-900 mb-2">
                Nota de satisfação (1 a 10):
            </label>
            <input type="number" name="avaliacao" id="avaliacao" min="1" max="10" required
                    class="w-full p-3 border border-cor-secundaria rounded-lg shadow-sm 
                                 focus:ring-2 focus:ring-cor-principal focus:border-cor-principal transition-colors">
        </div>

        <div>
            <label class="block text-lg font-semibold text-gray-900 mb-2">
                O seu comentário:
            </label>
            <div id="editor-container" style="height: 150px;" class="border border-cor-secundaria rounded-lg shadow-sm"></div>
        </div>

        <div>
            <button type="submit" 
                    class="w-full py-3 bg-cor-secundaria text-cor-principal font-bold rounded-xl 
                                 hover:bg-cor-principal hover:text-white transition-colors shadow-lg text-xl">
                Enviar a minha avaliação
            </button>
        </div>
    </form>
</div>

<?php endif;  ?>


<?php if (!$is_modal) :  ?>

            <?php if (!$erro_fatal): ?>
            </div> <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('editor-container')) {
                const quill = new Quill('#editor-container', {
                    theme: 'snow',
                    placeholder: 'Descreva a sua experiência aqui...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'], 
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['clean'] 
                        ]
                    }
                });

                const form = document.getElementById('avaliacao-form-quill');
                const hiddenInput = document.getElementById('comentario-hidden-input');

                if (form) {
                    form.addEventListener('submit', function(e) {
                        const content = quill.root.innerHTML;
                        const isContentEmpty = quill.getText().trim().length === 0;
                        const avaliacao = document.getElementById('avaliacao').value;
                        
                        if (isContentEmpty || !avaliacao || avaliacao < 1 || avaliacao > 10) {
                            alert('A nota e o comentário são obrigatórios.');
                            e.preventDefault(); 
                        } else {
                            hiddenInput.value = content;
                        }
                    });
                }
            }
        });
    </script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
<?php endif;  ?>