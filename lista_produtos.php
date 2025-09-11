<?php
session_start();
// Inicializa a lista de produtos caso não exista
if (!isset($_SESSION['produtos']) || !is_array($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}
// Remove produto via query string
if (isset($_GET['remove'])) {
    unset($_SESSION['produtos'][$_GET['remove']]);
    $_SESSION['produtos'] = array_values($_SESSION['produtos']);
    header('Location: lista_produtos.php');
    exit;
}

$coresPerfis = [
    ['nome' => 'OCÃ',     'hex' => '#6e7c2a'],
    ['nome' => 'WALNUT',  'hex' => '#7a2236'],
    ['nome' => 'NOGAL',   'hex' => '#827c6a'],
    ['nome' => 'CINZA',   'hex' => '#6f7272'],
    ['nome' => 'ANGELIM', 'hex' => '#c59a44'],
    ['nome' => 'MOGNO',   'hex' => '#a47f3a'],
    ['nome' => 'IPÊ',     'hex' => '#87613f'],
    ['nome' => 'BRANCO',  'hex' => '#ffffff'],
    ['nome' => 'PRETO',   'hex' => '#111111'],
    ['nome' => 'TEKA',    'hex' => '#a3783b'],
    ['nome' => 'TAUARI',  'hex' => '#ebc46f'],
];

$imagesBasePath = 'orcamento-php/assets/images/profiles/';
$imagensPerfis = [
    'Ripado 11cm' => 'ripado_11cm.png',
    'Deck 14cm'   => 'deck_14cm.png',
    'Brise'       => 'brise.png',
];

include 'templates/header.php';
?>
<link rel="stylesheet" href="assets/css/lista_produtos.css" />
<div class="container">
  <div class="table-box">
      <h2>Lista de Produtos</h2>
      <table>
          <thead>
              <tr>
                  <th>Produto</th>
                  <th>Cor</th>
                  <th>Imagem Perfil</th>
                  <th>Metragem (m²)</th>
                  <th>Quantidade de Peças</th>
                  <th>Ações</th>
              </tr>
          </thead>
          <tbody>
              <?php if (count($_SESSION['produtos']) === 0): ?>
              <tr>
                  <td colspan="6" class="text-secondary">Nenhum produto adicionado até o momento.</td>
              </tr>
              <?php else: foreach ($_SESSION['produtos'] as $i => $item): 
                  $corHex = $item['cor_hex'] ?? '#777';
                  $corNome = $item['cor_nome'] ?? '—';
                  $imagemProduto = isset($imagensPerfis[$item['nome']]) ? $imagesBasePath . $imagensPerfis[$item['nome']] : null;
                  $fileFisico = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagemProduto;
              ?>
              <tr>
                  <td><?= htmlspecialchars($item['nome']) ?></td>
                  <td>
                      <span class="circle-color" style="background:<?= htmlspecialchars($corHex) ?>"></span>
                      <?= htmlspecialchars($corNome) ?>
                  </td>
                  <td>
                      <?php if ($imagemProduto && file_exists($fileFisico)): ?>
                          <img src="/<?= htmlspecialchars($imagemProduto) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="profile-img" />
                      <?php else: ?>
                          <span class="text-secondary">—</span>
                      <?php endif; ?>
                  </td>
                  <td><strong><?= number_format($item['metragem'], 2, ',', '.') ?></strong></td>
                  <td>
                      <?php 
                      if (isset($item['quantidade']) && $item['quantidade'] !== null) {
                          $altura = (isset($item['altura_parede']) && $item['altura_parede'] !== null) ? number_format($item['altura_parede'], 2, ',', '.') . "m" : "—";
                          echo '<strong>' . htmlspecialchars($item['quantidade']) . " réguas</strong><br><span class='text-secondary small'>" . $altura . '</span>';
                      } else {
                          echo "—";
                      }
                      ?>
                  </td>
                  <td>
                      <a href="?remove=<?= $i ?>" class="btn-remove" title="Remover Produto">
                          <span>🗑</span> Remover
                      </a>
                  </td>
              </tr>
              <?php endforeach; endif; ?>
          </tbody>
      </table>
      <div class="actions-container">
          <a href="produtos.php" class="btn-action green">Adicionar Mais</a>
          <a href="resumo.php" class="btn-action blue">Finalizar Orçamento</a>
      </div>
  </div>
</div>
<?php
include 'templates/footer.php';
?>

