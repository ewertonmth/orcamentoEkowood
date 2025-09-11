<?php
require 'vendor/autoload.php'; // garanta que este caminho está correto

use Dompdf\Dompdf;

// Aqui pode carregar os dados do orçamento da SESSION (ou banco)
session_start();
$produtos = $_SESSION['produtos'] ?? [];
$cliente = $_SESSION['cliente'] ?? [];

$html = '
<style>
    body{font-family:sans-serif;}
    table{border-collapse:collapse;width:100%;}
    th,td{padding:8px;border-bottom:1px solid #eee;text-align:center;}
    thead th{background:#f3f4f6;}
    .profile-img{max-height:60px;max-width:90px;border-radius:6px;border:1px solid #ccc;}
</style>
<h2>Orçamento do Cliente</h2>
';

if ($cliente) {
    $html .= "<strong>Cliente:</strong> {$cliente['nome']} <br>";
    $html .= "<strong>Telefone:</strong> {$cliente['telefone']} <br>";
    $html .= "<strong>Endereço Entrega:</strong> {$cliente['endereco_entrega']} <br><br>";
}

$html .= "<table><thead>
<tr>
<th>Produto</th><th>Cor</th><th>Imagem Perfil</th>
<th>Metragem (m²)</th><th>Qtd. Peças</th>
</tr></thead><tbody>";

foreach ($produtos as $item) {
    // Ajuste o caminho da imagem do perfil para o caminho absoluto na url, se quiser exportar imagem!
    $img = '';
    if (!empty($item['nome'])) {
        $imgPath = 'orcamento-php/assets/images/profiles/'; // ajuste conforme o caminho do seu projeto
        $imagens = [
            'Ripado 11cm' => 'ripado_11cm.png',
            'Deck 14cm' => 'deck_14cm.png',
            'Brise' => 'brise.png'
        ];
        if (isset($imagens[$item['nome']])) {
            $img = '<img src="' . $imgPath . $imagens[$item['nome']] . '" class="profile-img">';
        }
    }
    $html .= "<tr>
        <td>{$item['nome']}</td>
        <td>{$item['cor']}</td>
        <td>{$img}</td>
        <td>" . number_format($item['metragem'], 2, ',', '.') . "</td>
        <td>" . (!empty($item['quantidade']) ? $item['quantidade'] . " réguas" : '—') . "</td>
    </tr>";
}
$html .= "</tbody></table>";

use Dompdf\Options;
$options = new Options();
$options->set('isRemoteEnabled', true); // precisa disso para permitir carregar imagens locais
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("orcamento.pdf", ["Attachment" => true]);
exit;
?>
