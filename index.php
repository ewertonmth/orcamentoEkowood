<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cnpj = trim($_POST['cnpj'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if ($nome && $cnpj && $endereco) {
        $_SESSION['cliente'] = [
            'nome' => $nome,
            'cnpj' => $cnpj,
            'endereco' => $endereco,
            'endereco_entrega' => trim($_POST['endereco_entrega'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'consultor' => trim($_POST['consultor'] ?? ''),
            'tipo' => trim($_POST['tipo'] ?? 'final'),
        ];
        header('Location: produtos.php');
        exit;
    } else {
        $error = 'Preencha corretamente os campos Nome, CNPJ e Endereço.';
    }
}

include 'templates/header.php';
?>
<?php if ($error): ?>
  <div class="error-message"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="index.php" novalidate>
  <label for="nome">Nome:</label>
  <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required placeholder="Digite o nome completo">

  <label for="cnpj">CNPJ:</label>
  <input type="text" id="cnpj" name="cnpj" class="form-control" value="<?= htmlspecialchars($_POST['cnpj'] ?? '') ?>" required placeholder="00.000.000/0000-00">

  <label for="endereco">Endereço:</label>
  <input type="text" id="endereco" name="endereco" class="form-control" value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>" required placeholder="Rua, número, bairro">

  <label for="endereco_entrega">Endereço de Entrega:</label>
  <input type="text" id="endereco_entrega" name="endereco_entrega" class="form-control" value="<?= htmlspecialchars($_POST['endereco_entrega'] ?? '') ?>" placeholder="Local de entrega">

  <label for="telefone">Telefone:</label>
  <input type="tel" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" placeholder="(99) 99999-9999">

  <label for="consultor">Consultor:</label>
  <input type="text" id="consultor" name="consultor" class="form-control" value="<?= htmlspecialchars($_POST['consultor'] ?? '') ?>" placeholder="Nome do consultor">

  <label for="tipo_cliente">Tipo de Cliente:</label>
  <div class="custom-select-wrapper">
    <select id="tipo_cliente" name="tipo" class="custom-select">
      <option value="final" <?= (($_POST['tipo'] ?? '') === 'final') ? 'selected' : '' ?>>Final</option>
      <option value="revendedor" <?= (($_POST['tipo'] ?? '') === 'revendedor') ? 'selected' : '' ?>>Revendedor</option>
    </select>
  </div>

  <button type="submit" class="btn-submit">Salvar Cadastro</button>
</form>

<?php include 'templates/footer.php'; ?>
