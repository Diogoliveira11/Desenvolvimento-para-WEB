<?php
session_start();
require 'dbconnection.php'; 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    http_response_code(403);
    echo "<div class='p-8 text-red-600'>Necessário fazer login para avaliar.</div>";
    exit;
}

// 2. OBTENÇÃO DO ID DO ALOJAMENTO E ID DO UTILIZADOR
// Se o ID não for fornecido, devolve uma mensagem de erro formatada em HTML.
$alojamento_id = isset($_GET['id_alojamento']) ? mysqli_real_escape_string($link, $_GET['id_alojamento']) : null;
$user_id = $_SESSION['id_user']; 

if (empty($alojamento_id)) {
    http_response_code(400);
    echo "<div class='p-8 text-center text-red-600'>Erro: ID do Alojamento não fornecido para avaliação.</div>";
    exit;
}

// 3. OBTENÇÃO DO NOME DO ALOJAMENTO (Para exibição)
$query_nome = "SELECT nome FROM alojamento WHERE id_alojamento = $alojamento_id";
$result_nome = mysqli_query($link, $query_nome);
if (!$result_nome || mysqli_num_rows($result_nome) === 0) { die("Alojamento não encontrado."); }
$alojamento = mysqli_fetch_assoc($result_nome);
$nome_alojamento = htmlspecialchars($alojamento['nome']);

?>

<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-xl border border-gray-200">
    
    <h1 class="text-3xl font-bold text-gray-900">Avaliar Alojamento</h1>
    <h2 class="text-xl text-gray-600 mb-6"><?php echo $nome_alojamento; ?></h2>

    <p class="mb-4 text-gray-600 border-l-4 border-[#c8c8b2] pl-2 italic">
        Partilhe a sua experiência e ajude outros viajantes a escolherem o seu próximo destino.
    </p>

    <form action="processaavaliacao.php" method="POST" class="space-y-6">
        
        <input type="hidden" name="id_alojamento" value="<?php echo $alojamento_id; ?>">
        <input type="hidden" name="id_user" value="<?php echo $user_id; ?>">

        <div>
            <label for="avaliacao" class="block text-lg font-semibold text-gray-900 mb-2">
                Nota de Satisfação (1 a 10):
            </label>
            <input type="number" name="avaliacao" id="avaliacao" min="1" max="10" required
                    class="w-full p-3 border border-[#707070] rounded-lg shadow-sm focus:ring-[#c8c8b2] focus:border-[#c8c8b2] transition-colors">
        </div>

        <div>
            <label for="comentario" class="block text-lg font-semibold text-gray-900 mb-2">
                O seu Comentário (Obrigatório):
            </label>
            <textarea name="comentario" id="comentario" rows="4" required
                        class="w-full p-3 border border-[#707070] rounded-lg shadow-sm focus:ring-[#c8c8b2] focus:border-[#c8c8b2] transition-colors"
                        placeholder="Descreva a sua experiência..."></textarea>
        </div>

        <div>
            <button type="submit" 
                    class="w-full py-3 bg-[#c8c8b2] text-[#565656] font-bold rounded-xl 
                           hover:bg-[#565656] hover:text-white transition-colors shadow-lg text-xl">
                Enviar a Minha Avaliação
            </button>
        </div>
    </form>
</div>