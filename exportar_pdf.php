<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

session_start();

$produtos = $_SESSION['produtos'] ?? [];
$cliente = $_SESSION['cliente'] ?? null;

$imagensPerfis = [
    'Ripado 11cm' => 'ripado_11cm.png',
    'Ripado 20cm' => 'ripado_20cm.png',
    'Deck 14cm'   => 'deck_14cm.png',
    'Deck 15cm'   => 'deck_15cm.png',
    'Lambri 10cm' => 'lambri_10cm.png',
];

$produtosDisponiveis = json_decode(file_get_contents('C:/xampp/htdocs/orcamento-php/produtos_independentes.json'), true);
function buscarPerfil($nome, $array) {
    foreach ($array as $perfil) {
        if ($perfil['nome'] === $nome) return $perfil;
    }
    return null;
}

$imagePath = 'C:/xampp/htdocs/orcamento-php/assets/images/logo/ekowood_logo.png';
$type = pathinfo($imagePath, PATHINFO_EXTENSION);
$data = file_get_contents($imagePath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$dompdf = new Dompdf();

$html = '
<style>
@page { margin: 0; background-color: #fff; }
body { font-family: "Arial", "Roboto", sans-serif; background: #fff !important; margin: 0; color: #111; padding: 0; }
.header-pdf { background: #fff; color: #111; padding: 24px 30px 16px 30px; border-radius: 0; margin-bottom: 16px; box-shadow: none; }
.header-logo { display: flex; align-itens: start; text-align: center; width: 100%; margin-bottom: 50px}
.header-logo img { width: 200px; height: 50px; }
table.header-info { width: 100%; color: #535353; font-size: 14px; border-collapse: collapse; margin-bottom: 2px; background: #fff; }
table.header-info td { vertical-align: top; padding: 4px 10px; background: #fff; }
.section-title { font-weight: bold; color: #000000ff; letter-spacing: 1.5px; font-size: 13px; }
.line { border-bottom: 1px solid #e5e5e5; margin: 7px 0 12px 0; }
.products-table { width: 100%; border-collapse: separate; border-spacing: 0 3px; font-size: 12px; margin-top: 18px; background: #fff; }
.products-table th { background: #f5f5f5; color: #222 !important; border-radius: 4px 4px 0 0; padding: 7px 4px; font-weight: bold; border: none; }
.products-table tr:nth-child(even) td { background: #fafafa; }
.products-table td { border: 1px solid #e5e5e5; padding: 5px 4px; border-radius: 0 0 3px 3px; color: #333 !important; text-align: center; background: #fff; }
.profile-img { max-height: 34px; max-width: 65px; border-radius: 4px; border: 1px solid #f1f1f1; }
.circle-color { display:inline-block; width:14px; height:14px; border-radius:50%; border:1px solid #ccc; margin-right:5px; vertical-align:middle; background:#eee; }
</style>

<div class="header-pdf">
  <div class="header-logo"><img src="' . $base64 . '" alt="Logo" /></div>
  <table class="header-info">
  <tr>
    <td style="width:50%; vertical-align:top;">
      <div class="section-title">CLIENTE</div>
      <div style="margin-bottom:6px;">' . (empty($cliente['nome']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['nome'])) . '</div>
      <div class="section-title">OBSERVAÇÕES</div>
      <div style="margin-bottom:6px;">' . (empty($cliente['observacoes']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['observacoes'])) . '</div>
      <div class="section-title">ENDEREÇO</div>
      <div style="margin-bottom:6px;">' . (empty($cliente['endereco']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['endereco'])) . '</div>
      <div class="section-title">ENDEREÇO ENTREGA</div>
      <div style="margin-bottom:6px;">' . (empty($cliente['enderecoentrega']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['enderecoentrega'])) . '</div>
      <div class="section-title">CONSULTOR</div>
      <div>' . (empty($cliente['consultor']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['consultor'])) . '</div>
    </td>
    <td style="width:50%; vertical-align:top;">
      <div class="section-title">CONTATO</div>
      <div style="margin-bottom:6px;">' . (empty($cliente['contato']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['contato'])) . '</div>
      <div class="section-title">CNPJ</div>
      <div style="margin-bottom:6px;">' . (empty($cliente['cnpj']) ? '<span style="color:#000">-</span>' : htmlspecialchars($cliente['cnpj'])) . '</div>
      <div class="section-title">DATA</div>
      <div style="margin-bottom:6px;">' . date('d/m/Y') . '</div>
    </td>
  </tr>
</table>

  <div class="line"></div>
  <table class="products-table">
    <thead>
      <tr>
        <th>PRODUTO</th>
        <th>IMAGEM PERFIL</th>
        <th>COR</th>
        <th>VALOR</th>
        <th>IPI</th>
        <th>METRAGEM</th>
        <th>PEÇAS</th>
        <th>VALOR TOTAL</th>
        <th>ÁREA ORÇADA</th>
      </tr>
    </thead>
    <tbody>';

if (count($produtos) === 0) {
    $html .= '<tr><td colspan="9" style="color:#838383; font-weight: 500;">Nenhum produto cadastrado no orçamento.</td></tr>';
} else {
    foreach ($produtos as $item) {
        $nomePerfil   = $item['nome'] ?? '-';
        $imagemPerfil = isset($imagensPerfis[$nomePerfil]) ? $imagensPerfis[$nomePerfil] : '';
        $imgFullLocalPath = 'C:/xampp/htdocs/orcamento-php/assets/images/profiles/' . $imagemPerfil;

        if (file_exists($imgFullLocalPath) && !empty($imagemPerfil)) {
            $typeImg = pathinfo($imgFullLocalPath, PATHINFO_EXTENSION);
            $dataImg = file_get_contents($imgFullLocalPath);
            $img64 = 'data:image/' . $typeImg . ';base64,' . base64_encode($dataImg);
            $imgHTML = '<img src="' . $img64 . '" alt="Perfil" class="profile-img"/>';
        } else {
            $imgHTML = '<span style="color:#fff">-</span>';
        }

        $corNome = $item['cor_nome'] ?? '-';
        $corHex  = $item['cor_hex'] ?? '#222';

        // Círculo visual igual ao UI da lista
        $corHTML = '<span class="circle-color" style="background:' . htmlspecialchars($corHex) . ';"></span>' . htmlspecialchars($corNome);

        $perfilInfo = buscarPerfil($nomePerfil, $produtosDisponiveis);
        $valorPerfil = isset($perfilInfo['valor_unitário']) ? $perfilInfo['valor_unitário'] : 0;
        $ipiPerfil = isset($perfilInfo['ipi_percentual']) ? $perfilInfo['ipi_percentual'] : 0;

        $html .= '<tr>
          <td>' . htmlspecialchars($nomePerfil) . '</td>
          <td>' . $imgHTML . '</td>
          <td>' . $corHTML . '</td>
          <td style="text-align: right;">R$ ' . number_format($valorPerfil, 2, ',', '.') . '</td>
          <td style="text-align: right;">' . number_format($ipiPerfil, 2, ',', '.') . '%</td>
          <td style="text-align: right;">' . (isset($item["metragem"]) ? number_format($item["metragem"], 2, ',', '.') : '0,00') . '</td>
          <td>' . (isset($item["quantidade"]) ? htmlspecialchars($item["quantidade"]) : '0') . '</td>
          <td style="text-align: right;">R$ ' . (isset($item["total"]) ? number_format($item["total"], 2, ',', '.') : '0,00') . '</td>
          <td style="text-align: right;">' . (isset($item["areaorcada"]) ? number_format($item["areaorcada"], 2, ',', '.') : (isset($item["metragem"]) ? number_format($item["metragem"], 2, ',', '.') : '0,00')) . '</td>
        </tr>';
    }
}

$html .= '</tbody></table></div>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('orcamento.pdf', ['Attachment' => false]);
exit;
?>
