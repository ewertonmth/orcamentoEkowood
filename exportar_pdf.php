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

$imagePath = 'C:/xampp/htdocs/orcamento-php/assets/images/logo/ecowood.png';
$type = pathinfo($imagePath, PATHINFO_EXTENSION);
$data = file_get_contents($imagePath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$dompdf = new Dompdf();

$html = '
<style>
@page { margin: 0; background-color: #000; }
body { font-family: "Arial", "Roboto", sans-serif; background: #000 !important; margin: 0; color: #fff; padding: 0; }
.header-pdf { background: #000; color: #fff; padding: 32px 36px 22px 36px; border-radius: 0; margin-bottom: 22px; box-shadow: none; }
.header-logo { display: flex; align-items: center; gap: 24px; margin-bottom: 18px; justify-content: flex-start;}
.header-logo img { height: 55px; }
table.header-info { width: 100%; color: #e5e5e5; font-size: 14px; border-collapse: collapse; margin-bottom: 3px; background: #000; }
table.header-info td { vertical-align: top; padding: 4px 10px; background: #000; }
.section-title { font-weight: bold; color: #e8bc76; letter-spacing: 1.5px; font-size: 13px; }
.line { border-bottom: 1px solid #444; margin: 8px 0 14px 0; }
.products-table { width: 100%; border-collapse: separate; border-spacing: 0 5px; font-size: 12px; margin-top: 23px; background: #000; }
.products-table th { background: #191919; color: #fff !important; border-radius: 6px 6px 0 0; padding: 9px 6px; font-weight: bold; border: none; }
.products-table tr:nth-child(even) td { background: #111; }
.products-table td { border: 1px solid #292929; padding: 7px 6px; border-radius: 0 0 5px 5px; color: #fff !important; text-align: center; background: #000; }
.profile-img { max-height: 38px; max-width: 70px; border-radius: 6px; border: 1px solid #333; }
.circle-color { display:inline-block; width:16px; height:16px; border-radius:50%; border:1px solid #555; margin-right:5px; vertical-align:middle; background:#222; }
</style>

<div class="header-pdf">
  <div class="header-logo"><img src="' . $base64 . '" alt="Logo" /></div>
  <table class="header-info">
    <tr>
      <td style="width:38%;">
        <div class="section-title">CLIENTE</div>
        <div style="margin-bottom:6px;">' . (empty($cliente['nome']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['nome'])) . '</div>
        <div class="section-title">OBSERVAÇÕES</div>
        <div style="margin-bottom:6px;">' . (empty($cliente['observacoes']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['observacoes'])) . '</div>
        <div class="section-title">ENDEREÇO</div>
        <div style="margin-bottom:6px;">' . (empty($cliente['endereco']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['endereco'])) . '</div>
        <div class="section-title">ENDEREÇO ENTREGA</div>
        <div style="margin-bottom:6px;">' . (empty($cliente['enderecoentrega']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['enderecoentrega'])) . '</div>
        <div class="section-title">CONSULTOR</div>
        <div>' . (empty($cliente['consultor']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['consultor'])) . '</div>
      </td>
      <td style="width:9%;"></td>
      <td style="width:33%;"></td>
      <td style="width:20%;">
        <div class="section-title">CONTATO</div>
        <div style="margin-bottom:6px;">' . (empty($cliente['contato']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['contato'])) . '</div>
        <div class="section-title">CNPJ</div>
        <div style="margin-bottom:6px;">' . (empty($cliente['cnpj']) ? '<span style="color:#fff">-</span>' : htmlspecialchars($cliente['cnpj'])) . '</div>
        <div class="section-title">DATA</div>
        <div>' . date('d/m/Y') . '</div>
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
